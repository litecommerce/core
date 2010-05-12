<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define('SHIPPING_CACHE_EXPIRATION', 3600 * 12); // half of a day

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Shipping_Online extends XLite_Model_Shipping
{
    public $optionsTable;

    public function getOptions()
    {
        
        $name = $this->configCategory;
        if (isset($this->config->$name)) {
            $options = $this->config->$name;
        } else {
            $options = new XLite_Base();
        }
        foreach ($this->optionsFields as $field) {
            if (!isset($options->$field)) {
                $options->$field = '';
            }
        }
        return $options;
    }

    function setOptions($options)
    {
        
        $c = new XLite_Model_Config();
        $category = $this->configCategory;
        $c->set("category", $category);
        $this->config->$category = new XLite_Base();
        foreach ($this->optionsFields as $field) {
            if (!isset($options->$field)) {
                continue;
            }
            $c->set("name", $field);
            $c->set("value", $options->$field);
            $this->config->$category->$field = $options->$field;
            if ($c->is("exists")) {
                $c->update();
            } else {
                $c->create();
            }
        }
    }
    
    function unserializeCacheRates($rates)
    {
        $result = array();

        $cart = XLite_Model_Cart::getInstance();

        $order = new XLite_Model_Order($cart->get('order_id'));
        $weight = doubleval($order->get('weight'));
        $total = doubleval($order->calcSubTotal(true)); // SubTotal for "shipped only" items
        $items = $order->get('shippedItemsCount');
        $zone = $this->getZone($order);

        if (!empty($rates)) {
            foreach (explode(',', $rates) as $rate) {
                list($shipping_id, $rate_value) = explode(':', $rate, 2);
                $rateObject = new XLite_Model_ShippingRate($shipping_id);

                $sql = '(shipping_id = -1 OR shipping_id = \'' . $shipping_id . '\')'
                    . ' AND (shipping_zone = -1 OR shipping_zone = \'' . $zone . '\')'
                    . ' AND min_weight <= ' . $weight
                    . ' AND max_weight >= ' . $weight
                    . ' AND min_total <= ' .$total
                    . ' AND min_items <= ' .$items
                    . ' AND max_items >= ' . $items
                    . ' AND max_total > ' . $total;

                $rateObject->find($sql, 'shipping_id DESC, shipping_zone DESC');

                $shipping = new XLite_Model_Shipping($shipping_id);
                $rateObject->shipping = XLite_Model_Shipping::getInstanceByName($shipping->get('class'), $shipping_id);

                if ($rateObject->shipping->isExists() && $rateObject->shipping->is('enabled')) {

                    // haven't we remove this shipping?
                    $rateObject->rate = doubleval($rate_value)
                        + doubleval($rateObject->get('flat'))
                        + doubleval($rateObject->get('per_item')) * $items
                        + doubleval($rateObject->get('percent')) * $total / 100
                        + doubleval($rateObject->get("per_lbs")) * $weight;

                    $result[$rateObject->shipping->get('shipping_id')] = $rateObject;
                }
            }
        }

        return $result;
    }

    function serializeCacheRates($rates)
    {
        $serialized = array();
        foreach ($rates as $rate) {
            $serialized[] = $rate->shipping->get('shipping_id') . ':' . $rate->rate;
        }
        return join(',', $serialized);
    }
    function _cleanCache($table)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        $this->db->query("DELETE FROM $cacheTable");
    }

    function _checkCache($table, $fields)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        // garbage collection
        if ($this->get("shippingCacheExpiration") > 0) {
            $this->db->query("DELETE FROM $cacheTable WHERE date<".(time()-$this->get("shippingCacheExpiration")));
        }

        // check cache for fresh values
        $condition = array();
        foreach ($fields as $key => $value) {
            $condition[] = "$key='".addslashes($value)."'";
        }
        $condition = join(" AND ", $condition);
        $cacheRow = $this->db->getRow("SELECT rates FROM $cacheTable WHERE $condition");
        if (!is_null($cacheRow)) {
            return $this->unserializeCacheRates($cacheRow["rates"]);
        }
        return false;
    }

    /**
    * store shipping rates in the cache    
    */
    function _cacheResult($table, $fields, &$rates)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        $values = array();
        foreach ($fields as $value) {
            $values[] = "'".addslashes($value)."'";
        }
        
        $this->db->query("REPLACE INTO $cacheTable " . 
                     "(" . join(",", array_keys($fields)) .  ",date,rates)".
                     " VALUES (" . join(",", $values) . ",".time().",'" . 
                     $this->serializeCacheRates($rates) . "')");
    }
    

    /**
    * Obtains the order's weight, in ounces.
    */
    function getOunces($order)
    {
        
        $w = $order->get("weight");
        switch ($this->config->getComplex('General.weight_unit')) {
        case 'lbs': return ceil($w*16.0);
        case 'oz':  return ceil($w*1.0);
        case 'kg':  return ceil($w*35.2740);
        case 'g':   return ceil($w*0.035274);
        }
        return 0;
    }

    /**
    * Strip off the last 4 digits from an extended US ZIP format #####-####
    */
    function _normalizeZip($zip)
    {
        if (preg_match("/([0-9]{5,5})[- ]+[0-9]{4,4}/", $zip, $result)) {
            return $result[1];
        }
        
        return preg_replace("/[ -]/", "", $zip);
    }
    
    function getShippingCacheExpiration()
    {
        return SHIPPING_CACHE_EXPIRATION;
    }
}

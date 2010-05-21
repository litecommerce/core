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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_EcommerceReports_Controller_Admin_GeographicSales extends XLite_Module_EcommerceReports_Controller_Admin_ProductSales
{
    function getGeoSales() 
    {
        if (is_null($this->geoSales)) {
            $this->geoSales = array();
            $items = $this->get('rawItems');
            // summarize
            array_map(array($this, 'sumProductSales'), $items);
            // sort
            foreach ($this->geoSales as $gl => $gs) {
                $productSales = $this->geoSales[$gl];
                usort($productSales, array($this, "cmpProducts"));
                $ps = array_reverse($productSales);
                $this->geoSales[$gl] = $ps;
            }
        }
        return $this->geoSales;
    }

    function getInCountries($table) 
    {
        $countryCodes = (array) $this->get('country_codes');
        if (!count($countryCodes)) {
            return parent::getInCountries($table);
        }
        foreach ($countryCodes as $idx => $code) {
            $countryCodes[$idx] = "'".$code."'";
        }
        $codes = implode(',', $countryCodes);
        $prefix = $this->get('group_by');
        return " AND {$table}.{$prefix}_country IN ($codes) ";
    }

    function getInStates($table) 
    {
        $stateIds = (array) $this->get('state_ids');
        if (!count($stateIds)) {
            return parent::getInStates($table);
        }
        if (in_array(-1, $stateIds)) {
            array_push($stateIds, 0);
        }
        $ids = implode(',', $stateIds);
        $prefix = $this->get('group_by');
        return " AND {$table}.{$prefix}_state IN ($ids) ";
    }
    
    function sumProductSales($item) 
    {
        $gid = $this->getGeoIndex($item);
        if (!isset($this->geoSales[$gid])) {
            $this->geoSales[$gid] = array();
        }
        $productSales = $this->geoSales[$gid];
        $id = $item['product_id'] . (strlen($item['options']) ? md5($item['options']) : "");
        $orderItem = new XLite_Model_OrderItem();
        $orderItem->find("order_id=".$item['order_id']." AND item_id='".addslashes($item['item_id'])."'");
        $order = new XLite_Model_Order($item['order_id']);
        $orderItem->set('order', $order);
        $item['price'] = $orderItem->get('price');
        if (!isset($productSales[$id])) {
            $productSales[$id] = $item;
            $productSales[$id]['total'] = 0;
            $productSales[$id]['order_item'] = $orderItem;
        } else {
            $productSales[$id]['amount'] += $item['amount'];
        }
        $productSales[$id]['total'] += $item['amount'] * $item['price'];
        $productSales[$id]['avg_price'] = $productSales[$id]['total'] / $productSales[$id]['amount'];
    }
    
    function getGeoIndex($item) 
    {
        $prefix = $this->get('group_by');
        if (!is_null($this->get('state_ids'))) { // has selected states
            $st = new XLite_Model_State($item[$prefix . "_state"]);
            $state = $st->get('state');
        } else {
            $state = "All";
        }
        if (!is_null($this->get('country_codes'))) { // has selected country
            $cnt = new XLite_Model_Country($item[$prefix . "_country"]);
            $country = $cnt->get('country');
        } else {
            $country = "All";
        }
        return $country . " / " . $state;
    }
    
    function getProductsFound() 
    {
        $found = 0;
        foreach ((array)$this->get('geoSales') as $gl => $gs) {
            $found += count($gs);
        }
        return $found;
    }
    
    function getCountries() 
    {
        if (is_null($this->countries)) {
            $country = new XLite_Model_Country();
            $this->countries = $country->findAll();
        }
        return $this->countries;
    }

    function getStates() 
    {
        if (is_null($this->states)) {
            $state = new XLite_Model_State();
            $this->states = $state->findAll();
        }
        return $this->states;
    }
    
}

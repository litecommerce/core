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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Shipping_Offline extends XLite_Model_Shipping
{
    function getModuleName()
    {
        return "Manually defined shipping methods";
    }

    function _buildRatesSql($sql, $order)
    {
    	return $sql;
    }

    function getRates(XLite_Model_Order $order)
    {
        $shop_country = $this->config->getComplex('Company.location_country');
        if (is_null($order->get("profile")) && !$this->config->getComplex('General.def_calc_shippings_taxes')) {
        	return array();
        }
        $dest_country = (is_null($order->get("profile"))) ? $this->config->getComplex('General.default_country') : $order->getComplex('profile.shipping_country');
        // select all national/international shipping methods
        if ($dest_country == $shop_country) {
            $dest = 'L';
        } else {
            $dest = 'I';
        }
        $sql = "destination='$dest' AND enabled=1 AND class='offline'";
        $methods = $this->findAll($this->_buildRatesSql($sql, $order));
        // join with rates table
        $result = array();
        for ($i=0; $i<count($methods); $i++)
        {
            $method = $methods[$i];
            $rate = $this->getRate($order, $method);
            if (isset($rate)) {
                $result[$method->get("shipping_id")] = $rate;
            }    
        }
        // TODO: sort by rate
        return $result;
    }

    function _buildRateSql($sql, $order, &$method)
    {
    	return $sql;
    }

    function getRate($order, &$method)
    {
        $shipping_id = $method->get("shipping_id");
        $weight = (double) $order->get("weight");
        $total = (double) $order->calcSubTotal(true); // SubTotal for "shipped only" items
        $r = new XLite_Model_ShippingRate();
        $zone = $this->getZone($order);
        $items = $order->get("shippedItemsCount");
        $sql = "(shipping_id=-1 OR shipping_id='$shipping_id') AND (shipping_zone=-1 OR shipping_zone='$zone') AND min_weight<=$weight AND max_weight>=$weight AND min_total<=$total AND min_items<=$items AND max_items>=$items AND max_total>$total";
        if ($r->find($this->_buildRateSql($sql, $order, $method), "shipping_id DESC, shipping_zone DESC")) {
            $r->rate = (double)$r->get("flat") + (double)$r->get("per_item") * $items + (double)$r->get("percent")*$total/100 + (double)$r->get("per_lbs")*$weight;
            $r->shipping = $method;
            return $r;
        }
        return null;
    }
}

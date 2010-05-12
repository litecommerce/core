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
class XLite_Controller_Admin_ShippingRates extends XLite_Controller_Admin_ShippingSettings
{
    public $params = array('target', 'shipping_zone_range', 'shipping_id_range');
    
    public $shipping_id_range = "";
    public $shipping_zone_range = "";

    function getPageTemplate()
    {
        return "shipping/charges.tpl";
    }

    function getShippingRates()
    {
        // read select condition from the request
        $condition = array();
        if (isset(XLite_Core_Request::getInstance()->shipping_zone_range) && strlen(XLite_Core_Request::getInstance()->shipping_zone_range) > 0) {
            $this->shipping_zone_range = XLite_Core_Request::getInstance()->shipping_zone_range;
            $condition[] = "shipping_zone='$this->shipping_zone_range'";
        }
        if (!empty(XLite_Core_Request::getInstance()->shipping_id_range)) {
            $this->shipping_id_range = XLite_Core_Request::getInstance()->shipping_id_range;
            $condition[] = "shipping_id='$this->shipping_id_range'";
        }
        $condition = implode(" AND ", $condition);
        $sr = new XLite_Model_ShippingRate();
        $shipping_rates = $sr->findAll($condition);
        $shipping = new XLite_Model_Shipping();
    	$modules = $shipping->getModules();
    	$modules = (is_array($modules)) ? array_keys($modules) : array();
        $shippings = $shipping->findAll();
        $validShippings = array("-1");
        foreach($shippings as $shipping) {
            if (in_array($shipping->get("class"), $modules) && $shipping->get("enabled")) {
                $validShippings[] = $shipping->get("shipping_id");
            }
        }

        // assign numbers
        $i = 1;
        $excluded_shipping_rates = array();
        foreach ($shipping_rates as $key => $val) {
            $shipping_rates[$key]->pos = $i++;
            if (!in_array($val->get("shipping_id"), $validShippings)) {
            	$excluded_shipping_rates[$key] = true;
            }
        }
        foreach ($excluded_shipping_rates as $key => $val) {
        	unset($shipping_rates[$key]);
        }

        return $shipping_rates;
    }

    function action_add()
    {
        $this->params[] = "message";
        $rate = new XLite_Model_ShippingRate();
        $rate->set("properties", XLite_Core_Request::getInstance()->getData());
        if (!$rate->isExists()) {
        	$this->set("message", "added");
        	$rate->create();
        } else {
        	$this->set("message", "add_failed");
        }
    }

    function action_update()
    {
        $shippingRates = $this->get("shippingRates");
        foreach(XLite_Core_Request::getInstance()->rate as $key => $rate_data) {
            if (array_key_exists($key, $shippingRates)) {
                $rate = new XLite_Model_ShippingRate();
                $rate->set("properties", $rate_data);
                if ($rate->isExists()) {
                    $rate->update();
                } else {
                    $rate = $shippingRates[$key];
    	            $rate->delete();
        	        $rate->set("properties", $rate_data);
            	    $rate->create();
                }
            }
        }
    }

    function action_delete()
    {
        $shippingRates = $this->get("shippingRates");
        $rate = $shippingRates[XLite_Core_Request::getInstance()->deleted_rate];
        $rate->delete();
    }
}

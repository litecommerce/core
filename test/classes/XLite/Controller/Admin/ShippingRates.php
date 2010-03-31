<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Configures the Shipping rates.
*
* @package Dialog
* @access public
* @version $Id$
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
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

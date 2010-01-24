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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Module_AOM_Controller_Admin_OrderList extends XLite_Controller_Admin_OrderList implements XLite_Base_IDecorator
{	
	public $orders = null;	
	public $params = array('target', 'mode', 'start_order_id', 'end_order_id', 'login', 'status', 'person_info', 'product_name', 'period', 'start_total', 'end_total', 'payment_method', 'shipping_id');	

	protected $_paymentMethods = null;
	protected $_shippingRates  = null;
	
	function getOrders() // {{{
	{
		if(is_null($this->orders)) {
			$order = new XLite_Model_Order();
			$order->collectGarbage();

// search dates // {{{ 
		if ($this->get("period") != 6) {
			list($startDate, $endDate) = $this->getPeriodDates($this->get("period"));
			$this->set("startDate", $startDate);
			$this->set("endDate", $endDate);
		}
 // }}}

			$orders = $order->search(
					null,
					$this->get("start_order_id"),
					$this->get("end_order_id"),
					$this->get("status"),
					$this->get("startDate"),
					$this->get("endDate")+24*3600,
					$this->get("start_total"),
					$this->get("end_total"),
					$this->get("shipping_id"),
					$this->get("payment_method"));
			$this->orders = $orders;

// search by profiles // {{{

		if ($this->get("login")||$this->get("person_info"))	{
			$profile = new XLite_Model_Profile();
			$profile->_range = null;
			$person_search = "";
			if ($this->get("person_info")) {
				$field_values = array ("billing_firstname", "billing_lastname", "billing_company", "billing_phone", "billing_fax", "billing_address", "billing_city", "billing_state", "billing_country", "billing_zipcode", "shipping_firstname", "shipping_lastname", "shipping_company", "shipping_phone", "shipping_fax",  "shipping_address", "shipping_city", "shipping_state", "shipping_country", "shipping_zipcode");
				$keywords = explode(" ", addslashes($this->get("person_info")));
	    	    $person_search = array();
	        	foreach($field_values as $field_value) {
		        	$query = array();
	                foreach ($keywords as $keyword)
    	                $query[] = "$field_value LIKE '%$keyword%'";
        	        $person_search[] = (count($keywords) > 1 ? "(" . implode(" OR ", $query) . ")" :  implode("", $query));
        		}
	        	$person_search = implode(" OR ",$person_search);
			}
			$profiles = $profile->findAll("login LIKE '%".addslashes($this->get("login"))."%' AND order_id <> 0" . ($person_search ? " AND ($person_search)" : ""));
			if (!(is_array($profiles) && count($profiles))) 
				$profiles = array();
			$this->orders = array();
			if (is_array($orders) && count($orders)) {
				foreach($orders as $order) {
					$order_id = $order->get("order_id");
					foreach($profiles as $profile) {
						if ($order_id == $profile->get("order_id")) {
							$this->orders[] = $order;
						}	
					}
				}
			}
		}
		// }}}

// search products // {{{

			$products = array();
			if ($this->get("product_name")) {
				$product = new XLite_Model_Product();
				$product_name = addslashes($this->get("product_name"));
				$products = $product->findAll("name LIKE '%$product_name%' OR sku LIKE '%$product_name%'");
				$item = new XLite_Model_OrderItem();
				$items 	 = $item->findAll("product_name LIKE '%$product_name%' OR product_sku LIKE '%$product_name%'");
				$product_ids = array();
                foreach($products as $product)
                    $product_ids[] = $product->get("product_id");
                $item_ids = array();
                foreach($items as $item) 
                    $item_ids[] = $item->get("product_id");
				$product_ids = array_unique(array_merge($product_ids,$item_ids));
				$orders = $this->orders;
				$this->orders = array();
				foreach($orders as $order) {
					$marked = false;
					foreach($order->get("items") as $item) 
						if (in_array($item->get("product_id"),$product_ids)) 
							$marked = true;
					if ($marked == true) 
						$this->orders[] = $order;
				}
				
			}
// }}} 

			if ($this->action == "export_xls") {
                foreach($this->orders as $ord_idx => $order) {
                    $taxes = 0;
                    foreach($order->getDisplayTaxes() as $tax_name => $tax) {
                        $taxes += $tax;
                    }
                    $this->orders[$ord_idx]->set("tax", $taxes);
                }
            }

	}
		return $this->orders;

	} // }}}

    function getPaymentMethods() // {{{ 
    {
		if (!is_null($this->_paymentMethods)) {
	        $paymentMethod = new XLite_Model_PaymentMethod();
			$this->_paymentMethods = $paymentMethod->getActiveMethods();
		}

        return $this->_paymentMethods;
    } // }}}

    function getShippingRates() // {{{
    {
    	if (!is_null($this->_shippingRates)) {
    		return $this->_shippingRates;
    	}

		$sr = new XLite_Model_ShippingRate();
        $shipping_rates = $sr->findAll();
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

        $unique_shippings = array();
        $excluded_shipping_rates = array();
        foreach ($shipping_rates as $key => $val) {
        	$shipping_id = $val->get("shipping.shipping_id");
        	if (!isset($unique_shippings[$shipping_id])) {
        		$unique_shippings[$shipping_id] = true;
        	} else {
            	$excluded_shipping_rates[$key] = true;
        	}
        }
        foreach ($excluded_shipping_rates as $key => $val) {
        	unset($shipping_rates[$key]);
        }

        $this->_shippingRates = $shipping_rates;
        return $shipping_rates;
    } // }}}

	function getPeriodDates($period) // {{{ 
	{
		$ct = getdate(time());
		switch($period) {
			case -1: 	// Whole period
				$startDate = mktime(0, 0, 0, 1, 2, 1970);
				$endDate   = mktime(0,0,0,$ct['mon'],$ct['mday'],$ct['year']+1);
			break; 
			case 0:     // Today
                $startDate = mktime(0,0,0,$ct['mon'],$ct['mday'],$ct['year']);
                $endDate = $startDate;
            break;
            case 1:     // Yesterday
                $startDate = mktime(0,0,0,$ct['mon'],$ct['mday']-1,$ct['year']);
                $endDate = $startDate;
            break;
            case 2:     // Current week
                $wday = ($ct['wday'] == 0) ? 7 : $ct['wday'];
                $startDate = mktime(0,0,0,$ct['mon'],$ct['mday']-$wday+1,$ct['year']);
                $endDate = mktime(0,0,0,$ct['mon'],$ct['mday']-$wday+7,$ct['year']);
            break;
            case 3:     // Previous week
                $wday = (($ct['wday'] == 0) ? 7 : $ct['wday']) + 7;
                $startDate = mktime(0,0,0,$ct['mon'],$ct['mday']-$wday+1,$ct['year']);
                $endDate = mktime(0,0,0,$ct['mon'],$ct['mday']-$wday+7,$ct['year']);
            break;
            case 4:     // Current month
                $startDate = mktime(0,0,0,$ct['mon'],1,$ct['year']);
                $endDate = mktime(0,0,0,$ct['mon']+1,0,$ct['year']);
            break;
            case 5:     // Previous month
                $startDate = mktime(0,0,0,$ct['mon']-1,1,$ct['year']);
                $endDate = mktime(0,0,0,$ct['mon'],0,$ct['year']);
            break;
		}
		 
		return array($startDate, $endDate);
	} // }}}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

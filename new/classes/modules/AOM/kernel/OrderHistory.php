<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

func_define('ORDER_HISTORY_CRYPTED_MESSAGE', 'Encrypted');
func_define('ORDER_HISTORY_CHANGED_MESSAGE', 'Changed');

/**
* 
*
* @package AOM
* @access public
* @version $Id$
*/
class OrderHistory extends Base
{
	var $fields = array("order_history_id" 	=> '',
						"order_id"			=> '',
						"login"				=> '',	
						"date"				=> '',
						"changes"			=> '',	
						"secureChanges"		=> '');

	var $autoIncrement = "order_history_id";
	var $alias = "order_history";					
	var $_changes = null;

	var $secure_prefix = array("cc_");

	function &get($name) {
		if ($name == 'changes') 
			return $this->getChanges();
		return parent::get($name);
	}
	
	function &getChanges() // {{{
	{
        if (is_null($this->_changes)) {
			$this->_changes = unserialize(parent::get("changes"));

			$val = parent::get("secureChanges");
			if ( trim($val) != "" ) {
				$gpg =& func_new("GPG");
				$secureChanges = unserialize($gpg->decrypt($val));
	
				if ( is_array($secureChanges) ) {
					$this->_changes = ( is_array($this->_changes) ) ? $secureChanges + $this->_changes : $secureChanges;
				}
			}
		}

		return $this->_changes;
	} // }}}

	function set($name, $value)
	{
        if ( $name == "changes" ) {
			$this->setChanges($value);
			return;
		}
		parent::set($name, $value);
	}

	function setChanges($value)
	{
        if ( !is_array($value) )
			$value = array();

		$secureChanges = "";

		if (!$this->xlite->get("config.AOM.cc_info_history")) {
			foreach ($value as $key=>$val) {
				if ( is_array($val) ) {
					foreach ($val as $k=>$v) {
						if ($this->isSecureKey($k)) {
							$value[$key][$k] = ORDER_HISTORY_CHANGED_MESSAGE;
						}
					}
				}
			}
		}

		if ( $this->xlite->get("config.AOM.cc_info_history") && $this->xlite->mm->get("activeModules.AdvancedSecurity") && $this->xlite->is("adminZone") && $this->get("config.AdvancedSecurity.gpg_crypt_db") ) {
			foreach ($value as $key=>$val) {
				if ( is_array($val) ) {
					foreach ($val as $k=>$v) {
						if ( $this->isSecureKey($k) ) {
							$secureChanges[$key][$k] = $v;
							$value[$key][$k] = ORDER_HISTORY_CRYPTED_MESSAGE;
						}
					}
				}
			}

			$gpg =& func_new("GPG");
			$secureChanges = $gpg->encrypt(serialize($secureChanges));
		}

		parent::set("changes", serialize($value));
		parent::set("secureChanges", $secureChanges);
	}

	function isSecureKey($key)
	{
		foreach ($this->secure_prefix as $prefix)
			if ( substr($key, 0, strlen($prefix)) == $prefix )
				return true;

		return false;
	}

	function log(&$order, $cloneOrder = null, $ordersItems = null, $action = null) // {{{ 
	{
		$history = array();
		if ($action == "create_order")
		{
			$history["order"]["created"] = $order->get("order_id");
		}
		
		if ($action == "split_order") 
		{ 
			if ($order->get("order_id") > $cloneOrder->get("order_id")) {
				$history["order"]["split"]["parent"] = $cloneOrder->get("order_id");
                $history["order"]["split"]["child"]  = $order->get("order_id");
			} else {
				$history["order"]["split"]["parent"] = $order->get("order_id");
				$history["order"]["split"]["child"]	 = $cloneOrder->get("order_id");
			}
		}
		
		if ($action == "clone_order")
		{
			$history["order"]["cloned"] = $cloneOrder->get("order_id");
		}
		
		if (!is_null($ordersItems))	{
			foreach($ordersItems as $items) {
				if (is_null($items['orderItem']) && !is_null($items['cloneItem'])) 
					$history['items']['added'][] = $items['cloneItem']->get("product_name");
                if (is_null($items['cloneItem']) && !is_null($items['orderItem'])) 
                    $history['items']['deleted'][] = $items['orderItem']->get("product_name");     				
			} 
			foreach($ordersItems as $items) {
				if (!is_null($items['cloneItem']) && !is_null($items['orderItem'])) {
					$cloneItem = $items['cloneItem']->get("properties");
					$orderItem = $items['orderItem']->get("properties");
					if ($cloneItem["price"] != $orderItem["price"]) 
					$history['items']['updated']['price'][] = array("name" => $orderItem["product_name"],"oldPrice" => $orderItem['price'],"newPrice" => $cloneItem['price']);
                    if ($cloneItem["amount"] != $orderItem["amount"])       
					$history['items']['updated']['amount'][] = array("name" => $orderItem["product_name"],"oldAmount" => $orderItem['amount'],"newAmount" => $cloneItem['amount']);
				}
			}
			if (empty($history)) $history["items"]["not_changed"] = true;
		}
		if (!is_null($order) && !is_null($cloneOrder) && $action == null) {
				$fields = array("subtotal","shipping_cost","payment_method","discount", "global_discount", "payedByGC", "total", "payedByPoints");
				foreach($fields as $field) 
					if ($order->get($field) != $cloneOrder->get($field)) {
						$history["totals"][$field] = $order->get($field);
						$history["changedTotals"][$field] = $cloneOrder->get($field);
					}

				// Log taxes changes
				$taxes = $order->get("displayTaxes");
				$cloneTaxes = $cloneOrder->get("displayTaxes");
				if ( is_array($taxes) ) {
					foreach ($taxes as $tax=>$value) {
						if ( $cloneTaxes[$tax] != $value ) {
							$history["totals"][$tax] = $value;
							$history["changedTotals"][$tax] = $cloneTaxes[$tax];
						}
					}
				}

				$profile =& $order->get("profile");
				if ($profile) {
					$cloneProfile =& $cloneOrder->get("profile");
					foreach ($profile->get("properties") as $key => $value)
						if (($cloneProfile->get("$key") != $value) && !($key == 'order_id' || $key == 'profile_id'))
						{
							$history["profile"][$key] = $value; 
							$history["changedProfile"][$key] = $cloneProfile->get("$key");
						}
					}
		}
		if (!is_null($order) && isset($_POST['substatus'])) {
			if ($order->get("notes") != $_POST['notes']) {
				$history['notes'] = $order->get("notes");
				$history['changedNotes'] = $_POST['notes'];
			}
			if ($order->get("admin_notes") != $_POST['admin_notes']) {
				$history['admin_notes'] = $order->get("admin_notes");
	            $history['changedAdmin_notes'] = $_POST['admin_notes'];
			}
			if ($_POST['details']) {
				if ( !is_null($this->session->get("masterPassword")) ) {
					$temp_details =& $order->getSecureDetails();
				} else {
					$temp_details = $order->get("details");
				}
				foreach($_POST['details'] as $ckey => $changedDetail) {
					foreach($temp_details as $key => $detail) {
						if ($key == $ckey && $detail != $changedDetail)
						{
							$details[$key] = $detail;
							$changedDetails[$ckey] = $changedDetail;	
						}
					}
				}
			}			
			if (!empty($details)) {		
				$history['details'] = $details;
				$history['changedDetails'] = $changedDetails;
			}
            $changedStatus = &func_new("OrderStatus");
            $changedStatus->find("status = '".$_POST["substatus"]."'");
			if ($order->get("orderStatus.name") != $changedStatus->get("name"))	{
		    	$history['status'] = $order->get("orderStatus.name");
				$history['changedStatus'] = $changedStatus->get("name");
			}
		}

		if ( count($history) == 1 && $history["items"]["not_changed"] == "1" ) {
			$history = array();
		}               

		if (!empty($history)) {
            $orderHistory = &func_new("OrderHistory");
			$orderHistory->set("order_id",$order->get("order_id"));
			$orderHistory->set("login",$this->auth->get("profile.login"));
			$orderHistory->set("changes", $history);
			$orderHistory->set("date",time());
			$orderHistory->create();
		}	
	} // }}}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

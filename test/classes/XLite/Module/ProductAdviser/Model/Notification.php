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
* CustomerNotification description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/

class XLite_Module_ProductAdviser_Model_Notification extends XLite_Model_Abstract
{	
	public $fields = array
	(
		"notify_id" 		=> 0,
		"type" 				=> "",
		"status"			=> CUSTOMER_REQUEST_QUEUED,
		"notify_key"		=> "",
		"date" 				=> 0,
		"profile_id" 		=> 0,
		"email" 			=> "",
		"person_info" 		=> "",
		"product_id" 		=> 0,
		"product_options" 	=> "",
		"quantity" 			=> 0,
		"price" 			=> 0,
	);	
	public $primaryKey = array("notify_id");	
    public $autoIncrement = "notify_id";	
	public $defaultOrder = "date";	
	public $alias = "customers_notifications";	

	public $ntfProduct = null;	
	public $errorPresent = false;	
	public $errorDescription;

    function _beforeSave() 
    {
    	parent::_beforeSave();
    	$po = $this->get("product_options");
    	if (!empty($po)) {
    		$this->set("product_options", serialize($this->get("product_options")));
    	}
    }

    function _updateProperties(array $properties = array())
    {
    	parent::_updateProperties($properties);
    	$po = $this->get("product_options");
    	if (!empty($po)) {
    		$this->set("product_options", unserialize($this->get("product_options")));
    	}
    }

    function get($name)
    {
    	switch ($name) {
    		case "listPrice":
                $result = parent::get("price");
                $product = new XLite_Model_Product($this->get("product_id"));
                $product->set("price", $result);
                $result = sprintf("%.02f", $product->get("listPrice"));
            break;
    		case "price":
    			$result = parent::get("price");
        		$result = sprintf("%.02f", doubleval($result));
            break;
    		default:
    			$result = parent::get($name);
            break;
        }

        return $result;
    }

    function getProductKey()
    {
    	$keyValue = array();
    	switch ($this->get("type")) {
    		case CUSTOMER_NOTIFICATION_PRODUCT:
    			$keyValue[] = $this->get("product_id");
				$po = $this->get("product_options");
    			if (!empty($po) && is_array($po)) {
        			$poStr = array();
        			foreach($po as $class => $v) {
        				$poStr[] = $class . ":" . $v["option"];
        			}
        			$keyValue[] = implode("|", $poStr);
    			}
    		break;
    		case CUSTOMER_NOTIFICATION_PRICE:
    			$keyValue[] = $this->get("product_id");
    		break;
    	}
		$keyValue = implode("|", $keyValue);
		return $keyValue;
    }

    function getProduct()
    {
    	if (!($this->get("type") == CUSTOMER_NOTIFICATION_PRODUCT || $this->get("type") == CUSTOMER_NOTIFICATION_PRICE))
    		return null;

    	if (is_null($this->ntfProduct)) {
			$p = new XLite_Model_Product($this->get("product_id"));
			if (!$p->is("exists")) {
				$this->errorPresent = true;
				$this->errorDescription = "Product was deleted.";
				return null;
			}

			$po = $this->get("product_options");
			if (!empty($po)) {
				if (!is_array($po)) {
					$po = unserialize($po);
				}
				if (is_array($po)) {
        			$poStr = array();
        			foreach($po as $class => $option) {
        				$poStr[] = $class . ": " . $option["option"];
        			}
        			$p->set("productOptionsStr", implode(", ", $poStr));
        		}
    		}

			$quantity = $this->get("quantity");
			$p->set("quantity", 0);
        	if ($this->xlite->get("PA_InventorySupport")) {
    			$inventory = new XLite_Module_InventoryTracking_Model_Inventory();
    			if ($inventory->find("inventory_id='".addslashes($this->getProductKey())."'")) {
        			$p->set("quantity", $inventory->get("amount"));
    			}
			}
			$this->ntfProduct = $p;
    	}
		
		return $this->ntfProduct;
    }

    function filter()
    {
		if ($this->xlite->get("NotificationProductNameFilter")) {
			$sub_str = strtolower($this->xlite->get("NotificationProductNameFilter"));
    		$product = $this->getProduct();
    		if (is_null($product)) {
    			return false;
    		}
    		if (!(strpos(strtolower($product->get("name")), $sub_str) !== false || strpos(strtolower($product->get("sku")), $sub_str) !== false)) {
    			return false;
    		}
		}
    	return parent::filter();
    }

    function createInventoryChangedNotification($inventoryChangedAmount)
    {
    	$result = false;

    	if (isset($inventoryChangedAmount) && is_array($inventoryChangedAmount) && $inventoryChangedAmount["enabled"]) {
    		if ($inventoryChangedAmount["amount"] > $inventoryChangedAmount["oldAmount"]) {
            	$check = array();
                $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";
        		$check[] = "notify_key='" . addslashes($inventoryChangedAmount["inventory_id"]) . "'";
        		$check[] = "quantity='" . $inventoryChangedAmount["oldAmount"] . "'";
        		$check[] = "status='" . CUSTOMER_REQUEST_QUEUED . "'";
        		$check = implode(" AND ", $check);

    			$notifications = $this->findAll($check);
    			if (is_array($notifications) && count($notifications) > 0) {
    				foreach($notifications as $notification) {
        				$notification->set("status", CUSTOMER_REQUEST_UPDATED);
                        $notification->update();
                        $result = true;
    				}
    			}
    		}
    	}

    	return $result;
    }
}

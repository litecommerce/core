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

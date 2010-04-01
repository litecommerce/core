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
* Admin_Dialog_product_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{	
	public $productsFound = 0;	
	public $notifyPresentedHash = array();	
	public $priceNotifyPresented = null;

	public function __construct(array $params)
	{
		parent::__construct($params);
		if ($this->is("relatedProductsEnabled")) {
			$this->pages["related_products"] = "Related products";
			$this->pageTemplates["related_products"] = "modules/ProductAdviser/RelatedProducts.tpl";
		}
	}

	public function getRelatedProducts($productId)
	{
		$product = new XLite_Module_ProductAdviser_Model_Product($productId);
		$relatedProducts = $product->getRelatedProducts();
		return $relatedProducts;
	}

	function getProducts()
	{
		if ($this->get("mode") != "search") {
			return array();
		}

		$p = new XLite_Model_Product();
		$result = $p->advancedSearch
		(
			$this->substring,
			$this->search_productsku,
			$this->search_category,
			$this->subcategory_search
		);
		if (is_array($result)) {
			$removedItems = array();
			foreach($result as $p_key => $product) {
				if ($product->get("product_id") == $this->product_id) {
					$removedItems[$p_key] = true;
				}
				if (!is_object($this->product)) {
					$this->product = new $this->product_id;
				}
				if (is_object($this->product)) {
					$rp = $this->product->getRelatedProducts();
					if (is_array($rp) && count($rp) > 0) {
						foreach($rp as $rp_item) {
							if ($rp_item->getComplex('product.product_id') == $product->get("product_id")) {
                        		$removedItems[$p_key] = true;
							}
						}
					}
				}
			}
        	if (is_array($result) && $this->new_arrivals_search) {
        		for($i=0; $i<count($result); $i++) {
                    if ($result[$i]->getNewArrival() == 0) {
                    	$removedItems[$i] = true;
                    }
        		}
    		}
    		if (count($removedItems) > 0) {
        		foreach($removedItems as $i => $j) {
    				unset($result[$i]);
        		}
    		}
			$this->productsFound = count($result);
		}

		return $result;
	}

	function action_add_related_products()
	{
		if (!$this->is("relatedProductsEnabled")) {
			return;
		}

		if (isset($this->product_ids) && is_array($this->product_ids)) {
			$relatedProducts = array();
			foreach ($this->product_ids as $product_id => $value) {
				$relatedProducts[] = new XLite_Model_Product($product_id);
			}
			$product = new XLite_Model_Product($this->product_id);
			$product->addRelatedProducts($relatedProducts);
		}	
	}

	function action_update_related_products()
	{
		if (!$this->is("relatedProductsEnabled")) {
			return;
		}

		if (isset($this->updates_product_ids) && is_array($this->updates_product_ids)) {
			foreach ($this->updates_product_ids as $product_id => $order_by) {
				$relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
				$relatedProduct->set("product_id", $this->product_id); 
				$relatedProduct->set("related_product_id", $product_id); 
				$relatedProduct->set("order_by", $order_by); 
				$relatedProduct->update();
			}
		}	
	}

	function action_delete_related_products()
	{
		if (!$this->is("relatedProductsEnabled")) {
			return;
		}

		if (isset($this->delete_product_ids) && is_array($this->delete_product_ids)) {
			$relatedProducts = array();
			foreach ($this->delete_product_ids as $product_id => $value) {
				$relatedProducts[] = new XLite_Model_Product($product_id);
			}
			$product = new XLite_Model_Product($this->product_id);
			$product->deleteRelatedProducts($relatedProducts);
		}
	}

	function action_info()
	{
		parent::action_info();

		if (!isset($this->NewArrivalStatus)) {
			return;
		}

        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
        $timeStamp = time();
        if (!$stats->find("product_id='".$this->get("product_id")."'")) {
        	$stats->set("product_id", $this->get("product_id"));
        	$stats->set("added", $timeStamp);
        	$stats->set("updated", $timeStamp);
            $stats->create();
        }

		$statusUpdated = false;

		switch ($this->NewArrivalStatus) {
			case 0:		// Unmark
				$stats->set("new", "N");
				$stats->set("updated", 0);
                $statusUpdated = true;
			break;
			case 1:		// Default period
				// (Forever || Unmark) --> Default period
				if ($stats->get("new") == "Y" || ($stats->get("new") == "N" && $stats->get("updated") == 0)) {
					$stats->set("new", "N");
					$stats->set("updated", $timeStamp);
                	$statusUpdated = true;
                }
			break;
			case 2:		// Forever
				$stats->set("new", "Y");
				$stats->set("updated", $timeStamp);
                $statusUpdated = true;
			break;
		}
		if ($statusUpdated) {
            $stats->update();
		}
	}

	function action_update_product_inventory()
    {
    	parent::action_update_product_inventory();

    	$this->checkNotification();
    }

    function action_update_tracking_option()
    {
    	parent::action_update_tracking_option();

    	$this->checkNotification();
    }

    function checkNotification()
    {
    	$inventoryChangedAmount = $this->xlite->get("inventoryChangedAmount");
		$this->session->set("inventoryNotify", null);
		
		$notification = new XLite_Module_ProductAdviser_Model_Notification();
		$notification->createInventoryChangedNotification($inventoryChangedAmount);
    }

    function isNotifyPresent($inventory_id)
    {
    	if (!isset($this->notifyPresentedHash[$inventory_id])) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";
    		$check[] = "notify_key='" . addslashes($inventory_id) . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(" AND ", $check);

    		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    		$this->notifyPresentedHash[$inventory_id] = $notification->count($check);
    	}
		return $this->notifyPresentedHash[$inventory_id];
    }

    function isPriceNotifyPresent()
    {
    	if (!isset($this->priceNotifyPresented)) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
    		$check[] = "notify_key='" . $this->product_id . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(" AND ", $check);

    		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    		$this->priceNotifyPresented = $notification->count($check);
    	}
		return $this->priceNotifyPresented;
    }

	function isRelatedProductsEnabled()
	{
		return (($this->config->getComplex('ProductAdviser.admin_related_products_enabled') == "Y") ? true : false);
	}
}

?>

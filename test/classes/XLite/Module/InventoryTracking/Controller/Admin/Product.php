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
* @package Module_InventoryTracking
* @access public
* @version $Id$
*/
class XLite_Module_InventoryTracking_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{	
	public $maxOrderBy = 1;
	protected $inventory = null;
	protected $orderItem = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["inventory_tracking"] = "Inventory tracking";
        $this->pageTemplates["inventory_tracking"] = "modules/InventoryTracking/product.tpl";
    }

    function init()
    {
        if (isset($_REQUEST["product_id"]) && intval($_REQUEST["product_id"]) > 0) {
			$product = new XLite_Model_Product($_REQUEST["product_id"]);
	    	if(!$this->xlite->get("ProductOptionsEnabled") || ($this->xlite->get("ProductOptionsEnabled") && !$product->hasOptions())) {
		    	if ($product->get("tracking") != 0 ) {
			    	$product->set("tracking", 0);
					$product->update();
				}
	    	}
		}
		parent::init();
	}

    function getOrderItem()
    {
        if (is_null($this->orderItem)) {
            $this->orderItem = new XLite_Model_OrderItem();
            $this->orderItem->set("product", $this->get("product"));
        }
        return $this->orderItem;
    }
    
    function getInventory()
    {
        if (is_null($this->inventory)) {
            $this->inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            $found = $this->inventory->find("inventory_id='" . addslashes($this->getOrderItem()->get('key')) . "'");
            $this->set("cardFound", $found);
            // set card status to DISABLED in ADD mode
            if (!$found) {
                $this->inventory->set("enabled", 0);
            }    
            if (isset($this->inventory_data)) {
                $this->inventory->set("properties", $this->inventory_data);
            }
        }
        return $this->inventory;
    }

	function action_tracking_selection()
	{	
		if (!isset($this->tracking)) return;
        $product = new XLite_Model_Product($this->product_id);
		$product->find("product_id = '".$this->product_id."'");
		$product->set("tracking",$this->tracking);
		$product->update();
	}

	function action_update_product_inventory()
    {
        $inventory = $this->get("inventory");
        if ($this->is("cardFound")) {
            $inventory->update();
        } else {
            $inventory->create();
        }
    }

    function getInventories()
    {
        $inventories = array();
		if (!$this->xlite->get("ProductOptionsEnabled")) {
            return $inventories;
        }    
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        $inventories = $inventory->findAll("inventory_id LIKE '".$this->product_id."|%'");
        for ($k = 0; $k < count($inventories); $k++) {
            $inventory_id = $inventories[$k]->get("inventory_id");
            $this->set("maxOrderBy", max($this->get("maxOrderBy"), $inventories[$k]->get("order_by")));
            $options = explode("|", $inventory_id);
            $id  = $options[0];
            $opt = array();
            for ($i = 1; $i < count($options); $i++) {
                @list($class, $option) = explode(":", $options[$i]);
                $opt[] = empty($option) ? "<b>$class</b>" : "<b>$class:</b> $option";
            }
            $inventories[$k]->product_options[$id] = $opt;
        }
        return $inventories;    
    }

    function getProductOptions()
    {
        $productOptions = array();
		if (!$this->xlite->get("ProductOptionsEnabled")) {
            return $productOptions;
        }
        $po = new XLite_Module_ProductOptions_Model_ProductOption();
        $productOptions = $po->findAll("product_id=$this->product_id");
        return $productOptions;
    }

	function updateProductInventorySku() // {{{
	{
		$product_id = addslashes($this->get("product_id"));
		$p = new XLite_Model_Product();
		if ($p->find("product_id='$product_id'")) {
			$p->updateInventorySku();
		}
	} // }}}

    function action_delete_tracking_option()
    {
        $i = new XLite_Module_InventoryTracking_Model_Inventory($this->inventory_id);
        $i->delete();
		$this->updateProductInventorySku();
    }

    function action_update_tracking_option()
    {
        $i = new XLite_Module_InventoryTracking_Model_Inventory($this->inventory_id);
		$this->optdata['inventory_sku'] = preg_replace("/\|/", "-", $this->optdata['inventory_sku']);
        $i->set("properties", $this->optdata);
        $i->update();
		$this->updateProductInventorySku();
    }

    function action_add_tracking_option()
    {
        if (empty($this->optdata)) {
            return;
        }    
		$inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        $options[] = $this->product_id;
        foreach ($this->optdata as $class => $optdata) {
            if (isset($optdata["used"])) {
                $options[] = stripslashes(isset($optdata["option"]) ?  "$class:" . $optdata["option"] : $class);
            }
        }
       	if (!$inventory->find("inventory_id='".addslashes(implode("|", $options))."'"))
		{
			$this->inventory_sku = preg_replace("/\|/", "-", $this->inventory_sku);
//			$this->changeProductInventorySku(null, $this->sku);
			$inventory->set("inventory_id", implode("|", $options));
            $inventory->set("inventory_sku", $this->inventory_sku);
	        $inventory->set("amount", $this->amount);
	   	    $inventory->set("low_avail_limit", $this->low_avail_limit);
	        $inventory->set("enabled", $this->enabled); 
	        $inventory->set("order_by", $this->order_by); 
	        $inventory->create();
			$this->updateProductInventorySku();
		} else
			$this->params[] = "error";
			$this->set("error", true);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

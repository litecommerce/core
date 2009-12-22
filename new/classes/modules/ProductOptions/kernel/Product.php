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
* Class description.
*
* @package Module_ProductOptions
* @access public
* @version $Id$
*/
class Module_ProductOptions_Product extends Product
{
	var $productOptions = null;

	function constructor($id = null)
	{
		parent::constructor($id);
		$this->fields['expansion_limit'] = 0;	
	}
	
    function getProductOptions()
    {
    	if (!is_null($this->productOptions)) {
    		return $this->productOptions;
    	}

        $po = func_new("ProductOption");
        $this->productOptions = $po->findAll("product_id='".$this->get("product_id")."'");
		return $this->productOptions;
    }

    function getProductOptionsNumber()
    {
		$this->getProductOptions();

		if (is_array($this->productOptions)) {
			return count($this->productOptions);
		} else {
			return 0;
		}
    }

    function hasOptions()
    {
        $po = func_new("ProductOption");
        return $po->hasOptions($this->get("product_id"));
    }

    function getOptionExceptions()
    {
        $pe = func_new("OptionException");
        return $pe->findAll("product_id='".$this->get("product_id")."'");
    }

    function hasExceptions()
    {
        $pe = func_new("OptionException");
        return $pe->hasExceptions($this->get("product_id"));
    }

    function hasOptionValidator()
    {
        $pv = func_new("OptionValidator");
        $pv->set("product_id", $this->get("product_id"));
        return strlen(trim($pv->get("javascript_code")));
    }

    function getOptionValidator()
    {
        $pv = func_new("OptionValidator");
        $pv->set("product_id", $this->get("product_id"));
        return $pv->get("javascript_code");
    }

	function isDisplayPriceModifier()
	{
		if ($this->xlite->get("WholesaleTradingEnabled")) {
			return $this->is("priceAvailable");
		}

		return true;
	}

    function delete()
    {
        // delete inventory card set with this product options
        // NOTE: this requires InventoryTracking module turned ON
        if ($this->xlite->get("InventoryTrackingEnabled")) {
            $inventory = func_new("Inventory");
            $product_id = $this->get("product_id");
            $inventories = $inventory->findAll("inventory_id='$product_id' OR inventory_id LIKE '$product_id" . "|%'");
            if (is_array($inventories)) {
    			foreach($inventories as $inventory_) {
    				$inventory_->delete();
    			}
            }
        }
        // delete product options, exceptions and javascript validator
        $option = func_new("ProductOption");
        $exception = func_new("OptionException");
        $validator = func_new("OptionValidator");
        $this->db->query("DELETE FROM ".$option->getTable(). " WHERE product_id='".$this->get("product_id")."'");
        $this->db->query("DELETE FROM ".$exception->getTable(). " WHERE product_id='".$this->get("product_id")."'");
        $this->db->query("DELETE FROM ".$validator->getTable(). " WHERE product_id='".$this->get("product_id")."'");

        // delete product
        parent::delete();
    }
	
	function clone()
	{
		if ( function_exists("func_is_clone_deprecated") && func_is_clone_deprecated() ) {
			$p = parent::cloneObject();
		} else {
			$p = parent::clone();
		}
		if ($this->config->get("ProductOptions.clone_product_options")) {

			$id = $p->get("product_id");

			$clone_option = func_new("ProductOption");
			$options = $clone_option->findAll("product_id='".$this->get("product_id")."'");
		
			if(empty($options))	return $p;
			foreach($options as $option) {
				$clone_option = func_new("ProductOption");
				$clone_option->set("properties",$option->get("properties"));
				$clone_option->set("option_id","");
				$clone_option->set("product_id",$id);
				$clone_option->create();
			}
 
			// Clone validator JS code
			$validator = func_new("OptionValidator", $this->get("product_id"));
			$js_code = $validator->get("javascript_code");
			if ( strlen(trim($js_code)) > 0 ) {
				$validator->set("product_id", $id);
				$validator->set("javascript_code", $js_code);
				$validator->create();
			}
			
			// Clone options exceptions
			$foo = func_new("OptionException");
			$exceptions = $foo->findAll("product_id = '" . $this->get("product_id") . "'");
			foreach ($exceptions as $exception) {			
				$optionException = func_new("OptionException");
				$optionException->set("product_id", $id);
				$optionException->set("exception", $exception->get("exception"));
				$optionException->create();
			}
			
		    if ($this->xlite->get("InventoryTrackingEnabled")&& $this->config->get('InventoryTracking.clone_inventory'))
    	        $this->cloneInventory($p, true);
		}	
			return $p;
	}

	/**
	* Remove unused ProductOptions records
	*/
	function collectGarbage()
	{
		parent::collectGarbage();

		$classes = array(
			"ProductOption" => array(
				"key" => "option_id",
				"table" => "product_options",
			),
			"OptionException" => array(
				"key" => "option_id",
				"table" => "product_options_ex",
			),
			"OptionValidator" => array(
				"key" => "product_id",
				"table" => "product_options_js",
			),
		);

		$products_table = $this->db->getTableByAlias("products");
		foreach ($classes as $class_name => $desc) {
	        $check_table = $this->db->getTableByAlias($desc["table"]);
	        $sql = "SELECT o.product_id AS object_product_id, o.".$desc["key"]." AS object_key FROM $check_table o LEFT OUTER JOIN $products_table p ON o.product_id=p.product_id WHERE p.product_id IS NULL";
    	    $result = $this->db->getAll($sql);

	        if (is_array($result) && count($result) > 0) {
    	        foreach ($result as $info) {
    	        	if ($class_name == "ProductOption" && $info["object_product_id"] == 0) {
    	        		continue;	// global product option
    	        	}
					$obj = func_new($class_name, $info["object_key"]);
            	    $obj->delete();
	            }
    	    }			
		}
	}

	function isInStock()
	{
		// check whether the method is already defined in InventoryTracking...
		if (method_exists(parent, "isInStock")) {
			return parent::isInStock();
		}

		if (!$this->xlite->get("mm.activeModules.InventoryTracking")) return true;

		// dublicate code of the method Module_InventoryTracking_Product::isInStock(): {{{
		$options = (array) $this->get("productOptions");
		$max_options = 0;
		if ($this->get("tracking") && $options) {
			// calculate the amount of options cominations for tracking with product options
			foreach ($options as $opt) {
				$type = strtolower($opt->get("opttype"));
				if ($type == "radio button" || $type == "selectbox") {
					if ($max_options == 0) $max_options = 1; 
					$cnt = count(explode("\n", $opt->get("options")));
					if ($cnt > 0) $max_options *= $cnt;
				}
			}
		}

		if ($max_options && $this->get("tracking")) {
			$inv = func_new("Inventory");
			$product_id = $this->get("product_id");
			$out_of_stock = $inv->count("inventory_id LIKE '$product_id|%' AND amount <= 0");
			return ($out_of_stock < $max_options);
		} else {
			$out_of_stock = ($this->get("inventory.found") && ($this->get("inventory.amount") <= 0));
			return !$out_of_stock;
		}
		return true;
		// }}} dublicate code of the method Module_InventoryTracking_Product::isInStock()
	}

    function isOutOfStock()
    {
		// check whether the method is already defined in InventoryTracking...
		if (method_exists(parent, "isOutOfStock")) {
			return parent::isOutOfStock();
		}
		return !$this->isInStock();
    }

	function _importCategory($product, $properties, $default_category)
	{
		$oldCategories = array();
		$categories = $product->get("categories");
		if (is_array($categories)) {
			foreach($categories as $cat) {
				$oldCategories[] = $cat->get("category_id");
			}
		}

		parent::_importCategory($product, $properties, $default_category);

		$product->updateGlobalProductOptions($oldCategories);
	}

	function updateGlobalProductOptions($oldCategories = array())
	{
		$product = $this;

		$newCategories = array();
		$categories = $product->get("categories");

		if (is_array($categories)) {
			foreach($categories as $cat) {
				$newCategories[] = $cat->get("category_id");
			}
		}

		$deleteOnly = array_diff($oldCategories, $newCategories);
		$addOnly = array_diff($newCategories, $oldCategories);

		if (count($deleteOnly) > 0) {
			$productOptions = $product->getProductOptions();
			$globalProductOptions = array();
			foreach($productOptions as $po) {
				if ($po->get("parent_option_id") > 0) {
					$gpo = func_new("ProductOption", $po->get("parent_option_id"));
					$categories = $gpo->getCategories();
					$result = array_intersect($categories, $deleteOnly);
					if (count($result) > 0 && count(array_intersect($categories, $newCategories)) == 0) {
						$po->delete();
					}
				}
			}
		}

		if (count($addOnly) > 0) {
			$gpo = func_new("ProductOption");
			$gpos = $gpo->findAll("product_id='0' AND parent_option_id='0'");
			if (is_array($gpos)) {
				foreach($gpos as $gp) {
					$categories = $gp->getCategories();
					$result = array_intersect($categories, $addOnly);

					if (count($result) > 0 || (is_array($categories) && count($categories) == 0)) {
						$productOptions = $product->getProductOptions();
						$need_update = true;
						foreach($productOptions as $po) {
							if ($po->get("parent_option_id") == $gp->get("option_id")) {
							$need_update = false;
							}
						}
						if ($need_update) {
							$po = func_new("ProductOption");
							$po->set("properties", $gp->get("properties"));
							$po->set("option_id", null);
							$po->set("product_id", $this->get("product_id"));
							$po->set("parent_option_id", $gp->get("option_id"));
							$po->create();
						}
					}
				}
			}
		}
	}
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

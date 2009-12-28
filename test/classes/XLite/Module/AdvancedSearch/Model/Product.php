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

/**
* 
*
* @package AdvancedSearch 
* @access public
* @version $Id$
*/

class XLite_Module_AdvancedSearch_Model_Product extends Product // {{{
{
    function __beforeAdvancedSearch
  	(	$substring			= "", 
		$orderby 			= "name",
		$sku 				= null, 
		$_category_id 		= null, 
		$subcategory_search	= false, 
		$onlyindexes 		= false, 
		$_logic		 		= 1, 
		$_name		 		= true,
		$_description		= false,
		$_brief_description	= false,
		$_meta_tags			= false,
		$_extra_fields		= false,
		$_options			= false,
		$start_price 		= null, 
		$end_price 			= null, 
		$start_weight 		= null, 
		$end_weight 		= null) 	
    {
    	$this->xlite->set("GlobalQuickCategoriesNumber", false);
    }
	
	function _advancedSearch // {{{
  	(	$substring			= "", 
		$orderby 			= "name",
		$sku 				= null, 
		$_category_id 		= null, 
		$subcategory_search	= false, 
		$onlyindexes 		= false, 
		$_logic		 		= 1, 
		$_name		 		= true,
		$_description		= false,
		$_brief_description	= false,
		$_meta_tags			= false,
		$_extra_fields		= false,
		$_options			= false,
		$start_price 		= null, 
		$end_price 			= null, 
		$start_weight 		= null, 
		$end_weight 		= null) 	
	{
		$this->__beforeAdvancedSearch($substring, $orderby, $sku, $_category_id, $subcategory_search, $onlyindexes, $_logic, $_name, $_description, $_brief_description, $_meta_tags, $_extra_fields, $_options, $start_price, $end_price, $start_weight, $end_weight);

		$substring = addslashes($substring);	
		$keywords = explode(" ", trim($substring));
	    switch($_logic) {
		    case 1: 
		    	$logic = "OR"; 
		    break;
		    case 2: 
		    	$logic = "AND"; 
		    break;
		    case 3: 
		    	$logic = "OR";
				$keywords = array($substring); 
			break;
		}
		
		$field_values = array
		(
			"name" => $_name, 
			"brief_description" => $_brief_description, 
			"description" => $_description, 
			"meta_tags" => $_meta_tags,
            "sku" => true
		);
		$search_query = $this->getSearchQuery($field_values, $keywords, $logic);

		if (!empty($_category_id)) {
			$products = in_array(true, $field_values) ? $this->getCategoryProducts($_category_id, $search_query, $subcategory_search) : array(); 
		} else {
			$product = new XLite_Model_Product();
            $product->fetchKeysOnly = true;
			$products = in_array(true, $field_values) ? $product->findAll($search_query, $orderby) : array();
		} 
		
		if ($_extra_fields && strlen($keywords[0]) > 0) {
			$field_values = array
			(
				"name" => true, 
				"default_value" => true
			);
			$search_query = $this->getSearchQuery($field_values, $keywords, $logic); 
			
			$extraField = new XLite_Model_ExtraField();	
			$field_ids = array();
			if (true == ($globalExtraFields = $extraField->findAll("product_id = 0 AND (" . $search_query.")"))) {
				foreach($globalExtraFields as $gef) 
					if (!is_null($gef->get("categories"))) {
						$categories = explode("|", $gef->get("categories"));
						foreach($categories as $cat_id) {
							$category = new XLite_Model_Category($cat_id);
							$field_ids = array_merge($field_ids, $this->getIds($category->get("products")));
						}
					}	
			}
			$productMethods = array_map("strtolower", get_class_methods($extraField->get("product")));
			$isNewEF = in_array("isglobal", $productMethods);
			if (true == ($extraFields = $extraField->findAll("product_id <> 0 AND (" . $search_query.")"))) {
				foreach($extraFields as $ef) {
					if ($isNewEF) {
						$field_ids = array_merge($field_ids, array($ef->get("product_id")));
					} else {
            			$product = $ef->getProduct();
    					if ($product->isExists()) {
    						$field_ids = array_merge($field_ids, array($ef->get("product_id")));
    					} else {
    						$ef->delete();
    					}
					}
				}
			}
			
			$field_values = array("value" => true);
			$search_query = $this->getSearchQuery($field_values, $keywords, $logic);
			$fieldValue = new XLite_Model_FieldValue();
			if (true == ($fieldValues = $fieldValue->findAll($search_query))) {
				foreach($fieldValues as $fv) {
					if ($isNewEF) {
						$field_ids = array_merge($field_ids, array($fv->get("product_id")));
					} else {
            			$product = new XLite_Model_Product($fv->get("product_id"));
    					if ($product->isExists()) {
                        	$field_ids = array_merge($field_ids, array($fv->get("product_id")));
    					} else {
    						$fv->delete();
                        }
					}
                }
            }
		}

		if ($_options&&$this->xlite->get("ProductOptionsEnabled")) {
			$field_values = array
			(
				"optclass" => true, 
				"opttext" => true, 
				"options" => true
			);
			$search_query = $this->getSearchQuery($field_values, $keywords, $logic);

			$productOption = new XLite_Module_ProductOptions_Model_ProductOption();
			$productOption->fetchKeysOnly = true;
			$productOptions = $productOption->findAll("product_id <> 0 AND (" . $search_query. ")"); 
			$option_ids = $this->getIds($productOptions);
		}
		
		$ef_po_products = array_unique(array_merge(is_array($field_ids) ? $field_ids : array(), is_array($option_ids) ? $option_ids : array()));
		if (isset($_category_id)) {
			$ef_po_products = array_unique(array_intersect($ef_po_products, $this->getIds($this->getCategoryProducts($_category_id, "", $subcategory_search))));
		}
		$ids = array_unique(array_merge($this->getIds($products), $ef_po_products));

		$search = $this->_constructSearchArray($start_price, $end_price, $start_weight, $end_weight, $sku);
		$search_query = implode(" AND ", (array)$search);
		
		$product_limit = array();
		if (!empty($search_query)) {
			$product = func_new("Product");
        	$product->fetchKeysOnly = true;
			$product_limit = $product->findAll($search_query, $orderby);
			$ids = array_unique(array_intersect($ids, $this->getIds($product_limit)));
		}
												 
		$products = array();	
		if (!empty($ids))
			foreach ($ids as $id) {
                $product = func_new("Product", $id);
                $products[$id] = $product; 
            } 
		return $products;
		

	} // }}}

	function _constructSearchArray($start_price, $end_price, $start_weight, $end_weight, $sku) // {{{
	{
		$search = array();
        if (!is_null($start_price))     $search[] = "price >= '".addslashes($start_price)."'";
        if (!is_null($end_price))       $search[] = "price <= '".addslashes($end_price)."'";
        if (!is_null($start_weight))    $search[] = "weight >= '".addslashes($start_weight)."'";
        if (!is_null($end_weight))      $search[] = "weight <= '".addslashes($end_weight)."'";
	    if (!is_null($sku))             $search[] = "sku LIKE '%".addslashes($sku)."%'";

		return $search;
	} // }}}

	function getIds($items) // {{{
	{
		$ids = array();
		if (is_array($items)) {
			foreach($items as $item) {
				$ids[] = $item->get("product_id");
			}
		}
		return !empty($ids) ? $ids : array();
	} // }}}
	
	function getSearchQuery($field_values, $keywords, $logic) // {{{ 
	{
		$search = array();
		foreach($field_values as $field_value => $condition) {
			if ($condition) {
				$query = array();
				foreach ($keywords as $keyword)
					$query[] = "$field_value LIKE '%$keyword%'";
				$search[] = (count($keywords) > 1 ? "(" . implode(" $logic ", $query) . ")" :  implode("", $query));
			}
		}	
		$search_query = implode(" OR ", $search);
		return $search_query;
	} // }}} 

	function getCategoryProducts(&$category_id, $search_query,  $subcategory_search = false) // {{{
	{
		$category = new XLite_Model_Category($category_id);
        $products = $category->getProducts(!empty($search_query) ? "($search_query)" : "", null, true);
		if ($subcategory_search) {
			$categories = $category->getSubcategories();
				for ($i=0; $i<count($categories); $i++) {
					$category_products = $this->getCategoryProducts($categories[$i]->get("category_id"), $search_query, $subcategory_search);
					$products = array_merge($products, array_values($category_products));
				}	
		}
		return $products;	
	} // }}}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

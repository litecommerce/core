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

class Dialog_advanced_search extends Dialog
{
    var $params = array("target", "mode", "substring");
    var $products = null;
	var $search = null;
	var $profile = null;

	function &getProfile()
	{
		if (is_null($this->profile)) 
			$this->profile = func_new("Profile",$this->auth->get("profile.profile_id"));
		return $this->profile;
	}

    function init()
    {
		if (is_null($this->session->get("search")) && $this->auth->is("logged")) {
			$profile = $this->get("profile");
			$this->session->set("search",unserialize($profile->get("search_settings")));
		}

        parent::init();

        $this->set("pager.itemsPerPage", $this->get("config.General.products_per_page"));
        if (!isset($this->action)) {
            $this->session->set("productListURL", $this->get("url"));
		}
		if ($this->get("properties.search")) {
		    $this->session->set("search", $this->get("properties.search"));
		}

		$this->search = $this->session->get("search");
		if (is_null($this->search) || !is_array($this->search) || $this->session->get("quick_search")) {
			$this->search["substring"] = $this->session->get("quick_search");
            $this->session->set("quick_search",null);
			$this->search["logic"] = 1;
			$this->search["title"] = 1;
			$this->search["brief_description"] = 1;
			$this->search["description"] = 1;
            $this->search["meta_tags"] = 1;
	        $this->search["extra_fields"] = 1;
	        $this->search["options"] = 1;
			$this->search["subcategories"] = 1;
		};
    }
	
	function action_save_filters()
	{
		$profile = $this->get("profile");
		$profile->set("search_settings",serialize($this->session->get("search")));
		$profile->update();
	}	

    function &getProducts()
    {
        if (!isset($this->mode)) return array();

        is_null($this->get("properties.search")) ?  $properties = $this->session->get("search") : $properties = $this->get("properties.search");

        if (!empty($properties["substring"])&&!isset($properties["title"])&&!isset($properties["brief_description"])&&!isset($properties["description"])&&!isset($properties["meta_tags"])&&!isset($properties["extra_fields"])&&!isset($properties["options"])) return array();
        if (is_null($this->products)) {
            $p = func_new("Product");
            if (is_array($properties))
                foreach($properties as $key => $value) {
                    if (empty($properties[$key])) $properties[$key] = null;
                    $properties[$key] = addslashes($properties[$key]);
                }
            $properties["title"] = isset($properties["title"]);
            $properties["description"] = isset($properties["description"]);
            $properties["brief_description"] = isset($properties["brief_description"]);
   			$properties["subcategories"] = isset($properties["subcategories"]);
            $properties["meta_tags"] = isset($properties["meta_tags"]);
            $properties["extra_fields"] = isset($properties["extra_fields"]);
            $properties["options"] = isset($properties["options"]);
															
            if (isset($properties["price"])) {
                $price = explode(",",$properties["price"]);
                $properties["start_price"] = $price[0];
                $properties["end_price"] = !empty($price[1]) ? $price[1] : null;
                $orderby = "price";
            }
            if (isset($properties["weight"])) {
                $weight = explode(",",$properties["weight"]);
                $properties["start_weight"] = $weight[0];
                $properties["end_weight"] = !empty($weight[1]) ? $weight[1] : null;
                $orderby = "weight";
            }
            $this->products =& $p->_advancedSearch
            (
            	$properties["substring"],
            	$orderby,
            	$properties["sku"],
            	$properties["category"],
            	$properties["subcategories"],
            	true,
            	$properties["logic"],
            	$properties["title"],
            	$properties["description"],
            	$properties["brief_description"],
				$properties["meta_tags"],
				$properties["extra_fields"],
				$properties["options"],
            	$properties["start_price"],
            	$properties["end_price"],
            	$properties["start_weight"],
            	$properties["end_weight"]
            );

            $searchStat = func_new("SearchStat");
            $searchStat->add($properties["substring"], count($this->products));
        }

        return $this->products;
    }

	function getCount()	{
		return count($this->get("products"));
	}
 
	function cmp($val1, $val2)
	{	if ($val1["start"] == $val2["start"]) { 
		if ($val1["label"] > $val2["label"]) return -1; else return 1; 
		}
		if ($val1["start"] < $val2["start"]) return -1; else return 1;
	}
	
	function &getPrices()
	{
		$prices = unserialize($this->config->get("AdvancedSearch.prices"));
		usort($prices, array(&$this,"cmp"));
		return $prices;
	}
	
	function &getWeights()
	{
     	$weights =  unserialize($this->config->get("AdvancedSearch.weights"));
        usort($weights, array(&$this,"cmp"));
        return $weights;
	}

	function strcat($val1,$val2,$delimeter)
	{
		return $val1.$delimeter.$val2;
	}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

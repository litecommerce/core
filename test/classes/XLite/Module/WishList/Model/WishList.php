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
* WishList base class.
*
* @package Wishlist base class
* @access public
* @version $Id$
*/

class XLite_Module_WishList_Model_WishList extends XLite_Model_Abstract
{ // {{{

	var $fields = array (
		"wishlist_id"	=>	0,
		"profile_id"	=>  0,
		"order_by"		=>	0,
		"date"			=> '');
		
	var $alias 			= "wishlist";
	var $defaultOrder 	= "wishlist_id";
	var $primaryKey 	= array("wishlist_id","profile_id");
	var $autoIncrement 	= "wishlist_id";
	var $profile		= null;

	function getProducts() // {{{
	{
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
        return $wishlist_product->findAll("wishlist_id =" . $this->get("wishlist_id"));

    } // }}}  
	
	function getProfile() // {{{ 
	{
		if (is_null($this->profile)) { 
			$this->profile = new XLite_Model_Profile($this->get("profile_id"));	
		}
		return $this->profile;
	} // }}} 

	function collectGarbage() // {{{ 
	{
		$wishlist = new XLite_Module_WishList_Model_WishList();
		$wishlists = $wishlist->findAll();
		if (is_array($wishlists)) {
			foreach($wishlists as $wishlist_) 
				if (!$wishlist_->get("products")) $wishlist_->delete();
		}

	} // }}}
	
	function search($start_id, $end_id, $profile, $sku, $name,$startDate,$endDate) // {{{
	{
		$where = array();

		if (!empty($start_id)) {
            $where[] = "wishlist_id >=".(int)$start_id;
        }
        if (!empty($end_id)) {
            $where[] = "wishlist_id <=".(int)$end_id;
        }
	    if ($profile) {
            $where[] = "profile_id='".$profile->get("profile_id")."'";
        }
        if ($startDate) {
            $where[] = "date>=$startDate";
        }
        if ($endDate) {
            $where[] = "date<=$endDate";
        }

		$wishlists = $this->findAll(implode(" AND ", $where),"date DESC");

		if (!empty($sku)||!empty($name)) {
	        $product = new XLite_Model_Product();
			$found = array();
			$found_product = $product->findImportedProduct($sku,"","",false);
			if ($found_product)
				$found[] = "product_id = " . $found_product->get("product_id");  
	       	$found_product = $product->findImportedProduct("","",$name,false);
            if ($found_product)
                $found[] = "product_id = " . $found_product->get("product_id");      
			if (empty($found)) return array();
			$wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
			$wishlist_products = $wishlist_product->findAll(implode(" OR ",$found));
			$wishlist_ids = array();
			foreach ($wishlist_products as $wishlist_product)
				if (!in_array($wishlist_product->get("wishlist_id"),$wishlist_ids)) 
					$wishlist_ids[] = $wishlist_product->get("wishlist_id"); 
			foreach($wishlists as $key => $wishlist)
				if (!in_array($wishlist->get("wishlist_id"),$wishlist_ids)) unset($wishlists[$key]);
		} 
		return $wishlists;  

	} // }}}

} // }}}


// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

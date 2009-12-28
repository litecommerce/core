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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
*
* @access public
* @version $Id$
* @package Module_FeaturedProducts
*
*/
class XLite_Module_FeaturedProducts_Controller_Admin_Categories extends XLite_Controller_Admin_Categories
{
    function constructor()
    {
        parent::constructor();
        if (!isset($_REQUEST["search_category"])) {
            $_REQUEST["search_category"] = $_REQUEST["category_id"];
        }    
    }

	function action_add_featured_products()
	{
		if (isset($_POST["product_ids"])) {
			$products = array();
			foreach ($_POST["product_ids"] as $product_id => $value) {
				$products[] = new XLite_Model_Product($product_id);
			}
			$category = new XLite_Model_Category($this->category_id);
			$category->addFeaturedProducts($products);
		}
	}

    function getProducts()
    {
        if ($this->get("mode") != "search") {
            return array();
        }
        $p = new XLite_Model_Product();
        $result = $p->advancedSearch($this->substring,
                                      $this->search_productsku,
                                      $this->search_category,
                                      $this->subcategory_search);
        $this->productsFound = count($result);
        return $result;
    }

	function action_update_featured_products()
	{
		if (isset($_POST["delete"])) {
			$products = array();
			foreach ($_POST["delete"] as $product_id => $value) {
				$products[] = func_new("Product",$product_id);
			}
			$category = new XLite_Model_Category($this->category_id);
			$category->deleteFeaturedProducts($products);
		}
		if (isset($_POST["orderbys"])) {
			foreach ($_POST["orderbys"] as $product_id => $order_by) {
				$fp = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
				$fp->set("category_id", $this->category_id);
				$fp->set("product_id", $product_id);
				$fp->set("order_by", $order_by);
				$fp->update();
			}
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

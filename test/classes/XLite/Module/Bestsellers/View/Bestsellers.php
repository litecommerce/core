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
* Returns the category for the bestsellers list.
*
* @package Module_Bestsellers
* @access public
* @version $Id$
*/
class XLite_Module_Bestsellers_View_Bestsellers extends XLite_View
{
    var $bestsellers = null;
    var $ids = array();

    function getVisible()
    {
        if (!$this->get("bestsellers")) {
            return false;
        }
        return $this->visible;
    }

    function getBestsellers()
    {
        $category = $this->get("category");
        $cat_id = $category->get("category_id");

        $bestsellersCategories = $this->xlite->get("BestsellersCategories");
        if (!(isset($bestsellersCategories) && is_array($bestsellersCategories))) {
        	$bestsellersCategories = array();
        }

        if (isset($bestsellersCategories[$cat_id])) {
        	$this->bestsellers = $bestsellersCategories[$cat_id];
            return $this->bestsellers;
        }

        // select category products
        $products = "";
        if ($cat_id != $category->get("topCategory.category_id")) {
            // get all subcategories ID
            $this->getSubcategories($category);
            if (empty($this->ids)) {
                return array();
            }
            $categories = join(',', $this->ids);
			$table = $this->db->getTableByAlias("product_links");
            $sql = "SELECT product_id FROM $table WHERE category_id IN ($categories)";
            
            $ids = $category->db->getAll($sql);    
            foreach ($ids as $id) {
                $array[] = $id["product_id"];        
            }
            // no products found
            if (empty($array)) {
                return array();
            } 
            $products = join(',', $array);
            $products = "AND items.product_id IN ($products)";
        }

        // build SQL query to select bestsellers
        $order_items_table = $this->db->getTableByAlias("order_items");
        $orders_table = $this->db->getTableByAlias("orders");
        $products_table = $this->db->getTableByAlias("products");
		
		$limit = 0;
        if (!is_null($this->get("config.Bestsellers.number_of_bestsellers")) && 
            is_numeric($this->get("config.Bestsellers.number_of_bestsellers")))
        {
            $limit = $this->get("config.Bestsellers.number_of_bestsellers");
        } else {
        	$limit = 5;
        }
        if ($limit <= 0) {
        	$limit = 5;
        }
        $limitGrace = $limit * 10;

        $sql =<<<EOT
        SELECT items.product_id, sum(items.amount) as amount
        FROM $order_items_table items
        LEFT OUTER JOIN $orders_table orders ON items.order_id=orders.order_id
        LEFT OUTER JOIN $products_table products ON items.product_id=products.product_id
        WHERE (orders.status='P' OR orders.status='C') AND products.enabled=1
        $products
        GROUP BY items.product_id
        ORDER BY amount DESC
        LIMIT $limitGrace
EOT;

        // fill bestsellers array with product instances
        $best = $category->db->getAll($sql);
        foreach ($best as $p) {
            $product = new XLite_Model_Product($p["product_id"]);
            $categories = $product->get("categories");
            if (!empty($categories) && $product->filter()) {
                $product->category_id = $categories[0]->get("category_id");
                $this->bestsellers[] = $product;
                if (count($this->bestsellers) == $limit) {
                	break;
                }
            }
        }

		if (!is_array($this->bestsellers)) {
			$this->bestsellers = array();
		}
		$bestsellersCategories[$cat_id] = $this->bestsellers;
		$this->xlite->set("BestsellersCategories", $bestsellersCategories);

        return $this->bestsellers;
    }

    function getCategory()
    {
        $category = new XLite_Model_Category();
        if (isset($_REQUEST["category_id"])) {
            $category = new XLite_Model_Category($_REQUEST["category_id"]); 
        } else {
            $category = $category->get("topCategory");
        }
        return $category;
    }

    function getSubcategories(&$category)
    {
        $this->ids[] = $category->get("category_id");
        $categories = $category->getSubcategories();
        for ($i=0; $i < count($categories); $i++) {
            $this->getSubcategories($categories[$i]);
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

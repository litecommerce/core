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
class Module_InventoryTracking_Admin_Dialog_product_list extends Admin_Dialog_product_list
{
    function init() // {{{
    {
        $this->params[] = "inventory";
        parent::init();
    } // }}}

    function getProducts() // {{{
    {
        if (is_null($this->productsList)) {
            $this->productsList = parent::getProducts();
            if (!is_array($this->productsList)) $this->productsList = array();

            if (!in_array($this->get("inventory"), array('low','out'))) return $this->productsList;

            if ($this->get("inventory") == 'out') $condition = "AND amount <= 0";
            elseif ($this->get("inventory") == 'low') $condition = "AND amount > 0 AND amount <= low_avail_limit";

            foreach ($this->productsList as $k=>$product) {
                if (!is_object($product)) {
                    $product = func_new("Product", $product['data']['product_id']);
                }
                $product_id = $product->get("product_id");

                $inv = func_new("Inventory");
                if ($this->xlite->get("ProductOptionsEnabled") && $product->get("productOptions") && $product->get("tracking")) {
                    $inventories = (array) $inv->findAll("inventory_id LIKE '$product_id" . "|%' AND enabled=1 $condition");
                    if (empty($inventories)) {
                        unset($this->productsList[$k]); 
                    }
                } else {
                    // track without options:
                    if (!$inv->find("inventory_id='$product_id' AND enabled=1 $condition")) {
                        unset($this->productsList[$k]); 
                    }
                }
            }
            $this->productsFound = count($this->productsList);
        }
        return $this->productsList;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

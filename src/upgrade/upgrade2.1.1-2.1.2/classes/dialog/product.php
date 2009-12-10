<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* @package Dialog
* @access public
* @version $Id: product.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*/
class Dialog_product extends Dialog
{
    var $params = array("target", "product_id", "category_id");

    function init()
    {
		if (isset($_REQUEST["product_id"]) && empty($_REQUEST["product_id"])) {
            return $this->redirect("cart.php" . ((isset($_REQUEST["category_id"]) && !empty($_REQUEST["category_id"])) ? "?target=category&category_id=" . $_REQUEST["category_id"] : "" ));
		}
        parent::init();
    }
    
    function action_buynow()
    {
        $this->set("returnUrl", "cart.php?target=cart&action=add&product_id=$this->product_id&category_id=$this->category_id");
    }

    function getLocationPath()
    {
        $result = array();
        $path = $this->get("category.path");
        if (!is_null($path)) {
            foreach ($this->get("category.path") as $category) {
                $result[$category->get("name")] = "cart.php?target=category&category_id=" . $category->get("category_id");
            }
        }    
        $result [$this->get("product.name")] = $this->get("url");
        return $result;
    }

    // 'description' meta tag
    function getDescription()
    {
        $description = $this->get("product.description");
        if (empty($description)) {
            $description = $this->get("product.brief_description");
        }
        return $description;
    }

    // 'keywords' meta tag
    function getKeywords()
    {
        return $this->get("product.meta_tags");
    }

    function isAvailableForSale()
    {
        return true;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

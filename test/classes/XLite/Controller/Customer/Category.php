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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Category navigation dialog.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Category extends XLite_Controller_Customer_Abstract
{	
    public $params = array("target", "category_id");

    function init()
    {
        parent::init();
		if (isset($this->category_id) && empty($this->category_id)) {
            return $this->redirect("cart.php");
		}
        $this->setComplex("pager.itemsPerPage", $this->getComplex('config.General.products_per_page'));
        if (!isset($_REQUEST["action"])) {
            $this->session->set("productListURL", $this->get("url"));
        }
    }
    
    function getLocationPath()
    {
        $result = array();
        foreach ($this->getComplex('category.path') as $category) {
            $name = $category->get("name");
            while (isset($result[$name])) {
            	$name .= " ";
            }
            $result[$name] = "cart.php?target=category&category_id=" . $category->get("category_id");
        }
        return $result;
    }

    function getTitle() 
    {
        return ($this->getComplex('category.meta_title') ? $this->getComplex('category.meta_title') : $this->getComplex('category.name'));
    }

    /**
    * 'description' meta-tag value.
    */
    function getDescription()
    {
        $description = $this->getComplex('category.description');
        if (empty($description)) {
            $description = null;
        }
		return $description;
    }

    function getMetaDescription()
    {
        $description = $this->getDescription();
        return ($this->getComplex('category.meta_desc') ? $this->getComplex('category.meta_desc') : $description);
    }

    /**
    * 'keywords' meta-tag value.
    */
    function getKeywords()
    {
        return $this->getComplex('category.meta_tags');
    }

    function handleRequest()
    {
        if (!$this->isComplex('category.exists') || !$this->isComplex('category.enabled')) {
            return $this->redirect("cart.php");
        }
        parent::handleRequest();
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
* Admin_Dialog_product_LayoutOrganizer description.
*
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class Admin_Dialog_product_LayoutOrganizer extends Admin_Dialog_product
{
    function init()
    {
    	if (!in_array("product_templates", array_keys($this->pages))) {
    		$pages1 = array_slice($this->pages, 0, 2);
    		$pages2 = array_slice($this->pages, 2);
            $this->pages = array_merge(array_slice($this->pages, 0, 2), array("product_templates" => "Templates"), array_slice($this->pages, 2));
            $this->pageTemplates["product_templates"] = "modules/LayoutOrganizer/product.tpl";
    	}

    	parent::init();
    }

    function action_modify_templates()
    {
        if (isset($this->product_id) && $this->product_id > 0) {
        	$product = func_new("Product", $this->product_id);
        	$product->set("custom_template", $this->custom_template);
			$parent = $product->get("parent");
    		if ($this->custom_template < 0) {
				$product->set("template_name", $parent->getTemplate("p_custom_template"));
    		} else {
    			$scheme = func_new("TemplatesScheme", $this->custom_template);
    			if (is_object($scheme)) {
    				$product->set("template_name", $scheme->getTemplate("p_custom_template"));
    			} else {
            		$product->set("template_name", $parent->getDefaultTemplate("p_custom_template"));
    			}
    		}
            $product->update();
    	}
    }
	
	function getSchemes()
	{
		if (!is_null($this->schemes)) {
			return $this->schemes;
		}

    	$sm = func_new("Admin_Dialog_Scheme_Manager");
		$sm->initLayout();
    	$sm->getSchemes(false);
    	$this->schemes = $sm->schemes;
		return $this->schemes;
	}

	function getProductCustomTemplate()
	{
		if (!is_object($this->product)) {
			return 0;
		}
		$scheme = ($this->product->get("custom_template_enabled")) ? $this->product->get("custom_template") : 0;
		return $scheme;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

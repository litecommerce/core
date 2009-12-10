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
* Module_Promotion description.
*
* @package Module_Promotion
* @access public
* @version $Id: Promotion.php,v 1.38 2008/10/23 11:59:15 sheriff Exp $
*/
class Module_Promotion extends Module
{
	var $minVer = '2.1.1';
    var $showSettingsForm = true;

    function init()
    {
        if(!check_module_license("Promotion", true)) {
        	return;
        }
        
        parent::init();

		$this->addDecorator("Category", "Module_Promotion_Category");
		$this->addDecorator("Product", "Module_Promotion_Product");
		$this->addDecorator("Profile", "Module_Promotion_Profile");
		$this->addDecorator("Order", "Module_Promotion_Order");
		$this->addDecorator("OrderItem", "Module_Promotion_OrderItem");
        $this->addDecorator("TaxRates", "Module_Promotion_TaxRates");

        // replace cart item and totals templates
        $this->addLayout("shopping_cart/item.tpl", "modules/Promotion/item.tpl");
        $this->addLayout("shopping_cart/totals.tpl", "modules/Promotion/totals.tpl");
		$this->addLayout("shopping_cart/delivery.tpl", "modules/Promotion/delivery.tpl");

		$this->addDecorator("Dialog_checkout", "Module_Promotion_Dialog_checkout");
		$this->addDecorator("Dialog_cart", "Module_Promotion_Dialog_cart");
		$this->addDecorator("Cart", "Module_Promotion_Cart");
		$this->addDecorator("Widget", "Module_Promotion_Widget");
		$pm =& func_new("PaymentMethod");
		$pm->registerMethod("bonus_points");

		$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_Promotion");
		$this->addDecorator("Admin_Dialog_taxes", "Module_Promotion_Admin_Dialog_taxes");

		$this->xlite->set("PromotionEnabled",true);
    }

    function uninstall()
    {
        func_cleanup_cache("classes");
        func_cleanup_cache("skins");

        parent::uninstall();
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

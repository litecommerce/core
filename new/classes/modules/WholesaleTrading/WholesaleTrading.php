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
| The Initial Developer of the Original Code is Creative Development LCC       |
| Portions created by Creative Development LCC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* TOP level module class.
*
* @package Module_WholesaleTrading
* @access public
* @version $Id$
*/
class Module_WholesaleTrading extends Module
{
	var $showSettingsForm = true;
	var $minVer = "2.1.2";

	function getSettingsForm()
	{
		return "admin.php?target=wholesale";
	}
	
    function init()
    {
        parent::init();

        // common class decorations
        $this->addDecorator("Product", "Module_WholesaleTrading_Product");
        $this->addDecorator("Category", "Module_WholesaleTrading_Category");
        $this->addDecorator("Order", "Module_WholesaleTrading_Order");
		$this->addDecorator("OrderItem", "Module_WholesaleTrading_OrderItem");
        $this->addDecorator("TaxRates", "Module_WholesaleTrading_TaxRates");
		$this->addDecorator("Profile", "Module_WholesaleTrading_Profile");
	    $this->addDecorator("Auth", "Module_WholesaleTrading_Auth");

		$this->addDecorator("Widget", "Module_WholesaleTrading_Widget");
		$this->addDecorator("CRegisterForm", "Module_WholesaleTrading_CRegisterForm");

        $this->addDecorator("Dialog_product", "Module_WholesaleTrading_Dialog_product");
        $this->addDecorator("Dialog_category", "Module_WholesaleTrading_Dialog_category");
        $this->addDecorator("Dialog_search", "Module_WholesaleTrading_Dialog_search");
		$this->addDecorator("Dialog_cart", "Module_WholesaleTrading_Dialog_cart_update");
		$this->addDecorator("Module_WholesaleTrading_Dialog_cart_update", "Module_WholesaleTrading_Dialog_cart");
		$this->addDecorator("Dialog_profile", "Module_WholesaleTrading_Dialog_profile");
		$this->addDecorator("Dialog_checkout", "Module_WholesaleTrading_Dialog_checkout");
		$this->addDecorator("Dialog_checkoutSuccess", "Module_WholesaleTrading_Dialog_checkoutSuccess");
		$this->addDecorator("Dialog_Order", "Module_WholesaleTrading_Dialog_Order");

		if ($this->xlite->is("adminZone")) {
		    $this->addDecorator("Admin_Dialog_product", "Module_WholesaleTrading_Admin_Dialog_product");
			$this->addDecorator("Admin_Dialog_export_catalog", "Module_WholesaleTrading_Admin_Dialog_export_catalog");
			$this->addDecorator("Admin_Dialog_import_catalog", "Module_WholesaleTrading_Admin_Dialog_import_catalog");
			$this->addDecorator("Admin_Dialog_add_product", "Module_WholesaleTrading_Admin_Dialog_add_product");
			$this->addDecorator("Admin_Dialog_profile", "Module_WholesaleTrading_Admin_Dialog_profile");
			$this->addDecorator("Admin_Dialog_users", "Module_WholesaleTrading_Admin_Dialog_users");
			$this->addDecorator("Admin_Dialog_Order", "Module_WholesaleTrading_Admin_Dialog_Order");
			$this->addDecorator("Admin_Dialog_taxes", "Module_WholesaleTrading_Admin_Dialog_taxes");
			$this->addDecorator("WysiwygMediator", "Module_WholesaleTrading_WysiwygMediator");
			$this->addLayout("common/select_membership.tpl", "modules/WholesaleTrading/common/select_membership.tpl");
		}


		$this->xlite->set("WholesaleTradingEnabled", true);
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

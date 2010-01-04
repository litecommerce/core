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

define('CUSTOMER_NOTIFICATION_PRODUCT', 'product');
define('CUSTOMER_NOTIFICATION_PRICE', 'price');

define('CUSTOMER_REQUEST_QUEUED', 'Q');
define('CUSTOMER_REQUEST_UPDATED', 'U');
define('CUSTOMER_REQUEST_SENT', 'S');
define('CUSTOMER_REQUEST_DECLINED', 'D');

class XLite_Module_ProductAdviser_Main extends XLite_Model_Module
{
	var $minVer = '2.1.2';
    var $showSettingsForm = true;

	function init() 
	{
		parent::init();

		$w = new XLite_View_Abstract();
		$widgetMethods = array_map("strtolower", get_class_methods($w));
		if (!in_array("isarraypointernth", $widgetMethods)) {
			$this->addDecorator("Widget", "Widget_ProductAdviser");
			$this->addDecorator("FlexyCompiler", "FlexyCompiler_ProductAdviser");
		} else {
			$this->xlite->set("PAPartialWidget", true);
			$this->addDecorator("Widget", "Widget_ProductAdviser");
		}

		$this->addDecorator("Product",  "Product_ProductAdviser");
		$this->addDecorator("Order",  "Order_ProductAdviser");

        if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_ProductAdviser");
		}

		/////////////////////////////////////
		// "RelatedProducts" section
		if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_product", "Admin_Dialog_product_ProductAdviser");
		}
		/////////////////////////////////////

		/////////////////////////////////////
		// "Recently viewed" section
		if ($this->xlite->is("adminZone")) {
			$this->validateConfig("number_recently_viewed");
		}
		$this->addDecorator("Dialog_product", "Dialog_product_ProductAdviser");

		/////////////////////////////////////

		/////////////////////////////////////
		// "New Arrivals" section
		if ($this->xlite->is("adminZone")) {
			$this->validateConfig("number_new_arrivals");
			$this->validateConfig("period_new_arrivals");
		}
		/////////////////////////////////////

		/////////////////////////////////////
		// "Product also buy" section
		if ($this->xlite->is("adminZone")) {
			if ($this->config->get("ProductAdviser.admin_products_also_buy_enabled") != "Y") {
				$cfg = new XLite_Model_Config();
                $cfg->createOption("ProductAdviser", "products_also_buy_enabled", "N");
			}
		}
		/////////////////////////////////////

		/////////////////////////////////////
		// "Customer Notifications" section
		if ($this->xlite->is("adminZone")) {
			$this->validateConfig("number_notifications", 1);
			$customer_notifications_enabled = ($this->config->get("ProductAdviser.customer_notifications_mode") == "0") ? "N" : "Y";
			$cfg = new XLite_Model_Config();
            $cfg->createOption("ProductAdviser", "customer_notifications_enabled", $customer_notifications_enabled);
		}
		/////////////////////////////////////

        $inventorySupport = func_class_exists("Inventory");
        $this->xlite->set("PA_InventorySupport", $inventorySupport);
		if ($inventorySupport) {
			$this->addDecorator("Inventory", "Inventory_ProductAdviser");
			if (!$this->xlite->is("adminZone")) {
				$this->addDecorator("Dialog_cart", "Dialog_cart_ProductAdviser");
			}
		}
		if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_product_list", "Admin_Dialog_product_list_ProductAdviser");
		}
		$this->addDecorator("Dialog_login", "Dialog_login_ProductAdviser");
		$this->addDecorator("Dialog_profile", "Dialog_profile_ProductAdviser");
		$this->addDecorator("Dialog_category", "Dialog_category_ProductAdviser");
		$this->addDecorator("Dialog_main", "Dialog_main_ProductAdviser");
		$this->addDecorator("Mailer",  "Mailer_ProductAdviser");

		$this->xlite->set("ProductAdviserEnabled", true);
	}

	function validateConfig($option, $limit=0)
	{
		$number_orig = $this->config->get("ProductAdviser.".$option);
		$number = intval($number_orig);
		$number_updated = false;
		if ($number < $limit) {
			$number = $limit;
            $number_updated = true;
		} else {
			if (strval($number) != strval($number_orig)) {
            	$number_updated = true;
			}
		}
		if ($number_updated) {
			$cfg = new XLite_Model_Config();
            $cfg->createOption("ProductAdviser", $option, $number);
		}
	}

	function uninstall()
	{
		func_cleanup_cache("skins");
		func_cleanup_cache("classes");

		parent::uninstall();
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

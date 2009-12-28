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
* Class description.
*
* @package Module_GoogleCheckout
* @access public
* @version $Id$
*/
class XLite_Module_GoogleCheckout_Main extends XLite_Model_Module
{
    var $minVer = "2.1.2";
    var $showSettingsForm = true;

    function getSettingsForm()
    {
       return "admin.php?target=payment_method&payment_method=google_checkout";
    }

    function init()
    {
        parent::init();

        $webDir = $this->xlite->get("options.host_details.web_dir");
        if (substr($webDir, -1) == "/") {
            $webDir = substr($webDir, 0, -1);
        }
        $this->xlite->set("options.host_details.web_dir_wo_slash", $webDir);

        $pm = new XLite_Model_PaymentMethod();
        $pm->registerMethod("google_checkout");

		$payment_method = new XLite_Model_PaymentMethod("google_checkout");
		if ($payment_method->get("params.disable_customer_notif")) {
			$this->xlite->set("gcheckout_disable_customer_notif", true);
		}

		if (!$this->xlite->is("adminZone")) {
			if ($payment_method->get("params.display_product_note") && $payment_method->is("parent_enabled")) {
				$this->xlite->set("gcheckout_display_product_note", true);
			}

			$currency = $payment_method->get("params.currency");
			switch ($currency) {
				case "USD":
				case "GBP":
				break;
				default:
					$currency = "USD";
				break;
			}
			$this->xlite->set("gcheckout_currency", $currency);
			$this->xlite->set("gcheckout_remove_discounts", $payment_method->get("params.remove_discounts"));
		}

		$this->addDecorator("CButton", "CButtonAltCheckout");
		$this->addDecorator("OrderItem", "Module_GoogleCheckout_OrderItem");
		$this->addDecorator("Order", "Module_GoogleCheckout_Order");
		$this->addDecorator("Mailer", "Module_GoogleCheckout_Mailer");
		$this->addDecorator("ShippingRate", "Module_GoogleCheckout_ShippingRate");
		$this->addDecorator("Product", "Module_GoogleCheckout_Product");
		$this->addDecorator("CStatusSelect", "Module_GoogleCheckout_CStatusSelect");

		if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_payment_method", "Admin_Dialog_payment_method_GoogleCheckout");
			$this->addDecorator("Admin_Dialog_Order", "Module_GoogleCheckout_Admin_Dialog_Order");
		} else {
			$this->addDecorator("Dialog_checkout", "Module_GoogleCheckout_Dialog_checkout");
		}

		$this->xlite->set("GoogleCheckoutEnabled",true);
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

<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/

class XLite_Module_UPSOnlineTools_Main extends XLite_Model_Module
{
    var $minVer = "2.1.2";
    var $showSettingsForm = true;

    function getSettingsForm()
	{
        return "admin.php?target=ups_online_tool";
    }

    function init()
	{
        parent::init();

        if ($this->disable_ups()) {
            $obj = new XLite_Controller_Abstract();
            $obj->redirect("admin.php?target=modules");
            exit();
        }

        $shipping = new XLite_Model_Shipping();
        $shipping->registerShippingModule("ups");

        $this->addDecorator("Order", "Module_UPSOnlineTools_Order");
		$this->addDecorator("OrderItem", "Module_UPSOnlineTools_OrderItem");
		$this->addDecorator("Product", "Module_UPSOnlineTools_Product");
        $this->addDecorator("Shipping_online", "Module_UPSOnlineTools_Shipping_online");
        $this->addDecorator("Shipping_offline", "Module_UPSOnlineTools_Shipping_offline");
        $this->addDecorator("Dialog", "Module_UPSOnlineTools_Dialog");

        $this->addDecorator("Dialog_cart", "Module_UPSOnlineTools_Dialog_cart");
        $this->addDecorator("CRegisterForm", "Module_UPSOnlineTools_CRegisterForm");
		$this->addDecorator("Dialog_image", "Module_UPSOnlineTools_Dialog_image");

		if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_product", "Module_UPSOnlineTools_Admin_Dialog_product");
			$this->addDecorator("Admin_Dialog_Order", "Modules_UPSOnlineTools_Admin_Dialog_Order");
			$this->addDecorator("Admin_Dialog_shipping_settings", "Module_UPSOnlineTools_Admin_Dialog_shipping_settings");

		}

		$this->xlite->set("UPSOnlineToolsEnabled", true);

		// Check UPS account activation
		$options = $this->config->get("UPSOnlineTools");
		if (!$options->get("UPS_username") || !$options->get("UPS_password") || !$options->get("UPS_accesskey")) {
			$this->config->set("UPSOnlineTools.av_status", "N");

		}
    }

    function disable_ups()
	{
        $mods = $this->xlite->mm->getActiveModules();
        if (isset($mods["UPS"])) {
            $mod = new XLite_Model_Module("UPS");
            $this->xlite->mm->changeModuleStatus($mod, false);
            $mod->update();
            func_cleanup_cache("classes");
            func_cleanup_cache("skins");
            return true;
        }
        return false;
    }

    function install()
	{
        $this->disable_ups();
        parent::install();
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

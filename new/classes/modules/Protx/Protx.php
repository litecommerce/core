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
* @package Module_Protx
* @access public
* @version $Id$
*/
class Module_Protx extends Module
{
	var $minVer = "2.0";
	var $showSettingsForm = true;

	function getSettingsForm()
	{
		return "admin.php?target=payment_method&payment_method=protxdirect_cc";
	}

    function init()
    {
        
        parent::init();
        $pm = func_new('PaymentMethod', "protxdirect_cc");

		switch($pm->get("params.solution")) {
			case "form":
				$pm->registerMethod("protxform_cc");
			break;
			default:
			case "direct":
				$pm->registerMethod("protxdirect_cc");
			break;
		}

		if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_payment_method", "Module_Protx_Admin_Dialog_payment_method");
			$this->addDecorator("Admin_Dialog_modules", "Module_Protx_Admin_Dialog_modules");
		}

		if ($this->xlite->mm->get("activeModules.ProtxDirect")) {
			$modules = $this->xlite->mm->get("modules");
			$ids = array();
			foreach ($modules as $module) {
				if ($module->get("name") != "ProtxDirect" && $module->get("enabled") ) {
					$ids[] = $module->get("module_id");
				}
			}

			$this->xlite->mm->updateModules($ids);
			$this->session->set("ProtxDirectOff", true);
		}

		$this->xlite->set("ProtxEnabled", true);
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

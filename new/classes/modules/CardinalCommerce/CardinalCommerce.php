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
* 
* @package CardinalCommerce
* @access public
* @version $Id$
*/

class Module_CardinalCommerce extends Module
{
	var $minVer = '2.1.2';
    var $showSettingsForm = true;

    function init()
    {
        parent::init();

        if ($this->xlite->mm->get("activeModules.VeriSign")) {
			$this->addDecorator("PaymentMethod_verisign_cc", "PaymentMethod_verisign_cc_CardinalCommerce");
		}

		$build_ver = $this->xlite->config->get("Version.version");
		if (version_compare($build_ver, "2.2", ">=") && version_compare($build_ver, "2.2.35", "<=")) {
			$this->xlite->set("cc_initRequestAlternate", true);
		} else {
			if ($this->xlite->get("AuthorizeNetEnabled")) {
				$this->addDecorator("PaymentMethod_authorizenet_cc", "PaymentMethod_authorizenet_cc_CardinalCommerce");
			}
			if ($this->xlite->get("PayFlowProEnabled")) {
				$this->addDecorator("PaymentMethod_payflowpro_cc", "PaymentMethod_payflowpro_cc_CardinalCommerce");
			}
			if ($this->xlite->get("NetworkMerchantsEnabled")) {
				$this->addDecorator("PaymentMethod_networkmerchants_cc", "PaymentMethod_networkmerchants_cc_CardinalCommerce");
			}
			if ($this->xlite->get("NetbillingEnabled")) {
				$this->addDecorator("PaymentMethod_netbilling_cc", "PaymentMethod_netbilling_cc_CardinalCommerce");
			}
		}

		$this->addDecorator("HTTPS", "HTTPS_CardinalCommerce");

		// admin frontend - specific class decorations
        if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_CardinalCommerce");
		}

		$this->addDecorator("Dialog_checkout", "Dialog_checkout_CardinalCommerce");

		$this->xlite->set("CardinalCommerceEnabled",true);
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

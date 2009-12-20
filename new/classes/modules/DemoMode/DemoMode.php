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

class Module_DemoMode extends Module
{
    var $isFree = true;

    function init()
    {
        parent::init();
        
        $this->addDecorator("Base", "Module_DemoMode_Base");

        $mm = func_new("module");
        $this->addDecorator("Dialog", "Module_DemoMode_Dialog");
		$this->addDecorator("Component", "Module_DemoMode_Component");
        if ($this->xlite->is("adminZone")) {
            $this->addLayout("welcome.tpl", "modules/DemoMode/welcome.tpl");
			$this->addDecorator("Admin_Dialog_login", "Module_DemoMode_Admin_Dialog_login");
			$this->addDecorator("Admin_Dialog_module", "Module_DemoMode_Admin_Dialog_module");
			$this->addDecorator("Admin_Dialog_categories", "Module_DemoMode_Admin_Dialog_categories");
            $this->addDecorator("Admin_Dialog_Scheme_Manager", "Module_DemoMode_Admin_Dialog_Scheme_Manager");
            $this->addDecorator("Admin_Dialog_template_editor_LayoutOrganizer", "Module_DemoMode_AD_template_editor_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_Change_Skin", "Module_DemoMode_Admin_Dialog_Change_Skin");
        } else {
            $this->addDecorator("Dialog_login", "Module_DemoMode_Dialog_login");
            $this->addDecorator("Dialog_profile", "Module_DemoMode_Dialog_profile");
            $this->addDecorator("PartnerDialog", "Module_DemoMode_PartnerDialog");

        }

		$this->addDecorator("NewsLetter", "NewsLetter_DemoMode");
		$this->addDecorator("Mailer", "Mailer_DemoMode");
		$this->addDecorator("FlexyCompiler", "FlexyCompiler_DemoMode");

        $cfg =& func_new("Config");
        $this->xlite->config =& $cfg->readConfig();
        if (!$this->session->get("superUser")) {
            global $options;
            $options["decorator_details"]["compileDir"] = "var/run/classes/" . $this->session->getID() . "/";
        }
    }

    function update()
    {
        $module = func_new("Module");
        $module->set("properties", $this->get("properties"));
        $module->update();
    }
    
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

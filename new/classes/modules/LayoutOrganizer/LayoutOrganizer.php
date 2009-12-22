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
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class Module_LayoutOrganizer extends Module
{
	var $minVer = '2.1.1';
    var $showSettingsForm = true;

    function init()
    {

        parent::init();

        $this->addDecorator("Category", "Category_LayoutOrganizer");
        $this->addDecorator("Dialog_category", "Dialog_category_LayoutOrganizer");
        $this->addDecorator("Product", "Product_LayoutOrganizer");
        $this->addDecorator("FileNode", "FileNode_LayoutOrganizer");
        $this->addDecorator("Dialog_product", "Dialog_product_LayoutOrganizer");

        // admin frontend - specific class decorations
        if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_modules", "Admin_Dialog_modules_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_settings", "Admin_Dialog_settings_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_category", "Admin_Dialog_category_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_product", "Admin_Dialog_product_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_wysiwyg", "Admin_Dialog_wysiwyg_LayoutOrganizer");
			$this->addDecorator("Admin_Dialog_template_editor", "Admin_Dialog_template_editor_LayoutOrganizer");
		}

		if ($this->xlite->mm->get("activeModules.ShowcaseOrganizer")) {
			$modules = $this->xlite->mm->get("modules");
			$ids = array();
        	foreach ($modules as $module) {
        		if ($module->get("name") != "ShowcaseOrganizer" && $module->get("enabled") ) {
        			$ids[] = $module->get("module_id");
        		}
			}
			$this->xlite->mm->updateModules($ids);
			$this->session->set("ShowcaseOrganizerOff", true);
		}

    	$this->xlite->set("LayoutOrganizerEnabled", true);
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

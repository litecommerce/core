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
* @package FlyoutCategories
* @access public
* @version $Id$
*/

class Module_FlyoutCategories extends Module
{
	var $minVer = "2.1.2";
    var $showSettingsForm = true;

    function init()
    {
    	// check for module license
    	if (!check_module_license("FlyoutCategories", true)) {
        	return;
       	}

        parent::init();

		$image = func_get_instance("Image");
		$image->registerImageClass("category_small", "Small category icons", "categories", "smallimage", "category_id");

		$this->addDecorator("Category", "FlyoutCategories_Category");

        // admin frontend - specific class decorations
        if ($this->xlite->is("adminZone")) 
        {
			$this->addDecorator("Admin_Dialog", "Admin_Dialog_FlyoutCategories");
			$this->addDecorator("Admin_Dialog_categories", "Admin_Dialog_categories_FlyoutCategories");
			$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_FlyoutCategories");
			$this->addDecorator("Admin_Dialog_category", "FlyoutCategories_Admin_Dialog_category");
			$this->addDecorator("Admin_Dialog_image_files", "Admin_Dialog_image_files_FlyoutCategories");
			$this->addDecorator("CImageUpload", "FlyoutCategories_CImageUpload");

			$this->addLayout("modules/LayoutOrganizer/main.tpl", "modules/FlyoutCategories/schemes_manager.tpl");

			if ($this->xlite->LayoutOrganizerEnabled) {
				$this->addDecorator("Admin_Dialog_Scheme_Manager", "Admin_Dialog_Scheme_Manager_FlyoutCategories");
			}
		}

        $scheme =& func_new("FCategoriesScheme", $this->get("config.FlyoutCategories.scheme"));
        $this->xlite->set('FlyoutCategoriesCssPath', 'styles/'.$scheme->get('options.color.value').'.css');

		$this->addDecorator("Profile", "Module_FlyoutCategories_Profile");
		$this->addDecorator("Auth", "Module_FlyoutCategories_Auth");

		$this->xlite->set("FlyoutCategoriesEnabled", true);
    }


    function uninstall()
    {
        func_cleanup_cache("classes");
        func_cleanup_cache("skins");

        parent::uninstall();
    }

    function isOldKernel()
    {
        if (!isset($this->_kernelNonSuppVersion)) {
            $configVersion = $this->config->get("Version.version");
            $configVersion = str_replace(" build ", ".", $configVersion);
            $this->_kernelNonSuppVersion = version_compare("2.2.21", $configVersion, ">=");
            // avoiding typo in 2.2.17
            if (defined('MODULE_COMMERCICAL_OTHER')) {
            	$this->_moduleType = MODULE_COMMERCICAL_OTHER;
            } else {
            	$this->_moduleType = MODULE_COMMERCIAL_OTHER;
            }
        }

        return $this->_kernelNonSuppVersion;
    }

    function &get($name)
    {
        $value =& parent::get($name);
        $this->isOldKernel();
        if ($name == "type" && $this->isOldKernel()) {
            $value = $this->_moduleType;
        }

        return $value;
    }

    function set($name, $value)
    {
        if ($name == "type" && $this->isOldKernel()) {
            $value = $this->_moduleType;
        }

        parent::set($name, $value);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

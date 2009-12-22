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
*
* @package module
* @access public
* @version $Id$
*/

class Module_LiveUpdating extends Module
{
    var $isFree = true;
	var $minVer = '2.2.21';
    var $showSettingsForm = true;

	function init() 
	{
		parent::init();

        if ($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_modules", "Admin_Dialog_modules_LiveUpdating");
			$this->addDecorator("Admin_Dialog_module", "Admin_Dialog_module_LiveUpdating");
			$this->addDecorator("Admin_Dialog_main", "Admin_Dialog_main_LiveUpdating");

			$this->xlite->set("FTP_support", function_exists("ftp_connect"));
			if (!$this->xlite->get("FTP_support")) {
				$cfg = func_new("Config");
                $cfg->createOption("LiveUpdating", "use_ftp", "N");
			}
		}

		$this->xlite->set("LiveUpdatingEnabled",true);
	}

	function install()
	{
		func_cleanup_cache("skins");
		func_cleanup_cache("classes");

		parent::install();
	}

	function uninstall()
	{
		func_cleanup_cache("skins");
		func_cleanup_cache("classes");

		parent::uninstall();
	}

    function isOldKernel()
    {
        if (!isset($this->_kernelNonSuppVersion)) {
            $configVersion = $this->config->get("Version.version");
            $configVersion = str_replace(" build ", ".", $configVersion);
            $this->_kernelNonSuppVersion = version_compare("2.2.21", $configVersion, ">=");
			$this->_moduleType = MODULE_FREE;
        }

        return $this->_kernelNonSuppVersion;
    }

    function get($name)
    {
        $value = parent::get($name);
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

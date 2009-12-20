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
* Admin_Dialog_module_LiveUpdating description.
*
* @package Module_LiveUpdating
* @access public
* @version $Id$
*/
class Admin_Dialog_modules_LiveUpdating extends Admin_Dialog_modules
{
	var $params = array('target', 'module_name', 'mode');
	var $_LUDialog = null;
	var $_modulesInfo = null;

	function init()
	{
		parent::init();

		if (!isset($this->action)) {
			$this->getModulesInfo();
		}
	}

	function getModulesInfo()
	{
		if (!$this->config->get("LiveUpdating.modules_news")) {
			return null;
		}

		if (!isset($this->_modulesInfo)) {
    		if (!isset($this->_LUDialog)) {
    			$this->_LUDialog =& func_new("Admin_Dialog_LiveUpdating");
    		}

    		$this->_modulesInfo = $this->_LUDialog->getModulesInfo();
		}
		return $this->_modulesInfo;
	}

	function getModulesUpdatesNumber()
	{
		if (!$this->config->get("LiveUpdating.modules_news")) {
			return 0;
		}

		$this->getModulesInfo();
		return (is_array($this->_modulesInfo)) ? count($this->_modulesInfo) : 0;
	}

	function checkModuleUpdate($name, $option=null)
	{
		$name = strval($name);
		if (!is_array($this->_modulesInfo)) {
			return false;
		}

		if (isset($this->_modulesInfo[$name])) {
			if (isset($option)) {
				$option = strval($option);
				return $this->_modulesInfo[$name][$option];
			} else {
				return true;
			}
		} else {
			return false;
		}
	}


	function action_install()
	{
		parent::action_install();
		if ($this->valid) {
			$moduleName = trim($this->xlite->mm->moduleName);
			if (empty($moduleName))
				return;

			$obj =& func_new("SystemUpdate");
			$updates = $obj->findAll("type='module' AND module_name LIKE ('".$moduleName." %')");
			if (is_array($updates) && count($updates) > 0) {
				foreach ($updates as $update) {
					$update->delete();
				}
			}

			$mlast = $this->xlite->get("config.LiveUpdating.modules_last_update");
			if (is_array($mlast) && array_key_exists($moduleName, $mlast)) {
				$mlast[$moduleName]["last_update"] = "";
				$config =& func_new("Config");
				$config->createOption("LiveUpdating", "modules_last_update", serialize($mlast), "serialized");
			}
		}
	}

}

?>

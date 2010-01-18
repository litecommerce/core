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
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_Modules extends XLite_Controller_Admin_Abstract
{
	protected $sections = array(
		XLite_Model_Module::MODULE_PAYMENT   => 'Payment modules', 
		XLite_Model_Module::MODULE_SHIPPING  => 'Shipping modules',
		XLite_Model_Module::MODULE_SKIN      => 'Skin modules',
		XLite_Model_Module::MODULE_GENERAL   => 'Add-ons',
		XLite_Model_Module::MODULE_3RD_PARTY => '3rd party modules',
		XLite_Model_Module::MODULE_UNKNOWN   => 'Unknown',
	);	

    public $success = true; // last operation status: true or false	
    public $_sort_modules = null;

	protected $modules = null;

	protected $currentModuleType = null;

    function getModules($type = null)
    {
		if (is_null($this->modules) || $type !== $this->currentModuleType) {
			$this->currentModuleType = $type;
			$this->modules = XLite_Model_ModulesManager::getInstance()->getModules($type);
		}

    	/*if (isset($this->modules)) {
    		return $this->modules;
    	}
		
		$modules = $this->xlite->mm->get("modules");
		$this->modules = array();
		if (is_array($modules)) {
			for($i=0; $i<count($modules); $i++) {
				if ($modules[$i]->get("enabled")) {
					$this->modules[] = $modules[$i];
				}
			}
			for($i=0; $i<count($modules); $i++) {
				if (!$modules[$i]->get("enabled")) {
					$this->modules[] = $modules[$i];
				}
			}
		}*/

		return $this->modules;
    }

	function getSortModules($type=0)
	{
		$type = intval($type);
		if (is_null($this->_sort_modules)) {
			$this->_sort_modules = array();
		}
		if (isset($this->_sort_modules[$type]) && is_array($this->_sort_modules[$type])) {
			return $this->_sort_modules[$type];
		} else {
			$this->_sort_modules[$type] = array();
		}

		$temp = $this->get("modules");
		foreach((array)$temp as $v) {
			if ($v->get("type") == XLite_Model_Module::MODULE_UNKNOWN) {
				// FIXME
				// $v->set("type", $this->xlite->mm->getPredefinedModuleType($v->get("name")));
				$v->update();
			}
			if ($v->get("type") == $type){
				$this->_sort_modules[$type][$v->get("name")] = $v;
			}
		}

		ksort($this->_sort_modules[$type]);

		return $this->_sort_modules[$type];
	}

    function getModulesForUpdate()
    {
        $modules = $this->getModules();
        $res = array();
        foreach ($modules as $module) {
            if ($module->is('needUpdate'))
                $res[] = $module->get('name');
        }
        if (count($res)>0) return $res;
        return "";
    }

    function action_install()
    {
        if (!isset($_REQUEST["mode"]) || $_REQUEST["mode"]!="cp") {
            $this->startDump();
		}
        if (!$this->xlite->mm->install()) {
            $this->set("valid", false);
            $this->hidePageHeader();
        } else {
            $this->set("silent", true);
        }
    }

    function action_update()
    {
		$activeModules = isset($_REQUEST["active_modules"]) ? $_REQUEST["active_modules"] : array();

		if (!XLite_Model_ModulesManager::getInstance()->updateModules($activeModules)) {
			$this->set("valid", false);
            $this->hidePageHeader();
		}
    }

    function action_uninstall()
    {
		if (!isset($_REQUEST["mode"]) || $_REQUEST["mode"]!="cp") {
			$this->startDump();
		}
        // uninstall and delete module
        $moduleName = $_REQUEST["module_name"];
        if (!$this->xlite->mm->uninstallModule($moduleName)) {
            $this->set("valid", false);
			$this->hidePageHeader();
		} else {
        	$this->set("silent", true);
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

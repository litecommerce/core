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

// Constants {{{
define("MM_ARCHIVE_CORRUPTED", 1);
define("MM_BROKEN_DEPENDENCIES", 2);
define("MM_OK", 0);
// }}}


/**
* Class ModulesManager implements the mechanism for modules initialization
* and management. All classes which are the subject for extending with 
* modules functionality should be instantiated using the ModulesManager
* controlling mechanism.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_ModulesManager extends XLite_Base implements XLite_Base_ISingleton
{
	/**
	 * GET params to enable safe mode
	 */

	const PARAM_SAFE_MODE = 'safe_mode';
	const PARAM_AUTH_CODE = 'auth_code';

	/**
	 * Session variable to determine current mode
	 */
	const SESSION_VAR_SAFE_MODE = 'safe_mode';


	/**
	 * Determines if we need to initialize modules or not 
	 * 
	 * @var    bool
	 * @access protected
	 * @since  1.0
	 */
	protected $safeMode = false;

	/**
	 * Module object
	 * 
	 * @var    XLite_Model_Module
	 * @access protected
	 * @since  1.0
	 */
	protected $module = null;

	/**
	 * List of active modules (array with module names as the keys) 
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $activeModules = null;


	/**
	 * Instantiate moduel object
	 * 
	 * @return XLite_Model_Module
	 * @access protected
	 * @since  1.0
	 */
	protected function getModule()
	{
		if (is_null($this->module)) {
			$this->module = new XLite_Model_Module();
		}

		return $this->module;
	}

	/**
	 * Determines current mode 
	 * 
	 * @return bool
	 * @access protected
	 * @since  1.0
	 */
	protected function isInSafeMode()
	{
		$result = false;

		if (XLite::getInstance()->is('adminZone') && isset($_GET[self::PARAM_SAFE_MODE])) {
			$authCode = XLite::getInstance()->getOptions(array('installer_details', 'auth_code'));
			$result = empty($authCode) xor (isset($_GET[self::PARAM_AUTH_CODE]) && ($authCode == $_GET[self::PARAM_AUTH_CODE]));
		}

		return $result;
	}
	
	/**
	 * Run the "init" function for all active modules
	 * 
	 * @return void
	 * @access protected
	 * @since  1.0
	 */
	protected function initModules()
	{
		foreach ($this->getModule()->findAll('enabled = \'1\'') as $module) {
			$className = 'XLite_Module_' . $module->get('name') . '_Main';
			$moduleObject = new $className();
			$moduleObject->init();
			$moduleObject = null;
		}
	}


	/**
	 * Return singleton reference 
	 * 
	 * @return XLite_Model_Modules_Manager
	 * @access public
	 * @since  1.0
	 */
	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Attempts to initialize the ModulesManager and all active modules
     * 
     * @return void
     * @access public
     * @since  1.0
     */
    public function init()
    {
		if ($this->isInSafeMode()) {

			if ('on' == $_GET[self::PARAM_SAFE_MODE]) {
                XLite_Model_Session::getInstance()->setComplex(self::SESSION_VAR_SAFE_MODE, true);
				$this->set('safeMode', true);
			} elseif ('off' == $_GET[self::PARAM_SAFE_MODE]) {
				XLite_Model_Session::getInstance()->setComplex(self::SESSION_VAR_SAFE_MODE, null);
			}
		}

		if ($this->config->General->safe_mode || XLite_Model_Session::getInstance()->isRegistered('safe_mode')) {
			XLite::getInstance()->setCleanUpCacheFlag(true);
			$this->set('safeMode', true);
		} 

        $this->initModules();
    } 

	public function getModules($type = null)
	{
		return $this->getModule()->findAll(is_null($type) ? '' : 'type = \'' . $type . '\'');
	}

	public function getActiveModules($moduleName = null)
	{
		if (is_null($this->activeModules)) {
			$this->activeModules = array();
			foreach ($this->getModule()->findAll('enabled = \'1\'') as $module) {
				$this->activeModules[$module->get('name')] = true;
			}
		}

		return is_null($moduleName) ? $this->activeModules : isset($this->activeModules[$moduleName]);
	}

	public function getActiveModulesNumber()
	{
		return count($this->getActiveModules());
	}

	public function rebuildCache()
	{
		$decorator = new Decorator();
		$decorator->rebuildCache(true);
	}

	public function cleanupCache()
	{
		$decorator = new Decorator();
        $decorator->cleanupCache();
	}

	public function changeModuleStatus($module, $status, $cleanupCache = false)
    {
		if (!($module instanceof XLite_Model_Module)) {
			$module = new XLite_Model_Module($module);
		}

        $status ? $module->enable() : $module->disable();
		$result = $module->update();

		if ($cleanupCache) {
			XLite::getInstance()->setCleanUpCacheFlag(true);
		}

		return $result;
    }

	public function updateModules(array $moduleIDs, $type = null)
    {
        foreach ($this->getModules($type) as $module) {
			$this->changeModuleStatus($module, in_array($module->get("module_id"), $moduleIDs));
        }

		XLite::getInstance()->setCleanUpCacheFlag(true);

		return true;
    }

	public function isActiveModule($moduleName)
	{
		return $this->getActiveModules($moduleName);
	}
}


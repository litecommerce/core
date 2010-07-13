<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

define('MM_OK', 0);
define('MM_ARCHIVE_CORRUPTED', 1);
define('MM_BROKEN_DEPENDENCIES', 2);

/**
 * Modules manager
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModulesManager extends \XLite\Base implements \XLite\Base\ISingleton
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
     * @var    \XLite\Model\Module
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
     * Determines current mode 
     * 
     * @return bool
     * @access protected
     * @since  1.0
     */
    protected function isInSafeMode()
    {
        $result = false;

        $safeMode = self::PARAM_SAFE_MODE;

        if (\XLite::isAdminZone() && isset(\XLite\Core\Request::getInstance()->$safeMode)) {
            $authCode = \XLite::getInstance()->getOptions(array('installer_details', 'auth_code'));
            $authCodeName = self::PARAM_AUTH_CODE;
            $result = empty($authCode)
                xor (
                    isset(\XLite\Core\Request::getInstance()->$authCodeName)
                    && $authCode == \XLite\Core\Request::getInstance()->$authCodeName
                );
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
        $list = \XLite\Core\Database::getRepo('XLite\Model\Module')->findAllEnabled();
        foreach ($list as $module) {
            $className = $module->getMainClassName();
            if (\XLite\Core\Operator::isClassExists($className)) {
                $moduleObject = new $className();
                $moduleObject->init();
                $moduleObject = null;
            }
        }
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

            $safeMode = self::PARAM_SAFE_MODE;

            if ('on' == \XLite\Core\Request::getInstance()->$safeMode) {
                \XLite\Model\Session::getInstance()->setComplex(self::SESSION_VAR_SAFE_MODE, true);
                $this->set('safeMode', true);

            } elseif ('off' == \XLite\Core\Request::getInstance()->$safeMode) {
                \XLite\Model\Session::getInstance()->setComplex(self::SESSION_VAR_SAFE_MODE, null);
            }
        }

        if (
            $this->config->General->safe_mode
            || \XLite\Model\Session::getInstance()->isRegistered('safe_mode')
        ) {
            \XLite::getInstance()->setCleanUpCacheFlag(true);
            $this->set('safeMode', true);
        }

        $this->initModules();
    }

    /**
     * Get modules by type or all list
     * 
     * @param integer $type Type
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModules($type = null)
    {
        return isset($type)
            ? \XLite\Core\Database::getRepo('XLite\Model\Module')->findByType($type)
            : \XLite\Core\Database::getRepo('XLite\Model\Module')->findAllModules();
    }

    /**
     * Update modules list 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function updateModulesList()
    {
        // Get modules from DB
        $names = array();
        foreach ($this->getModules() as $module) {
            $names[$module->getName()] = true;
        }

        // Search modules repositories
        foreach (glob(LC_MODULES_DIR . '*' . LC_DS . 'Main.php') as $f) {
            $parts = explode(LC_DS, $f);
            $name = $parts[count($parts) - 2];
            if (!isset($names[$name])) {

                // Install new module
                $this->registerModule($name);

            } else {
                unset($names[$name]);
            }
        }

        // Uninstall removed modules
        foreach ($names as $name => $tmp) {
            $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->findOneByName($name);
            if ($module) {
                \XLite\Core\Database::getEM()->remove($module);
            }
        }
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Register new module 
     * 
     * @param string $name Module name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerModule($name)
    {
        $module = new \XLite\Model\Module();
        $module->setName($name);

        $module->includeMainClass();

        $className = $module->getMainClassName();

        $module->setMutualModules($className::getMutualModulesList());
        $module->setType($className::getModuleType());

        \XLite\Core\Database::getEM()->persist($module);
        \XLite\Core\Database::getEM()->flush();

        // Install SQL dump
        $installSQLPath = LC_MODULES_DIR . $name . LC_DS . 'install.sql';

        if (file_exists($installSQLPath)) {
            $error = query_upload($installSQLPath, $this->db->connection, true, true);

            if ($error) {
                // TODO - display error
            }
        }
    }

    public function getActiveModules($moduleName = null)
    {
        if (!isset($this->activeModules)) {
            $this->activeModules = array();
            $list = \XLite\Core\Database::getRepo('XLite\Model\Module')->findAllEnabled();
            foreach ($list as $module) {
                $this->activeModules[$module->getName()] = true;
            }
        }

        return isset($moduleName) ? isset($this->activeModules[$moduleName]) : $this->activeModules;
    }

    public function getActiveModulesNumber()
    {
        return count($this->getActiveModules());
    }

    public function rebuildCache()
    {
        $iterator = new \RecursiveDirectoryIterator(LC_CLASSES_CACHE_DIR . LC_DS);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        $iterator = new \RegexIterator($iterator, '/\.php$/Ss');

        foreach ($iterator as $f) {
            require_once $f->getRealPath();
        }

        $this->cleanupCache();
    }

    public function cleanupCache()
    {
        $decorator = new \Decorator();
        $decorator->cleanUpCache();
        $decorator = null;
    }

    public function changeModuleStatus($module, $status, $cleanupCache = false)
    {
        if (!($module instanceof \XLite\Model\Module)) {
            $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->findOneByName($module);
        }

        if ($module) {
            $module->setEnabled((bool)$status);
            \XLite\Core\Database::getEM()->persist($module);
            \XLite\Core\Database::getEM()->flush();

            if ($cleanupCache) {
                \XLite::getInstance()->setCleanUpCacheFlag(true);
            }
        }

        return (bool)$module;
    }

    public function updateModules(array $moduleIDs, $type = null)
    {
        foreach ($this->getModules($type) as $module) {
            $this->changeModuleStatus($module, in_array($module->getModuleId(), $moduleIDs));
        }

        $this->rebuildCache();

        return true;
    }

    public function isActiveModule($moduleName)
    {
        return $this->getActiveModules($moduleName);
    }
}

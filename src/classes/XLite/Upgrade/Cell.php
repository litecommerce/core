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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Upgrade;

/**
 * Cell 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Cell extends \XLite\Base\Singleton
{
    /**
     * Name of TmpVar
     */
    const CELL_NAME = 'upgradeCell';

    /**
     * Dedicated cell entry - LC core
     */
    const CORE_IDENTIFIER = '____CORE____';

    /**
     * List of cell entries 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $entries = array();

    // {{{ Public methods

    /**
     * Getter
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Method to clean up cell
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clear()
    {
        $this->entries = array();
    }

    /**
     * Add module to update/install
     * 
     * @param \XLite\Model\Module $module Module model
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addMarketplaceModule(\XLite\Model\Module $module)
    {
        $this->entries[md5($module->getActualName())] = new \XLite\Upgrade\Entry\Module\Marketplace($module);
    }

    /**
     * Add module to update/install
     * 
     * @param string $path Path to uploaded module pack
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addUploadedModule($path)
    {
        $this->entries[md5($path)] = new \XLite\Upgrade\Entry\Module\Uploaded($path);
    }

    // }}}

    // {{{ Core version routines

    /**
     * Quick access to the "Core" entry
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCoreMajorVersion()
    {
        return $this->callCoreEntryMethod('getMajorVersion') ?: \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Quick access to the "Core" entry
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCoreMinorVersion()
    {
        return $this->callCoreEntryMethod('getMinorVersion') ?: \XLite::getInstance()->getMinorVersion();
    }

    /**
     * Quick access to the "Core" entry
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCoreVersion()
    {
        return $this->callCoreEntryMethod('getVersion') ?: \XLite::getInstance()->getVersion();
    }

    /**
     * Helper
     * 
     * @param string $method Name of method to call
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function callCoreEntryMethod($method)
    {
        $entry = \Includes\Utils\ArrayManager::getIndex($this->getEntries(), self::CORE_IDENTIFIER, true);

        // If core entry found, call method with the passed name on it
        return isset($entry) ? $entry->$method() : null;
    }

    // }}}

    // {{{ Constructor and destructor

    /**
     * Save data in DB
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __destruct()
    {
        \XLite\Core\TmpVars::getInstance()->{self::CELL_NAME} = $this->entries;
    }

    /**
     * Protected constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $cache = \XLite\Core\TmpVars::getInstance()->{self::CELL_NAME};

        if (is_array($cache)) {
            $this->entries = array_merge($this->entries, $cache);
        } else {
            $this->collectEntries();
        }
    }

    // }}}

    // {{{ Methods to collect entries

    /**
     * Check and add (if needed) upgrade entries
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function collectEntries()
    {
        // :NOTE: do not change call order!
        $this->checkForCoreUpgrade();
        $this->checkForModulesUpgrade();
    }

    /**
     * Check and add (if needed) core upgrade entry
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkForCoreUpgrade()
    {
    }

    /**
     * Check and add (if needed) upgrade entries
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkForModulesUpgrade()
    {
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd) as $module) {
            $module = $this->getModuleForUpgrade($module);

            if ($module) {
                $this->addMarketplaceModule($module);
            }
        }
    }

    /**
     * Method to get module for update/upgrade
     *
     * @param \XLite\Model\Module $module Currently installed module version
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleForUpgrade(\XLite\Model\Module $module)
    {
        $version = $this->getCoreMajorVersion();
        $method  = \XLite::getInstance()->checkVersion($version, '<') ? 'getModuleForUpgrade' : 'getModuleForUpdate';

        // "ForUpgrade" or "ForUpdate" method call
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->$method($module, $version);
    }

    // }}}
}

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

namespace XLite\View\ItemsList\Module;

/**
 * Abstract product list
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AModule extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.module';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return parent::getDir() . LC_DS . 'module';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageBodyDir()
    {
        return null;
    }

    /**
     * getJSHandlerClassName
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getJSHandlerClassName()
    {
        return 'ModulesList';
    }

    /**
     * Check if the module can be enabled
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function canEnable(\XLite\Model\Module $module)
    {
        $result = $this->isModuleCompatible($module);

        if ($result) {
            $dependencies = $module->getDependencies();

            if ($dependencies) {
                $modules = array_keys(\Includes\Decorator\Utils\ModulesManager::getActiveModules());
                $result  = ! (bool) array_diff($dependencies, $modules);
            }
        }

        return $result;
    }

    /**
     * Check if the module can be disabled
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function canDisable(\XLite\Model\Module $module)
    {
        return ! (bool) $module->getDependentModules();
    }

    /**
     * Return modules list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd, $countOnly);
    }

    // {{{ Version-related checks

    /**
     * Check if the module major version is the same as the core one.
     * Alias
     * 
     * @param \XLite\Model\Module $module Module to check
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isModuleCompatible(\XLite\Model\Module $module)
    {
        return $this->checkModuleMajorVersion($module, '=');
    }

    /**
     * Check if module requires new core version.
     * Alias
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCoreUpgradeNeeded(\XLite\Model\Module $module)
    {
        return $this->checkModuleMajorVersion($module, '<');
    }

    /**
     * Check if core requires new module version.
     * Alias
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isModuleUpgradeNeeded(\XLite\Model\Module $module)
    {
        return $this->checkModuleMajorVersion($module, '>');
    }

    /**
     * Compare module version with the core one 
     * 
     * @param \XLite\Model\Module $module   Module to check
     * @param string              $operator Comparison operator
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkModuleMajorVersion(\XLite\Model\Module $module, $operator)
    {
        return \XLite::getInstance()->checkVersion($module->getMajorVersion(), $operator);
    }

    // }}}

    // {{{ Methods to search modules of certain types

    /**
     * Check if core requires new (but the same as core major) version of module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function isModuleUpdateAvailable(\XLite\Model\Module $module);

    /**
     * Is core upgrade available
     *
     * @param string $majorVersion core version to check
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCoreUpgradeAvailable($majorVersion)
    {
        return in_array($majorVersion, (array) \XLite\Core\Marketplace::getInstance()->getCoreVersions());
    }

    /**
     * Search for module for update. Alias
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleForUpdate(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleForUpdate($module);
    }

    /**
     * Search for module from marketplace. Alias
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleFromMarketplace(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleFromMarketplace($module);
    }

    /**
     * Search for installed module
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleInstalled(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleInstalled($module);
    }

    /**
     * Get module version. Alias
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleVersion(\XLite\Model\Module $module)
    {
        return $module->getVersion();
    }

    // }}}
}

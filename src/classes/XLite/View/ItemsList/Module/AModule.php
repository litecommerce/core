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
     * List of core versions to update
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $coreVersions;

    /**
     * Check if the module is installed
     *                                 
     * @param \XLite\Model\Module $module Module to check
     *                                          
     * @return boolean                          
     * @see    ____func_see____                 
     * @since  1.0.0                            
     */                                         
    abstract protected function isInstalled(\XLite\Model\Module $module);

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
        return parent::getDir() . '/module';
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
     * Check if there are some errors for the current module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasErrors(\XLite\Model\Module $module)
    {
        return !$this->canEnable($module);
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
                $modules = array_keys(\Includes\Utils\ModulesManager::getActiveModules());
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
     * Check if the module is enabled
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isEnabled(\XLite\Model\Module $module)
    {
        $installed = $this->getModuleInstalled($module);

        return isset($installed) && $installed->getEnabled();
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

    /**
     * Return list of modules current module depends on
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyModules(\XLite\Model\Module $module)
    {
        return $module->getDependencyModules(true);
    }

    /**
     * Check if there are modules current module depends on
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasWrongDependencies(\XLite\Model\Module $module)
    {
        return (bool) $this->getDependencyModules($module);
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
     * Return list of core versions for update
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCoreVersions()
    {
        if (!isset($this->coreVersions)) {
            $this->coreVersions = (array) \XLite\Core\Marketplace::getInstance()->getCores();
        }

        return $this->coreVersions;
    }

    /**
     * Is core upgrade available
     *
     * @param string $majorVersion core version to check
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCoreUpgradeAvailable($majorVersion)
    {
        return (bool) \Includes\Utils\ArrayManager::getIndex($this->getCoreVersions(), $majorVersion, true);
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

    // {{{ Dependency statuses

    /**
     * Get all data to dependency item in list
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyData(\XLite\Model\Module $module)
    {
        if ($module->isPersistent()) {
            if ($module->getInstalled()) {
                if ($module->getEnabled()) {
                    $result = array('status' => 'enabled', 'class' => 'good');

                } else {
                    $result = array('status' => 'disabled', 'class' => 'none');
                }

                $result['href'] = $this->buildURL('addons_list_installed') . '#' . $module->getName();

            } else {
                $url  = $this->buildURL('addons_list_marketplace', '', array('substring' => $module->getModuleName()));
                $url .= '#' . $module->getName();

                $result = array('href' => $url, 'status' => 'not installed', 'class' => 'none');
            }

        } else {
            $result = array('status' => 'unknown', 'class' => 'poor');
        }

        return $result;
    }

    /**
     * Get some data for depenendecy in list
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyHRef(\XLite\Model\Module $module)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getDependencyData($module), 'href', true);
    }

    /**
     * Get some data for depenendecy in list
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyStatus(\XLite\Model\Module $module)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getDependencyData($module), 'status', true);
    }

    /**
     * Get some data for depenendecy in list
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyCSSClass(\XLite\Model\Module $module)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getDependencyData($module), 'class', true);
    }

    // }}}
}

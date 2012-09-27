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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\Upgrade\Step\Prepare;

/**
 * IncompatibleEntries
 *
 */
class IncompatibleEntries extends \XLite\View\Upgrade\Step\Prepare\APrepare
{
    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/js/widget.js';

        return $list;
    }

    /**
     * Get directory where template is located (body.tpl)
     *
     * @return string
     */
    protected function getDir()
    {
        return parent::getDir() . '/incompatible_entries';
    }

    /**
     * Return internal list name
     *
     * @return string
     */
    protected function getListName()
    {
        return parent::getListName() . '.incompatible_entries';
    }

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'These components require your attention';
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getIncompatibleEntries();
    }

    /**
     * Return list of inclompatible modules
     *
     * @return array
     */
    protected function getIncompatibleEntries()
    {
        $result = array();

        foreach (\XLite\Upgrade\Cell::getInstance()->getIncompatibleModules() as $module) {
            if ($this->isModuleToDisable($module) || $this->isModuleCustom($module)) {
                $result[] = $module;
            }
        }

        return $result;
    }

    /**
     * Check if module will be disabled after upgrade
     *
     * :TRICKY: check if the "getMajorVersion" is not declared in the main module class
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     */
    protected function isModuleToDisable(\XLite\Model\Module $module)
    {
        $versionCore   = \XLite\Upgrade\Cell::getInstance()->getCoreMajorVersion();
        $versionModule = $module->getMajorVersion();

        $classModule = \Includes\Utils\ModulesManager::getClassNameByModuleName($module->getActualName());
        $reflection  = new \ReflectionMethod($classModule, 'getMajorVersion');

        $classModule = \Includes\Utils\Converter::prepareClassName($classModule);
        $classActual = \Includes\Utils\Converter::prepareClassName($reflection->getDeclaringClass()->getName());

        return version_compare($versionModule, $versionCore, '<') || $classModule !== $classActual;
    }

    /**
     * Check for custom module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     */
    protected function isModuleCustom(\XLite\Model\Module $module)
    {
        return $module->isCustom();
    }
}

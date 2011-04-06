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
 * @since     3.0.0
 */

namespace XLite\View\ItemsList\Module;

/**
 * Abstract product list
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class AModule extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Allowed sort criterions
     */

    const SORT_BY_MODE_NAME    = 'm.moduleName';
    const SORT_BY_MODE_POPULAR = 'm.downloads';
    const SORT_BY_MODE_RATING  = 'm.rating';
    const SORT_BY_MODE_DATE    = 'm.date';
    const SORT_BY_MODE_ENABLED = 'm.enabled';

    /**
     * Widget param names 
     */

    const PARAM_SUBSTRING    = 'substring';
    const PARAM_TAG          = 'tag';
    const PARAM_PRICE_FILTER = 'priceFilter';
    const PARAM_STATUS       = 'status';


    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += array(
            self::SORT_BY_MODE_NAME    => 'Name',
            self::SORT_BY_MODE_POPULAR => 'Popular',
            self::SORT_BY_MODE_RATING  => 'Most rated',
            self::SORT_BY_MODE_DATE    => 'Newest',
            self::SORT_BY_MODE_ENABLED => 'Enabled',
        );

        parent::__construct($params);
    }


    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
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
     * @since  3.0.0
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
     * @since  3.0.0
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
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function canEnable(\XLite\Model\Module $module)
    {
        $result = $this->isVersionValid($module);

        if ($result && ($dependencies = $module->getDependencies())) {
            $result = ! (bool) \Includes\Utils\ArrayManager::filterByKeys(
                $dependencies,
                array_keys(\Includes\Decorator\Utils\ModulesManager::getActiveModules())
            );
        }

        return $result;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();
    
        $this->widgetParams += array(
            self::PARAM_SUBSTRING    => new \XLite\Model\WidgetParam\String('Substring', ''),
            self::PARAM_TAG          => new \XLite\Model\WidgetParam\String('Tag', ''),
            self::PARAM_PRICE_FILTER => new \XLite\Model\WidgetParam\String('Price filter', ''),
        );
    }

    /**
     * Return modules list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')
            ->search($cnd, $countOnly);
    }

    // {{{ Version-related checks

    /**
     * Check if core requires new (but the same as core major) version of module. (module is NOT marketplace one)
     * 
     * @param \XLite\Model\Module $module Module to check
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isModuleUpdateAvailable(\XLite\Model\Module $module)
    {
        return $this->isModuleCompatible($module) && (bool) $this->getModuleForUpdate($module);
    }

    /**
     * Check if the module major version is the same as the core one
     * 
     * @param \XLite\Model\Module $module Module to check
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isModuleCompatible(\XLite\Model\Module $module)
    {
        return \XLite::getInstance()->checkVersion($module->getMajorVersion(), '=');
    }

    /**
     * Check if module requires new core version
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isCoreUpgradeNeeded(\XLite\Model\Module $module)
    {
        return \XLite::getInstance()->checkVersion($module->getMajorVersion(), '<');
    }

    /**
     * Check if core requires new module version
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isModuleUpgradeNeeded(\XLite\Model\Module $module)
    {
        return \XLite::getInstance()->checkVersion($module->getMajorVersion(), '>');
    }

    /**
     * Search for module for update
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleForUpdate(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleForUpdate($module);
    }

    /**
     * Get max available module version for update
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMaxModuleVersion(\XLite\Model\Module $module)
    {
        if ($result = $this->getModuleForUpdate($module)) {
            $result = \Includes\Utils\Converter::composeVersion($result->getMajorVersion(), $result->getMinorVersion());
        }

        return $result;
    }

    /**
     * Check if module has a correct version
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVersionValid(\XLite\Model\Module $module)
    {
        return \XLite::getInstance()->checkVersion($module->getMajorVersion(), '=');
    }

    // }}}
}

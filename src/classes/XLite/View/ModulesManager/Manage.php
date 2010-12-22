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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ModulesManager;

/**
 * Modules modify widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Manage extends \XLite\View\Dialog
{
    /**
     * Filter name definitions
     */
    const FILTER_ALL        = 'all';
    const FILTER_INACTIVE   = 'inactive';
    const FILTER_UPGRADABLE = 'upgradable';

    /**
     * Modules list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $modules = null;

    /**
     * Currently applie filter (cached)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $filter = null;

    /**
     * Possible filters (cached)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $filters = null;

    /**
     * Possible filters (cached)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $modulesCount = null;


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'modules';
    
        return $result;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . LC_DS . 'style.css';

        return $list;
    }

    /**
     * Get module human-readable status
     * TODO: to revise or, possibly, remove later,
     *       when modules installation procedure is reworked
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleStatus(\XLite\Model\Module $module)
    {
        $statuses = array();

        switch ($module->getInstalled()) {
            case $module::INSTALLED:
                $statuses[] = '<div class="good">' . $this->t('Installed') . '</div>';
                break;

            case $module::INSTALLED_WO_SQL:
                $statuses[] = '<div class="poor">' . $this->t('Installed partially (w/o SQL)') . '</div>';
                break;

            case $module::INSTALLED_WO_PHP:
                $statuses[] = '<div class="poor">' . $this->t('Installed partially') . '</div>';
                break;

            case $module::INSTALLED_WO_CTRL:
                $statuses[] = '<div class="poor">' . $this->t('Installed, but broken') . '</div>';
                break;

            default:
                $statuses[] = '<div class="poor">' . $this->t('Not installed') . '</div>';
        }

        $statuses[] = $module->getEnabled()
            ? '<div class="good">' . $this->t('Enabled')  . '</div>'
            : '<div class="none">' . $this->t('Disabled') . '</div>';

        return implode('', $statuses);
    }

    /**
     * Get modules list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModules()
    {
        if (is_null(static::$modules)) {

            $method = 'find' . \XLite\Core\Converter::convertToCamelCase(static::getFilter()) . 'Modules';

            // Call corresponding method depending on filter:
            // find{All|Active|Upgradable}Modules()
            static::$modules = \XLite\Core\Database::getRepo('\XLite\Model\Module')->$method();
        }

        return static::$modules;
    }

    /**
     * Get modules count for different filters
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModulesCount($filter = null)
    {
        if (is_null(static::$modulesCount)) {

            $mCount = array();

            foreach (array_keys(static::getFilters()) as $f) {

                $method = 'find' . \XLite\Core\Converter::convertToCamelCase($f) . 'Modules';

                // Call corresponding method depending on filter: find{All|Active|Upgradable}Modules()
                $mCount[$f] = count(\XLite\Core\Database::getRepo('\XLite\Model\Module')->$method());
            }
            static::$modulesCount = $mCount;
        }
        
        return is_null($filter) 
            ? static::$modulesCount
            : static::$modulesCount[$filter];
    }

    /**
     * Return filters array
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function getFilters()
    {
        if (is_null(static::$filters)) {

            static::$filters = array(
                static::FILTER_ALL        => 'All',
                static::FILTER_INACTIVE   => 'Inactive',
                static::FILTER_UPGRADABLE => 'Upgradable',
            );
        }

        return static::$filters;
    }

    /**
     * Return filters array
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected static function getFilter()
    {
        $filter = \XLite\Core\Request::getInstance()->filter;

        if (
            is_null($filter)
            || empty($filter)
            || !in_array($filter, array_keys(static::getFilters()))
        ) {

            static::$filter = static::FILTER_ALL;

        } else {
        
            static::$filter = $filter;
        }

        return static::$filter;
    }


    /**
     * Check if the module can be enabled
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function canEnable(\XLite\Model\Module $module)
    {
        return $module->getEnabled()
            || $module->canEnable();
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Manage Add-ons';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules_manager' . LC_DS . 'manage';
    }

    /**
     * Return installed module property
     *
     * @param \XLite\Model\Module $module   Module
     * @param string              $property Module property
     *
     * @return string|array
     * @access protected
     * @since  3.0.0
     */
    protected function getInstalledProperty(\XLite\Model\Module $module, $property = '')
    {
        return $module->__call('get' . \XLite\Core\Converter::convertToCamelCase($property));
    }
    
}

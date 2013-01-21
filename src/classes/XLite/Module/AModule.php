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

namespace XLite\Module;

/**
 * Module
 *
 */
abstract class AModule
{
    /**
     * Method to initialize concrete module instance
     *
     * @return void
     */
    public static function init()
    {
    }

    /**
     * Decorator run this method at the end of cache rebuild
     *
     * @return void
     */
    public static function runBuildCacheHandler()
    {
    }

    /**
     * Return module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        \Includes\ErrorHandler::fireErrorAbstractMethodCall(__METHOD__);
    }

    /**
     * Return author full name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        \Includes\ErrorHandler::fireErrorAbstractMethodCall(__METHOD__);
    }

    /**
     * Return module description
     *
     * @return string
     */
    public static function getDescription()
    {
        \Includes\ErrorHandler::fireErrorAbstractMethodCall(__METHOD__);
    }

    /**
     * Return URL for module icon
     *
     * @return string
     */
    public static function getIconURL()
    {
        list($author, $name) = explode(
            '\\',
            \Includes\Utils\ModulesManager::getModuleNameByClassName(get_called_class())
        );

        $path = \Includes\Utils\ModulesManager::getModuleIconFile($author, $name);
        $url  = '';

        if (\Includes\Utils\FileManager::isFileReadable($path)) {
            $url = \XLite\Core\Converter::buildURL('module', null, compact('author', 'name'), 'image.php');
        }

        return $url;
    }

    /**
     * Return link to the module author page
     *
     * @return string
     */
    public static function getAuthorPageURL()
    {
        return '';
    }

    /**
     * Return link to the module page
     *
     * @return string
     */
    public static function getPageURL()
    {
        return '';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean
     */
    public static function showSettingsForm()
    {
        return false;
    }

    /**
     * Return link to settings form
     *
     * @return string
     */
    public static function getSettingsForm()
    {
        return null;
    }

    /**
     * Return module dependencies
     *
     * @return array
     */
    public static function getDependencies()
    {
        return array();
    }

    /**
     * Return list of mutually exclusive modules
     *
     * @return array
     */
    public static function getMutualModulesList()
    {
        return array();
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Get module minor version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        \Includes\ErrorHandler::fireErrorAbstractMethodCall(__METHOD__);
    }

    /**
     * Get module version
     *
     * @return string
     */
    public static function getVersion()
    {
        return \Includes\Utils\Converter::composeVersion(static::getMajorVersion(), static::getMinorVersion());
    }

    /**
     * Return true if module is 'system module' and admin cannot disable/uninstall and view this module in the modules list
     *
     * @return boolean
     */
    public static function isSystem()
    {
        return false;
    }
}

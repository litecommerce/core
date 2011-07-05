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

namespace XLite\Module;

/**
 * Module
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AModule
{
    /**
     * Method to initialize concrete module instance
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function init()
    {
    }

    /**
     * Return module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getModuleName()
    {
        \Includes\ErrorHandler::fireError('Abstract method call: ' . __METHOD__);
    }

    /**
     * Return author full name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAuthorName()
    {
        \Includes\ErrorHandler::fireError('Abstract method call: ' . __METHOD__);
    }

    /**
     * Return module description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDescription()
    {
        \Includes\ErrorHandler::fireError('Abstract method call: ' . __METHOD__);
    }

    /**
     * Return URL for module icon
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAuthorPageURL()
    {
        return '';
    }

    /**
     * Return link to the module page
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getPageURL()
    {
        return '';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function showSettingsForm()
    {
        return false;
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getSettingsForm()
    {
        return null;
    }

    /**
     * Return module dependencies
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDependencies()
    {
        return array();
    }

    /**
     * Get module major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getMajorVersion()
    {
        return \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Get module minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getMinorVersion()
    {
        \Includes\ErrorHandler::fireError('Abstract method call: ' . __METHOD__);
    }

    /**
     * Get module version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getVersion()
    {
        return \Includes\Utils\Converter::composeVersion(static::getMajorVersion(), static::getMinorVersion());
    }
}

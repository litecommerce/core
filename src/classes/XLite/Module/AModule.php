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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module;

/**
 * Module
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AModule
{
   /**
     * Method to initialize concrete module instance
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init()
    {
    }

    /**
     * Return module name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getModuleName()
    {
        throw new \Exception('Name is not specified for the ' . $this->getName() . ' add-on');
    }

    /**
     * Return author full name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getAuthorName()
    {
        throw new \Exception('Full name is not specified for the ' . $this->getAuthor() . ' author class');
    }

    /**
     * Return module description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getDescription()
    {
        throw new \Exception('Description is not specified for the ' . $this->getName() . ' add-on');
    }

    /**
     * Return URL for module icon
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getIconURL()
    {
        return null;
    }

    /**
     * Return link to the module author page
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAuthorPageURL()
    {
        return null;
    }

    /**
     * Return link to the module page
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPageURL()
    {
        return null;
    }

    /**
     * Determines if we need to show settings form link
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0
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
     * @since  3.0
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
     * @since  3.0
     */
    public static function getDependencies()
    {
        return array();
    }

    /**
     * Get post-installation user notes
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPostInstallationNotes()
    {
        return '';
    }

    /**
     * Get post-deinstallation user notes
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPostUninstallationNotes()
    {
        return '';
    }

    /**
     * Custom installation routine
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function installModule(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Custom deinstallation routine
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function uninstallModule(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Custom wake-up (enable) module routine
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function wakeUpModule(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Custom sleep (disable) module routine
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sleepModule(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Get backup data 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return array|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getBackupData(\XLite\Model\Module $module)
    {
        return null;
    }

    /**
     * Return module name by class name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected static function getModuleCode()
    {
        if (!preg_match('/XLite\\\Module\\\(\w+)\\\Main/S', get_called_class(), $matches)) {
            throw new \Exception('Could not resolve base module code from the class name: ' . get_called_class());
        }

        return $matches[1];
    }


    // ------------------------------ Module versions -

    /**
     * Get module version
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getVersion()
    {
        return \Includes\Utils\Converter::composeVersion(static::getMajorVersion(), static::getMinorVersion());
    }

    /**
     * Get module major version
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getMajorVersion()
    {
        return \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Get module minor version
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getMinorVersion()
    {
        \Includes\ErrorHandler::fireError('Abstract method call: ' . __METHOD__);
    }
}

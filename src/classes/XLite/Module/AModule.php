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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
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
     * Module types
     */

    const MODULE_UNKNOWN   = 0;
    const MODULE_PAYMENT   = 1;
    const MODULE_SHIPPING  = 2;
    const MODULE_SKIN      = 3;
    const MODULE_CONNECTOR = 4;
    const MODULE_GENERAL   = 5;
    const MODULE_3RD_PARTY = 6;

   /**
     * Method to initialize concrete module instance
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init()
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
    public function getModuleName()
    {
        return $this->getModuleCode();
    }

    /**
     * Return module type 
     * 
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public function getModuleType()
    {
        return self::MODULE_3RD_PARTY;
    }

    /**
     * Return module version
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    abstract public function getVersion();

    /**
     * Return module description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    abstract public function getDescription();

    /**
     * Determines if we need to show settings form link
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public function showSettingsForm()
    {
        return false;
    }

    /**
     * Return link to settings form.
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public function getSettingsForm()
    {
        return null;
    }

    /**
     * Return module dependencies
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public function getDependencies()
    {
        return array();
    }

    /**
     * Return list of modules whitch are not allowed to be enbled at one time 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public function getMutualModules()
    {
        return array();
    }

    /**
     * Get post-installation user notes
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPostInstallationNotes()
    {
        return '';
    }

    /**
     * Get post-deinstallation user notes
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPostUninstallationNotes()
    {
        return '';
    }

    /**
     * Check module
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function check()
    {
        return true;
    }

    /**
     * Custom installation routine
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function installModule(\XLite\Model\Module $module)
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
    public function uninstallModule(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Get module icon 
     * TODO: implement the module icons functionality
     * 
     * @return \XLite\Model\Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIcon()
    {
        return null;
    }

    /**
     * Check if module has icon 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasIcon()
    {
        return !is_null($this->getIcon());
    }

    /**
     * Get external page URL
     * TODO: rework this when modules management is implemented
     *       on LiteCommerce site
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExternalPageURL()
    {
        return '#' . $this->getModuleCode();
    }

    /**
     * Page on LiteCommerce site checker
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasExternalPage()
    {
        return false;
    }

    /**
     * Return module name by class name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected function getModuleCode()
    {
        if (!preg_match('/XLite\\\Module\\\(\w+)\\\Main/S', get_class($this), $matches)) {
            // TODO - throw exception
        }

        return $matches[1];
    }

    /**
     * Easy way to register payment method
     * 
     * @param string $name payment method name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected function registerPaymentMethod($name)
    {
        $method = new \XLite\Model\PaymentMethod();
        $class  = 'Module\\' . $this->getModuleCode() . '\Model\PaymentMethod\\' . \XLite\Core\Converter::convertToCamelCase($name);

        return $method->registerMethod($name, $class);
    }

    /**
     * Easy way to register shipping module 
     * 
     * @param string $name shipping module name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected function registerShippingModule($name)
    {
        $shipping = new \XLite\Model\Shipping();
        $class  = 'Module\\' . $this->getModuleCode() . '\Model\Shipping\\' . \XLite\Core\Converter::convertToCamelCase($name);

        return $shipping->registerShippingModule($name, $class);
    }

    /**
     * Adds layout template file for the specified widget
     * 
     * @param string $widgetName   The widget name
     * @param string $templateName The template file name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addLayout($widgetName, $templateName)
    {
        \XLite\Model\Layout::getInstance()->addLayout($widgetName, $templateName);
    }

}

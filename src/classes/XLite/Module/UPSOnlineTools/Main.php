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
 * @subpackage Module
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\UPSOnlineTools;

/**
 * UPS Online Tools integration
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Module name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getModuleName()
    {
        return 'UPS OnLine Tools';
    }

    const CRYPT_SALT = 85;
    const START_CHAR_CODE = 100;

    /**
     * Module version
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getDescription()
    {
        return 'This module enables the access to UPS OnLine Tools';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getSettingsForm()
    {
        return \XLite\Core\Converter::buildUrl('ups_online_tool', '', array(), \XLite::ADMIN_SELF);
    }

    /**
     * Initialize module 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function init()
    {
        parent::init();

        // Register UPS shipping processor
        \XLite\Model\Shipping::getInstance()->registerProcessor('\XLite\Module\UPSOnlineTools\Model\Shipping\Processor\UPS');

        \XLite::getInstance()->set('UPSOnlineToolsEnabled', true);

        // Check UPS account activation
        $options = \XLite\Core\Config::getInstance()->UPSOnlineTools;
        if (!$options->UPS_username || !$options->UPS_password || !$options->UPS_accesskey) {
            \XLite\Core\Config::getInstance()->UPSOnlineTools->av_status = 'N';
        }
    }

    /**
     * Check - gdlib PHP extension is avalable or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isGDLibValid()
    {
        $functions = array(
            'imagecreate',
            'ImageColorAllocate',
            'imagefilledrectangle',
            'imagerectangle',
            'imagestring',
            'imagejpeg'
        );

        $result = true;
        foreach ($functions as $function) {
            if (!function_exists($function)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}


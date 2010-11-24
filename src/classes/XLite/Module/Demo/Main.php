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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\Demo;

/**
 * Demo module
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Module type
     *
     * @return integer 
     * @access public
     * @since  3.0.0
     */
    public static function getModuleType()
    {
        return self::MODULE_GENERAL;
    }

    /**
     * Module version
     *
     * @return string
     * @access public
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
     * @since  3.0.0
     */
    public static function getDescription()
    {
        return 'Demo mode';
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public static function init()
    {
        if (\XLite::getInstance()->isAdminZone()) {
            \Includes\Utils\ConfigParser::registerConfigFile('config.demo.php');
        }
        
        parent::init();
    }

    /**
     * Forbid action processing
     * 
     * @param string $message Action message
     * @param string $url     Redirect URL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function doForbidAction($message = null, $url = null)
    {
        self::doForbidOperation($message);

        if (!$url) {
            $url = \XLite\Core\Converter::buildURL(\XLite\Core\Request::getInstance()->target);
        }

        header('Location: ' . $url);
        exit(0);
    }

    /**
     * Forbid operation processing
     * 
     * @param string $message Message
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function doForbidOperation($message = null)
    {
        if (!$message) {
            $message = 'You cannot do this in demo mode.';
        }

        \XLite\Core\TopMessage::getInstance()->add($message, \XLite\Core\TopMessage::WARNING);
    }
}

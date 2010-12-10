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

namespace XLite\Module\CDev\Intershipper;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

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
        return 'Intershipper';
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'Intershipper real-time shipping calculation';
    }
    /**
     * Determines if we need to show settings form link
     *
     * @return boolean 
     * @access public
     * @since  3.0
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
     * @since  3.0
     */
    public static function getSettingsForm()
    {
        return "admin.php?target=intershipper";
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init()
    {
        parent::init();

        // Register AustraliaPost shipping processor
        \XLite\Model\Shipping::getInstance()->registerProcessor('\XLite\Module\CDev\Intershipper\Model\Shipping\Processor\Intershipper');

    }

    /**
     * Get post-installation user notes
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPostInstallationNotes()
    {
        return '<b>Note:</b> You should purchase an account from <a href="http://www.intershipper.com/">www.intershipper.com</a> to access this service.<br /><br />After that, please setup your Intershipper settings on the <a href="admin.php?target=intershipper">Intershipper setup page</a>, also available in your "Shipping" menu.';
    }
}

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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\Moneybookers;

/**
 * Moneybookers payment gateway integration (iframe)
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public static function getModuleName()
    {
        return 'Moneybookers';
    }

    /**
     * Module version
     *
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getMinorVersion()
    {
        return '4';
    }

    /**
     * Module description
     *
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getDescription()
    {
        return 'Enables taking payments for your online shop via Moneybookers payment gateway (iframe integration method)';
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
        return 'admin.php?target=moneybookers_settings';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean
     * @access public
     * @since  1.0.0
     */
    public static function showSettingsForm()
    {
        return true;
    }
}

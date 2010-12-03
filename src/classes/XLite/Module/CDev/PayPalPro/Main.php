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

namespace XLite\Module\CDev\PayPalPro;

/**
 * Module controller
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
        return 'Creative Development LLC <info@cdev.ru>';
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
        return 'PayPal Pro';
    }

    /**
     * Module version
     *
     * @return string
     * @access public
     * @since  3.0
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
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'This module introduces support for several PayPal website payment solutions';
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
        return \XLite\Core\Converter::buildURL(
            'payment_method',   
            '',
            array('payment_method' => 'paypalpro'),
            \XLite::ADMIN_SELF
        );
    }

    /**
     * Perform some actions at startup
     * FIXME: must be completely revised
     *        registerPaymentMethod() method no longer exists
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init() 
    {
        parent::init();

        $pm = new \XLite\Model\PaymentMethod('paypalpro');
        $params = $pm->get('params');
        $solution = $params['solution'];

        switch ($solution) {
            case 'standard':
                $this->registerPaymentMethod('paypalpro');
                \XLite\Model\PaymentMethod::factory('paypalpro')->checkServiceURL();
                break;

            case 'pro':
                $this->registerPaymentMethod('paypalpro');

            case 'express':
                $this->registerPaymentMethod('paypalpro_express');
                break;

            default:
        }

        \XLite::getInstance()->set('PayPalProEnabled', true);
        \XLite::getInstance()->set('PayPalProSolution', $solution);

        if ('standard' !== $solution) {
            \XLite::getInstance()->set(
                'PayPalProExpressEnabled',
                \XLite\Model\PaymentMethod::factory('paypalpro_express')->get('enabled')
            );
        }
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
        return '<b>Note:</b> Please visit the <a href="admin.php?target=payment_method&payment_method=paypalpro">Payment method setup page</a> in order to configure PayPal Pro module.';
    }
}

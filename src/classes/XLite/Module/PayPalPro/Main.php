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

namespace XLite\Module\PayPalPro;

/**
 * Module controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \XLite\Module\AModule
{
    /**
     * Module type
     *
     * @return int
     * @access public
     * @since  3.0
     */
    public static function getModuleType()
    {
        return self::MODULE_PAYMENT;
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
        return '2.9';
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
     * @return bool
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
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init() 
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
}

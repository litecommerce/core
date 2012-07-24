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

namespace XLite\Module\CDev\Paypal\View;

/**
 * Paypal payment method settings dialog
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class PaypalSettings extends \XLite\View\Dialog
{
    /**
     * Parameter names
     */
    const PARAM_PAYMENT_METHOD = 'paymentMethod';


    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/Paypal/settings/style.css';

        return $list;
    }

    /**
     * getPaymentProcessor 
     * 
     * @return \XLite\Payment\Base\Processor
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function getPaymentProcessor()
    {
        return $this->getParam(self::PARAM_PAYMENT_METHOD)
            ? $this->getParam(self::PARAM_PAYMENT_METHOD)->getProcessor()
            : null;
    }


    /**
     * defineWidgetParams 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PAYMENT_METHOD => new \XLite\Model\WidgetParam\Object('Payment method', null),
        );
    }

    /**
     * getPaypalMethodTemplate 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getDir()
    {
        return $this->getPaymentProcessor() ? $this->getPaymentProcessor()->getSettingsTemplateDir() : null;
    }

    // {{{ Content

    /**
     * Get register URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPaypalRegisterURL()
    {
        return 'http://www.paypal.com/';
    }

    // }}}
}

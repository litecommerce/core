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

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Paypal Payments Advanced payment processor
 *
 * @see   ____class_see____
 * @since 1.1.0
 */
class PaypalAdvanced extends \XLite\Module\CDev\Paypal\Model\Payment\Processor\APaypal
{
    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getSettingsTemplateDir()
    {
        return 'modules/CDev/Paypal/settings/paypal_advanced';
    }

    /**
     * Get the list of merchant countries where this payment processor can work
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAllowedMerchantCountries()
    {
        return array('US', 'CA');
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array_merge(
            parent::getAllowedCurrencies($method),
            array(
                'USD', // US Dollar
                'CAD', // Canadian Dollar
                'AUD', // Australian Dollar
                'EUR', // Euro
                'GBP', // British Pound Sterling
                'JPY', // Japanese Yen
            )
        );
    }

    /**
     * Return array of parameters for 'CAPTURE' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCaptureRequestParams(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $params = parent::getCaptureRequestParams($transaction);

        $params['CAPTURECOMPLETE'] = 'Y';

        return $params;
    }

}

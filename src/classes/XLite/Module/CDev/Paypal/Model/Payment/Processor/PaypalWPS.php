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
 * Paypal Payments Standard payment processor
 *
 */
class PaypalWPS extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Referral page URL 
     * 
     * @var string
     */
    protected $referralPageURL = 'https://www.paypal.com/webapps/mpp/referral/paypal-payments-standard?partner_id=LiteCommerce';


    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsWidget()
    {
        return '\XLite\Module\CDev\Paypal\View\PaypalSettings';
    }

    /**
     * Return false to use own submit button on payment method settings form
     * 
     * @return boolean
     */
    public function useDefaultSettingsFormButton()
    {
        return false;
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsTemplateDir()
    {
        return 'modules/CDev/Paypal/settings/paypal_wps';
    }

    /**
     * Get URL of referral page
     *
     * @return string
     */
    public function getPartnerPageURL(\XLite\Model\Payment\Method $method)
    {
        return \XLite::PRODUCER_SITE_URL . 'partners/paypal.html';
    }

    /**
     * Get URL of referral page
     *
     * @return string
     */
    public function getReferralPageURL(\XLite\Model\Payment\Method $method)
    {
        return $this->referralPageURL;
    }

    /**
     * Prevent enabling Paypal Standard if Express Checkout is already enabled
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return boolean
     */
    public function canEnable(\XLite\Model\Payment\Method $method)
    {
        return parent::canEnable($method)
            && \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPS == $method->getServiceName()
            && !$this->isExpressCheckoutEnabled();
    }

    /**
     * Return true if ExpressCheckout method is enabled 
     * 
     * @return boolean
     */
    public function isExpressCheckoutEnabled()
    {
        static $result = null;

        if (!isset($result)) {
    
            $m = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(
                array(
                    'service_name' => \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC,
                )
            );

            $result = $m && $m->isEnabled();
        }

        return $result;
    }

    /**
     * Get note with explanation why payment method can not be enabled
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return string
     */
    public function getForbidEnableNote(\XLite\Model\Payment\Method $method)
    {
        $result = parent::getForbidEnableNote($method);

        if (\XLite\Module\CDev\Paypal\Main::PP_METHOD_PPS == $method->getServiceName()) {
            $result = 'This payment method cannot be enabled together with PayPal Express Checkout method';
        }

        return $result;
    }

    /**
     * Process callback
     *
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     *
     * @return void
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processCallback($transaction);

        if (\XLite\Module\CDev\Paypal\Model\Payment\Processor\PaypalIPN::getInstance()->isCallbackIPN()) {
            // If callback is IPN request from Paypal

            \XLite\Module\CDev\Paypal\Model\Payment\Processor\PaypalIPN::getInstance()
                ->processCallbackIPN($transaction, $this);
        }

        $this->saveDataFromRequest();
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        if (\XLite\Core\Request::getInstance()->cancel) {

            $this->setDetail(
                'cancel',
                'Payment transaction is cancelled'
            );

            $this->transaction->setStatus($transaction::STATUS_FAILED);

        } elseif ($transaction::STATUS_INPROGRESS == $this->transaction->getStatus()) {

            $this->transaction->setStatus($transaction::STATUS_PENDING);
        }
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('account');
    }

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return true;
    }


    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        return $this->isTestMode($this->transaction->getPaymentMethod())
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';
    }

    /**
     * Return TRUE if the test mode is ON
     *
     * @param \XLite\Model\Payment\Method $method Payment method object
     *
     * @return boolean
     */
    public function isTestMode(\XLite\Model\Payment\Method $method)
    {
        return \XLite\View\FormField\Select\TestLiveMode::TEST === $method->getSetting('mode');
    }


    /**
     * Return ITEM NAME for request
     *
     * @return string
     */
    protected function getItemName()
    {
        return $this->getSetting('description') . '(Order #' . $this->getOrder()->getOrderId() . ')';
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        $orderId = $this->getOrder()->getOrderId();

        $fields = array(
            'charset'       => 'UTF-8',
            'cmd'           => '_ext-enter',
            'custom'        => $orderId,
            'invoice'       => $this->getSetting('prefix') . $orderId,
            'redirect_cmd'  => '_xclick',
            'item_name'     => $this->getItemName(),
            'rm'            => '2',
            'email'         => $this->getProfile()->getLogin(),
            'first_name'    => $this->getProfile()->getBillingAddress()->getFirstname(),
            'last_name'     => $this->getProfile()->getBillingAddress()->getLastname(),
            'business'      => $this->getSetting('account'),
            'amount'        => $this->getAmountValue(),
            'tax_cart'      => 0,
            'shipping'      => 0,
            'handling'      => 0,
            'weight_cart'   => 0,
            'currency_code' => $this->getOrder()->getCurrency()->getCode(),

            'return'        => $this->getReturnURL(null, true),
            'cancel_return' => $this->getReturnURL(null, true, true),
            'shopping_url'  => $this->getReturnURL(null, true, true),
            'notify_url'    => $this->getCallbackURL(null, true),

            'country'       => $this->getCountryFieldValue(),
            'state'         => $this->getStateFieldValue(),
            'address1'      => $this->getProfile()->getBillingAddress()->getStreet(),
            'address2'      => 'n/a',
            'city'          => $this->getProfile()->getBillingAddress()->getCity(),
            'zip'           => $this->getProfile()->getBillingAddress()->getZipcode(),
            'upload'        => 1,
            'bn'            => 'LiteCommerce',
        );

        // Always use address passed from shopping cart
        // (prevent customer from selection of other address on Paypal side)
        $fields['address_override'] = 1;

        $fields = array_merge($fields, $this->getPhone());

        return $fields;
    }

    /**
     * Return amount value. Specific for Paypal
     *
     * @return string
     */
    protected function getAmountValue()
    {
        $value = $this->transaction->getValue();

        settype($value, 'float');

        $value = sprintf('%0.2f', $value);

        return $value;
    }

    /**
     * Return Country field value. if no country defined we should use '' value
     *
     * @return string
     */
    protected function getCountryFieldValue()
    {
        return $this->getProfile()->getBillingAddress()->getCountry()
            ? $this->getProfile()->getBillingAddress()->getCountry()->getCode()
            : '';
    }

    /**
     * Return State field value. If country is US then state code must be used.
     *
     * @return string
     */
    protected function getStateFieldValue()
    {
        return 'US' === $this->getCountryFieldValue()
            ? $this->getProfile()->getBillingAddress()->getState()->getCode()
            : $this->getProfile()->getBillingAddress()->getState()->getState();
    }

    /**
     * Return Phone structure. specific for Paypal
     *
     * @return array
     */
    protected function getPhone()
    {
        $result = array();

        $phone = $this->getProfile()->getBillingAddress()->getPhone();

        $phone = preg_replace('![^\d]+!', '', $phone);

        if ($phone) {
            if (
                $this->getProfile()->getBillingAddress()->getCountry()
                && 'US' == $this->getProfile()->getBillingAddress()->getCountry()->getCode()
            ) {
                $result = array(
                    'night_phone_a' => substr($phone, -10, -7),
                    'night_phone_b' => substr($phone, -7, -4),
                    'night_phone_c' => substr($phone, -4),
                );
            } else {
                $result['night_phone_b'] = substr($phone, -10);
            }
        }

        return $result;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
     */
    protected function defineSavedData()
    {
        return array(
            'secureid'          => 'Transaction id',
            'mc_gross'          => 'Payment amount',
            'payment_type'      => 'Payment type',
            'payment_status'    => 'Payment status',
            'pending_reason'    => 'Pending reason',
            'reason_code'       => 'Reason code',
            'mc_currency'       => 'Payment currency',
            'auth_id'           => 'Authorization identification number',
            'auth_status'       => 'Status of authorization',
            'auth_exp'          => 'Authorization expiration date and time',
            'auth_amount'       => 'Authorization amount',
            'payer_id'          => 'Unique customer ID',
            'payer_email'       => 'Customer\'s primary email address',
            'txn_id'            => 'Original transaction identification number',
        );
    }

    /**
     * Log redirect form
     *
     * @param array $list Form fields list
     *
     * @return void
     */
    protected function logRedirect(array $list)
    {
        $list = $this->maskCell($list, 'account');

        parent::logRedirect($list);
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array_merge(
            parent::getAllowedCurrencies($method),
            array(
                'USD', 'CAD', 'EUR', 'GBP', 'AUD',
                'CHF', 'JPY', 'NOK', 'NZD', 'PLN',
                'SEK', 'SGD', 'HKD', 'DKK', 'HUF',
                'CZK', 'BRL', 'ILS', 'MYR', 'MXN',
                'PHP', 'TWD', 'THB',
            )
        );
    }
}

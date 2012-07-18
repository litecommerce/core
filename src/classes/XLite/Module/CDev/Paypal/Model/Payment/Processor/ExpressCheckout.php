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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.1
 */

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Paypal Express Checkout payment processor
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class ExpressCheckout extends \XLite\Module\CDev\Paypal\Model\Payment\Processor\APaypal
{
    const PAYPAL_PAYMENT_METHOD_CODE = 'Paypal Express Checkout';

    /**
     * Request types definition
     */
    const REQ_TYPE_SET_EXPRESS_CHECKOUT         = 'SetExpressCheckout';
    const REQ_TYPE_GET_EXPRESS_CHECKOUT_DETAILS = 'GetExpressCheckoutDetails';
    const REQ_TYPE_DO_EXPRESS_CHECKOUT_PAYMENT  = 'DoExpressCheckoutPayment';

    const EC_TYPE_SHORTCUT = 'shortcut';
    const EC_TYPE_MARK     = 'mark';


    protected $ecPostURL = 'https://www.paypal.com/cgi-bin/webscr';


    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getSettingsWidget()
    {
        return '\XLite\Module\CDev\Paypal\View\PaypalSettings';
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getSettingsTemplateDir()
    {
        return 'modules/CDev/Paypal/settings/express_checkout';
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('vendor')
            && $method->getSetting('pwd')
            && $this->isMerchantCountryAllowed();
    }

    /**
     * Return true if merchant country is allowed for this payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMerchantCountryAllowed()
    {
        return in_array(
            \XLite\Core\Config::getInstance()->Company->location_country,
            $this->getAllowedMerchantCountries()
        );
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
     * Perform 'SetExpressCheckout' request and get Token value from Paypal
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function doSetExpressCheckout()
    {
        $token = null;

        $responseData = $this->doRequest(self::REQ_TYPE_SET_EXPRESS_CHECKOUT);

        if (!empty($responseData['TOKEN'])) {
            $token = $token = $responseData['TOKEN'];
        }

        return $token;
    }

    /**
     * Redirect customer to Paypal server for authorization and address selection
     * 
     * @param string $token Express Checkout token
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function redirectToPaypal($token, $type = self::EC_TYPE_SHORTCUT)
    {
        $url = $this->getPostURL($this->ecPostURL, $this->getPostParams($token, $type));

        $page = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="javascript: self.location = '$url';">
</body>
</html>
HTML;

        print ($page);
        exit ();
    }

    /**
     * Get array of parameters for redirecting customer to Paypal server
     * 
     * @param string $token Express Checkout token
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getPostParams($token, $type = self::EC_TYPE_SHORTCUT)
    {
        return array(
            'cmd' => '_express_checkout',
            'token' => $token,
            'useraction' => (self::EC_TYPE_MARK == $type ? 'commit' : 'continue'),
        );
    }

    /**
     * Get array of parameters for SET_EXPRESS_CHECKOUT request
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSetExpressCheckoutRequestParams($type = self::EC_TYPE_SHORTCUT)
    {
        $postData = array(
            'TRXTYPE'           => $this->getSetting('transaction_type'),
            'TENDER'            => 'P',
            'ACTION'            => 'S',
            'RETURNURL'         => urldecode($this->getECReturnURL(null, true)),
            'CANCELURL'         => urldecode($this->getECReturnURL(null, true, true)),
            'AMT'               => $this->getOrder()->getCurrency()->roundValue($this->transaction->getValue()),
            'CURRENCY'          => $this->getCurrencyCode(),
            'FREIGHTAMT'        => $this->getOrder()->getCurrency()->roundValue($this->getOrder()->getSurchargeSumByType('SHIPPING')),
            'HANDLINGAMT'       => 0,
            'INSURANCEAMT'      => 0,
            'NOSHIPPING'        => 0,
            'INVNUM'            => $this->getOrder()->getOrderId(),
            'ALLOWNOTE'         => 1,
            'CUSTOM'            => $this->getOrder()->getOrderId(),
            'LOCALECODE'        => 'EN',
            // 'HDRIMG', // The URL for an image to be used as the header image for the PayPal Express Checkout pages
            'PAYFLOWCOLOR'      => 'FF0000', // The secondary gradient color for the order summary section of the PayPal Express Checkout pages
        );

        $postData = $postData + $this->getLineItems();

        if (self::EC_TYPE_SHORTCUT == $type) {
            $postData['REQCONFIRMSHIPPING'] = 0;

        } elseif (self::EC_TYPE_MARK == $type) {
            $postData += array(
                'ADDROVERRIDE'      => 'N',
                'PHONENUM'    => $this->getProfile()->getBillingAddress()->getPhone(),
                'EMAIL'       => $this->getProfile()->getLogin(),
                'SHIPTONAME'        => $this->getProfile()->getShippingAddress()->getFirstname() . $this->getProfile()->getShippingAddress()->getLastname(),
                'SHIPTOSTREET'      => $this->getProfile()->getShippingAddress()->getStreet(),
                'SHIPTOSTREET2'     => '',
                'SHIPTOCITY'        => $this->getProfile()->getShippingAddress()->getCity(),
                'SHIPTOSTATE'       => $this->getProfile()->getShippingAddress()->getState()->getCode(),
                'SHIPTOZIP'         => $this->getProfile()->getShippingAddress()->getZipcode(),
                'SHIPTOCOUNTRY'     => $this->getProfile()->getShippingAddress()->getCountry()->getCode3(),
            );
        }

        return $postData;
    }

    /**
     * processReturnFromExpressCheckout 
     * 
     * @param mixed $token ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function doGetExpressCheckoutDetails($token)
    {
        $data = array();

        $params = array('token' => $token);

        $responseData = $this->doRequest(self::REQ_TYPE_GET_EXPRESS_CHECKOUT_DETAILS, $params);

        if (!empty($responseData) && '0' == $responseData['RESULT']) {
            $data = $responseData;
        }

        return $data;
    }

    /**
     * Return array of parameters for 'GetExpressCheckoutDetails' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getGetExpressCheckoutDetailsRequestParams($transaction = null, $customParams = array())
    {
        $params = array(
            'TRXTYPE' => $this->getSetting('transaction_type'),
            'TENDER' => 'P',
            'ACTION' => 'G',
            'TOKEN' => $customParams['token'],
        );

        return $params;
    }


    protected function doInitialPayment()
    {
        if ('EC_TYPE_MARK' == $this->getCart()->get('ec_type')) {
            
        }

        $this->transaction->createBackendTransaction($this->getInitialTransactionType());

        $status = $this->doExpressCheckoutPayment();

        return $status;
    }

    protected function doExpressCheckoutPayment()
    {
        $status = self::FAILED;

        $transaction = $this->transaction;

        $responseData = $this->doRequest(self::REQ_TYPE_DO_EXPRESS_CHECKOUT_PAYMENT);

        $transactionStatus = $transaction::STATUS_FAILED;

        if (!empty($responseData) && '0' == $responseData['RESULT']) {
            
            if ($this->isSuccessResponse($responseData)) {
                $transactionStatus = $transaction::STATUS_SUCCESS;
                $status = self::SUCCESS;

            } else {
                $transactionStatus = $transaction::STATUS_PENDING;
                $status = self::PENDING;
            }
        }

        $transaction->setStatus($transactionStatus);

        $this->updateInitialBackendTransaction($transaction, $transactionStatus);

        return $status;
    }

    /**
     * Return array of parameters for 'DoExpressCheckoutPayment' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDoExpressCheckoutPaymentRequestParams($transaction = null, $customParams = array())
    {
        $params = array(
            'TRXTYPE'      => $this->getSetting('transaction_type'),
            'TENDER'       => 'P',
            'ACTION'       => 'D',
            'TOKEN'        => $this->getOrder()->get('ec_token'),
            'PAYERID'      => $this->getOrder()->get('ec_payer_id'),
            'AMT'          => $this->getOrder()->getCurrency()->roundValue($this->transaction->getValue()),
            'CURRENCY'     => $this->getCurrencyCode(),
            'FREIGHTAMT'   => $this->getOrder()->getCurrency()->roundValue($this->getOrder()->getSurchargeSumByType('SHIPPING')),
            'HANDLINGAMT'  => 0,
            'INSURANCEAMT' => 0,
            'NOTIFYURL'    => $this->getCallbackURL(null, true),
            'INVNUM'       => $this->getOrder()->getOrderId(),
            'ALLOWNOTE'    => 1,
            'CUSTOM'       => $this->getOrder()->getOrderId(),
        );

        $params += $this->getLineItems();

        return $params;
    }


    protected function getECReturnURL($asCancel = false)
    {
        $params = $asCancel ? array('cancel' => 1) : array();

        return \XLite::getInstance()->getShopURL(
            \XLite\Core\Converter::buildURL('checkout', 'express_checkout_return', $params),
            \XLite\Core\Config::getInstance()->Security->customer_security
        );
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
                'USD', 'CAD', 'EUR', 'GBP', 'AUD',
                'CHF', 'JPY', 'NOK', 'NZD', 'PLN',
                'SEK', 'SGD', 'HKD', 'DKK', 'HUF',
                'CZK', 'BRL', 'ILS', 'MYR', 'MXN',
                'PHP', 'TWD', 'THB',
            )
        );
    }
}

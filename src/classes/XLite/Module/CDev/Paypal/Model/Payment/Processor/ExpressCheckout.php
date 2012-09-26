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
 * Paypal Express Checkout payment processor
 *
 */
class ExpressCheckout extends \XLite\Module\CDev\Paypal\Model\Payment\Processor\APaypal
{
    /**
     * Request types definition
     */
    const REQ_TYPE_SET_EXPRESS_CHECKOUT         = 'SetExpressCheckout';
    const REQ_TYPE_GET_EXPRESS_CHECKOUT_DETAILS = 'GetExpressCheckoutDetails';
    const REQ_TYPE_DO_EXPRESS_CHECKOUT_PAYMENT  = 'DoExpressCheckoutPayment';

    /**
     * Express Checkout flow types definition
     */
    const EC_TYPE_SHORTCUT = 'shortcut';
    const EC_TYPE_MARK     = 'mark';


    /**
     * Express Checkout token TTL is 3 hours (10800 seconds)
     */
    const TOKEN_TTL = 10800;


    /**
     * Live PostURL 
     * 
     * @var string
     */
    protected $livePostURL = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * Test PostURL 
     * 
     * @var string
     */
    protected $testPostURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';


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
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsTemplateDir()
    {
        return 'modules/CDev/Paypal/settings/express_checkout';
    }

    /**
     * Get payment method row checkout template
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getCheckoutTemplate(\XLite\Model\Payment\Method $method)
    {
        return 'modules/CDev/Paypal/method.tpl';
    }

    /**
     * Get the list of merchant countries where this payment processor can work
     *
     * @return array
     */
    public function getAllowedMerchantCountries()
    {
        return array('US', 'CA');
    }

    /**
     * Perform 'SetExpressCheckout' request and get Token value from Paypal
     * 
     * @return string
     */
    public function doSetExpressCheckout(\XLite\Model\Payment\Method $method)
    {
        $token = null;

        if (!isset($this->transaction)) {
            $this->transaction = new \XLite\Model\Payment\Transaction();
            $this->transaction->setPaymentMethod($method);
            $this->transaction->setOrder(\XLite\Model\Cart::getInstance());
        }

        $responseData = $this->doRequest(self::REQ_TYPE_SET_EXPRESS_CHECKOUT);

        if (!empty($responseData['TOKEN'])) {
            $token = $responseData['TOKEN'];

        } elseif (self::EC_TYPE_MARK == \XLite\Core\Session::getInstance()->ec_type) {
            $this->setDetail(
                'status',
                isset($responseData['RESPMSG']) ? $responseData['RESPMSG'] : 'Unknown',
                'Status'
            );
        }

        return $token;
    }

    /**
     * Redirect customer to Paypal server for authorization and address selection
     * 
     * @param string $token Express Checkout token
     *  
     * @return void
     */
    public function redirectToPaypal($token)
    {
        $url = $this->getRedirectURL($this->getPostParams($token));

        \XLite\Module\CDev\Paypal\Main::addLog(
            'redirectToPaypal()',
            $url
        );

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
     * doGetExpressCheckoutDetails
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     * @param string                      $token  Token
     *  
     * @return array
     */
    public function doGetExpressCheckoutDetails(\XLite\Model\Payment\Method $method, $token)
    {
        $data = array();

        $params = array('token' => $token);

        if (!isset($transaction)) {
            $this->transaction = new \XLite\Model\Payment\Transaction();
            $this->transaction->setPaymentMethod($method);
        }

        $responseData = $this->doRequest(self::REQ_TYPE_GET_EXPRESS_CHECKOUT_DETAILS);

        if (!empty($responseData) && '0' == $responseData['RESULT']) {
            $data = $responseData;
        }

        return $data;
    }

    /**
     * Process return (this used when customer pay via Express Checkout mark flow)
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Payment transaction object
     *  
     * @return void
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        if (!\XLite\Core\Request::getInstance()->cancel) {

            \XLite\Core\Session::getInstance()->ec_payer_id = \XLite\Core\Request::getInstance()->PayerID;

            $this->doExpressCheckoutPayment();
        }
    }


    /**
     * Get PostURL to redirect customer to Paypal
     * 
     * @return string
     */
    protected function getExpressCheckoutPostURL()
    {
        return $this->isTestMode($this->transaction->getPaymentMethod()) ? $this->testPostURL : $this->livePostURL;
    }

    /**
     * Get array of parameters for redirecting customer to Paypal server
     * 
     * @param string $token Express Checkout token
     *  
     * @return array
     */
    protected function getPostParams($token)
    {
        $params = array(
            'cmd' => '_express-checkout',
            'token' => $token,
        );

        if (self::EC_TYPE_MARK == \XLite\Core\Session::getInstance()->ec_type) {
            $params['useraction'] = 'commit';
        }

        return $params;
    }

    /**
     * Get array of parameters for SET_EXPRESS_CHECKOUT request
     *
     * @return array
     */
    protected function getSetExpressCheckoutRequestParams()
    {
        $cart = \XLite\Model\Cart::getInstance();

        $shippingModifier = $cart->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');

        if ($shippingModifier && $shippingModifier->canApply()) {
            $noShipping = '0';
            $freightAmt = $cart->getCurrency()->roundValue($cart->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_SHIPPING));

        } else {
            $noShipping = '1';
            $freightAmt = 0;
        }

        $postData = array(
            'TRXTYPE'           => $this->getSetting('transaction_type'),
            'TENDER'            => 'P',
            'ACTION'            => 'S',
            'RETURNURL'         => urldecode($this->getECReturnURL()),
            'CANCELURL'         => urldecode($this->getECReturnURL(true)),
            'AMT'               => $cart->getCurrency()->roundValue($cart->getTotal()),
            'CURRENCY'          => $cart->getCurrency()->getCode(),
            'FREIGHTAMT'        => $freightAmt,
            'HANDLINGAMT'       => 0,
            'INSURANCEAMT'      => 0,
            'NOSHIPPING'        => $noShipping,
            'INVNUM'            => $cart->getOrderId(),
            'ALLOWNOTE'         => 1,
            'CUSTOM'            => $cart->getOrderId(),
        );

        $postData = $postData + $this->getLineItems($cart);

        $type = \XLite\Core\Session::getInstance()->ec_type;

        if (self::EC_TYPE_SHORTCUT == $type) {
            $postData['REQCONFIRMSHIPPING'] = 0;

        } elseif (self::EC_TYPE_MARK == $type) {
            $postData += array(
                'ADDROVERRIDE'  => 1,
                'PHONENUM'      => $this->getProfile()->getBillingAddress()->getPhone(),
                'EMAIL'         => $this->getProfile()->getLogin(),
            );

            if ('1' !==$noShipping) {
                $postData += array(
                    'SHIPTONAME'    => $this->getProfile()->getShippingAddress()->getFirstname() . $this->getProfile()->getShippingAddress()->getLastname(),
                    'SHIPTOSTREET'  => $this->getProfile()->getShippingAddress()->getStreet(),
                    'SHIPTOSTREET2' => '',
                    'SHIPTOCITY'    => $this->getProfile()->getShippingAddress()->getCity(),
                    'SHIPTOSTATE'   => $this->getProfile()->getShippingAddress()->getState()->getCode(),
                    'SHIPTOZIP'     => $this->getProfile()->getShippingAddress()->getZipcode(),
                    'SHIPTOCOUNTRY' => $this->getProfile()->getShippingAddress()->getCountry()->getCode(),
                );
            }
        }

        return $postData;
    }


    /**
     * Return array of parameters for 'GetExpressCheckoutDetails' request 
     *
     * @return array
     */
    protected function getGetExpressCheckoutDetailsRequestParams()
    {
        $params = array(
            'TRXTYPE' => $this->getSetting('transaction_type'),
            'TENDER' => 'P',
            'ACTION' => 'G',
            'TOKEN' => \XLite\Core\Session::getInstance()->ec_token,
        );

        return $params;
    }


    /**
     * Do initial payment and return status
     * 
     * @return string
     */
    protected function doInitialPayment()
    {
        $this->transaction->createBackendTransaction($this->getInitialTransactionType());

        $result = self::FAILED;

        if (!$this->isTokenValid() || self::EC_TYPE_MARK == \XLite\Core\Session::getInstance()->ec_type) {

            \XLite\Core\Session::getInstance()->ec_type = self::EC_TYPE_MARK;

            $token = $this->doSetExpressCheckout($this->transaction->getPaymentMethod());

            if (isset($token)) {
                \XLite\Core\Session::getInstance()->ec_token = $token;
                \XLite\Core\Session::getInstance()->ec_date = time();
                \XLite\Core\Session::getInstance()->ec_payer_id = null;

                $this->redirectToPaypal($token);

            } else {
                \XLite\Core\TopMessage::getInstance()->addError('Failure to redirect to PayPal.');
            }

        } else {
            $result = $this->doExpressCheckoutPayment();
        }

        return $result;
    }

    /**
     * Returns true if token initialized and is not expired
     * 
     * @return boolean
     */
    protected function isTokenValid()
    {
        return !empty(\XLite\Core\Session::getInstance()->ec_token)
            && self::TOKEN_TTL > time() - \XLite\Core\Session::getInstance()->ec_date;
    }

    /**
     * Perform 'DoExpressCheckoutPayment' request and return status of payment transaction
     * 
     * @return string
     */
    protected function doExpressCheckoutPayment()
    {
        $status = self::FAILED;

        $transaction = $this->transaction;

        $responseData = $this->doRequest(
            self::REQ_TYPE_DO_EXPRESS_CHECKOUT_PAYMENT,
            $transaction->getInitialBackendTransaction()
        );

        $transactionStatus = $transaction::STATUS_FAILED;

        if (!empty($responseData)) {

            if ('0' == $responseData['RESULT']) {
            
                if ($this->isSuccessResponse($responseData)) {
                    $transactionStatus = $transaction::STATUS_SUCCESS;
                    $status = self::COMPLETED;

                } else {
                    $transactionStatus = $transaction::STATUS_PENDING;
                    $status = self::PENDING;
                }

            } elseif (preg_match('/^10486/', $responseData['RESPMSG'])) {
                $this->retryExpressCheckout(\XLite\Core\Session::getInstance()->ec_token);

            } else {
                $this->setDetail(
                    'status',
                    'Failed: ' . $responseData['RESPMSG'],
                    'Status'
                );
            }

            // Save payment transaction data
            $this->saveFilteredData($responseData);

        } else {
            $this->setDetail(
                'status',
                'Failed: unexpected response received from PayPal',
                'Status'
            );
        }

        $transaction->setStatus($transactionStatus);

        $this->updateInitialBackendTransaction($transaction, $transactionStatus);

        \XLite\Core\Session::getInstance()->ec_token = null;
        \XLite\Core\Session::getInstance()->ec_date = null;
        \XLite\Core\Session::getInstance()->ec_payer_id = null;
        \XLite\Core\Session::getInstance()->ec_type = null;

        return $status;
    }

    /**
     * Retry ExpressCheckout
     * 
     * @param string $token Express Checkout token value
     *  
     * @return void
     */
    protected function retryExpressCheckout($token)
    {
        $this->redirectToPaypal($token);
    }

    /**
     * Return array of parameters for 'DoExpressCheckoutPayment' request 
     *
     * @return array
     */
    protected function getDoExpressCheckoutPaymentRequestParams($transaction = null, $customParams = array())
    {
        $params = array(
            'TRXTYPE'      => $this->getSetting('transaction_type'),
            'TENDER'       => 'P',
            'ACTION'       => 'D',
            'TOKEN'        => \XLite\Core\Session::getInstance()->ec_token,
            'PAYERID'      => \XLite\Core\Session::getInstance()->ec_payer_id,
            'AMT'          => $this->getOrder()->getCurrency()->roundValue($this->transaction->getValue()),
            'CURRENCY'     => $this->getCurrencyCode(),
            'FREIGHTAMT'   => $this->getOrder()->getCurrency()->roundValue($this->getOrder()->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_SHIPPING)),
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

    /**
     * Get return URL
     * 
     * @param boolean $asCancel Flag: true if URL is for Cancel action) OPTIONAL
     *  
     * @return string
     */
    protected function getECReturnURL($asCancel = false)
    {
        $params = $asCancel ? array('cancel' => 1) : array();

        if (self::EC_TYPE_MARK == \XLite\Core\Session::getInstance()->ec_type) {
            $url = $this->getReturnURL(null, true, $asCancel);

        } else {
            $url = \XLite::getInstance()->getShopURL(
                \XLite\Core\Converter::buildURL('checkout', 'express_checkout_return', $params),
                \XLite\Core\Config::getInstance()->Security->customer_security
            );
        }

        return $url;
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

    /**
     * Get post URL 
     * 
     * @param array $params Array of URL parameters OPTIONAL
     *  
     * @return string
     */
    protected function getRedirectURL($params = array())
    {
        $postURL = $this->getExpressCheckoutPostURL();

        $postData = array();

        foreach ($params as $k => $v) {
            $postData[] = sprintf('%s=%s', $k, $v);
        }

        return $postURL . '?' . implode('&', $postData);
    }
}

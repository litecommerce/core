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

/**
 * PayPal Direct payment / Standart
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Model_PaymentMethod_Paypalpro extends XLite_Model_PaymentMethod_CreditCardWebBased
{
    /**
     * Pending reasons 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pendingReasons = array(
        'echeck'     => 'The payment is pending because it was made by an eCheck, which has not yet cleared',
        'multi_currency' => 'You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment',
        'intl'       => 'The payment is pending because you, the merchant, hold an international account and do not have a withdrawal method.  You must manually accept or deny this payment from your Account Overview',
        'verify'     => 'The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment',
        'address'    => 'The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments.  To change your preference, go to the Preferences section of your Profile',
        'upgrade'    => 'The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds',
        'unilateral' => 'The payment is pending because it was made to an email address that is not yet registered or confirmed',
        'other'      => 'The payment is pending for some reason. For more information, contact PayPal customer service',
    );

    /**
     * AVS response codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $avsResponses = array(
        'A' => 'Address only (no ZIP)',
        'B' => 'Address only (no ZIP)',
        'C' => 'None',
        'D' => 'Address and Postal Code',
        'E' => 'Not allowed for MOTO (Internet/Phone) transactions',
        'F' => 'Address and Postal Code',
        'G' => 'Global Unavailable',
        'I' => 'International Unavailable', 
        'N' => 'No', 
        'P' => 'Postal Code only (no Address)',
        'R' => 'Retry',
        'S' => 'Service not supported',
        'U' => 'Unavailable',
        'W' => 'Whole ZIP',
        'X' => 'Exact match',
        'Y' => 'Yes',
        'Z' => 'ZIP',
    );

    /**
     * CVV response codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cvvResponses = array(
        'M' => 'Match',
        'N' => 'Not match',
        'P' => 'Not processed',
        'S' => 'Service not supported',
        'U' => 'Unavailable',
        'X' => 'No response',
    );

    /**
     * Configuration template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $configurationTemplate = 'modules/PayPalPro/config.tpl';

    /**
     * Processor name 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $processorName = 'PayPal Standard';

    /**
     * Phone parts
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $phone = null;

    /**
     * Configuration request handler (controller part)
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleConfigRequest()
    {
        $pm = new XLite_Model_PaymentMethod('paypalpro');
        $pm->handleConfigRequest();
    }

    /**
     * Check service URL 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkServiceURL()
    {
        $params = $this->get('params');
        if ('standard' == $params['solution']) {
            $sUrls = array(
                'live_url' => 'https://www.paypal.com/cgi-bin/webscr',
                'test_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
            );

            $paramsUpdated = false;

            foreach ($sUrls as $sUrlParam => $sUrl) {
                if (
                    !isset($params['standard'][$sUrlParam])
                    || (isset($params['standard'][$sUrlParam]) && strlen(trim($params['standard'][$sUrlParam])) == 0)
                ) {
                    $paramsUpdated = true;
                    $params['standard'][$sUrlParam] = $sUrl;
                }
            }

            if ($paramsUpdated) {
                $this->set('params', $params);
                $this->update();
            }
        }
    }

    /**
     * Get form URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFormURL()
    {
        return '1' == $this->getComplex('params.standard.mode')
            ? 'https://www.paypal.com/cgi-bin/webscr'
            : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }

    /**
     * Get form fields 
     *
     * @param XLite_Model_Cart $cart $cart
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFields(XLite_Model_Cart $cart)
    {
        $params = $this->get('params');

        $fields = array(
            'charset'       => 'ISO-8859-1',
            'cmd'           => '_ext-enter',
            'invoice'       => $params['standard']['prefix'] . $cart->get('order_id'),
            'redirect_cmd'  => '_xclick',
            'mrb'           => 'R-2JR83330TB370181P',
            'pal'           => 'RDGQCFJTT6Y6A',
            'rm'            => 2,
            'email'         => $cart->getProfile()->get('login'),
            'first_name'    => $cart->getProfile()->get('billing_firstname'),
            'last_name'     => $cart->getProfile()->get('billing_lastname'),
            'address1'      => $cart->getProfile()->get('billing_address'),
            'city'          => $cart->getProfile()->get('billing_city'),
            'state'         => $this->getBillingState($cart),
            'country'       => $cart->getProfile()->get('billing_country'),
            'zip'           => $cart->getProfile()->get('billing_zipcode'),
            'business'      => $params['standard']['login'],
            'item_name'     => $this->getItemName($cart),
            'amount'        => sprintf('%0.2f', $cart->get('total')),
            'currency_code' => $params['standard']['currency'],
            'bn'            => 'x-cart',
            'return'        => $this->getCartReturnURL($cart),
            'cancel_return' => $this->getCancelUrl(),
            'notify_url'    => $this->getNotifyUrl($cart),
            'tax_cart'      => 0,
            'shipping'      => 0,
            'handling'      => 0,
            'weight_cart'   => 0,
            'upload'        => 1,
            'address_override' => 1,
            'night_phone_a'    => $this->getPhone($cart, 'a'),
            'night_phone_b'    => $this->getPhone($cart, 'b'),
            'night_phone_c'    => $this->getPhone($cart, 'c'),
        );

        if ('1' == $params['standard']['auth']) {
            $fields['paymentaction'] = 'authorization';
        }

        if (!$cart->get('shipping_cost')) {
            $fields['no_shipping'] = 1;
        }

        return $fields;
    }

    /**
     * Handle request
     *
     * @param XLite_Model_Cart $cart Cart
     * @param string           $type Call type
     *
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart, $type = self::CALL_CHECKOUT)
    {
        parent::handleRequest($cart, $type);

        if (self::CALL_BACK == $type) {

            $params = $this->get('params');
            $request = XLite_Core_Request::getInstance();

            if (strcasecmp($params['standard']['login'], $request->business) != 0) {
                $this->doDie(
                    'IPN validation error: PayPal account doesn\'t match: '
                    . $request->business
                    . '. Please contact administrator.'
                );
            }

            if (is_null($cart->getDetail('reason'))) { 

                $r = new XLite_Model_HTTPS();
                $r->url = '1' == $params['standard']['mode']
                    ? $params['standard']['live_url']
                    : $params['standard']['test_url'];

                $r->data = $request->getData();
                $r->data['cmd'] = '_notify-validate';
                $r->request();
            
                if ($r->error) {

                    $cart->setDetailsCell('error', 'HTTPS Error', $r->error);
                    $cart->set('status', 'F');
                    $cart->update();

                    return self::PAYMENT_FAILURE; 

                } elseif (preg_match('/VERIFIED/i', $r->response)) {

                    $txnId = $cart->getDetail('reason')
                        ? ''
                        : $cart->getDetail('txn_id');
                }    

                $paymentStatus = $request->payment_status;

                if (
                    0 == strcasecmp($paymentStatus, 'Completed')
                    || 0 == strcasecmp($paymentStatus, 'Pending')
                ) {

                    if ($request->txn_id == $txnId) {

                        $total = sprintf('%.2f', $cart->get('total'));
                        $postTotal = sprintf('%.2f', $request->mc_gross);

                        if (
                            0 != strcasecmp($paymentStatus, 'Completed')
                            || $total != $postTotal
                            || $params['standard']['currency'] != $request->mc_currency
                        ) {
                            $cart->setDetailsCell('error', 'Error', 'Duplicate transaction - ' . $request->txn_id);
                            $cart->set('status', 'F');
                            $cart->update();

                            return self::PAYMENT_FAILURE;
                        }

                    } else {

                        $cart->setDetailsCell('txn_id', 'Transaction ID', $request->txn_id);
                        $cart->setDetailsCell('payment_status', 'Payment Status', $paymentStatus);
                        if (isset($request->memo)) {
                            $cart->setDetailsCell('memo', 'Customer notes entered on the PayPal page', $request->memo);
                        }

                        $total = sprintf('%.2f', $cart->get('total'));
                        $postTotal = sprintf('%.2f', $request->mc_gross);

                        if ($total != $postTotal) {
                            $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
                            $cart->setDetailsCell(
                                'errorDescription',
                                'Hacking attempt details',
                                'Total amount doesn\'t match: Order total = ' . $total
                                . ', PayPal amount = ' . $postTotal
                            );
                            $cart->set('status', 'F');
                            $cart->update();

                            $this->doDie(
                                'IPN validation error: PayPal amount doesn\'t match. Please contact administrator.'
                            );
                        }

                        $currency = $this->getComplex('params.standard.currency');
                        if ($currency != $request->mc_currency) {
                            $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
                            $cart->setDetailsCell(
                                'errorDescription',
                                'Hacking attempt details',
                                'Currency code doesn\'t match: Order currency = ' . $currency
                                . ', PayPal currency = ' . $request->mc_currency
                            );
                            $cart->set('status', 'F');
                            $cart->update();

                            $this->doDie(
                                'IPN validation error: PayPal currency code doesn\'t match.'
                                . ' Please contact administrator.'
                            );
                        }

                        if (0 == strcasecmp($paymentStatus, 'Pending')) {

                            $cart->set('status', $params['standard']['use_queued'] ? 'Q' : 'I');
                            $cart->setDetailsCell(
                                'reason',
                                'Pending Reason',
                                $this->pendingReasons[$request->pending_reason]
                            );

                        } else {

                            $cart->set('status', 'P');

                        }     

                        $cart->unsetDetailsCell('error');
                        $cart->unsetDetailsCell('errorDescription');

                        $cart->update();
                    }
                }

            } else {

                $cartPaymentStatus = $cart->getDetail('payment_status');
                $cartTxnId = $cart->getDetail('txn_id');
                $cartReason = $cart->getDetail('reason');
                $paymentStatus = $request->payment_status;

                if (
                    $cartPaymentStatus == 'Pending'
                    && $cartTxnId == $request->txn_id
                    && $cartReason == $this->pendingReasons[$request->payment_type]
                ) {

                    $cart->setDetailsCell('payment_status', 'Payment Status', $paymentStatus);

                    if (isset($request->memo)) {
                        $cart->setDetailsCell('memo', 'Customer notes entered on the PayPal page', $request->memo);
                    }

                    if (0 == strcasecmp($paymentStatus, 'Pending')) {

                        $cart->set('status', $params['standard']['use_queued'] ? 'Q' : 'I');
                        $cart->setDetailsCell(
                            'reason',
                            'Pending Reason',
                            $this->pendingReasons[$request->pending_reason]
                        );

                    } elseif (0 == strcasecmp($paymentStatus, 'Completed')) {

                        $cart->set('status', 'P');

                    }

                    $cart->unsetDetailsCell('error');
                    $cart->unsetDetailsCell('errorDescription');
                    
                    $cart->update();
                }
            }
        
            return self::PAYMENT_SUCCESS; 
        }
    }

    /**
     * Get phone part
     * 
     * @param XLite_Model_Cart $cart Cart
     * @param string           $type Phone part
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPhone(XLite_Model_Cart $cart, $type = 'a')
    {
        if (empty($this->phone)) {

            $phone = preg_replace('/[ ()-]/', '', $cart->getProfile()->get('billing_phone'));
            if ('US' == $cart->getProfile()->get('billing_country')) {
                $this->phone = array(
                    'a' => substr($phone, -10, -7),
                    'b' => substr($phone, -7, -4),
                    'c' => substr($phone, -4),
                );

            } else {
                $this->phone = array(
                    'a' => '',
                    'b' => $phone,
                    'c' => '',
                );
            }
        }

        return $this->phone[$type];        
    }

    /**
     * Get item name 
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemName(XLite_Model_Cart $cart)
    {
        return $this->config->Company->company_name . ' order #' . $cart->get('order_id');
    }

    /**
     * Get billing state 
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBillingState(XLite_Model_Cart $cart)
    {
        $profile = $cart->getProfile();

        $billingState = $profile->getComplex('billingState.code');
        if (empty($billingState)) {
            return 'International';
        }

        $country = $profile->get('billing_country');
        $billingState = ('US' == $country || 'CA' == $country) ? 'code' : 'state';

        return $profile->getComplex('billingState.' . $billingState);
    }

    /**
     * Get cancel URL
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCancelUrl()
    {
        return $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('checkout', 'paypal_cancel'),
            $this->config->Security->customer_security
        );
    }

    /**
     * Get notify (callback) URL 
     *
     * @param XLite_Model_Cart $cart Cart
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNotifyUrl(XLite_Model_Cart $cart)
    {
        return $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('callback', 'callback', array('order_id' => $cart->get('order_id')))
        );
    }

    /**
     * Get return URL
     *
     * @param XLite_Model_Cart $cart Cart
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCartReturnURL(XLite_Model_Cart $cart)
    {
        return $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('checkout', 'paypal_return'),
            XLite_Core_Request::getInstance()->isHTTPS()
        );
    }

}

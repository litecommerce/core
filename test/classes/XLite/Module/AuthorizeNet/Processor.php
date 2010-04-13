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
 * @subpackage Module
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Authorize.NET processor 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_AuthorizeNet_Processor extends XLite_Base
{
    /**
     * CVV error codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cvverr = array(
        'M' => 'Card Code matches',
        'N' => 'Card Code does not match',
        'P' => 'Card Code was not processed',
        'S' => 'Card code should be on card but was not indicated',
        'U' => 'Issuer was not certified for Card Code',
    );

    /**
     * AVS error codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $avserr = array(
        'A' => 'Address (Street) matches, ZIP does not',
        'B' => 'Address information not provided for AVS check',
        'E' => 'AVS error',
        'G' => 'Non-U.S. Card Issuing Bank ', 
        'N' => 'No Match on Address (Street) or ZIP',
        'P' => 'AVS not applicable for this transaction',
        'R' => 'Please Retry. System unavailable or timed out',
        'S' => 'AVS Service not supported by issuer',
        'U' => 'Address information is unavailable',
        'W' => '9 digit ZIP matches, Address (Street) does not',
        'X' => 'Address (Street) and 9 digit ZIP match',
        'Y' => 'Address (Street) and 5 digit ZIP match',
        'Z' => '5 digit ZIP matches, Address (Street) does not',
    );

    /**
     * Process cart
     *
     * @param XLite_Model_Cart                     $cart          Cart
     * @param XLite_Model_PaymentMethod_CreditCard $paymentMethod Payment method
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function process(XLite_Model_Cart $cart, XLite_Model_PaymentMethod_CreditCard $paymentMethod)
    {
        $params = $paymentMethod->get('params');

        $request = new XLite_Model_HTTPS();
        $request->url = $params['url'];
        $request->data = array(
            'x_delim_data' => 'true',
            'x_relay_response' => 'false',
            'x_delim_char' => ';,',
            'x_encap_char' => '\'',
            'x_Login' => $params['login'],
            'x_Tran_Key' => $params['key'],
            'x_Amount' => $cart->get('total'),
            'x_Type' => $params['type'],
            'x_Test_Request' => $params['test'], // TRUE|FALSE
            'x_Address' => $cart->getComplex('profile.billing_address'),
            'x_Ship_To_Address' => $cart->getComplex('profile.shipping_address'),
            'x_City' => $cart->getComplex('profile.billing_city'),
            'x_Ship_To_City' => $cart->getComplex('profile.shipping_city'),
            'x_Country' => $cart->getComplex('profile.billing_country'),
            'x_Ship_To_Country' => $cart->getComplex('profile.shipping_country'),
            'x_First_Name' => $cart->getComplex('profile.billing_firstname'),
            'x_Ship_To_First_Name' => $cart->getComplex('profile.shipping_firstname'),
            'x_Last_Name' => $cart->getComplex('profile.billing_lastname'),
            'x_Ship_To_Last_Name' => $cart->getComplex('profile.shipping_lastname'),
            'x_State' => $cart->getComplex('profile.billingState.code'),
            'x_Ship_To_State' => $cart->getComplex('profile.shippingState.code'),
            'x_Zip' => $cart->getComplex('profile.billing_zipcode'),
            'x_Ship_To_Zip' => $cart->getComplex('profile.shipping_zipcode'),
            'x_Phone' => $cart->getComplex('profile.billing_phone'),
            'x_Email' => $cart->getComplex('profile.login'),
            'x_Cust_ID' => $cart->getComplex('profile.profile_id'),
            'x_Merchant_Email' => $this->config->getComplex('Company.orders_department'),
            'x_Currency_Code' => $params['currency'],
            'x_Invoice_Num' => $params['prefix'] . $cart->get('order_id'),
            'x_Description' => $cart->get('description'),
            'x_Version' => '3.1',
        );

        if ($request->data['x_Country'] != 'US') {
            $request->data['x_State'] = 'Non US';
            $request->data['x_Ship_To_State'] = 'Non US' ;
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $request->data['x_Customer_IP'] = $_SERVER['REMOTE_ADDR'];
        }
        $paymentMethod->initRequest($cart, $request);

        $request->request();
        $response = explode(';,', $request->response);

        // strip '''
        foreach ($response as $key => $val) {
            $response[$key] = substr($val, 1, strlen($val)-2);
        }

        $status = 'I';
        if (count($response) < 38) {
            $error = 'Can\'t connect to ' . $params['url'];
            $status = 'F';

        } else {
            $transid = $response[6];
            $cart->setComplex('details.transid', $transid);
            $cart->set('detailLabels.transid', 'Authorize.Net Transaction ID');
            // md5 hash check, if configured
            $amount = sprintf('%.2f', $cart->get('total')); 
            if (!empty($params['md5HashValue'])) {
                $value = md5(
                    $params['md5HashValue']
                    . $params['login']
                    . $transid
                    . $amount
                );
            }        

            if (
                !empty($params['md5HashValue']) 
                && strcasecmp($value, $response[37])
            ) {

                // MD5 mismatch
                $msg = 'MD5 hash is invalid: ' . $response[37] . '. Please contact administrator';
                $cart->set('details.error', $msg);
                $cart->setComplex('detailLabels.error', 'Error');
                $this->doDie('Your order won\'t go thru. ' . $msg);
                // do not update order
                return;

            } else {

                if ('1' == $response[0] && 'F' != $status) {
                    // success
                    $error = '';
                    $status = 'P';

                } else {
                    // failure
                    $error = $response[3];
                    $status = 'F';
                }

                $cart->set('detailLabels.cvvMessage', 'CVV message');

                if ($response[38]) {
                    $cart->setComplex('details.cvvMessage', $this->cvverr[$response[38]]);
                } else {
                    $cart->setComplex('details.cvvMessage', null);
                }

                $cart->set('detailLabels.avsMessage', 'AVS message');

                if ($response[5]) {
                    $cart->setComplex('details.avsMessage', $this->avserr[$response[5]]);
                } else {
                    $cart->setComplex('details.avsMessage', null);
                }
            }
        }

        $cart->setComplex('details.error', $error);
        $cart->setComplex('detailLabels.error', 'Error');
        $cart->set('status', $status);

        $cart->update();
    }

    /**
     * Handle configuration request
     *
     * @return mixed Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleConfigRequest()
    {
        $params = XLite_Core_Request::getInstance()->params;

        $pm = XLite_Model_PaymentMethod::factory('authorizenet_cc');
        $pm->set('params', $params);
        $pm->update();

        $pm = XLite_Model_PaymentMethod::factory('authorizenet_ch');
        $pm->set('params', $params);
        $pm->update();
    }
}

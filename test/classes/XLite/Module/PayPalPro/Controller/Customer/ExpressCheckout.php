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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Express checkout landing controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Controller_Customer_ExpressCheckout extends XLite_Controller_Customer_Abstract
{
    /**
     * Get secure controller status
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSecure()
    {
        return $this->config->Security->customer_security;
    }

    /**
     * Call 'profile' action
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callActionProfile()
    {
        $this->doActionProfile();
    }
    
    /**
     * Profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionProfile()
    {
        $pm = XLite_Model_PaymentMethod::factory('paypalpro_express');

        $response = $pm->sendExpressCheckoutRequest($this->getCart()); 

        if ($response['ACK'] == 'Success' && !empty($response['TOKEN'])) {

            $pmpro = XLite_Model_PaymentMethod::factory('paypalpro');

            $redirect = $pmpro->getComplex('params.pro.mode')
                ? 'https://www.paypal.com'
                : 'https://www.sandbox.paypal.com';

            header('Location: ' . $redirect . '/webscr?cmd=_express-checkout&token=' . $response['TOKEN']);
            $this->doDie();

        } else {
            $this->set('returnUrl', $this->buildUrl('checkout'));
        }
    }

    /**
     * Retrieve profile from PayPal
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRetrieveProfile()
    {
        $request = XLite_Core_Request::getInstance();

        $pm = new XLite_Model_PaymentMethod('paypalpro');

        if (
            in_array($pm->getComplex('params.solution'), array('pro', 'express'))
            && isset($request->token)
            && !empty($request->token)
        ) {

            $profile = new XLite_Model_Profile();
            $pm = XLite_Model_PaymentMethod::factory('paypalpro_express');
            $response = $pm->sendExpressCheckoutDetailsRequest($request->token);

            if (
                $response
                && 'Success' == $pm->getXMLResponseValue('base:Ack', $response)
            ) {

                $details = $pm->xpath->query('base:GetExpressCheckoutDetailsResponseDetails/base:PayerInfo', $response)->item(0);

                $state = new XLite_Model_State();
                $countryCode = $pm->getXMLResponseValue('base:Address/base:Country', $details);
                $stateCode = addslashes($pm->getXMLResponseValue('base:Address/base:StateOrProvince', $details));
                $stateCondition = 'US' == $countryCode
                    ? 'code = \'' . $stateCode . '\''
                    : '(code = \'' . $stateCode . '\ OR state = \'' . $stateCode . '\')';

                $state->find('country_code = \'' . $countryCode . '\' AND ' . $stateCondition);

                $payer = $pm->getXMLResponseValue('base:Payer', $details);

                if ($this->getCart()->getProfile()) {

                    // User is present

                    $profile = $this->getCart()->getProfile();

                    $profile->set(
                        'shipping_firstname',
                        $pm->getXMLResponseValue('base:PayerName/base:FirstName', $details)
                    );
                    $profile->set(
                        'shipping_lastname',
                        $pm->getXMLResponseValue('base:PayerName/base:LastName', $details)
                    );
                    $profile->set('shipping_company', '');
                    $profile->set('shipping_fax', '');
                    $profile->set(
                        'shipping_phone',
                        $pm->getXMLResponseValue('base:Address/base:Phone', $details)
                    );
                    $profile->set(
                        'shipping_address', 
                        $pm->getXMLResponseValue('base:Address/base:Street1', $details)
                        . ' '
                        . $pm->getXMLResponseValue('base:Address/base:Street2', $details)
                    );
                    $profile->set(
                        'shipping_city',
                        $pm->getXMLResponseValue('base:Address/base:CityName', $details)
                    );
                    $profile->set(
                        'shipping_state',
                        $state->get('state_id')
                    );
                    $profile->set(
                        'shipping_country',
                        $pm->getXMLResponseValue('base:Address/base:Country', $details)
                    );
                    $profile->set(
                        'shipping_zipcode',
                        $pm->getXMLResponseValue('base:Address/base:PostalCode', $details)
                    );

                    $profile->update();

                } elseif ($profile->find('login = \'' . addslashes($payer) . '\' AND order_id = 0')) {

                    // Profile is found but this is nor current cart profile
                    $this->set('valid', false);
                    $this->redirect($ths->buldUrl('profile', 'login'));

                } else {

                    // New profile

                    $profile = new XLite_Model_Profile();

                    $profile->set('login', $payer);

                    $profile->set(
                        'billing_firstname',
                        $pm->getXMLResponseValue('base:PayerName/base:FirstName', $details)
                    );
                    $profile->set(
                        'billing_lastname',
                        $pm->getXMLResponseValue('base:PayerName/base:LastName', $details)
                    );
                    $profile->set('billing_company', '');
                    $profile->set('billing_fax', '');
                    $profile->set(
                        'billing_phone',
                        $pm->getXMLResponseValue('base:Address/base:Phone', $details)
                    );
                    $profile->set(
                        'billing_address', 
                        $pm->getXMLResponseValue('base:Address/base:Street1', $details)
                        . ' '
                        . $pm->getXMLResponseValue('base:Address/base:Street2', $details)
                    );
                    $profile->set(
                        'billing_city',
                        $pm->getXMLResponseValue('base:Address/base:CityName', $details)
                    );
                    $profile->set(
                        'billing_state',
                        $state->get('state_id')
                    );
                    $profile->set(
                        'billing_country',
                        $pm->getXMLResponseValue('base:Address/base:Country', $details)
                    );
                    $profile->set(
                        'billing_zipcode',
                        $pm->getXMLResponseValue('base:Address/base:PostalCode', $details)
                    );

                    $this->auth->register($profile);
                    $this->auth->loginProfile($profile);
                    $this->auth->getProfile()->set('order_id', $this->getCart()->get('order_id'));
                    $this->auth->getProfile()->update();
                }
                                                        
                XLite_Model_Auth::getInstance()->getProfile()->read();

                $this->getCart()->set('paymentMethod', $pm);

                $this->getCart()->setDetail(
                    'token',
                    $pm->getXMLResponseValue('base:GetExpressCheckoutDetailsResponseDetails/base:Token', $response)
                );
                $this->getCart()->setDetail(
                    'payer_id',
                    $pm->getXMLResponseValue('base:PayerID', $details)
                );

                $this->updateCart();

            } 
        }

        $this->set('returnUrl', $this->buildUrl('checkout'));
    }
}

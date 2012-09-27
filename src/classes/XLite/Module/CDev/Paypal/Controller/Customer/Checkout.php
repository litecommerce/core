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

namespace XLite\Module\CDev\Paypal\Controller\Customer;

/**
 * Checkout controller
 * 
 */
class Checkout extends \XLite\Controller\Customer\Checkout implements \XLite\Base\IDecorator
{
    /**
     * Modify request to allow start Express Checkout process
     * 
     * @return void
     */
    public function handleRequest()
    {
        if (
            isset(\XLite\Core\Request::getInstance()->action)
            && $this->isExpressCheckoutAction()
            && $this->getCart()->checkCart()
        ) {
            \XLite\Core\Request::getInstance()->setRequestMethod('POST');
        }

        parent::handleRequest();
    }

    /**
     * Returns true if action is related to Express Checkout process 
     * 
     * @return void
     */
    protected function isExpressCheckoutAction()
    {
        return in_array(
            \XLite\Core\Request::getInstance()->action,
            array('start_express_checkout', 'express_checkout_return')
        );
    }

    /**
     * doActionStartExpressCheckout 
     * 
     * @return void
     */
    protected function doActionStartExpressCheckout()
    {
        if (\XLite\Module\CDev\Paypal\Main::isExpressCheckoutEnabled()) {
            $paymentMethod = $this->getExpressCheckoutPaymentMethod();

            $this->getCart()->setPaymentMethod($paymentMethod);

            $this->updateCart();

            \XLite\Core\Session::getInstance()->ec_type
                = \XLite\Module\CDev\Paypal\Model\Payment\Processor\ExpressCheckout::EC_TYPE_SHORTCUT;

            $token = $paymentMethod->getProcessor()->doSetExpressCheckout($paymentMethod);

            if (isset($token)) {
                \XLite\Core\Session::getInstance()->ec_token = $token;
                \XLite\Core\Session::getInstance()->ec_date = time();
                \XLite\Core\Session::getInstance()->ec_payer_id = null;

                $paymentMethod->getProcessor()->redirectToPaypal($token);

            } else {
                \XLite\Core\TopMessage::getInstance()->addError('Failure to redirect to PayPal.');
            }
        }
    }

    /**
     * doExpressCheckoutReturn 
     * 
     * @return void
     */
    protected function doActionExpressCheckoutReturn()
    {
        $request = \XLite\Core\Request::getInstance();
        $cart = $this->getCart();

        \XLite\Module\CDev\Paypal\Main::addLog('doExpressCheckoutReturn()', $request->getData());

        if (isset($request->cancel)) {
            \XLite\Core\Session::getInstance()->ec_token = null;
            \XLite\Core\Session::getInstance()->ec_date = null;
            \XLite\Core\Session::getInstance()->ec_payer_id = null;
            \XLite\Core\Session::getInstance()->ec_type = null;

            $cart->unsetPaymentMethod();

            \XLite\Core\TopMessage::getInstance()->addWarning('Express Checkout process stopped.');

        } elseif (!isset($request->token) || $request->token != \XLite\Core\Session::getInstance()->ec_token) {
            \XLite\Core\TopMessage::getInstance()->addError('Wrong token of Express Checkout.');

        } elseif (!isset($request->PayerID)) {
            \XLite\Core\TopMessage::getInstance()->addError('PayerID value was not returned by PayPal.');

        } else {

            // Express Checkout shortcut flow processing

            \XLite\Core\Session::getInstance()->ec_type
                = \XLite\Module\CDev\Paypal\Model\Payment\Processor\ExpressCheckout::EC_TYPE_SHORTCUT;

            \XLite\Core\Session::getInstance()->ec_payer_id = $request->PayerID;
            $paymentMethod = $this->getExpressCheckoutPaymentMethod();

            $buyerData = $paymentMethod->getProcessor()->doGetExpressCheckoutDetails($paymentMethod, $request->token);

            if (empty($buyerData)) {
                \XLite\Core\TopMessage::getInstance()->addError('Your address data was not received from PayPal.');

            } else {
                // Fill the cart with data received from Paypal
                $this->requestData = $this->prepareBuyerData($buyerData);

                $this->updateProfile();

                $this->requestData['billingAddress'] = $this->requestData['shippingAddress'];
                $this->requestData['same_address'] = true;

                $this->updateShippingAddress();

                $this->updateBillingAddress();
            }
        }
    }

    /**
     * Set up ec_type flag to 'mark' value if payment method selected on checkout
     * 
     * @return void
     */
    protected function doActionPayment()
    {
        \XLite\Core\Session::getInstance()->ec_type
            = \XLite\Module\CDev\Paypal\Model\Payment\Processor\ExpressCheckout::EC_TYPE_MARK;

        parent::doActionPayment();
    }

    /**
     * Translate array of data received from Paypal to the array for updating cart
     * 
     * @param array $paypalData Array of customer data received from Paypal
     *  
     * @return array
     */
    protected function prepareBuyerData($paypalData)
    {
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')
            ->findOneByCode($paypalData['SHIPTOCOUNTRY']);

        $state = \XLite\Core\Database::getRepo('XLite\Model\State')
            ->findOneByCountryAndCode($country->getCode(), $paypalData['SHIPTOSTATE']);

        $data = array(
            'shippingAddress' => array(
                'name' => $paypalData['SHIPTONAME'],
                'street' => $paypalData['SHIPTOSTREET'] . (!empty($paypalData['SHIPTOSTREET2']) ? ' ' . $paypalData['SHIPTOSTREET2'] : ''),
                'country' => $country,
                'state' => $state ? $state : $paypalData['SHIPTOSTATE'],
                'city' => $paypalData['SHIPTOCITY'],
                'zipcode' => $paypalData['SHIPTOZIP'],
                'phone' => $paypalData['PHONENUM'] ?: '',
            ),
        );

        if (!\XLite\Core\Auth::getInstance()->isLogged()) {
            $data += array(
                'email' => $paypalData['EMAIL'],
                'create_profile' => false,
            );
        }

        return $data;
    }

    /**
     * Get Express Checkout payment method
     * 
     * @return \XLite\Model\Payment\Method
     */
    protected function getExpressCheckoutPaymentMethod()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findOneBy(array('service_name' => 'ExpressCheckout'));
    }
}

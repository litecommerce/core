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

namespace XLite\Controller\Customer;

/**
 * Checkout 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Checkout extends \XLite\Controller\Customer\Cart
{
    protected $requestData;

    /**
     * Check for order min/max total 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isCheckoutNotAllowed()
    {
        return $this->getCart()->isMinOrderAmountError() || $this->getCart()->isMaxOrderAmountError();
    }

    /**
     * isRegistrationNeeded 
     * (CHECKOUT_MODE_REGISTER step check)
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isRegistrationNeeded()
    {
        return !\XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Check if order total is zero
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isZeroOrderTotal()
    {
        return 0 == $this->getCart()->getTotal() && $this->config->Payments->default_offline_payment;
    }

    /**
     * Check if we are ready to select payment method
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPaymentNeeded()
    {
        return !$this->getCart()->getPaymentMethod() && $this->getCart()->getOpenTotal();
    }

    /**
     * Check if we are ready to select shipping method
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isShippingNeeded()
    {
        return !$this->getCart()->isShippingSelected();
    }

    /**
     * Common method to determine current location 
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Checkout';
    }

    /**
     * Update profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateProfile()
    {
        $form = new \XLite\View\Form\Checkout\UpdateProfile;
        $this->requestData = $form->getRequestData();

        $this->updateProfile();
        $this->updateShippingAddress();
        $this->updateBillingAddress();
    }

    /**
     * Update profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateProfile()
    {
        $login = $this->requestData['email'];

        if (isset($login)) {
            $tmpProfile = new \XLite\Model\Profile;
            $tmpProfile->setProfileId(0);
            $tmpProfile->setLogin($login);

            $profile = $this->requestData['create_profile']
                ? \XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($tmpProfile)
                : null;

            if ($profile) {

                // Profile with same login is exists
                \XLite\Core\Database::getEM()->detach($profile);

                $this->valid = false;

                $label = $this->t(
                    'This email address is used for an existing account. Enter another email address or sign in',
                    array('URL' => $this->getLoginURL())
                );
                \XLite\Core\Event::invalidElement('email', $label);

            } elseif (false !== $this->valid) {

                if (
                    $this->getCart()->getOrigProfile()
                    && $this->getCart()->getOrigProfile()->getOrderId() != $this->getCart()->getOrderId()
                ) {
                    // Original profile is not anonymous
                    $this->getCart()->setOrigProfile(null);
                }

                $profile = $this->getCartProfile();

                $profile->setLogin($login);

                \XLite\Core\Database::getEM()->flush();

                $this->getCart()->setProfile($profile);

                \XLite\Core\Session::getInstance()->order_create_profile = (bool)$this->requestData['create_profile'];
                $this->getCart()->setOrigProfile($profile);

                \XLite\Core\Database::getEM()->flush();
            }
        }
    }

    /**
     * Update shipping address 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateShippingAddress()
    {
        $data = $this->requestData['shippingAddress'];

        if (is_array($data)) {
            $profile = $this->getCartProfile();
            $address = $profile->getShippingAddress();
            $andAsBilling = false;

            if (!$address || $data['save_as_new']) {
                if ($address) {
                    $andAsBilling = $address->getIsBilling();
                    $address->setIsBilling(false);
                    $address->setIsShipping(false);
                }
                $address = new \XLite\Model\Address;
                $address->setProfile($profile);
                $address->setIsShipping(true);
                $address->setIsBilling($andAsBilling);
                $profile->addAddresses($address);
                \XLite\Core\Database::getEM()->persist($address);
            }

            $address->map($this->prepareAddressData($data));

            if (!$profile->getBillingAddress()) {
                // Same address as default behavior
                $address->setIsBilling(true);
            }

            $this->updateCart();

            \XLite\Core\Event::updateCart(array('shippingAddress' => true));
        }
    }

    /**
     * Update profiel billing address 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateBillingAddress()
    {
        $data = $this->requestData['billingAddress'];
        $profile = $this->getCartProfile();

        if ($this->requestData['same_address']) {

            // Shipping and billing are same addresses
            $address = $profile->getBillingAddress();
            if ($address) {

                // Unselect old billing address
                $address->setIsBilling(false);
            }

            $address = $profile->getShippingAddress();
            if ($address) {
    
                // Link shipping and billing address
                $address->setIsBilling(true);

            } else {
                $this->valid = false;
            }

        } elseif (
            isset($this->requestData['same_address'])
            && !$this->requestData['same_address']
        ) {

            // Unlink shipping and billing addresses 
            $address = $profile->getShippingAddress();
            if ($address && $address->getIsBilling()) {
                $address->setIsBilling(false);
            }
        }

        if (!$this->requestData['same_address'] && is_array($data)) {

            // Save separate billing address
            $address = $profile->getBillingAddress();
            $andAsShipping = false;

            if (!$address || $data['save_as_new']) {
                if ($address) {
                    $andAsShipping = $address->getIsShipping();
                    $address->setIsBilling(false);
                    $address->setIsShipping(false);
                }
                $address = new \XLite\Model\Address;
                $address->setProfile($profile);
                $address->setIsBilling(true);
                $address->setIsShipping($andAsShipping);
                $profile->addAddresses($address);
                \XLite\Core\Database::getEM()->persist($address);
            }

            $address->map($this->prepareAddressData($data));

            \XLite\Core\Event::updateCart(
                array(
                    'billingAddress' => array(
                        'same' => $address->getIsShipping(),
                    ),
                )
            );
        }

        $this->updateCart();

    }

    /**
     * Prepare address data 
     * 
     * @param array $data Address data
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareAddressData(array $data)
    {
        // State preprocess
        if (isset($data['state'])) {
            if ($data['state']->getStateId()) {
                $data['custom_state'] = '';
                $data['state_id'] = $data['state']->getStateId();

            } else {
                $data['custom_state'] = $data['state']->getState();
                $data['state_id'] = 0;
            }

            $data['state']->detach();
            unset($data['state']);
        }

        // Country preprocess
        if (isset($data['country'])) {
            $data['country_code'] = $data['country']->getCode();
            $data['country']->detach();
            unset($data['country']);
        }

        unset($data['save_as_new']);

        return $data;
    }

    /**
     * Set payment method
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPayment()
    {
        $this->checkHtaccess();

        $pm = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->find(\XLite\Core\Request::getInstance()->methodId);
        if (!$pm) {
            \XLite\Core\TopMessage::getInstance()->add(
                'No payment method selected',
                \XLite\Core\TopMessage::ERROR
            );
    
        } else {

            $this->getCart()->getProfile()->setLastPaymentId($pm->getMethodId());
            $this->getCart()->setPaymentMethod($pm);
            $this->updateCart();

            if ($this->isPaymentNeeded()) {
                \XLite\Core\TopMessage::getInstance()->add(
                    'The selected payment method is obsolete or invalid. Select another payment method',
                    \XLite\Core\TopMessage::ERROR
                );
            }
        }
    }

    /**
     * Change shipping method
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionShipping()
    {
        // FIXME - is it really needed?
        $this->checkHtaccess();

        if (isset(\XLite\Core\Request::getInstance()->methodId)) {

            $this->getCart()->getProfile()->setLastShippingId(\XLite\Core\Request::getInstance()->methodId);
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->methodId);
            $this->updateCart();

        } else {
            $this->valid = false;
        }
    }

    /**
     * Go to cart view if cart is empty
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($this->getCart()->isEmpty()) {
            $this->setReturnUrl($this->buildURL('cart'));
        }

        parent::handleRequest();
    }

    /**
     * Get page title
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Checkout';
    }


    // TODO - all of the methods below must be revised

    /**
     * Checkout
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionCheckout()
    {
        $itemsBeforeUpdate = $this->getCart()->getItemsFingerprint();
        $this->updateCart();
        $itemsAfterUpdate = $this->getCart()->getItemsFingerprint();

        if (
            $this->get('absence_of_product')
            || $this->getCart()->isEmpty()
            || $itemsAfterUpdate != $itemsBeforeUpdate
        ) {

            // Cart is changed
            $this->set('absence_of_product', true);
            $this->redirect($this->buildURL('cart'));

        } elseif ($this->isPaymentNeeded()) {

            // Payment method is not selected
            $this->redirect($this->buildURL('checkout'));

        } elseif (!\XLite\Core\Request::getInstance()->agree) {

            // Terms and Conditions not signed
            $this->redirect($this->buildURL('checkout'));

        } else {

            if (isset(\XLite\Core\Request::getInstance()->notes)) {
                $this->getCart()->setNotes(\XLite\Core\Request::getInstance()->notes);
            }

            $this->getCart()->processCheckOut();

            // Get first (and only) payment transaction
        
            $transaction = $this->getCart()->getFirstOpenPaymentTransaction();

            $result = null;

            if ($transaction) {
                $result = $transaction->handleCheckoutAction();

            } elseif (!$this->getCart()->isOpen()) {

                $result = \XLite\Model\Payment\Transaction::COMPLETED;

                $status = \XLite\Model\Order::STATUS_PROCESSED;

                foreach ($this->getCart()->getPaymentTransactions() as $t) {
                    if ($t::STATUS_SUCCESS != $t->getStatus()) {
                        $status = \XLite\Model\Order::STATUS_QUEUED;
                        break;
                    }
                }

                $this->getCart()->setStatus($status);
            }

            if (\XLite\Model\Payment\Transaction::PROLONGATION == $result) {
                $this->set('silent', true);
                exit (0);

            } elseif ($this->getCart()->isOpen()) {

                // Order is open - go to Select payment method step

                if ($transaction && $transaction->getNote()) {
                    \XLite\Core\TopMessage::getInstance()->add(
                        $transaction->getNote(),
                        $transaction->isFailed() ? \XLite\Core\TopMessage::ERROR : \XLite\Core\TopMessage::INFO,
                        true
                    );
                }

                $this->setReturnUrl($this->buildURL('checkout'));

            } else {

                $status = $this->getCart()->isPayed()
                    ? \XLite\Model\Order::STATUS_PROCESSED
                    : \XLite\Model\Order::STATUS_QUEUED;
                $this->getCart()->setStatus($status);

                $this->processSucceed();
                $this->setReturnUrl(
                    $this->buildURL(
                        'checkoutSuccess',
                        '',
                        array('order_id' => $this->getCart()->getOrderId())
                    )
                );

            }

            $this->updateCart();
        }
    }

    /**
     * Return from payment gateway
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionReturn()
    {
        // some of gateways can't accept return url on run-time and
        // use the one set in merchant account, so we can't pass
        // 'order_id' in run-time, instead pass the order id parameter name
        $orderId = \XLite\Core\Request::getInstance()->order_id;
        $cart = \XLite\Core\Database::getRepo('XLite\Model\Cart')->find($orderId);

        if ($cart) {
            \XLite\Model\Cart::setObject($cart);
        }

        if (!$cart) {
            \XLite\Core\Session::getInstance()->order_id = null;

            \XLite\Core\TopMessage::addError(
                'Order not found'
            );
            $this->redirect($this->buildURL('cart'));

        } elseif ($cart->isOpen()) {
            \XLite\Core\TopMessage::addInfo(
                'Order is open'
            );
            $this->redirect($this->buildURL('checkout'));

        } else {

            $cart->setStatus(
                $cart->isPayed() ? \XLite\Model\Order::STATUS_PROCESSED : \XLite\Model\Order::STATUS_QUEUED
            );

            $this->processSucceed();

            $this->redirect($this->buildURL('checkoutSuccess', '', array('order_id' => $orderId)));
        }
    }

    /**
     * External call processSucceed() method
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callSuccess()
    {
        return $this->processSucceed();
    }
 
    /**
     * Order placement is success 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processSucceed()
    {
        $isAnonymous = 0 < $this->getCart()->getProfile()->getOrderId();

        if ($isAnonymous && \XLite\Core\Session::getInstance()->order_create_profile) {

            // Create profile based on anonymous order profile
            $this->saveAnonymousProfile();

        } elseif (!$isAnonymous) {

            // Clone profile
            $this->cloneProfile();
        }

        unset(\XLite\Core\Session::getInstance()->order_create_profile);

        $this->getCart()->processSucceed();

        \XLite\Core\Session::getInstance()->last_order_id = $this->getCart()->getOrderId();
        unset(\XLite\Core\Session::getInstance()->order_id);

        \XLite\Core\Database::getEM()->persist($this->getCart());
        \XLite\Core\Database::getEM()->flush();

        // anonymous checkout: logoff
        if ($isAnonymous && \XLite\Core\Auth::getInstance()->getProfile()) {
            \XLite\Core\Auth::getInstance()->logoff();
        }
    }

    /**
     * Check - controller must work in secure zone or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSecure()
    {
        return $this->config->Security->customer_security;
    }

    /**
     * Get login URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLoginURL()
    {
        return $this->buildURL('login');
    }

    /**
     * Save anonymous profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveAnonymousProfile()
    {
        $profile = $this->getCart()->getProfile()->cloneObject();
        $profile->setOrderId(0);

        \XLite\Core\Database::getEM()->persist($profile);
        \XLite\Core\Database::getEM()->flush();

        $this->getCart()->setOrigProfile($profile);
    }

    /**
     * Clone profile and move profile to original profile
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function cloneProfile()
    {
        $profile = $this->getCart()->getProfile()->cloneObject();
        $profile->setOrderId($this->getCart()->getOrderId());
        $this->getCart()->setProfile($profile);
    }

    /**
     * Check - current profile is aninymous or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAnonymous()
    {
        return !$this->getCart()->getProfile()
            || $this->getCart()->getOrigProfile()->getOrderId() == $this->getCart()->getOrderId();
    }

}


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

namespace XLite\Controller\Customer;

/**
 * Checkout
 *
 */
class Checkout extends \XLite\Controller\Customer\Cart
{
    /**
     * Request data
     *
     * @var mixed
     */
    protected $requestData;

    /**
     * Payment widget data
     *
     * @var array
     */
    protected $paymentWidgetData = array();

    /**
     * Go to cart view if cart is empty
     *
     * @return void
     */
    public function handleRequest()
    {
        if (!$this->getCart()->checkCart()) {

            $this->setHardRedirect();

            $this->setReturnURL($this->buildURL('cart'));
        }

        parent::handleRequest();
    }

    /**
     * Get page title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Checkout';
    }

    /**
     * External call processSucceed() method
     * TODO: to revise
     *
     * @return mixed
     */
    public function callSuccess()
    {
        return $this->processSucceed();
    }

    /**
     * Check - controller must work in secure zone or not
     * TODO: to revise
     *
     * @return boolean
     */
    public function isSecure()
    {
        return \XLite\Core\Config::getInstance()->Security->customer_security;
    }

    /**
     * Get login URL
     *
     * @return string
     */
    public function getLoginURL()
    {
        return $this->buildURL('login');
    }

    /**
     * Check - current profile is aninymous or not
     *
     * @return boolean
     */
    public function isAnonymous()
    {
        return !$this->getCart()->getProfile() || $this->getCart()->getProfile()->getOrder();
    }

    /**
     * Get payment widget data
     *
     * @return array
     */
    public function getPaymentWidgetData()
    {
        return $this->paymentWidgetData;
    }


    /**
     * Checkout
     * TODO: to revise
     *
     * @return void
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

        } elseif (!$this->checkCheckoutAction()) {

            // Check access
            $this->redirect($this->buildURL('checkout'));

        } else {

            $data = is_array(\XLite\Core\Request::getInstance()->payment)
                ? \XLite\Core\Request::getInstance()->payment
                : array();

            $errors = array();

            $firstOpenTransaction = $this->getCart()->getFirstOpenPaymentTransaction();

            if ($firstOpenTransaction) {
                $errors = $firstOpenTransaction
                    ->getPaymentMethod()
                    ->getProcessor()
                    ->getInputErrors($data);
            }

            if ($errors) {

                foreach ($errors as $error) {

                    \XLite\Core\TopMessage::addError($error);
                }

                $this->redirect($this->buildURL('checkout'));

            } else {
                // Register 'Place order' event in the order history 
                \XLite\Core\OrderHistory::getInstance()->registerPlaceOrder($this->getCart()->getOrderId());

                // Make order payment step
                $this->doPayment();
            }
        }
    }

    /**
     * Do payment
     *
     * :TODO: to revise
     * :FIXME: decompose
     *
     * @return void
     */
    protected function doPayment()
    {
        $cart = $this->getCart();

        if (isset(\XLite\Core\Request::getInstance()->notes)) {
            $cart->setNotes(\XLite\Core\Request::getInstance()->notes);
        }

        if (\XLite\Model\Order::STATUS_TEMPORARY == $cart->getStatus()) {
            $cart->setDate(time());

            $profile = \XLite\Core\Auth::getInstance()->getProfile();
            if ($profile->getOrder()) {
                // anonymous checkout:
                // use the current profile as order profile
                $cart->setProfile($profile);
            }
        }

        // Get first (and only) payment transaction

        $transaction = $cart->getFirstOpenPaymentTransaction();

        $result = null;

        // Default order status on successful payment
        $paymentStatus = \XLite\Model\Order::STATUS_PROCESSED;

        if ($transaction) {

            $result = $transaction->handleCheckoutAction();

        } elseif (!$cart->isOpen()) {

            $result = \XLite\Model\Payment\Transaction::COMPLETED;

            $status = \XLite\Model\Order::STATUS_PROCESSED;

            $hasIncompletePayment = (0 < $cart->getOpenTotal());
            $hasAuthorizedPayment = false;

            foreach ($cart->getPaymentTransactions() as $t) {
                $hasAuthorizedPayment = $hasAuthorizedPayment || $t->isAuthorized();
            }

            if ($hasIncompletePayment) {
                $status = \XLite\Model\Order::STATUS_QUEUED;

            } elseif ($hasAuthorizedPayment) {
                $status = \XLite\Model\Order::STATUS_AUTHORIZED;
                $paymentStatus = \XLite\Model\Order::STATUS_AUTHORIZED;
            }

            $cart->setStatus($status);
        }

        if (\XLite\Model\Payment\Transaction::PROLONGATION == $result) {

            $this->set('silent', true);

            \XLite\Core\TopMessage::addError(
                'You have an unpaid order #{{ORDER}}',
                array(
                    'ORDER' => $cart->getOrderId(),
                )
            );

            $cart->setStatus(\XLite\Model\Order::STATUS_INPROGRESS);

            $this->processSucceed();

            exit (0);

        } elseif (\XLite\Model\Payment\Transaction::SILENT == $result) {

            $this->paymentWidgetData = $transaction->getPaymentMethod()->getProcessor()->getPaymentWidgetData();
            $this->set('silent', true);

        } elseif (\XLite\Model\Payment\Transaction::SEPARATE == $result) {

            $this->setReturnURL($this->buildURL('checkoutPayment'));

        } elseif ($cart->isOpen()) {

            // Order is open - go to Select payment method step

            if ($transaction && $transaction->getNote()) {

                \XLite\Core\TopMessage::getInstance()->add(
                    $transaction->getNote(),
                    array(),
                    null,
                    $transaction->isFailed() ? \XLite\Core\TopMessage::ERROR : \XLite\Core\TopMessage::INFO,
                    true
                );
            }

            $this->setReturnURL($this->buildURL('checkout'));

        } else {

            $status = $cart->isPayed()
                ? $paymentStatus
                : \XLite\Model\Order::STATUS_QUEUED;


            if (!empty($transaction) && $transaction->isFailed()) {
                $status = \XLite\Model\Order::STATUS_FAILED;
            }

            $cart->setStatus($status);

            $this->processSucceed();

            \XLite\Core\TopMessage::getInstance()->clearTopMessages();

            $this->setReturnURL(
                $this->buildURL(
                    \XLite\Model\Order::STATUS_FAILED == $status ? 'checkoutFailed' : 'checkoutSuccess',
                    '',
                    array('order_id' => $cart->getOrderId())
                )
            );

        }

        // Commented out in connection with E:0041438
        //$this->updateCart();
    }

    /**
     * Return from payment gateway
     *
     * :TODO: to revise
     * :FIXME: decompose
     *
     * @return void
     */
    protected function doActionReturn()
    {
        // some of gateways can't accept return url on run-time and
        // use the one set in merchant account, so we can't pass
        // 'order_id' in run-time, instead pass the order id parameter name
        $orderId = \XLite\Core\Request::getInstance()->order_id;
        $cart = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($orderId);

        if ($cart) {

            \XLite\Model\Cart::setObject($cart);
        }

        if (!$cart) {

            \XLite\Core\Session::getInstance()->order_id = null;

            \XLite\Core\TopMessage::addError(
                'Order not found'
            );

            $this->setReturnURL($this->buildURL('cart'));

        } elseif (0 < $cart->getOpenTotal() && !in_array($cart->getStatus(), array(\XLite\Model\Order::STATUS_FAILED, \XLite\Model\Order::STATUS_DECLINED))) {

            \XLite\Core\TopMessage::addWarning(
                'Payment was not finished',
                array(
                    'url' => $this->buildURL(
                        'cart',
                        'add_order',
                        array('order_id' => $cart->getOrderId())
                    )
                )
            );

            $this->setReturnURL(
                $this->buildURL(
                    \XLite\Core\Auth::getInstance()->isLogged() ? 'order_list' : ''
                )
            );

        } else {

            if ($cart->isPayed()) {

                $status = \XLite\Model\Order::STATUS_PROCESSED;

                $hasIncompletePayment = (0 < $cart->getOpenTotal());
                $hasAuthorizedPayment = false;

                foreach ($cart->getPaymentTransactions() as $t) {
                    $hasAuthorizedPayment = $hasAuthorizedPayment || $t->isAuthorized();
                }

                if ($hasIncompletePayment) {
                    $status = \XLite\Model\Order::STATUS_QUEUED;

                } elseif ($hasAuthorizedPayment) {
                    $status = \XLite\Model\Order::STATUS_AUTHORIZED;
                }

            } else {

                $status = \XLite\Model\Order::STATUS_QUEUED;

                $transactions = $cart->getPaymentTransactions();

                if (!empty($transactions)) {
                    $lastTransaction = $transactions[count($transactions) - 1];
                    if ($lastTransaction->isFailed()) {
                        $status = \XLite\Model\Order::STATUS_FAILED;
                    }
                }
            }

            $cart->setStatus($status);

            $this->processSucceed();

            \XLite\Core\TopMessage::getInstance()->clearTopMessages();

            $this->setReturnURL(
                $this->buildURL(
                    \XLite\Model\Order::STATUS_FAILED == $status ? 'checkoutFailed' : 'checkoutSuccess',
                    '',
                    array('order_id' => $orderId)
                )
            );
        }
    }

    /**
     * Order placement is success
     *
     * :TODO: to revise
     * :FIXME: decompose
     *
     * @return void
     */
    protected function processSucceed()
    {
        $isAnonymous = $this->isAnonymous();

        if ($isAnonymous) {

            if (\XLite\Core\Session::getInstance()->order_create_profile) {

                // Create profile based on anonymous order profile
                $this->saveAnonymousProfile();
                $this->loginAnonymousProfile();

                $isAnonymous = false;
            }

        } else {

            // Clone profile
            $this->cloneProfile();
        }

        unset(\XLite\Core\Session::getInstance()->order_create_profile);

        $this->getCart()->processSucceed();

        // Save order id in session and forget cart id from session
        \XLite\Core\Session::getInstance()->last_order_id = $this->getCart()->getOrderId();
        unset(\XLite\Core\Session::getInstance()->order_id);

        // Commented out in connection with E:0041438
        //$this->updateCart();

        // anonymous checkout: logoff
        if ($isAnonymous && \XLite\Core\Auth::getInstance()->getProfile()) {

            \XLite\Core\Auth::getInstance()->logoff();
        }
    }

    /**
     * Save anonymous profile
     *
     * @return void
     */
    protected function saveAnonymousProfile()
    {
        // Create cloned profile
        $profile = $this->getCart()->getProfile()->cloneEntity();

        // Generate password
        $pass = \XLite\Core\Database::getRepo('XLite\Model\Profile')->generatePassword();
        $profile->setPassword(md5($pass));

        // Set cloned profile as original profile
        $this->getCart()->setOrigProfile($profile);

        // Send notifications
        $this->sendCreateProfileNotifications($pass);
    }

    /**
     * Login anonymous profile
     *
     * @return void
     */
    protected function loginAnonymousProfile()
    {
        \XLite\Core\Auth::getInstance()->loginProfile($this->getCart()->getOrigProfile());
    }

    /**
     * Send create profile notifications
     *
     * @param string $password Password
     *
     * @return void
     */
    protected function sendCreateProfileNotifications($password)
    {
        $profile = $this->getCart()->getOrigProfile();

        // Send notification to the user
        \XLite\Core\Mailer::sendProfileCreatedUserNotification($profile, $password);

        // Send notification to the users department
        \XLite\Core\Mailer::sendProfileCreatedAdminNotification($profile);
    }

    /**
     * Clone profile and move profile to original profile
     *
     * @return void
     */
    protected function cloneProfile()
    {
        $origProfile = $this->getCart()->getProfile();
        $profile = $origProfile->cloneEntity();

        // Assign cloned order's profile
        $this->getCart()->setProfile($profile);
        $profile->setOrder($this->getCart());

        // Save old profile as original profile
        $this->getCart()->setOrigProfile($origProfile);
        $origProfile->setOrder(null);
    }

    /**
     * isRegistrationNeeded
     * (CHECKOUT_MODE_REGISTER step check)
     *
     * @return boolean
     */
    protected function isRegistrationNeeded()
    {
        return !\XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Check if order total is zero
     *
     * @return boolean
     */
    protected function isZeroOrderTotal()
    {
        return 0 == $this->getCart()->getTotal() && \XLite\Core\Config::getInstance()->Payments->default_offline_payment;
    }

    /**
     * Check if we are ready to select payment method
     *
     * @return boolean
     */
    protected function isPaymentNeeded()
    {
        return !$this->getCart()->getPaymentMethod() && $this->getCart()->getOpenTotal();
    }

    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Checkout';
    }

    /**
     * Check amount for all cart items
     *
     * @return void
     */
    protected function checkItemsAmount()
    {
        // Do not call parent: it's only needed to check amounts in cart, not on checkout
    }

    /**
     * Update profile
     *
     * @return void
     */
    protected function doActionUpdateProfile()
    {
        $form = new \XLite\View\Form\Checkout\UpdateProfile();

        $this->requestData = $form->getRequestData();

        $this->updateProfile();

        $this->updateShippingAddress();

        $this->updateBillingAddress();
    }

    /**
     * Update profile
     *
     * @return void
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

                $label = static::t(
                    'This email address is used for an existing account. Enter another email address or sign in',
                    array('URL' => $this->getLoginURL())
                );

                \XLite\Core\Event::invalidElement('email', $label);

            } elseif (false !== $this->valid) {

                $profile = $this->getCartProfile();

                $profile->setLogin($login);

                $this->getCart()->setProfile($profile);

                \XLite\Core\Session::getInstance()->order_create_profile = (bool)$this->requestData['create_profile'];

                $this->getCart()->setOrigProfile($profile);

                $this->updateCart();
            }
        }
    }

    /**
     * Update shipping address
     *
     * @return void
     */
    protected function updateShippingAddress()
    {
        $data = $this->requestData['shippingAddress'];

        $profile = $this->getCartProfile();

        $address = $profile->getShippingAddress();
        if ($address) {
            \XLite\Core\Database::getEM()->refresh($address);
        }

        if (is_array($data)) {

            $noAddress = !isset($address);

            $andAsBilling = false;

            if ($noAddress || $data['save_as_new']) {

                if (!$noAddress) {

                    $andAsBilling = $address->getIsBilling();
                    $address->setIsBilling(false);
                    $address->setIsShipping(false);
                }

                $address = new \XLite\Model\Address;

                $address->setProfile($profile);
                $address->setIsShipping(true);
                $address->setIsBilling($andAsBilling);

                if ($noAddress || !(bool)\XLite\Core\Request::getInstance()->only_calculate) {

                    $profile->addAddresses($address);

                    \XLite\Core\Database::getEM()->persist($address);
                }
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

            if ($address) {
                \XLite\Core\Database::getEM()->refresh($address);
            }

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

                if (!(bool)\XLite\Core\Request::getInstance()->only_calculate) {
                    $profile->addAddresses($address);

                    \XLite\Core\Database::getEM()->persist($address);
                }
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
     */
    protected function prepareAddressData(array $data)
    {
        unset($data['save_as_new']);

        return $data;
    }

    /**
     * Set payment method
     *
     * @return void
     */
    protected function doActionPayment()
    {
        $pm = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->find(\XLite\Core\Request::getInstance()->methodId);

        if (!$pm) {

            \XLite\Core\TopMessage::addError(
                'No payment method selected'
            );

        } else {

            if ($this->getCart()->getProfile()) {
                $this->getCart()->getProfile()->setLastPaymentId($pm->getMethodId());
            }

            $this->getCart()->setPaymentMethod($pm);

            $this->updateCart();

            if ($this->isPaymentNeeded()) {

                \XLite\Core\TopMessage::addError(
                    'The selected payment method is obsolete or invalid. Select another payment method'
                );
            }
        }
    }

    /**
     * Change shipping method
     *
     * @return void
     */
    protected function doActionShipping()
    {
        if (isset(\XLite\Core\Request::getInstance()->methodId)) {

            $this->getCart()->getProfile()->setLastShippingId(\XLite\Core\Request::getInstance()->methodId);
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->methodId);
            $this->updateCart();

        } else {

            $this->valid = false;
        }
    }

    /**
     * Check checkout action accessibility
     *
     * @return boolean
     */
    protected function checkCheckoutAction()
    {
        $result = true;

        $steps = new \XLite\View\Checkout\Steps();
        foreach (array_slice($steps->getSteps(), 0, -1) as $step) {
            if (!$step->isCompleted()) {
                $result = false;
                break;
            }
        }

        return $result && $this->checkReviewStep();
    }

    /**
     * Check review step - complete or not
     *
     * @return void
     */
    protected function checkReviewStep()
    {
        return \XLite\Core\Request::getInstance()->agree
            && $this->getCart()->getProfile()->getLogin();
    }
}

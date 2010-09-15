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
    /**
     * Avaliable checkout steps (modes)
     */
   
    const CHECKOUT_MODE_ERROR          = 'error';
    const CHECKOUT_MODE_NOT_ALLOWED    = 'notAllowed';
    const CHECKOUT_MODE_REGISTER       = 'register';
    const CHECKOUT_MODE_ZERO_TOTAL     = 'zeroTotal';
    const CHECKOUT_MODE_NO_SHIPPING    = 'noShipping';
    const CHECKOUT_MODE_NO_PAYMENT     = 'noPayment';
    const CHECKOUT_MODE_PAYMENT_METHOD = 'paymentMethod';
    const CHECKOUT_MODE_DETAILS        = 'details';

    /**
     * Indexes in step description 
     */
    
    const STEP_WIDGET_CLASS  = 'widgetClass';
    const STEP_IS_NOT_PASSED = 'isNotPassed';


    /**
     * List of all checkout steps 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $checkoutSteps = null;


    /**
     * Return class name of the register form 
     * 
     * @return string|null
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\Checkout';
    }

    /**
     * Check if user profile is valid
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkProfile()
    {
        // TODO - check this
        //return \XLite\Model\CachingFactory::getObject(__METHOD__, $this->getModelFormClass())->isValid();

        return $this->getModelForm()->isValid();
    }

    /**
     * Check if an error occured during checkout
     * (CHECKOUT_MODE_ERROR step check)
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isCheckoutError()
    {
        return 'error' == \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check for order min/max total 
     * (CHECKOUT_MODE_NOT_ALLOWED step check)
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
        return !\XLite\Model\Auth::getInstance()->isLogged() || !$this->checkProfile();
    }

    /**
     * Check if order total is zero
     * (CHECKOUT_MODE_ZERO_TOTAL (pseudo)step check) 
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
     * Check if there are no shipping methods
     * (CHECKOUT_MODE_NO_SHIPPING step check)
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isNoShipping()
    {
        return $this->getCart()->isShipped() && !$this->getCart()->isShippingSelected();
    }

    /**
     * Check if there are no payment methods
     * (CHECKOUT_MODE_NO_PAYMENT step check)
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isNoPayment()
    {
        return !$this->getPaymentMethods();
    }

    /**
     * Check if we are ready to select payment method
     * (CHECKOUT_MODE_PAYMENT_METHOD step check) 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPaymentNeeded()
    {
        return !$this->getCart()->getPaymentMethod();
    }

    /**
     * Check if we are ready to select shipping method
     * (CHECKOUT_MODE_PAYMENT_METHOD step check) 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isShippingNeeded()
    {
        $cart = $this->getCart();

        return !$cart->isShippingSelected();
    }

    /**
     * Check if we are on the last step
     * (CHECKOUT_MODE_DETAILS step check) 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isFinalStep()
    {
        return true;
    }

    /**
     * Checkout steps definition. Order is important! 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCheckoutStepDescriptions()
    {
        return array(
            self::CHECKOUT_MODE_ERROR => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Pseudo\Error',
                self::STEP_IS_NOT_PASSED => $this->isCheckoutError(),
            ),
            self::CHECKOUT_MODE_NOT_ALLOWED => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Pseudo\NotAllowed',
                self::STEP_IS_NOT_PASSED => $this->isCheckoutNotAllowed(),
            ),
            self::CHECKOUT_MODE_REGISTER => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Regular\Register',
                self::STEP_IS_NOT_PASSED => $this->isRegistrationNeeded(),
            ),
            self::CHECKOUT_MODE_ZERO_TOTAL => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Pseudo\ZeroTotal',
                self::STEP_IS_NOT_PASSED => $this->isZeroOrderTotal(),
            ),
            self::CHECKOUT_MODE_NO_SHIPPING => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Pseudo\NoShipping',
                self::STEP_IS_NOT_PASSED => $this->isNoShipping(),
            ),
            self::CHECKOUT_MODE_NO_PAYMENT => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Pseudo\NoPayment',
                self::STEP_IS_NOT_PASSED => $this->isNoPayment(),
            ),
            self::CHECKOUT_MODE_PAYMENT_METHOD => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Regular\PaymentMethod',
                self::STEP_IS_NOT_PASSED => $this->isPaymentNeeded() || $this->isShippingNeeded(),
            ),
            self::CHECKOUT_MODE_DETAILS => array(
                self::STEP_WIDGET_CLASS  => '\XLite\View\CheckoutStep\Regular\Details',
                self::STEP_IS_NOT_PASSED => $this->isFinalStep(),
            ),
        );
    }

    /**
     * Define checkout steps by schema
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineCheckoutSteps()
    {
        foreach ($this->getCheckoutStepDescriptions() as $mode => $data) {
            $this->checkoutSteps->add(
                new \XLite\Model\ListNode\CheckoutStep(
                    $mode,  
                    $data[self::STEP_WIDGET_CLASS],
                    !$data[self::STEP_IS_NOT_PASSED]
                )
            );
        }

        // Use the "$this->checkoutSteps->insert(Before|After)" methods
        // to add new checkout steps
    }

    /**
     * Return checkout steps list
     * 
     * @return \XLite\Model\Collection\CheckoutSteps
     * @access protected
     * @since  3.0.0
     */
    protected function getCheckoutSteps()
    {
        if (!isset($this->checkoutSteps)) {
            $this->checkoutSteps = new \XLite\Model\Collection\CheckoutSteps();
            $this->defineCheckoutSteps();
        }

        return $this->checkoutSteps;
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
     * Return current order 
     * 
     * @return \XLite\Model\Order
     * @access protected
     * @since  3.0.0
     */
    protected function getOrder()
    {
        return \XLite\Model\CachingFactory::getObject(
            __METHOD__,
            '\XLite\Model\Order',
            \XLite\Core\Request::getInstance()->order_id
        );
    }

    /**
     * Return list of available payment methods
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getPaymentMethods()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findAllActive();
    }

    /**
     * Set error/info top message 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setStepTopMessage()
    {
        if (!$this->isActionError()) {
            $data = $this->getActualStep()->getTopMessage();

            if (isset($data)) {
                \XLite\Core\TopMessage::getInstance()->add(
                    $data[\XLite\Core\TopMessage::FIELD_TEXT],
                    $data[\XLite\Core\TopMessage::FIELD_TYPE]
                );
            }
        }
    }

    /**
     * Perform some actions before redirect 
     * 
     * @param mixed $action performed action
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocess($action)
    {
        parent::actionPostprocess($action);

        $this->setStepTopMessage();
    }

    /**
     * doActionModifyProfile 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionModifyProfile()
    {
        return $this->getModelForm()->performAction('modify');
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
        // FIXME - is it really needed?
        $this->checkHtaccess();

        $pm = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(\XLite\Core\Request::getInstance()->payment_id);
        if (!$pm) {
            \XLite\Core\TopMessage::getInstance()->add(
                'No payment method selected',
                \XLite\Core\TopMessage::ERROR
            );
    
        } else {

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

        if (isset(\XLite\Core\Request::getInstance()->shipping)) {
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->shipping);
            $this->updateCart();
        }
    }

    /**
     * Return current checkout step
     *
     * NOTE: function is public since it's required for the \XLite\View\Checkout widget
     * 
     * @return \XLite\Model\ListNode\CheckoutStep
     * @access public
     * @since  3.0.0
     */
    public function getCurrentStep()
    {
        return $this->getCheckoutSteps()->getCurrentStep();
    }

    /**
     * Return actual heckout step
     *
     * NOTE: function is public since it can be useful for the \XLite\View\Checkout widget
     *
     * @return \XLite\Model\ListNode\CheckoutStep
     * @access public
     * @since  3.0.0
     */
    public function getActualStep()
    {
        return $this->getCheckoutSteps()->getActualStep();
    }

    /**
     * Initialize controller 
     * FIXME - simplify
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        $step = $this->getCurrentStep();

        if ($this->getCheckoutSteps()->isCorrectedStep()) {

            $this->actionPostprocess(null);
            $url = isset($step)
                ? $this->buildURL('checkout', '', array('mode' => $step->getMode()))
                : $this->buildURL('cart');

            $this->setReturnUrl($url);

        } else {

            if (!isset(\XLite\Core\Request::getInstance()->mode)) {
                \XLite\Core\Request::getInstance()->mode = $step->getMode();
            }

            switch (\XLite\Core\Request::getInstance()->mode) {

                case self::CHECKOUT_MODE_ZERO_TOTAL:
                    \XLite\Core\Request::getInstance()->payment_id = $this->config->Payments->default_offline_payment;
                    $this->doActionPayment();
                    $this->getCart()->processCheckOut();
                    $this->doActionCheckout();

                    break;

                default:
            }
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

        } elseif (is_null($this->getCart()->getPaymentMethod())) {

            // Payment method is not selected
            $this->redirect(
                $this->buildURL(
                    'cart',
                    '',
                    array('mode' => 'paymentMethod')
                )
            );

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

                foreach ($this->getCart()->getTransactions() as $t) {
                    if ($t::STATUS_SUCCESS != $t->getStatus()) {
                        $status = \XLite\Model\Order::STATUS_QUEUED;
                        break;
                    }
                }

                $this->getCart()->setStatus($status);
            }

            if (\XLite\Model\Payment\Transaction::PROLONGATION == $result) {
                $this->set('silent', true);

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

                $this->getCart()->setStatus(
                    $this->getCart()->isPayed() ? \XLite\Model\Order::STATUS_PROCESSED : \XLite\Model\Order::STATUS_QUEUED
                );

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

    public function action_return()
    {
        // some of gateways can't accept return url on run-time and
        // use the one set in merchant account, so we can't pass
        // 'order_id' in run-time, instead pass the order id parameter name
        $request = \XLite\Core\Request::getInstance();
        $orderId = isset($request->order_id_name) ? $request->order_id_name : $request->order_id;

        if ($this->isCartProcessed()) {
            $this->processSucceed();
            $this->returnUrl = $this->buildURL('checkoutSuccess', '', array('order_id' => $orderId));

        } else {
            $this->returnUrl = $this->buildURL('checkout', '', array('mode' => 'error', 'order_id' => $orderId));
        }
    }




    // mode ::= null | register | notAllowed | noShipping | paymentMethod | details | success | error    
    /*public $params = array('target');
    public $mode = null;*/



    function _initCCInfo()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "checkout") {
            if (isset($_REQUEST['cc_info']) && is_array($_REQUEST['cc_info'])) {
                if (isset($_REQUEST['cc_info_cc_date_Month']) && isset($_REQUEST['cc_info_cc_date_Year'])) {
                    $_REQUEST['cc_info']["cc_date"] = sprintf("%02d%s", intval($_REQUEST['cc_info_cc_date_Month']), substr($_REQUEST['cc_info_cc_date_Year'],2));
                    unset($_REQUEST['cc_info_cc_date_Month']);
                    unset($_REQUEST['cc_info_cc_date_Year']);
                    if (isset($_POST['cc_info']) && is_array($_POST['cc_info'])) {
                        $_POST['cc_info']["cc_date"] = $_REQUEST['cc_info']["cc_date"];
                    }
                }
                if (isset($_REQUEST['cc_info_cc_start_date_Month']) && isset($_REQUEST['cc_info_cc_start_date_Year'])) {
                    $_REQUEST['cc_info']["cc_start_date"] = sprintf("%02d%s", intval($_REQUEST['cc_info_cc_start_date_Month']), substr($_REQUEST['cc_info_cc_start_date_Year'],2));
                    unset($_REQUEST['cc_info_cc_start_date_Month']);
                    unset($_REQUEST['cc_info_cc_start_date_Year']);
                    if (isset($_POST['cc_info']) && is_array($_POST['cc_info'])) {
                        $_POST['cc_info']["cc_start_date"] = $_REQUEST['cc_info']["cc_start_date"];
                    }
                }

            }
        }
    }

    function _initCHInfo()
    {
        if (isset($_REQUEST['ch_info'])) {
            foreach ($_REQUEST['ch_info'] as $k => $v) {
                $this->getCart()->setDetail($k, $v);
            }
        }
    }

    /**
    * Returns return URL for checkout/login
    */
    function getBackUrl()
    {
        return $this->get('url');
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
        $this->getCart()->processSucceed();

        \XLite\Model\Session::getInstance()->set('last_order_id', $this->getCart()->getOrderId());
        \XLite\Model\Session::getInstance()->set('order_id', null);

        \XLite\Core\Database::getEM()->persist($this->getCart());
        \XLite\Core\Database::getEM()->flush();

        // anonymous checkout: logoff
        if ($this->auth->getProfile() && $this->auth->getProfile()->get('order_id')) {
            $this->auth->logoff();
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
}


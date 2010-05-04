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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Checkout
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Controller_Customer_Checkout extends XLite_Controller_Customer_Cart
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
        return 'XLite_View_Model_Profile_Checkout';
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
        return XLite_Model_CachingFactory::getObject(__METHOD__, $this->getModelFormClass())->isValid();
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
        return 'error' == XLite_Core_Request::getInstance()->mode;
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
        return !XLite_Model_Auth::getInstance()->isLogged() || !$this->checkProfile();
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
        return 0 == $this->getCart()->get('total') && $this->config->Payments->default_offline_payment;
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
        return !$this->getCart()->isShippingAvailable() && $this->getCart()->isShipped();
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
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Pseudo_Error',
                self::STEP_IS_NOT_PASSED => $this->isCheckoutError(),
            ),
            self::CHECKOUT_MODE_NOT_ALLOWED => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Pseudo_NotAllowed',
                self::STEP_IS_NOT_PASSED => $this->isCheckoutNotAllowed(),
            ),
            self::CHECKOUT_MODE_REGISTER => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Regular_Register',
                self::STEP_IS_NOT_PASSED => $this->isRegistrationNeeded(),
            ),
            self::CHECKOUT_MODE_ZERO_TOTAL => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Pseudo_ZeroTotal',
                self::STEP_IS_NOT_PASSED => $this->isZeroOrderTotal(),
            ),
            self::CHECKOUT_MODE_NO_SHIPPING => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Pseudo_NoShipping',
                self::STEP_IS_NOT_PASSED => $this->isNoShipping(),
            ),
            self::CHECKOUT_MODE_NO_PAYMENT => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Pseudo_NoPayment',
                self::STEP_IS_NOT_PASSED => $this->isNoPayment(),
            ),
            self::CHECKOUT_MODE_PAYMENT_METHOD => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Regular_PaymentMethod',
                self::STEP_IS_NOT_PASSED => $this->isPaymentNeeded(),
            ),
            self::CHECKOUT_MODE_DETAILS => array(
                self::STEP_WIDGET_CLASS  => 'XLite_View_CheckoutStep_Regular_Details',
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
                new XLite_Model_ListNode_CheckoutStep($mode, $data[self::STEP_WIDGET_CLASS], !$data[self::STEP_IS_NOT_PASSED])
            );
        }

        // Use the "$this->checkoutSteps->insert(Before|After)" methods
        // to add new checkout steps
    }

    /**
     * Return checkout steps list
     * 
     * @return XLite_Model_List_CheckoutSteps
     * @access protected
     * @since  3.0.0
     */
    protected function getCheckoutSteps()
    {
        if (!isset($this->checkoutSteps)) {
            $this->checkoutSteps = new XLite_Model_List_CheckoutSteps();
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
     * @return XLite_Model_Order
     * @access protected
     * @since  3.0.0
     */
    protected function getOrder()
    {
        return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Order', XLite_Core_Request::getInstance()->order_id);
    }

    /**
     * Return list of available payment methods
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getPaymentMethods()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(__METHOD__, 'XLite_Model_PaymentMethod', 'getActiveMethods');
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
                XLite_Core_TopMessage::getInstance()->add(
                    $data[XLite_Core_TopMessage::FIELD_TEXT],
                    $data[XLite_Core_TopMessage::FIELD_TYPE]
                );
            }
        }
    }

    /**
     * Perform some actions before redirect
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocess()
    {
        parent::actionPostprocess();

        $this->setStepTopMessage();
    }


    /**
     * Register user during checkout
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionRegister()
    {
        $this->getModelForm()->performAction('modify');
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

        $pm = new XLite_Model_PaymentMethod(XLite_Core_Request::getInstance()->payment_id);
        $this->getCart()->set('paymentMethod', $pm);
        $this->updateCart();

        // FIXME - must be corrected according to the checkout/steps/failure.tpl template
        if ($this->isPaymentNeeded()) {
            $this->params[] = 'error';
            $this->set('error', 'pmSelect');
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

        if (isset(XLite_Core_Request::getInstance()->shipping)) {
            $this->getCart()->set('shipping_id', XLite_Core_Request::getInstance()->shipping);
            $this->updateCart();
        }
    }


    /**
     * Return current checkout step
     *
     * NOTE: function is public since it's required for the XLite_View_Checkout widget
     * 
     * @return XLite_Model_ListNode_CheckoutStep
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
     * NOTE: function is public since it can be useful for the XLite_View_Checkout widget
     *
     * @return XLite_Model_ListNode_CheckoutStep
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

            $this->actionPostprocess();
            $this->setReturnUrl(
                isset($step) 
                    ? $this->buildURL('checkout', '', array(XLite_View_Abstract::PARAM_MODE => $step->getMode()))
                    : $this->buildURL('cart')
            );
        } else {

            if (!isset(XLite_Core_Request::getInstance()->mode)) {
                XLite_Core_Request::getInstance()->mode = $step->getMode();
            }

            switch (XLite_Core_Request::getInstance()->mode) {

                case self::CHECKOUT_MODE_ZERO_TOTAL:
                    XLite_Core_Request::getInstance()->payment_id = $this->config->Payments->default_offline_payment;
                    $this->doActionPayment();
                    $this->getCart()->checkout();
                    $this->doActionCheckout();
                    break;

                default:
                    // ...
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

        if ($this->get("absence_of_product") || $this->getCart()->isEmpty() || $itemsAfterUpdate != $itemsBeforeUpdate) {
            $this->set("absence_of_product", true);
            $this->redirect($this->buildURL('cart'));
            return;
        }

        $pm = $this->getCart()->get("paymentMethod");
        if (!is_null($pm)) {
            $notes = isset(XLite_Core_Request::getInstance()->notes)
                ? XLite_Core_Request::getInstance()->notes
                : '';
            $this->getCart()->set('notes', $notes);

            $this->getCart()->checkout();

            switch ($pm->handleRequest($this->getCart())) {

                case XLite_Model_PaymentMethod::PAYMENT_SILENT:
                    // don't call output()
                    $this->set("silent", true);
                    break;

                case XLite_Model_PaymentMethod::PAYMENT_SUCCESS:
                    $this->success();
                    $this->set(
                        'returnUrl',
                        $this->buildURL(
                            'checkoutSuccess',
                            '',
                            array('order_id' => $this->getCart()->get('order_id'))
                        )
                    );
                    break;

                case XLite_Model_PaymentMethod::PAYMENT_FAILURE:
                    $this->set(
                        'returnUrl',
                        $this->buildURL(
                            'checkout',
                            '',
                            array('mode' => 'error', 'order_id' => $this->getCart()->get('order_id'))
                        )
                    );
                    break;
            }
        }
    }

    public function action_return()
    {
        // some of gateways can't accept return url on run-time and
        // use the one set in merchant account, so we can't pass
        // 'order_id' in run-time, instead pass the order id parameter name
        $request = XLite_Core_Request::getInstance();
        $orderId = isset($request->order_id_name) ? $request->order_id_name : $request->order_id;

        if ($this->isCartProcessed()) {
            $this->success();
            $this->returnUrl = $this->buildURL('checkoutSuccess', '', array('order_id' => $orderId));

        } else {
            $this->returnUrl = $this->buildURL('checkout', '', array('mode' => 'error', 'order_id' => $orderId));
        }
    }




    // mode ::= null | register | notAllowed | noShipping | paymentMethod | details | success | error    
    /*public $params = array("target");    
    public $mode = null;*/



    function _initCCInfo()
    {
        if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "checkout") { 
            if (isset($_REQUEST["cc_info"]) && is_array($_REQUEST["cc_info"])) {
                if (isset($_REQUEST["cc_info_cc_date_Month"]) && isset($_REQUEST["cc_info_cc_date_Year"])) {
                    $_REQUEST["cc_info"]["cc_date"] = sprintf("%02d%s", intval($_REQUEST["cc_info_cc_date_Month"]), substr($_REQUEST["cc_info_cc_date_Year"],2));
                    unset($_REQUEST["cc_info_cc_date_Month"]);
                    unset($_REQUEST["cc_info_cc_date_Year"]);
                    if (isset($_POST["cc_info"]) && is_array($_POST["cc_info"])) {
                        $_POST["cc_info"]["cc_date"] = $_REQUEST["cc_info"]["cc_date"];
                    }
                }
                if (isset($_REQUEST["cc_info_cc_start_date_Month"]) && isset($_REQUEST["cc_info_cc_start_date_Year"])) {
                    $_REQUEST["cc_info"]["cc_start_date"] = sprintf("%02d%s", intval($_REQUEST["cc_info_cc_start_date_Month"]), substr($_REQUEST["cc_info_cc_start_date_Year"],2));
                    unset($_REQUEST["cc_info_cc_start_date_Month"]);
                    unset($_REQUEST["cc_info_cc_start_date_Year"]);
                    if (isset($_POST["cc_info"]) && is_array($_POST["cc_info"])) {
                        $_POST["cc_info"]["cc_start_date"] = $_REQUEST["cc_info"]["cc_start_date"];
                    }
                }

            }
        }
    }

    function _initCHInfo()
    {
        if (isset($_REQUEST['ch_info']))
            $this->getCart()->set("details", $_REQUEST['ch_info']);
    }

    /**
    * Returns return URL for checkout/login
    */
    function getBackUrl()
    {
        return $this->get('url');
    }

    /**
     * External call success() method
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callSuccess()
    {
        return $this->success();
    }
 
    /**
     * Order placement is success 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function success()
    {
        $this->getCart()->succeed();
        $this->session->set('last_order_id', $this->getCart()->get('order_id'));
        $this->getCart()->clear();

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

    function getCountriesStates()
    {
        if (!isset($this->_profileDialog)) {
            $this->_profileDialog = new XLite_Controller_Customer_Profile();
        }
        return $this->_profileDialog->getCountriesStates();
    }

    function isDisplayNumber()
    {
        return $this->getComplex('xlite.config.General.display_check_number');
    }
}


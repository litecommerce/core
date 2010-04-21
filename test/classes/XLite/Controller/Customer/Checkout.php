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
    
    const CHECKOUT_MODE_NOT_ALLOWED    = 'notAllowed';
    const CHECKOUT_MODE_REGISTER       = 'register';
    const CHECKOUT_MODE_ZERO_TOTAL     = 'zeroTotal';
    const CHECKOUT_MODE_NO_SHIPPING    = 'noShipping';
    const CHECKOUT_MODE_NO_PAYMENT     = 'noPayment';
    const CHECKOUT_MODE_PAYMENT_METHOD = 'paymentMethod';
    const CHECKOUT_MODE_DETAILS        = 'details';


    /**
     * checkoutSteps 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $checkoutSteps = null;


    /**
     * getModelFormClass 
     * 
     * @return string|null
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return 'XLite_View_Model_Profile';
    }

    protected function checkProfile()
    {
        return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_View_Model_Profile')->isValid();
    }

    protected function isCheckoutNotAllowed()
    {
        return $this->getCart()->isMinOrderAmountError() || $this->getCart()->isMaxOrderAmountError();
    }

    protected function isRegistrationNeeded()
    {
        return !XLite_Model_Auth::getInstance()->isLogged() || !$this->checkProfile();
    }

    protected function isZeroOrderTotal()
    {
        return 0 == $this->getCart()->get('total') && $this->config->Payments->default_offline_payment;
    }

    protected function isNoShipping()
    {
        return !$this->getCart()->isShippingAvailable() && $this->getCart()->isShipped();
    }

    protected function isNoPayment()
    {
        return !$this->getPaymentMethods();
    }

    protected function isPaymentNeeded()
    {
        return !$this->getCart()->getPaymentMethod();
    }

    protected function isFinalStep()
    {
        return true;
    }

    protected function getCheckoutStepDescriptions()
    {
        return array(
            self::CHECKOUT_MODE_NOT_ALLOWED    => $this->isCheckoutNotAllowed(),
            self::CHECKOUT_MODE_REGISTER       => $this->isRegistrationNeeded(),
            self::CHECKOUT_MODE_ZERO_TOTAL     => $this->isZeroOrderTotal(),
            self::CHECKOUT_MODE_NO_SHIPPING    => $this->isNoShipping(),
            self::CHECKOUT_MODE_NO_PAYMENT     => $this->isNoPayment(),
            self::CHECKOUT_MODE_PAYMENT_METHOD => $this->isPaymentNeeded(),
            self::CHECKOUT_MODE_DETAILS        => $this->isFinalStep(),
        );
    }

    protected function defineCheckoutSteps()
    {
        foreach ($this->getCheckoutStepDescriptions() as $mode => $isPassed) {
            $this->checkoutSteps->add(new XLite_Model_CheckoutStep($mode, $isPassed));
        }

        // Use the "$this->checkoutSteps->insert(Before|After)" methods
        // to add new checkout step
    }

    protected function getCheckoutSteps()
    {
        if (!isset($this->checkoutSteps)) {
            $this->checkoutSteps = new XLite_Model_List();
            $this->defineCheckoutSteps();
        }

        return $this->checkoutSteps;
    }

    protected function getCurrentStep()
    {
        return $this->getCheckoutSteps()->findByCallbackResult('checkMode', XLite_Core_Request::getInstance()->mode);
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

    protected function getPaymentMethods()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(__METHOD__, 'XLite_Model_PaymentMethod', 'getActiveMethods');
    }



    /*protected function initCheckMode()
    {
        $mode = "";
        if ($this->_initCheckNotAllowed()) {
            $mode = "notAllowed";
        } elseif ($this->_initNeedRegister()) {
            $mode = "register";
        } elseif ($this->_initCheckZeroTotal()) {
            $mode = "zeroTotal";
        } elseif ($this->_initNeedShipping()) {
            $mode = "noShipping";
        } elseif ($this->_initCheckNotPayment()) {
            $mode = "noPayment";
        } elseif ($this->_initNeedPayment()) {
            $mode = "paymentMethod";
        } else {
            $mode = "details";
        }

        return $mode;
    }*/

    /**
     * Set new value for the reqest param "mode" 
     * 
     * @param string $mode mode to set
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    /*protected function initSetMode($mode)
    {
        XLite_Core_Request::getInstance()->mode = $this->getCurrentStep()->getKey();

        /*switch($mode) {
            case "notAllowed":
                $this->set("mode", "notAllowed");
            break;
            case "register":
                $this->set("mode", "register");
            break;
            case "noShipping":
                $this->set("mode", "noShipping");
            break;
            case "noPayment":
                $this->set("mode", "noPayment");
            break;
            case "paymentMethod":
                $activeMethods = $this->getPaymentMethods();
                if (is_array($activeMethods) && count($activeMethods) == 1) {
                    $activeMethods = array_values($activeMethods);
                    $_POST["payment_id"] = $activeMethods[0]->get("payment_method");
                    $this->doActionPayment();
                    $this->set("returnUrl", "cart.php?target=checkout");
                    $this->redirect();
                    return true;
                } else {
                    $this->set("mode", "paymentMethod");
                }
            break;
            case "zeroTotal":
                $_POST["payment_id"] = $this->config->getComplex('Payments.default_offline_payment');
                $this->doActionPayment();
                $this->getCart()->checkout();
                $this->action_checkout();
                return true;
            break;
            case "details":
                $this->set("mode", "details");
                // in checkout details template: <widget template="{cart.paymentMethod.templateWidget}"/>
                $this->getCart()->checkout();
            break;
        }

        return false;*/
    //}


    public function getOrder()
    {
        return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Order', XLite_Core_Request::getInstance()->order_id);
    }

    /**
     * Initialize controller 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        XLite_Core_Request::getInstance()->mode = $this->getCurrentStep()->getKey();

        /*var_dump($this->getCurrentStep());die;

        if (isset(XLite_Core_Request::getInstance()->mode)) {

            parent::init();

        } else {

            // We've got sign for return
            $this->initSetMode($this->initCheckMode());
        }*/



        // TODO -check if it can be moved
        // $this->_initCCInfo();
        // $this->_initCHInfo();

/*        if (isset($_REQUEST["mode"])) {
            $this->set("mode", $_REQUEST["mode"]);
        }

        if (is_null($this->get("mode"))) {
            if ($this->initSetMode($this->initCheckMode())) {
                return; // we've got sign for return
            }
        }

        parent::init();

        if (!empty($this->registerForm)) {
            $this->registerForm->set("mode", "register");
        }*/
    }

    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
	public function handleRequest()
    {
        // Go to cart view if cart is empty
        if ($this->getCart()->isEmpty()) {
		    $this->returnUrl = $this->buildURL('cart');
        } else {
            parent::handleRequest();
        }
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

    public function doActionRegister()
    {
        $this->getModelForm()->performAction('modify');
    }

    // TODO - move functionality to the the function above
    /*function action_register()
    {
        $this->registerForm->action_register();
        $this->set("valid", $this->registerForm->is("valid"));
        if ($this->registerForm->is("valid")) {
            $this->auth->loginProfile($this->registerForm->get("profile"));
            if (!strlen($this->get("password"))) {
                // is anonymous?
                $this->auth->setComplex("profile.order_id", $this->getCart()->get("order_id"));
                $this->auth->getProfile()->update();
            }
            $cart = XLite_Model_Cart::getInstance();
            if (!$cart->isEmpty()) {
                $cart->set("profile_id", $this->auth->getComplex('profile.profile_id'));
                $cart->update();
                $this->recalcCart();
            }
        }
    }*/


    /**
     * action_checkout
     * FIXME - must be completely revised 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function action_checkout()
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
            $notes = isset(XLite_Core_Request::getInstance()->notes) ? XLite_Core_Request::getInstance()->notes : '';
            $this->getCart()->set('notes', $notes);

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

    /*function _initCheckNotAllowed()
    {
        if ($this->getCart()->get("minOrderAmountError")) {
        	return true;
        } else if ($this->getCart()->get("maxOrderAmountError")) {
        	return true;
        } else {
        	return false;
        }
	}

    function _initCheckZeroTotal()
    {
    	return (($this->getCart()->get("total") == 0) && (strlen($this->config->getComplex('Payments.default_offline_payment')) > 0)) ? true : false;
	}

	function _initCheckNotPayment()
	{
		return (count($this->getPaymentMethods()) <= 0) ? true : false;
	}

    function _initNeedRegister()
    {
    	return (!$this->auth->is("logged")) ? true : false;
	}

    function _initNeedShipping()
    {
    	return ($this->getCart()->is("shipped") && !$this->getCart()->is("shippingAvailable")) ? true : false;
	}

    function _initNeedPayment()
    {
    	return (!$this->getCart()->get("payment_method")) ? true : false;
	}*/

    /**
    * Returns return URL for checkout/login
    */
    function getBackUrl()
    {
        return $this->get("url");
    }
    
    /*function getPaymentMethods()
    {
        $paymentMethod = new XLite_Model_PaymentMethod();
        return $paymentMethod->get("activeMethods");
    }*/

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

		$pm = new XLite_Model_PaymentMethod(XLite_Core_Request::getInstance()->payment_id);
        $this->getCart()->set('paymentMethod', $pm);
        $this->updateCart();

		if ($this->isPaymentNeeded()) {
			$this->params[] = 'error';
			$this->set('error', 'pmSelect');
		}
    }

    function action_shipping()
    {
        $this->checkHtaccess();

        if (isset(XLite_Core_Request::getInstance()->shipping)) {
            $this->getCart()->set('shipping_id', XLite_Core_Request::getInstance()->shipping);
            $this->updateCart();
        }
    }

    function success()
    {
        $this->getCart()->succeed();
        $this->session->set("last_order_id", $this->getCart()->get("order_id"));
        $this->getCart()->clear();
        // anonymous checkout: logoff
        if ($this->auth->getComplex('profile.order_id')) {
            $this->auth->logoff();
        }
    }

    function isSecure()
    {
        return $this->getComplex('config.Security.customer_security');
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


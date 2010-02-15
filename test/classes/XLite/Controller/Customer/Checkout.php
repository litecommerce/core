<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Dialog_checkout description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Checkout extends XLite_Controller_Customer_Cart
{
    // mode ::= null | register | notAllowed | noShipping | paymentMethod | details | success | error	
    public $params = array("target");	
    public $mode = null;

    function handleRequest()
    {
        // go to cart view if cart is empty
        if ($this->cart->is("empty")) {
            $this->redirect("cart.php?target=cart");
            return;
        }
        parent::handleRequest();
    }

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
			$this->cart->set("details", $_REQUEST['ch_info']);
	}

    function _initCheckNotAllowed()
    {
        if ($this->cart->get("minOrderAmountError")) {
        	return true;
        } else if ($this->cart->get("maxOrderAmountError")) {
        	return true;
        } else {
        	return false;
        }
	}

    function _initCheckZeroTotal()
    {
    	return (($this->cart->get("total") == 0) && (strlen($this->config->getComplex('Payments.default_offline_payment')) > 0)) ? true : false;
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
    	return ($this->cart->is("shipped") && !$this->cart->is("shippingAvailable")) ? true : false;
	}

    function _initNeedPayment()
    {
    	return (!$this->cart->get("payment_method")) ? true : false;
	}

    function _initCheckMode()
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
    }

    function _initSetMode($mode)
    {
    	switch($mode) {
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
            		$this->action_payment();
            		$this->set("returnUrl", "cart.php?target=checkout");
            		$this->redirect();
            		return true;
            	} else {
                	$this->set("mode", "paymentMethod");
                }
    		break;
    		case "zeroTotal":
				$_POST["payment_id"] = $this->config->getComplex('Payments.default_offline_payment');
				$this->action_payment();
                $this->cart->checkout();
				$this->action_checkout();
				return true;
    		break;
    		case "details":
                $this->set("mode", "details");
                // in checkout details template: <widget template="{cart.paymentMethod.templateWidget}"/>
                $this->cart->checkout();
    		break;
    	}
		
		return false;
    }

    function init()
    {
    	$this->_initCCInfo();
    	$this->_initCHInfo();

        if (isset($_REQUEST["mode"])) {
            $this->set("mode", $_REQUEST["mode"]);
        }

        if (is_null($this->get("mode"))) {
        	if ($this->_initSetMode($this->_initCheckMode())) {
        		return; // we've got sign for return
        	}
        }

        parent::init();
       
		if (!empty($this->registerForm)) { 
	        $this->registerForm->set("mode", "register");
		}
    }


    /**
    * Returns return URL for checkout/login
    */
    function getBackUrl()
    {
        return $this->get("url");
    }
    
    function getPaymentMethods()
    {
        $paymentMethod = new XLite_Model_PaymentMethod();
        return $paymentMethod->get("activeMethods");
    }

    function action_payment()
    {
        $this->checkHtaccess();

		$pm = new XLite_Model_PaymentMethod($_POST["payment_id"]);
        $this->cart->set("paymentMethod", $pm);
        $this->updateCart();

		if ($this->_initNeedPayment()) {
			$this->params[] = "error";
			$this->set("error", "pmSelect");
		}
    }

    function action_register()
    {
        $this->registerForm->action_register();
        $this->set("valid", $this->registerForm->is("valid"));
        if ($this->registerForm->is("valid")) {
            $this->auth->loginProfile($this->registerForm->get("profile"));
            if (!strlen($this->get("password"))) {
                // is anonymous?
                $this->auth->setComplex("profile.order_id", $this->cart->get("order_id"));
                $this->auth->getProfile()->update();
            }
    		$cart = XLite_Model_Cart::getInstance();
     		if (!$cart->isEmpty()) {
     			$cart->set("profile_id", $this->auth->getComplex('profile.profile_id'));
     			$cart->update();
    			$this->recalcCart();
     		}
        }
    }

    function action_checkout()
    {
    	$itemsBeforeUpdate = $this->cart->getItemsFingerprint();
        $this->updateCart();
    	$itemsAfterUpdate = $this->cart->getItemsFingerprint();
		if ($this->get("absence_of_product") || $this->cart->isEmpty() || $itemsAfterUpdate != $itemsBeforeUpdate) {
			$this->set("absence_of_product", true);
			$this->redirect("cart.php?target=cart");
			return;
		}

        $pm = $this->cart->get("paymentMethod");
        if (!is_null($pm)) {
            $notes = isset($_POST["notes"]) ? $_POST["notes"] : '';
            $this->setComplex("cart.notes", $notes);

            switch($pm->handleRequest($this->cart)) {

	            case XLite_Model_PaymentMethod::PAYMENT_SILENT:
					// don't call output()
    	            $this->set("silent", true);
        	        break;

            	case XLite_Model_PaymentMethod::PAYMENT_SUCCESS:
                	$this->success();
	                $this->set("returnUrl", "cart.php?target=checkoutSuccess&order_id=".$this->cart->get("order_id"));
    	            break;

        	    case XLite_Model_PaymentMethod::PAYMENT_FAILURE:
            	    $this->set("returnUrl", "cart.php?target=checkout&mode=error&order_id=".$this->cart->get("order_id"));
                	break;
            }
        }
    }

    function success()
    {
        $this->cart->succeed();
        $this->session->set("last_order_id", $this->cart->get("order_id"));
        $this->cart->clear();
        // anonymous checkout: logoff
        if ($this->auth->getComplex('profile.order_id')) {
            $this->auth->logoff();
        }
    }

    function getOrder()
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order($_REQUEST["order_id"]);
        }
        return $this->order;
    }
    
    function output()
    {
        if (!$this->get("silent")) {
            parent::output();
        }
    }

    function action_return()
    {
        if (isset($_REQUEST["order_id_name"])) {
            // some of gateways can't accept return url on run-time and
            // use the one set in merchant account, so we can't pass
            // 'order_id' in run-time, instead pass the order id parameter name
            $order_id_name = $_REQUEST["order_id_name"];
            $_REQUEST["order_id"] = $_REQUEST[$order_id_name];
        } else {
            $order_id_name = "order_id";
        }
        $status = $this->getComplex('order.status');
        if ($status == "P" || $status == "C" || $status == "Q") {
            $this->success();
            $this->set("returnUrl", "cart.php?target=checkoutSuccess&order_id=" . $_REQUEST["order_id"]);
        } else {
            $this->set("returnUrl", "cart.php?target=checkout&mode=error&order_id=" . $_REQUEST["order_id"]);
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

    /**
     * Get page instance data (name and URL)
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
        $this->target = 'checkout';

        return parent::getPageInstanceData();
    }

    /**
     * Get page type name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeName()
    {
        return 'Checkout';
    }

}


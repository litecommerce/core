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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* This implementation complies the following documentation: <br>
* Cart Payment Routine 2 Specifications (Authorize.net) - <br>
*   http://www.2checkout.com/cart_specs.htm <br>
* 2Checkout MD5 Specifications - <br>
*   http://www.2checkout.com/md5_specs.htm <br>
*
* @package Module_2CheckoutCom
* @access public
* @version $Id$
*/
class XLite_Module_2CheckoutCom_Model_PaymentMethod_2Checkout extends XLite_Model_PaymentMethod_CreditCard
{	
    public $cvverr = array(
            "M" => "Match",
            "N" => "Wrong CVV2 code",
            "P" => "CVV2 code was not processed",
            "S" => "Please specify your CVV2 code",
            "U" => "Issuer unable to process request"
            );	
    public $avserr = array(
            "A" => "Wrong billing address: Address (Street) matches, ZIP does not",
            "E" => "Wrong billing address",
            "N" => "Wrong billing address: No Match on Address (Street) or ZIP",
            "P" => "AVS not applicable for this transaction",
            "R" => "Please Retry. System unavailable or timed out",
            "S" => "AVS Service not supported by issuer",
            "U" => "Address information is unavailable",
            "W" => "Wrong billing address: 9 digit ZIP matches, Address (Street) does not",
            "X" => "Exact AVS Match",
            "Y" => "Wrong billing address: Address (Street) and 5 digit ZIP match",
            "Z" => "Wrong billing address: 5 digit ZIP matches, Address (Street) does not"
            );	

    public $processorName = "2Checkout.com";	
    public $configurationTemplate = "modules/2CheckoutCom/config.tpl";	
    public $formTemplate ="modules/2CheckoutCom/checkout.tpl";

    function handleRequest(XLite_Model_Cart $cart)
    {
		$params = $this->get("params");
		if ($params["version"] != 2) {
    		// Authorize.Net now returns all POST in lowercase.
            if (!isset($_POST["securenumber"]) || $_POST["securenumber"] != $cart->getComplex('details.secureNumber')) {
                die("<font color=red><b>Security check failed!</b></font> Please contact administrator <b>" . $this->config->getComplex('Company.site_administrator') . "</b> .");
            }
            require_once LC_MODULES_DIR . '2CheckoutCom' . LC_DS . 'encoded.php';
            PaymentMethod_2checkout_handleRequest($this, $cart);
		} else {
    		$security_check = true;

    		$order_number = ($params["test_mode"]=="Y") ? 1 : $_POST["order_number"];
    		$securekey = strtoupper(md5($params["secret_word"].$params["account_number"].$order_number.$_POST["total"]));
    		if ($securekey != $_POST["key"]) {
    			$security_check = false;
    		}

    		if ($cart->get("total") != $_POST["total"]) {
                $security_check = false; 
            }        

    		if (isset($_SERVER["HTTP_REFERER"])) {
    			$referers = array("www.2checkout.com", "2checkout.com", "www2.2checkout.com");
    			$referer_check = false;
    			foreach ($referers as $referer) {
    				if (!(preg_match("/https?:\/\/([^\/]*)$referer/i", $_SERVER["HTTP_REFERER"]) == false)) {
    					$referer_check = true;
    					break;
    				}
    			}
    			if (!$referer_check) {
    				$security_check = false;
    			}
    		}

            require_once LC_MODULES_DIR . '2CheckoutCom' . LC_DS . 'encoded.php';
            PaymentMethod_2checkout_v2_handleRequest($this, $cart, $security_check);
		}		
    }

    function createSecureNumber($order)
    {
        if (!$order->getComplex('details.secureNumber')) {
            $num = generate_code();
            $order->setComplex("details.secureNumber", $num);
            $order->update();
            return $num;
        }
        return $order->getComplex('details.secureNumber');
    }

    function price($value=null)
    {
    	if (!isset($value)) {
    		$value = 0;
    	}
    	return sprintf("%.02f", doubleval($value));
    }

    function stripSpecials($value)
    {
		$value = parent::_stripSpecials($value);
		$value = str_replace("\t", " ", $value);
		$value = str_replace("\r", " ", $value);
		$value = str_replace("\n", " ", $value);
		$value = str_replace("\"", "", $value);
		$value = strip_tags($value);
        return $value;
    }

    function fieldName($name, $idx)
    {
    	return $name . (($idx > 0) ? ("_" . $idx) : "");
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

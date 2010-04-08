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

func_define('CALLBACK_CHECK_MESSAGE', 'CHECK_CALLBACK_STATUS');
func_define('CALLBACK_PASSED_MESSAGE', 'CALLBACK TEST PASSED');

/**
* XLite_Module_GoogleCheckout_Controller_Admin_PaymentMethod
*
* @package GoogleCheckout
* @access public
* @version $Id$
*/
class XLite_Module_GoogleCheckout_Controller_Admin_PaymentMethod extends XLite_Controller_Admin_PaymentMethod implements XLite_Base_IDecorator
{
    function sendRequest(&$payment, $url, $data)
    {
    	$auth = base64_encode($payment->getComplex('params.merchant_id').":".$payment->getComplex('params.merchant_key'));
    	$h = array(
    		"Authorization" => "Basic ".$auth,
    		"Accept" => "application/xml"
    	);  

        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
		$https = GoogleCheckout_getHTTPS_Object();
    	$https->data     = $data;
    	$https->method   = "POST";
    	$https->conttype = "application/xml";
    	$https->headers  = $h;
    	$https->url      = $url;

		$this->xlite->logger->log("Request to: " . $url . " with data:\n" . $data);
    	if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
    		$this->error = $https->error;
    		return array();
    	}

		$this->xlite->logger->log("Response:\n" . $https->response);
        if ($https->response != CALLBACK_PASSED_MESSAGE) {
            $this->error = "not passed";
            return array();
        }

    	return $https->response;
    }

	function checkCallbackConnection(&$payment)
	{
		$this->error = null;
		$this->sendRequest($payment, $payment->getCallbackURL(), CALLBACK_CHECK_MESSAGE);
		return ($this->error) ? false : true;
	}

	function getAccessLevel()
    {
        return (isset($this->action) && 'callback' == $this->action) ? 0 : parent::getAccessLevel();
    }

	function checkDisableCustomerNotif($value)
	{
		return (bool) $value;
	}

	function checkCallbackAuthorization()
	{
		$this->set("silent", true);
		$this->xlite->logger->log("Received callback from GoogleCheckout");

		$this->pm = new XLite_Model_PaymentMethod("google_checkout");
        $params = $this->pm->get("params");

		$this->phpAuthUser = $GLOBALS["_SERVER"]["PHP_AUTH_USER"];
		$this->phpAuthPW = $GLOBALS["_SERVER"]["PHP_AUTH_PW"];
		$this->httpRawPostData = $GLOBALS["HTTP_RAW_POST_DATA"];
		// workaround for a bug in PHP 5.2.2 - see http://bugs.php.net/bug.php?id=41293
		if (empty($this->httpRawPostData)) {
    		$this->httpRawPostData = $GLOBALS["HTTP_RAW_POST_DATA"] = @file_get_contents("php://input");
		}

		if (empty($this->httpRawPostData)) {
			// Google checkout payment module: Script called with no data passed to it.
			$this->xlite->logger->log("ERROR: Script called with no data passed to it.");
			exit;
		}
		$this->xlite->logger->log("RawPostData:\n" . $this->httpRawPostData);

		// check if callback-request has been successfully authorized
		if ($this->phpAuthUser != $params["merchant_id"] || $this->phpAuthPW != $params["merchant_key"]) {
			$this->xlite->logger->log("ERROR: Unauthorized access to callback script.");
			header("WWW-Authenticate: Basic");
			header("HTTP/1.0 401 Unauthorized");
			die;
		}
	}

    function action_callback()
    {
        $this->checkCallbackAuthorization();

		if ($this->httpRawPostData == CALLBACK_CHECK_MESSAGE) {
        	die(CALLBACK_PASSED_MESSAGE);
		}

        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
		$xml = GoogleCheckout_getXML_Object();
		$parsed = $xml->parse($this->httpRawPostData);

		if (empty($parsed)) {
			// Google checkout payment module: Received data could not be identified correctly.
			$this->xlite->logger->log("ERROR: Received data could not be identified correctly.");
			exit;
		}
		ob_start();
		var_dump($parsed);
		$parsedData = ob_get_contents();
		ob_end_clean();
		$this->xlite->logger->log("Parsed XML callback data:\n" . $parsedData);
		$this->xlite->logger->log("Callback from IP: ".$GLOBALS["REMOTE_ADDR"]."\n");

		// processing callback
		$this->pm->handleCallback($parsed);
    }
}

?>

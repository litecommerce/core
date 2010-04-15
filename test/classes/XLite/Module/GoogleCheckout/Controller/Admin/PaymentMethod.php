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

func_define('CALLBACK_CHECK_MESSAGE', 'CHECK_CALLBACK_STATUS');
func_define('CALLBACK_PASSED_MESSAGE', 'CALLBACK TEST PASSED');

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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

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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Module_SnsIntegration.
*
* @package Sns_Integration
* @version $Id$
*/

define('PERSONALIZE_CLIENT_ID', 'personal_client_id');

function func_get_sns_client_id()
{
	if (!empty($_COOKIE[PERSONALIZE_CLIENT_ID])) {
		return intval($_COOKIE[PERSONALIZE_CLIENT_ID]);
	}

	$remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$remote_addr = substr($_SERVER['HTTP_X_FORWARDED_FOR'] . ', ' . $remote_addr, 0, 255);
	}

    $accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
    $user_agent      = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    return crc32($remote_addr) ^ crc32($accept_language) ^ crc32($user_agent);
}

function func_sns_request($config, $clientId, $actions, $timestamp = null)
{
    if (!isset($timestamp)) {
    	$timestamp = time();
    } else {
    	$timestamp = intval($timestamp);
    	if (!$timestamp) {
    		$timestamp = time();
    	}
    }

	require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PEAR.php';
	require_once LC_ROOT_DIR . 'lib' . LC_DS . 'HTTP' . LC_DS . 'Request.php';

	$url = $config->getComplex('SnsIntegration.collectorURL');

	if (!(strlen($url) > 0 && strlen($config->getComplex('SnsIntegration.collectorLanguage')) > 0)) {
		return false;
	}
	if ($url == "http://" || $url == "https://") {
		return false;
	}

	$url .= "/event." . $config->getComplex('SnsIntegration.collectorLanguage');

    $logger = new XLite_Logger();
    $logger->log("SnS request at $url:");
	if (!$clientId) {
		// no client id is given
    	$logger->log("[Sns request] - ERROR: no client id is given");
		return false;
	}
	if (!preg_match('/^https/i', $url)) {
		$http = new HTTP_Request($url);
    	$http->_timeout = 5; // can't wait long when we are in shopping cart
    	$http->_method = HTTP_REQUEST_METHOD_POST;
    	$http->addPostData("clientId",$clientId);
    	$http->addPostData("timestamp", $timestamp);
        $http->addPostData("passphrase", $config->getComplex('SnsIntegration.passphrase'));
    	$http->addPostData("shopDisplayName", $config->getComplex('SnsIntegration.shopDisplayName'));
    	$http->addPostData("site", $config->xlite->getShopUrl(""));

    	$n = 0;
    	foreach ($actions as $action) {
    		$http->addPostData("actions[".$n."]", $action);
            $logger->log($action);
    		$n++;
    	}
        $logger->log("/Sns request");
        $config->xlite->set("SNSResponse", null);
        $result = @$http->sendRequest();
        if (PEAR::isError($result)) {
        	$logger->log("[Sns request] - ERROR: Connection error.");
    		return false;
        }
        $config->xlite->set("SNSResponse", $http->getResponseBody());
    } else {
        $https = new XLite_Model_HTTPS();

        $postData = array();
    	$postData["clientId"] = $clientId;
    	$postData["timestamp"] = $timestamp;
        $postData["passphrase"] = $config->getComplex('SnsIntegration.passphrase');
    	$postData["shopDisplayName"] = $config->getComplex('SnsIntegration.shopDisplayName');
    	$postData["site"] = $config->xlite->getShopUrl("");
    	$n = 0;
    	foreach ($actions as $action) {
    		$postData["actions[".$n."]"] = $action;
            $logger->log($action);
    		$n++;
    	}

		$https->data = $postData;
		$https->urlencoded = false;
    	$https->url = $url;
        $logger->log("/Sns request");
        $config->xlite->set("SNSResponse", null);

        if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
        	$logger->log("[Sns request] - ERROR: Connection error (" . $https->error . ")");
    		return false;
        }
        $config->xlite->set("SNSResponse", $https->response);
	}

	return true;
}

function func_sns_profile_params($profile)
{
    $action = "&billing_country=" . urlencode($profile->getComplex('billingCountry.country'));
    $action .= "&billing_city=" . urlencode($profile->get("billing_city"));
    $action .= "&billing_company=" . urlencode($profile->get("billing_company"));
    $action .= "&billing_fax=" . urlencode($profile->get("billing_fax"));
    $action .= "&billing_phone=" . urlencode($profile->get("billing_phone"));
    $action .= "&billing_address=" . urlencode($profile->get("billing_address"));
    $action .= "&billing_state=" . urlencode($profile->getComplex('billingState.code'));
    $action .= "&billing_zipcode=" . urlencode($profile->get("billing_zipcode"));
    $action .= "&email=" . urlencode($profile->get("login"));
    $action .= "&billing_firstname=" . urlencode($profile->get("billing_firstname"));
    $action .= "&billing_lastname=" . urlencode($profile->get("billing_lastname"));
    $action .= "&profile_id=" . urlencode($profile->get("profile_id"));
    return $action;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

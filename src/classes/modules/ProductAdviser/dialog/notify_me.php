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
* Dialog_notify_me description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id: notify_me.php,v 1.5 2008/10/23 11:58:31 sheriff Exp $
*/
class Dialog_notify_me extends Dialog
{
    var $product = null;

	function init()
	{
		parent::init();

		if ($this->session->isRegistered("NotifyMeReturn") && $this->session->isRegistered("NotifyMeInfo")) {
			$_REQUEST = $this->session->get("NotifyMeInfo");
			$this->mapRequest($_REQUEST);
			$this->session->set("NotifyMeInfo", null);
			$this->session->set("NotifyMeReturn", null);
		}

		if (isset($this->product_id) && intval($this->product_id) > 0) {
			$this->product =& func_new("Product", $this->product_id);
		}

		if (!(is_object($this->product) && ($this->product->is("exists")))) {
			$this->redirect("cart.php?target=main&mode=accessDenied");
			return;
		}

		if ($this->xlite->get("ProductOptionsEnabled") && isset($this->product_options)) {
			$poArr = array();
			foreach($this->product_options as $class => $po) {
				$poArr[] = array("class" => $class, "option" => $po["option"], "option_id" => $po["option_id"]);
			}
			$this->set("productOptions", $poArr);
			$poStr = array();
			foreach($this->product_options as $class => $po) {
				$poStr[] = $class . ": " . $po["option"];
			}
			$this->set("productOptionsStr", implode(", ", $poStr));
		}
		$this->set("prevUrl", $this->url);

		$this->session->set("NotifyMeInfo", $_REQUEST);

		if (!$this->auth->is("logged")) {
			$this->set("email", $this->session->get("customerEmail"));
		}
	}

	function action_notify_product()
	{
		if (!$this->isProductNotificationEnabled()) {
			return;
		}
        if (!isset($_REQUEST["email"]) || (isset($_REQUEST["email"]) && strlen(trim($_REQUEST["email"]))==0)) {
			$this->set("valid", false);
			return;
		}
		$this->email = trim($_REQUEST["email"]);

		$notification =& func_new("CustomerNotification");
    	$check = array();
		$notification->set("type", CUSTOMER_NOTIFICATION_PRODUCT);
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";

		if ($this->auth->is("logged")) {
			$profile = $this->auth->get("profile");
    		$notification->set("profile_id", $profile->get("profile_id"));
    		$notification->set("email", $profile->get("login"));
    		$notification->set("person_info", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));
		} else {
    		$notification->set("email", $this->email);
    		$this->session->set("customerEmail", $this->email);
    		$notification->set("person_info", $this->person_info);
		}
        $check[] = "profile_id='" . $notification->get("profile_id") . "'";
        $check[] = "email='" . $notification->get("email") . "'";

    	$notification->set("product_id", $this->product_id);
    	if (isset($this->product_options)) {
    		$notification->set("product_options", $this->product_options);
    	}
    	if (isset($this->amount)) {
    		$notification->set("quantity", $this->amount);
    	}
        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

        $check = implode(" AND ", $check);
    	if (!$notification->find($check)) {
    		$notification->set("notify_key", addslashes($notification->get("productKey")));
    		$notification->set("date", time());
    		$notification->create();
    	}

		$this->session->set("rejectedItem", null);
		$this->set("returnUrl", $this->url);
	}

	function action_notify_price()
	{
		if (!$this->isPriceNotificationEnabled()) {
			return;
		}
		if (!$this->is("product.priceNotificationAllowed")) {
			return;
		}
        if (!isset($_REQUEST["email"]) || (isset($_REQUEST["email"]) && strlen(trim($_REQUEST["email"]))==0)) {
			$this->set("valid", false);
			return;
		}
		$this->email = trim($_REQUEST["email"]);

		$notification =& func_new("CustomerNotification");
    	$check = array();
		$notification->set("type", CUSTOMER_NOTIFICATION_PRICE);
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";

		if ($this->auth->is("logged")) {
			$profile = $this->auth->get("profile");
    		$notification->set("profile_id", $profile->get("profile_id"));
    		$notification->set("email", $profile->get("login"));
    		$notification->set("person_info", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));
		} else {
    		$notification->set("email", $this->email);
    		$this->session->set("customerEmail", $this->email);
    		$notification->set("person_info", $this->person_info);
		}
        $check[] = "profile_id='" . $notification->get("profile_id") . "'";
        $check[] = "email='" . $notification->get("email") . "'";

    	$notification->set("product_id", $this->product_id);
        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

        $check = implode(" AND ", $check);
    	if (!$notification->find($check)) {
    		$notification->set("notify_key", addslashes($notification->get("productKey")));
    		$notification->set("price", $this->get("product_price"));
    		$notification->set("date", time());
    		$notification->create();
    	}

		$this->set("returnUrl", $this->url);
	}

	function isPriceNotificationEnabled()
	{
		$mode = $this->config->get("ProductAdviser.customer_notifications_mode");
		return (($mode & 1) != 0) ? true : false;
	}

	function isProductNotificationEnabled()
	{
		$mode = $this->config->get("ProductAdviser.customer_notifications_mode");
		return (($mode & 2) != 0) ? true : false;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

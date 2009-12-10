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
* Dialog_cart_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id: cart.php,v 1.4 2008/10/23 11:58:25 sheriff Exp $
*/
class Dialog_cart_ProductAdviser extends Dialog_cart
{
	var $rejectedItemInfo = null;

    function action_add()
    {
        parent::action_add();

        require_once "modules/ProductAdviser/encoded.php";
		ProductAdviser_action_add($this);
    }

    function getRejectedItem()
    {
		if (!($this->xlite->get("PA_InventorySupport") && $this->config->get("ProductAdviser.customer_notifications_enabled"))) {
			return null;
		}

		if (!$this->session->isRegistered("rejectedItem")) {
			if (!is_null($this->rejectedItemInfo)) {
				return ($this->rejectedItemInfo);
			}
			$this->rejectedItemInfo = null;
			return null;
		}

		if (is_null($this->rejectedItemInfo)) {
			$rejectedItemInfo = $this->session->get("rejectedItem");
			$this->session->set("rejectedItem", null);
			$this->rejectedItemInfo =& func_new("Object");
			$this->rejectedItemInfo->set("product_id", $rejectedItemInfo->product_id);
			$this->rejectedItemInfo->set("product", func_new("Product", $this->rejectedItemInfo->product_id));
			$this->rejectedItemInfo->set("amount", $rejectedItemInfo->availableAmount);
			$this->rejectedItemInfo->set("key", $rejectedItemInfo->itemKey);
			if (isset($rejectedItemInfo->productOptions)) {
    			$this->rejectedItemInfo->set("productOptions", $rejectedItemInfo->productOptions);
    			$poStr = array();
    			foreach($rejectedItemInfo->productOptions as $po) {
    				$poStr[] = $po->class . ": " . $po->option;
    			}
    			$this->rejectedItemInfo->set("productOptionsStr", implode(", ", $poStr));
    		}

        	if ($this->isNotificationSaved($rejectedItemInfo)) {
    			$this->rejectedItemInfo = null;
        	}
		}

		return ($this->rejectedItemInfo);
	}

	function isNotificationSaved($rejectedItemInfo)
	{
    	$check = array();
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";

		if ($this->auth->is("logged")) {
			$profile = $this->auth->get("profile");
    		$profile_id = $profile->get("profile_id");
    		$email = $profile->get("login");
		} else {
    		$profile_id = 0;
    		if ($this->session->isRegistered("customerEmail")) {
    			$email = $this->session->get("customerEmail");
    		}
		}
        $check[] = "profile_id='$profile_id'";
        $check[] = "email='$email'";

		$notification =& func_new("CustomerNotification");
		$notification->set("type", CUSTOMER_NOTIFICATION_PRODUCT);
    	$notification->set("product_id", $rejectedItemInfo->product_id);
		if (isset($rejectedItemInfo->productOptions)) {
			if (isset($rejectedItemInfo->productOptions[0]) && is_object($rejectedItemInfo->productOptions[0])) {
    			$poArray = array();
    			foreach($rejectedItemInfo->productOptions as $po) {
    				$poArray[$po->class] = array("option_id" => $po->option_id, "option" => $po->option);
    			}
        		$notification->set("product_options", $poArray);
			} else {
    			$notification->set("product_options", $rejectedItemInfo->productOptions);
    		}
    	}
    	if (isset($rejectedItemInfo->amount)) {
    		$notification->set("quantity", $rejectedItemInfo->amount);
    	}
        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

        $check = implode(" AND ", $check);

    	return $notification->find($check);
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

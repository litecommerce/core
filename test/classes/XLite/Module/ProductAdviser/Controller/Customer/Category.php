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
* Dialog_category_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_Controller_Customer_Category extends XLite_Controller_Customer_Category implements XLite_Base_IDecorator
{	
	public $priceNotified = array();

	function getPriceNotificationSaved($product_id = 0)
	{
		if (!$this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
			return true;
		}

		if (!isset($this->priceNotified[$product_id])) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";

			$email = '';

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

    		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    		$notification->set("type", CUSTOMER_NOTIFICATION_PRICE);
        	$notification->set("product_id", $product_id);
            $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

            $check = implode(" AND ", $check);
            $this->priceNotified[$product_id] = $notification->find($check);
		}
    	return $this->priceNotified[$product_id];
	}

	function isPriceNotificationEnabled()
	{
		$mode = $this->config->getComplex('ProductAdviser.customer_notifications_mode');
		return (($mode & 1) != 0) ? true : false;
	}

	function isProductNotificationEnabled()
	{
		$mode = $this->config->getComplex('ProductAdviser.customer_notifications_mode');
		return (($mode & 2) != 0) ? true : false;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

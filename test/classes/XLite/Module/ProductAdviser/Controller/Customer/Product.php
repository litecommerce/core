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
* Dialog_product_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_Controller_Customer_Product extends XLite_Controller_Customer_Product implements XLite_Base_IDecorator
{	
	public $rejectedItemInfo = null;	
	public $priceNotified = null;

    function init()
    {
		$request = $request = XLite_Core_Request::getInstance();
		$product_id = intval($request->product_id);
		if (
			$product_id > 0
			&& $this->config->ProductAdviser->number_recently_viewed > 0
			&& $this->xlite->get("HTMLCatalogWorking") != true
		) {
    		$referer = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : "NO_HTTP_REFERER";
            $referer = md5($referer);
            $referers = ($this->session->isRegistered("HTTP_REFERER")) ? $this->session->get("HTTP_REFERER") : array();

            if (!is_array($referers)) {
            	$referers = array();
            }

            if (
				!isset($referers[$product_id])
				|| (isset($referers[$product_id]) && $referers[$product_id] != $referer)
			) {
            	$referers[$product_id] = $referer;
            	$this->session->set("HTTP_REFERER", $referers);

            	$statistic = new XLite_Module_ProductAdviser_Model_ProductRecentlyViewed();
            	$sid = $this->session->getID();
                $statistic->set("sid", $sid);
                $statistic->set("product_id", $product_id);
                $statistic->set("last_viewed", time());

            	if ($statistic->find("sid='$sid' AND product_id='$product_id'")) {
            		$statistic->set("views_number", intval($statistic->get("views_number"))+1);
                    $statistic->update();

            	} else {
            		$statistic->set("views_number", 1);
                    $statistic->create();
            	}
    		}
    	}

		if ($product_id > 0 && $this->xlite->get("HTMLCatalogWorking") == true) {
			$statistic = new XLite_Module_ProductAdviser_Model_ProductRecentlyViewed();
			$statistic->collectGarbage();
			$statistic->cleanCurrentGarbage();
		}

        parent::init();

		if ($this->xlite->PA_InventorySupport && $this->config->ProductAdviser->customer_notifications_enabled) {

			if ($this->getProduct()->getComplex('inventory.amount') == 0 && $this->getProduct()->get("tracking") == 0) {
    			$this->rejectedItemInfo = new XLite_Base();
    			$this->rejectedItemInfo->set("product_id", $this->getProduct()->get("product_id"));
    			$this->rejectedItemInfo->set("product", new XLite_Model_Product($this->getProduct()->get("product_id")));

            	if ($this->isNotificationSaved($this->rejectedItemInfo)) {
        			$this->rejectedItemInfo = null;
            	}
			} else if($this->getProduct()->get("tracking") != 0) {
                if ($this->session->isRegistered("rejectedItem")) {
                	$rejectedItemInfo = $this->session->get("rejectedItem");
                	if ($rejectedItemInfo->product_id != $this->getProduct()->get("product_id")) {
                		$this->rejectedItemInfo = null;
                		$this->session->set("rejectedItem", null);

                	} else {
            			$this->rejectedItemInfo = new XLite_Base();
            			$this->rejectedItemInfo->set("product_id", $rejectedItemInfo->product_id);
            			$this->rejectedItemInfo->set("product", new XLite_Model_Product($rejectedItemInfo->product_id));

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
                			$this->session->set("rejectedItem", null);
                        }
                    }
                }
            }
		}
    }

    function getRejectedItem()
    {
		return ($this->xlite->get('PA_InventorySupport') && $this->config->ProductAdviser->customer_notifications_enabled)
			? $this->rejectedItemInfo
			: null;
	}

	function isNotificationSaved($rejectedItemInfo)
	{
		$result = true;

		if ($this->config->ProductAdviser->customer_notifications_enabled) {

	    	$check = array(
    	    	"type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'"
			);

			$email = '';

			$profile_id = 0;
			if ($this->auth->is("logged")) {
				$profile = $this->auth->get("profile");
    			$profile_id = $profile->get("profile_id");
	    		$email = $profile->get("login");

			} elseif ($this->session->isRegistered("customerEmail")) {
    			$email = $this->session->get("customerEmail");
			}

    	    $check[] = "profile_id = '$profile_id'";
	        $check[] = "email = '$email'";

			$notification = new XLite_Module_ProductAdviser_Model_Notification();
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

    	    $check[] = "notify_key = '" . addslashes($notification->get("productKey")) . "'";

    		$result = $notification->find(implode(' AND ', $check));
		}

		return $result;
	}

	function getPriceNotificationSaved()
	{
		if (!$this->config->ProductAdviser->customer_notifications_enabled) {
			return true;
		}

		if (!isset($this->priceNotified)) {
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
        	$notification->set("product_id", $this->get("product_id"));
            $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

            $check = implode(" AND ", $check);
            $this->priceNotified = $notification->find($check);
		}
    	return $this->priceNotified;
	}

	function isPriceNotificationEnabled()
	{
		return ($this->config->ProductAdviser->customer_notifications_mode & 1) != 0;
	}

	function isProductNotificationEnabled()
	{
		return ($this->config->ProductAdviser->customer_notifications_mode & 2) != 0;
	}

	function action_rp_bulk()
	{
		if (isset($this->rp_bulk) && is_array($this->rp_bulk)) {
			foreach($this->rp_bulk as $product_id => $pended) {
				if ($pended) {
					$request = XLite_Core_Request::getInstance();
					$request->product_id = $product_id;
					$cart = new XLite_Controller_Customer_Cart();
					$cart->init();
					$cart->action_add();
				}
			}

        	if ($this->config->General->redirect_to_cart) {
            	$this->set("returnUrl", "cart.php?target=cart");
	        }
		}
	}

    function isShowBulkAddForm()
    {
		$result = false;

        if (
			$this->config->ProductAdviser->rp_show_buynow
			& $this->config->ProductAdviser->rp_bulk_shopping
		) {
	        $products = (array) $this->getComplex('pager.pageData');
    	    foreach ($products as $p) {
        	    if (!$p->get('product')->checkHasOptions()) {
					$result = true;
					break;
				}
	        }
		}

        return $result;
    }
}


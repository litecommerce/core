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


/**
 * Product controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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

        parent::init();

		if ($this->xlite->PA_InventorySupport && $this->config->ProductAdviser->customer_notifications_enabled) {

			if ($this->getProduct()->getComplex('inventory.amount') == 0 && $this->getProduct()->get("tracking") == 0) {

				// Product is out-of-stock
    			$this->rejectedItemInfo = new XLite_Base();
    			$this->rejectedItemInfo->set("product_id", $this->getProduct()->get("product_id"));
    			$this->rejectedItemInfo->set("product", new XLite_Model_Product($this->getProduct()->get("product_id")));

            	if ($this->isNotificationSaved($this->rejectedItemInfo)) {
        			$this->rejectedItemInfo = null;
            	}

			} elseif ($this->getProduct()->get("tracking") != 0) {

				// Quantity tracking is enabled
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
    	    	"type = '" . CUSTOMER_NOTIFICATION_PRODUCT . "'"
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


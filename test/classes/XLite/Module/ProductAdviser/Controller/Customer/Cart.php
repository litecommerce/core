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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductAdviser_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{
    public $rejectedItemInfo = null;

    /**
     * 'add' action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        parent::action_add();

        if ($this->xlite->get("PA_InventorySupport") && $this->config->ProductAdviser->customer_notifications_enabled) {

            if (!is_null($this->cart->get("outOfStock"))) {

    			$rejectedItemInfo = new StdClass();
            	$rejectedItem = new XLite_Model_OrderItem();
            	$product = $this->get("product");
            	$rejectedItemInfo->product_id = $product->get("product_id");
                $rejectedItem->set("product", $product);

            	if ($this->xlite->get("ProductOptionsEnabled") && $product->hasOptions() && isset($this->product_options)) {
                	$rejectedItem->set("productOptions", $this->product_options);
            		$rejectedItemInfo->productOptions = $rejectedItem->get("productOptions");
                }

                $this->session->set("rejectedItem", $rejectedItemInfo);

    		} elseif (!$this->xlite->get("rejectedItemPresented")) {
            		$this->session->set("rejectedItem", null);
    		}
    	}
    }

    /**
     * getRejectedItem 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRejectedItem()
    {
        if (!($this->xlite->get("PA_InventorySupport") && $this->config->ProductAdviser->customer_notifications_enabled)) {
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
            $this->rejectedItemInfo = new XLite_Base();
            $this->rejectedItemInfo->set("product_id", $rejectedItemInfo->product_id);
            $this->rejectedItemInfo->set("product", new XLite_Model_Product($this->rejectedItemInfo->product_id));
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

    /**
     * isNotificationSaved 
     * 
     * @param mixed $rejectedItemInfo ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNotificationSaved($rejectedItemInfo)
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
        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

        $check = implode(" AND ", $check);

        return $notification->find($check);
    }

    /**
     * isPriceNotificationEnabled 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPriceNotificationEnabled()
    {
        $mode = $this->config->ProductAdviser->customer_notifications_mode;
        return (($mode & 1) != 0) ? true : false;
    }

    /**
     * isProductNotificationEnabled 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isProductNotificationEnabled()
    {
        $mode = $this->config->ProductAdviser->customer_notifications_mode;
        return (($mode & 2) != 0) ? true : false;
    }
}


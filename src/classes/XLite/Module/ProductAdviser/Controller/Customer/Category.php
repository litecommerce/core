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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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

    		if ($this->auth->is('logged')) {
    			$profile = $this->auth->get('profile');
        		$profile_id = $profile->get('profile_id');
        		$email = $profile->get('login');
    		} else {
        		$profile_id = 0;
        		if ($this->session->isRegistered('customerEmail')) {
        			$email = $this->session->get('customerEmail');
        		}
    		}
            $check[] = "profile_id='$profile_id'";
            $check[] = "email='$email'";

    		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    		$notification->set('type', CUSTOMER_NOTIFICATION_PRICE);
        	$notification->set('product_id', $product_id);
            $check[] = "notify_key='" . addslashes($notification->get('productKey')) . "'";

            $check = implode(' AND ', $check);
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

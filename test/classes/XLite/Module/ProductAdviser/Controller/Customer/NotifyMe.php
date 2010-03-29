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
 * Notify me page controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductAdviser_Controller_Customer_NotifyMe extends XLite_Controller_Customer_Abstract
{	
    public $product = null;


	/**
     * Common method to determine current location 
     * 
     * @return array 
     * @access protected 
     * @since  3.0.0
     */      
    protected function getLocation()
    {
        return 'Notify me';
    }

	/**
	 * Initialization 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function init()
	{
		parent::init();

		if (
			$this->session->isRegistered("NotifyMeReturn")
			&& $this->session->isRegistered("NotifyMeInfo")
		) {
			$_REQUEST = $this->session->get("NotifyMeInfo");
			$this->mapRequest($_REQUEST);
			$this->session->set("NotifyMeInfo", null);
			$this->session->set("NotifyMeReturn", null);
		}

		$this->product = $this->getProduct();

		if (!$this->product->isExists()) {
			$this->redirect($this->buildURL('main', '', array('mode' => 'accessDenied')));
			return;
		}

		if ($this->xlite->get("ProductOptionsEnabled") && isset($this->product_options)) {
			$poArr = array();
			foreach ($this->product_options as $class => $po) {
				$poArr[] = array("class" => $class, "option" => $po["option"], "option_id" => $po["option_id"]);
			}
			$this->set("productOptions", $poArr);
			$poStr = array();
			foreach ($this->product_options as $class => $po) {
				$poStr[] = $class . ": " . $po["option"];
			}
			$this->set("productOptionsStr", implode(", ", $poStr));
		}
		$this->set("prevUrl", urlencode($this->url));

		$this->session->set("NotifyMeInfo", XLite_Core_Request::getInstance()->getData());

		if (!$this->auth->is("logged")) {
			$this->set("email", $this->session->get("customerEmail"));
		}
	}

	/**
	 * notify_product action
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function action_notify_product()
	{
		if (!$this->isProductNotificationEnabled()) {
			return;
		}

		$request = XLite_Core_Request::getInstance();

        if (
			!isset($request->email)
			|| (isset($request->email) && strlen(trim($request->email)) == 0)
		) {
			$this->set("valid", false);
			return;
		}
		$email = trim($request->email);

		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    	$check = array();
		$notification->set("type", CUSTOMER_NOTIFICATION_PRODUCT);
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";

		if ($this->auth->is("logged")) {
			$profile = $this->auth->get("profile");
    		$notification->set("profile_id", $profile->get("profile_id"));
    		$notification->set("person_info", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));
			$notification->set("email", $profile->get("login"));

		} else {
			$notification->set("email", $email);
    		$this->session->set("customerEmail", $email);
    		$notification->set("person_info", $this->person_info);
		}

        $check[] = "profile_id='" . $notification->get("profile_id") . "'";
        $check[] = "email='" . $notification->get("email") . "'";

    	$notification->set("product_id", $this->product_id);
        /* TODO - it must affected with xlite_inventories.inventory_id and rejectedItemInfo 
            from XLite_Module_ProductAdviser_Controller_Customer_Product but ... it's not work correctly

    	if (isset($request->product_options)) {
    		$notification->set("product_options", $request->product_options);
    	}
        */

    	if (isset($request->amount)) {
    		$notification->set("quantity", $request->amount);
    	}

        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

    	if (!$notification->find(implode(' AND ', $check))) {
    		$notification->set("notify_key", addslashes($notification->get("productKey")));
    		$notification->set("date", time());
    		$notification->create();
    	}

		$this->session->set("rejectedItem", null);

		$this->set("returnUrl", urldecode($this->url));
	}

	/**
	 * notify_price action
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function action_notify_price()
	{
		if (!$this->isPriceNotificationEnabled() || !$this->isComplex('product.priceNotificationAllowed')) {
			return;
		}

		$request = XLite_Core_Request::getInstance();

        if (
			!isset($request->email)
			|| (isset($request->email) && strlen(trim($request->email)) == 0)
		) {
			$this->set("valid", false);
			return;
		}
		$email = trim($request->email);

		$notification = new XLite_Module_ProductAdviser_Model_Notification();
    	$check = array();
		$notification->set("type", CUSTOMER_NOTIFICATION_PRICE);
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";

		if ($this->auth->is("logged")) {
			$profile = $this->auth->get("profile");
    		$notification->set("profile_id", $profile->get("profile_id"));
    		$notification->set("email", $profile->get("login"));
    		$notification->set("person_info", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));

		} else {
    		$notification->set("email", $email);
    		$this->session->set("customerEmail", $email);
    		$notification->set("person_info", $this->person_info);
		}

        $check[] = "profile_id='" . $notification->get("profile_id") . "'";
        $check[] = "email='" . $notification->get("email") . "'";

    	$notification->set("product_id", $this->product_id);
        $check[] = "notify_key='" . addslashes($notification->get("productKey")) . "'";

        $check = implode(' AND ', $check);
    	if (!$notification->find($check)) {
    		$notification->set("notify_key", addslashes($notification->get("productKey")));
    		$notification->set("price", $request->product_price);
    		$notification->set("date", time());

    		$notification->create();
    	}

		$this->set("returnUrl", urldecode($this->url));
	}

	/**
	 * Check - price notification enabled or not 
	 * 
	 * @return boolean
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	function isPriceNotificationEnabled()
	{
		return ($this->config->ProductAdviser->customer_notifications_mode & 1) != 0;
	}

	/**
	 * Check - product notification enabled or not
	 * 
	 * @return boolean
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	function isProductNotificationEnabled()
	{
		return ($this->config->ProductAdviser->customer_notifications_mode & 2) != 0;
	}
}

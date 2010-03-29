<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * XLite_Controller_Customer_Login 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Controller_Customer_Login extends XLite_Controller_Customer_Abstract
{
	/**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Authentication';
    }


    public $params = array("target", "mode");

    protected $profile = null;

    function action_login()
    {
        $this->profile = $this->auth->login(XLite_Core_Request::getInstance()->login, XLite_Core_Request::getInstance()->password);

        if ($this->profile === ACCESS_DENIED) {
            $this->set("valid", false);
            return;
        }

        $this->set("returnUrl", XLite_Core_Request::getInstance()->returnUrl);

        if (!$this->get("returnUrl")) {
            $cart = XLite_Model_Cart::getInstance();
            $url = $this->getComplex('xlite.script');
            if (!$cart->get("empty")) {
                $url .= "?target=cart";
            }

            $this->set("returnUrl", $url);
        }

		$cart = XLite_Model_Cart::getInstance();
		$cart->set("profile_id", $this->profile->get("profile_id"));

		$this->recalcCart();
    }

    function getShopUrl($url, $secure = false, $pure_url = false)
    {
        $add = (strpos($url, '?') ? '&' : '?') . 'feed='.$this->get("action");
        return parent::getShopUrl($url . $add, $secure);
    }

    function action_logoff()
    {
        $this->auth->logoff();
        $this->returnUrl = $this->getComplex('xlite.script');
        if (!$this->getCart()->get("empty")) {
        	if ($this->config->getComplex('Security.logoff_clear_cart') == "Y") {
            	$this->getCart()->delete();
        	} else {
				$this->recalcCart();
        	}
        }
    }

    function getSecure()
    {
        if ($this->get("action") == "login") {
            return $this->getComplex('config.Security.customer_security');
        }
        return false;
    }
}


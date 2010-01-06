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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Login extends XLite_Controller_Abstract
{
    var $params = array("target", "mode");

    protected $profile = null;

    function action_login()
    {
        $this->profile = $this->auth->login($_POST["login"], $_POST["password"]);

        if ($this->profile == ACCESS_DENIED) {
            $this->set("valid", false);
            return;
        }   
        if (is_null($this->get("returnUrl"))) {
            $cart = XLite_Model_Cart::getInstance();
            $url = $this->get("xlite.script");
            if (!$cart->get("empty")) {
                $url .= "?target=cart";
            }
            $this->set("returnUrl", $url);
        }

		$cart = XLite_Model_Cart::getInstance();
		$cart->set("profile_id", $this->profile->get("profile_id"));

		$this->recalcCart();
    }

    function shopURL($url, $secure = false)
    {
        $add = (strpos($url, '?') ? '&' : '?') . 'feed='.$this->get("action");
        return parent::shopURL($url . $add, $secure);
    }

    function action_logoff()
    {
        $this->auth->logoff();
        $this->returnUrl = $this->get("xlite.script");
        if (!$this->cart->get("empty")) {
        	if ($this->config->get("Security.logoff_clear_cart") == "Y") {
            	$this->cart->delete();
        	} else {
				$this->recalcCart();
        	}
        }
    }

    function getSecure()
    {
        if ($this->get("action") == "login") {
            return $this->get("config.Security.customer_security");
        }
        return false;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

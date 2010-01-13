<?php

class XLite_Module_GreetVisitor_Controller_Customer_Main extends XLite_Controller_Customer_Main implements XLite_Base_IDecorator
{
    function isGreetVisitor()
    {
        if (isset($_COOKIE['GreetingCookie'])) {
            $this->set("disable_head", true); // disable welcome message head
            $this->set("visitor", $_COOKIE['GreetingCookie']);
            return true;
        }
        return false;
    }

    function startPage()
    {
        parent::startPage();

		$options = XLite::getInstance()->getOptions('host_details');
		$auth    = XLite_Model_Auth::getInstance();

        if ($auth->isLogged()) {
            $first_last = $auth->get('profile.billing_firstname') . ' ' . $auth->get('profile.billing_lastname');
            setcookie("GreetingCookie", $first_last, time() + 3600 * 24 * 180, "/", func_parse_host($options['http_host']));
			setcookie("GreetingCookie", $first_last, time() + 3600 * 24 * 180, "/", func_parse_host($options['https_host']));
        }
    }
    
    function action_nogreeting()
    {
		$options = XLite::getInstance()->getOptions('host_details');

        setcookie("GreetingCookie", "", time() - 3600 * 24 * 180, "/", func_parse_host($options['http_host']));
		setcookie("GreetingCookie", "", time() - 3600 * 24 * 180, "/", func_parse_host($options['https_host']));
    }

}


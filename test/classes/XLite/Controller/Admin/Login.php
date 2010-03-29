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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Class description.
*
* @package $Package$
* @version $Id$
*/
class XLite_Controller_Admin_Login extends XLite_Controller_Admin_Abstract
{	
    public $returnUrl = "admin.php";

	/**
     * getRegularTemplate
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        return 'login.tpl';
    }


	public function getAccessLevel()
    {
        return XLite_Model_Auth::getInstance()->getCustomerAccessLevel();
    }

	function fillForm()
	{
		parent::fillForm();
		$login = $this->get("login");
		if ( empty($login) )
			$this->set("login", $this->auth->remindLogin());
	}

    function action_login()
    {
        $profile = $this->auth->adminLogin($_POST["login"], $_POST["password"]);

        if (is_int($profile) && ACCESS_DENIED === $profile) {
            $this->set("valid", false);
            $this->set("mode", "access_denied");
        } else {
        	$returnUrl = "admin.php";
        	if ($this->xlite->session->isRegistered("lastWorkingURL")) {
        		$returnUrl = $this->xlite->session->get("lastWorkingURL");
        		$this->xlite->session->set("lastWorkingURL", null);
        	}
            $this->set("returnUrl", $returnUrl);
        }

        $this->initSBStatuses();
    }

    function action_logoff()
    {
        $this->auth->logoff();
        $this->clearSBStatuses();
    }

    function initSBStatuses()
    {
		if ($this->auth->is("logged")) 
		{
        	$profile = $this->auth->get("profile");
        	if (!is_object($profile)) {
        		return;
        	}

            $sidebar_box_statuses = $profile->get("sidebar_boxes");

        	if (strlen($sidebar_box_statuses) > 0)
        	{
        		$sidebar_box_statuses = unserialize($sidebar_box_statuses);
            	$this->session->set("sidebar_box_statuses", $sidebar_box_statuses);
            	$this->session->writeClose();
        	}
        	else
        	{
        		$profile->set("sidebar_boxes", serialize($this->session->get("sidebar_box_statuses")));
        		$profile->update();
        	}
        }
    }
    
    function clearSBStatuses()
    {
    	$this->session->set("sidebar_box_statuses", null);
    	$this->session->writeClose();
    }
    
    function getSecure()
    {
        if ($this->session->get("no_https")) {
            return false;
        }
        return $this->getComplex('config.Security.admin_security');
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

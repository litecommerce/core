<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
*
* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4:
*/

/**
* @package Base
* @access public
* @version $Id: Dialog.php,v 1.1 2006/07/11 06:38:03 sheriff Exp $
*/
class Admin_Dialog extends Dialog
{
    function getAccessLevel()
    {
        return $this->auth->get("adminAccessLevel");
    }    

    function handleRequest()
    {
        // auto-login request
        if (!$this->auth->is("logged") && isset($_POST["login"]) && isset($_POST["password"])) {
            if($this->auth->adminLogin($_POST["login"], $_POST["password"]) == ACCESS_DENIED) {
                die("ACCESS DENIED");
            }
        }
        if (!$this->auth->isAuthorized($this)) {
			$this->xlite->session->set("lastWorkingURL", $this->get("url"));
            $this->redirect("admin.php?target=login");
        } else {
            parent::handleRequest();
        }
    }

    function getSecure()
    {
        if ($this->session->get("no_https")) {
            return false;
        }
        return $this->get("config.Security.admin_security");
    }

    function &getRecentAdmins()
    {
        if ($this->auth->isLogged() && is_null($this->recentAdmins)) {
            $profile =& func_new("Profile");
            $this->recentAdmins =& $profile->findAll("access_level>='".$this->get("auth.adminAccessLevel")."' AND last_login>'0'", "last_login ASC", null, "0, 7");
        }    
        return $this->recentAdmins;
    }

	function getCharset()
	{
		return $this->xlite->config->Company->locationCountry->get("charset");
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

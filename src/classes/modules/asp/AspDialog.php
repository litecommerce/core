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
* Class Dialog_
*
* @package Base
* @access public
* @version $Id: AspDialog.php,v 1.19 2009/03/04 08:23:31 fundaev Exp $
*/
class AspDialog extends Dialog
{
    var $template = "modules/asp/main.tpl";
	var $pages = array(
		"shops" 	=> "Shops",
		"modules" 	=> "Modules",
		"license"	=> "License",
		"options"	=> "Settings",
        "profiles"  => "Access policy",
        "defaultShop" => "Default shop",
	);
	var $icons = array(
		"shops"		=> "icon_shops.gif",
		"modules"	=> "icon_modules.gif",
		"license"	=> "icon_license.gif",
		"options"	=> "icon_settings.gif",
		"profiles"	=> "icon_access_policy.gif",
		"defaultShop"	=> "icon_default_shop.gif",
	);

	var $page = "shops";

	function handleRequest()
	{
		if (!$this->auth->is('logged')) {
            if (isset($_POST["login"]) && isset($_POST["password"])) {
                if ($this->auth->adminLogin($_POST["login"], $_POST["password"]) == ACCESS_DENIED) {
                    die("LOGIN INCORRECT");
                }
            } else {
				$url = $this->shopURL('cpanel.php?target=login', $this->get("secure"));
				$url = preg_replace("/\&XSID=[\d\w]+/si", "", $url);	// get pure URL
				header("Location: " . $url);
    			return;
            }    
		}
		parent::handleRequest();
	}

    function getSecure()
    {
        if ($this->session->get("no_https")) {
            return false;
        }
        return $this->get("xlite.aspConfig.Security.cp_security");
    }

	function getTabberUrl()
	{
		return $this->get("url");
	}

	function getPageIcon($page=null)
	{
		if (is_null($page)) {
			$page = $this->get("page");
		}

		if (isset($this->icons[$page])) {
			return $this->icons[$page];
		}
		return null;
	}

	function getTemplate()
	{
		$template = parent::getTemplate();
		if ($this->xlite->is("aspZone") && $this->get("config.General.add_on_mode")) {
			$template = $this->template;
		}
		return $template;
	}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

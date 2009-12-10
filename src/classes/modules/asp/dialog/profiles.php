<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
* @version $Id: profiles.php,v 1.10 2008/10/23 12:05:47 sheriff Exp $
*/
class AspDialog_profiles extends AspDialog
{
	var $params = array('target', 'profile_name');
    var $profile_name = null;
    var $orderby = 10;
	
    // see kernel/AppProfile.php for policy details
    
	function &getPageTemplate()
	{
		return "modules/asp/profiles.tpl";
	}

	function fillForm()
	{
        if (!isset($this->profile_name)) {
            $profile =& func_new("AspProfile");
        	$profiles =& $profile->findAll();
        	if (is_array($profiles)) {
        		$this->profile_name = $profiles[0]->get("name");
        	}
        }

		parent::fillForm();
	}

    function &getProfile()
    {
        if (is_null($this->profile)) {
            $this->profile =& func_new("AspProfile", $this->profile_name);
        }
        return $this->profile;
    }
    
    function action_update_profile()
    {
        $profile =& $this->get("profile");
        $profile->set("rules", isset($_POST["rules"]) ? implode(",", $_POST["rules"]) : "");
        $profile->update();
    }

    function action_add_profile()
    {
        // validate form data
        $profile =& func_new('AspProfile');
        if ($profile->find("name='$_POST[name]'")) {
            $this->set("profileExists", true);
            $this->set("valid", false);
            return;
        }
        $profile =& func_new('AspProfile');
        $profile->set("properties", $_POST);
        $profile->create();
        $this->set("profile_name", $_POST["name"]); // switch to modify profile
    }

    function action_delete_profile()
    {
        $profile =& $this->get("profile");
        $profile->delete();
        $this->set("profile_name", null);
    }

    function isActiveRule($rules, $rule)
    {
        return strpos($rules, $rule) !== false;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

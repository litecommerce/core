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
* @version $Id: options.php,v 1.14 2008/10/23 12:05:46 sheriff Exp $
*/
class AspDialog_options extends AspDialog
{
	var $params = array("target", "adminLoginUpdated", "rootLoginUpdated","mysqlLoginUpdated", "section");
    var $_sections = null;
    var $section = null;

	function fillForm() // {{{
	{
        if (!isset($this->section)) {
        	$sections =& $this->getSections();
        	if (is_array($sections)) {
        		$sections = array_keys($sections);
        		$this->section = $sections[0];
        	}
        }

		parent::fillForm();

		$this->set("mysql_login", $this->xlite->get("options.database_details.username"));
	} // }}}
	
	function &getPageTemplate() // {{{
	{
		return "modules/asp/options.tpl";
	} // }}}

    // GENERAL SETTINGS METHODS {{{
    function &getSettings()
    {
        return func_new("AspConfig");
    }

    function &getSections()
    {
    	if (is_null($this->_sections)) {
            $categories = $this->get("settings.categories");
            $names = $this->get("settings.categoryNames");
            $pages = array();
            $settings = $this->get("settings");
            for ($i = 0; $i < count($categories); $i++) {
    			if (count($settings->getByCategory($categories[$i])) > 0) {
                	$pages[$categories[$i]] = $names[$i];
                }
            }
            $this->_sections = $pages;
        }
        return $this->_sections;
    }

    function &getOptions()
    {
        $settings = $this->get("settings");
        return $settings->getByCategory($this->section);
    }

    function action_update_options()
    {
        $options =& $this->get("options");
        for ($i=0; $i<count($options); $i++) {
            $name = $options[$i]->get("name");
            $type = $options[$i]->get("type");
            if ($type=='checkbox') {
                if (empty($_REQUEST[$name])) {
                    $val = 'N';
                } else {
                    $val = 'Y';
                }
            } elseif ($type == "serialized" && is_array($_POST[$name])) {
                $val = serialize($_POST[$name]);
            } else {
                $val = trim($_REQUEST[$name]);
            }
            $options[$i]->set("value", $val);
        }

        // optional validation goes here

        // write changes on success
        for ($i=0; $i<count($options); $i++) {
            $options[$i]->update();
        }
    }
    // GENERAL SETTINGS METHODS }}}
    
	function action_update_profile() // {{{
	{
		$profile =& $this->auth->get('profile');
		$profile->set('login', $_REQUEST["login"]);
		$profile->set('password', $_REQUEST["password"]);
		$this->auth->modify($profile);
		$this->set("adminLoginUpdated", true);
	} // }}}

	function action_mysql_update_root() // {{{
	{
		$config =& func_new('AspConfig');
        $config->createOption("MySQL", "root_user", $_REQUEST['mysql_root_user']);
        $config->createOption("MySQL", "root_password", $_REQUEST['mysql_root_password']);
		$this->set("rootLoginUpdated", true);
	} // }}}

    function action_mysql_remove_root() // {{{
    {
		$config =& func_new('AspConfig');
        if ($config->find("name='root_user' AND category='MySQL'")) {
            $config->delete();
        }
		$config =& func_new('AspConfig');
        if ($config->find("name='root_password' AND category='MySQL'")) {
            $config->delete();
        }
    } // }}}

	function action_mysql_update() // {{{
	{
		$configFile = "etc/config.php";
		if (file_exists("etc/config.local.php")) {
			$configFile = "etc/config.local.php";
		}
		if (is_writable($configFile)) {
			if (isset($_POST["root_user"])) {
				$rootUser = $_POST["root_user"];
				$rootPassword = $_POST["root_password"];
			} else {
				// read from config
				$rootUser = $this->get("xlite.aspConfig.MySQL.root_user");
				$rootPassword = $this->get("xlite.aspConfig.MySQL.root_password");
			}
			$host = $this->xlite->get("options.database_details.hostspec");
			if(!($cnn = @mysql_connect($host, $rootUser, $rootPassword))) {
				$this->error = "Can't connect to $host as $rootUser: check your login & password.";
				$this->valid = false;
				return;
			}
			if (!mysql_select_db("mysql", $cnn)) {
				$this->error = mysql_error();
				$this->valid = false;
				return;
			}
			@mysql_query("SET sql_mode='MYSQL40'", $cnn);
			mysql_query($sql = "update user set password=PASSWORD('".addslashes($_POST["mysql_password"])."') where user='".addslashes($this->xlite->get("options.database_details.username"))."'", $cnn);
			mysql_query("flush privileges", $cnn);
			mysql_select_db($this->xlite->get("options.database_details.database"));
			// change config.php
			$config = file_get_contents($configFile);
			$mysqlPassword = $_POST["mysql_password"];
			$encPassword = text_crypt($mysqlPassword);
			$config = preg_replace("/^password *=.*\$/m", "password = \"$encPassword\"", $config);
			$config = preg_replace("/^emergency_password.*\$/m", "", $config);
			$fd = fopen($configFile, "wb");
			fwrite($fd, $config);
			fclose($fd);
		} else {
			$this->error = "Can't write etc/config.php; please change permissions.";
			$this->valid = false;
		}
		if ($this->valid) {
			$this->set("mysqlLoginUpdated", true);
		}
	} // }}}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

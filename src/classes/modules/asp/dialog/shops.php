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
* @version $Id: shops.php,v 1.43 2008/11/27 11:34:05 vgv Exp $
*/
class AspDialog_shops extends AspDialog
{
    var $params = array("target", "filter", "enabled", "shopname", "profile_name", "mode", "shop_id");
    var $installedShops = null;
	var $schemas_repository = "schemas";

	function init()
	{
		parent::init();

		if (intval($this->get("itemsPerPage")) > 0) {
			$config =& func_new('AspConfig');
			$config->createOption("LookFeel", "shops_per_page", intval($this->get("itemsPerPage")));
		} else {
			$value = $this->get("xlite.aspConfig.LookFeel.shops_per_page");
			$this->set("itemsPerPage", max(5, $value));
		}

	}

	function &getPageTemplate() // {{{
	{
		return "modules/asp/shops.tpl";
	} // }}}

	function &getInstalledShops() // {{{
	{
		$shop =& func_new("AspShop");
        if (is_null($this->installedShops)) {
			$filter = addslashes(trim($this->get("filter")));
			$shopname = addslashes(trim($this->get("shopname")));
			$enabled = addslashes(trim($this->get("enabled")));

			$where = "";
			if ($filter || $shopname) {
				$where .= "(";
				$filter_added = false;
				if ($filter) {
					$where .= "url LIKE '%".$filter."%'";
					$filter_added = true;
				}
				if ($shopname) {
					$where .= ((!$filter_added) ? "" : " OR ")."name LIKE '%".$shopname."%'";
				}
				$where .= ")";
			}
			if (isset($enabled) && strlen($enabled)) {
				$where .= (empty($where)?"":" AND ") . "enabled='".$enabled."'";
			}

    		$this->installedShops = $shop->findAll($where);
    		if (isset($this->profile_name) && strlen($this->profile_name) >0) {
    			foreach($this->installedShops as $shop_key => $shop) {
    				if ($shop->get("profile") != $this->profile_name) {
    					unset($this->installedShops[$shop_key]);
    				}
    			}
    		}
        }
        return $this->installedShops;
	} // }}}

	function &getCurrentShop() // {{{
	{
		return func_new("AspShop", $_REQUEST["shop_id"]);
	} // }}}

	function &getModules() // {{{
	{
		$dialog =& func_new("AspDialog_modules");
		return $dialog->getModules();
	} // }}}

	function getSortModules($type=0)
	{
		$dialog =& func_new("AspDialog_modules");
		return $dialog->getSortModules($type);
	}

	function isModuleInstalled($module_name) // {{{
	{
		$shop =& func_new("AspShop", $_REQUEST["shop_id"]);
		if (in_array($module_name, $shop->get('installedModules'))) {
			return true;
		}
		return false;
	} // }}}

	function handleRequest()
	{

		if ($this->get("mode") == "configure") {
			$shop = $this->get("currentShop");

			$config_err = "";
			if (!file_exists($shop->get("configFile"))) {
				$config_err = "not_found";
			} elseif (!is_readable($shop->get("configFile"))) {
				$config_err = "not_read";
			}
			$this->set("config_err", $config_err);

			$diff = array();
			if (!$config_err) {
				$config = $shop->get("localConfig");

				// primary path
				if (text_decrypt($config["primary_installation"]["path"]) != $this->get("primaryPath")) {
					$diff["primary_path"] = text_decrypt($config["primary_installation"]["path"]);
				}

				// database.name
				if ($config["database_details"]["database"] != $shop->get("db_name")) {
					$diff["db_name"] = $config["database_details"]["database"];
				}

				// database.username
				if ($config["database_details"]["username"] != $shop->get("db_username")) {
					$diff["db_username"] = $config["database_details"]["username"];
				}

				// database.password
				if ($config["database_details"]["password"] != $shop->get("db_password")) {
					$diff["db_password"] = $config["database_details"]["password"];
				}

				// shop URL
				if ("http://".$config["host_details"]["http_host"].$config["host_details"]["web_dir"] != $shop->get("url")) {
					$diff["url"] = "http://".$config["host_details"]["http_host"].$config["host_details"]["web_dir"];
				}

				// shop URL
				if ("https://".$config["host_details"]["https_host"].$config["host_details"]["web_dir"] != $shop->get("secure_url")) {
					$diff["secure_url"] = "https://".$config["host_details"]["https_host"].$config["host_details"]["web_dir"];
				}

			}
			$this->set("config_diff", ((is_array($diff) && count($diff) > 0) ? $diff : null));
		}

		parent::handleRequest();
	}

    // fill in the installation form
    function fillForm() // {{{
    {
        parent::fillForm();
        // generate passwords
        $passwd = generate_code();
        $this->set("shop_db_password", $passwd);
        $this->set("shop_password", $passwd);
        $this->set("shop_password_confirm", $passwd);
    } // }}}

	function process_urls($url, $surl)
	{
		$url = preg_replace("/\/*$/", "", $url);
		$surl = preg_replace("/\/*$/", "", $surl);

		$parsed_url = parse_url($url);
		$parsed_surl = parse_url($surl);

		$info = array(
			"http_host"     => $parsed_url["host"],
			"https_host"    => $parsed_surl["host"],
			"web_dir"       => $parsed_url["path"]
		);

		if (!preg_match("/".preg_quote($parsed_url["path"], "/")."$/", $parsed_surl["path"])) {
			return false;
		}

		$info["https_host"] .= preg_replace("/".preg_quote($parsed_url["path"], "/")."$/", "", $parsed_surl["path"]);

		return $info;
	}

	function action_install() // {{{
	{
		// Return if validators fail
		if (!$this->is("valid")) {
			return;
		}

		// Admin's input data validation
		$post = array();
		$fields_replicate = array("shop_url", "shop_secure_url", "shop_path", "root_user", "root_password", "shop_db_database", "shop_db_database_usage", "shop_db_user", "shop_db_user_usage", "shop_db_password", "shop_profile", "shop_modules", "shop_user", "shop_password", "shop_password_confirm", "skin_layout", "memory_limit", "name");
		$fields_slash = array("shop_path", "root_user", "root_password", "shop_db_database", "shop_db_user", "shop_db_password", "shop_profile", "shop_user", "shop_password", "shop_password_confirm");

		foreach ($_POST as $key=>$value) {
			if (!in_array($key, $fields_replicate)) {
				continue;
			}

			if (in_array($key, $fields_slash)) {
				$value = addslashes($value);
			}

			$post[$key] = $value;
		}

		if (!$post["skin_layout"]) {
			$post["skin_layout"] = $this->get("xlite.config.Skin.skin");;
		}

        // validate form data
		$shop =& func_new("AspShop");

		$shop_url = addslashes($post["shop_url"]);

		// check URL protocol, should be the HTTP
		$parsed_url = parse_url($post["shop_url"]);
		if (strtolower($parsed_url["scheme"]) != "http") {
			$this->handleError("urlWrong", true);
			$GLOBALS["retcode"] = SHOP_URL_WRONG;
			return;
		}

		// check shop HTTP URL exists
		if ($shop->find("url='$shop_url'")) {
			$this->handleError("urlExists", true);
			$GLOBALS["retcode"] = SHOP_URL_EXISTS;
			return;
		}


		$shop_secure_url = "";
		if ($post["shop_secure_url"]) {
			$shop_secure_url = addslashes($post["shop_secure_url"]);
		} else {
			$shop_secure_url = preg_replace("/^http:\/\//", "https://", $shop_url);
		}

		// check secure URL protocol, should be the HTTPS
		$parsed_surl = parse_url($shop_secure_url);
		if (strtolower($parsed_surl["scheme"]) != "https") {
			$this->handleError("secureUrlWrong", true);
			$GLOBALS["retcode"] = SHOP_URL_WRONG;
			return;
		}

		// check shop HTTPS URL exists
		if ($shop->find("secure_url='$shop_secure_url'")) {
			$this->handleError("secureUrlExists", true);
			$GLOBALS["retcode"] = SHOP_URL_EXISTS;
			return;
		}

		$shop_url_info = $this->process_urls($shop_url, $shop_secure_url);
		if ($shop_url_info === false) {
			$this->handleError("secureUrlMismatch", true);
			$GLOBALS["retcode"] = SHOP_URL_WRONG;
			return;
		}

		$shop_path = addslashes($post["shop_path"]);

		// check shop installation path
		if (!LC_OS_IS_WIN) {
    		if (realpath($post["shop_path"]) !== false && realpath($post["shop_path"]) != $post["shop_path"]) {
    			$this->handleError("pathSymLink", true);
    			$GLOBALS["retcode"] = SHOP_PATH_SYMLINK;
    			return;
    		}
    	} else {
    		$shop_path = $post["shop_path"];
    		if (@is_dir($shop_path)) {
        		if (realpath($shop_path) === false) {
        			$this->handleError("pathSymLink", true);
        			$GLOBALS["retcode"] = SHOP_PATH_SYMLINK;
        			return;
        		}
        		$realpath = strtolower(realpath($shop_path));
        		$shop_path = str_replace("/", "\\", $shop_path);
        		if ($shop_path != $realpath) {
        			$this->handleError("pathSymLink", true);
        			$GLOBALS["retcode"] = SHOP_PATH_SYMLINK;
        			return;
        		}
        	}
    	}
		if ($shop->find("path='$shop_path'")) {
			$this->handleError("pathExists", true);
			$GLOBALS["retcode"] = SHOP_PATH_EXISTS;
			return;
		}

		// get root username & password
        if (isset($_POST["root_user"])) {
            $rootUser = $post["root_user"];
            $rootPassword = $post["root_password"];
        } else {
            // read from config
            $rootUser = $this->get("xlite.aspConfig.MySQL.root_user");
            $rootPassword = $this->get("xlite.aspConfig.MySQL.root_password");
        }

		$shop_db_database_usage = (($post["shop_db_database_usage"] == "exists") ? DATABASE_USAGE_EXIST : DATABASE_USAGE_CREATE);
		$shop_db_user_usage = (($post["shop_db_user_usage"] == "exists") ? DATABASE_USAGE_EXIST : DATABASE_USAGE_CREATE);

		// check MySQL user name exists
		if ($rootUser == $post["shop_db_user"] && $shop_db_user_usage == DATABASE_USAGE_CREATE) {
			$this->handleError("dbUserExists", true);
			$GLOBALS["retcode"] = SHOP_DB_USERNAME_EXISTS;
			return;
		}


        // install store
		$this->startDump();
		$shop =& func_new("AspShop");
		$shop->set("memory_limit", $post["memory_limit"]);
		$shop->set("name", $post["name"]);
		$shop->set("url", $shop_url);
		$shop->set("secure_url", $shop_secure_url);
		$shop->set("path", $shop_path);
		$shop->set("profile", $post["shop_profile"]);

		if (!$shop->install($rootUser, $rootPassword, $post["shop_db_database"], $shop_db_database_usage, $post["shop_db_user"], $shop_db_user_usage, $shop_url_info, $post["shop_db_password"], $post["shop_user"], $post["shop_password"], $post["skin_layout"])) {
			$this->handleError("error", $shop->get("error"));

			// Perform rollback actions
			if ($shop->_shop_path_created ||$shop->_mysql_db_created || $shop->_mysql_username_created) {
				print "Rolling back installation...";
				if ($shop->_shop_path_created) {
					// rollback file stucture
					@unlinkRecursive($shop->get('path'));
				}
				if ($shop->_mysql_db_created || $shop->_mysql_username_created) {
					$database = $shop->get("localConfig.database_details.database");
					$username = $shop->get("localConfig.database_details.username");
					$userpassword = $shop->get("localConfig.database_details.password");

					$cnn = mysql_connect($this->xlite->get("options.database_details.hostspec"), $rootUser, $rootPassword);
					if ($shop->selectDatabase($database, $cnn)) {
						if ($shop->_mysql_db_created) {
							// rollback database
							mysql_query("DROP DATABASE `" . $database . "`", $cnn);
						}

						if ($shop->_mysql_username_created && $shop->selectDatabase("mysql", $cnn)) {
							// rollback username
							mysql_query("DELETE FROM db WHERE user='" . $username ."'", $cnn);
							mysql_query("DELETE FROM tables_priv WHERE user='" . $username ."'", $cnn);
							mysql_query("DELETE FROM columns_priv WHERE user='" . $username ."'", $cnn);
							mysql_query("DELETE FROM user WHERE user='" . $username ."'", $cnn);
						}
					}

					$shop->selectDatabase($this->xlite->get("options.database_details.database"), $cnn);
					$cnn = null;
				}
				print "[<font color=green>OK</font>]<br>\n";
			}

			$url_substring = "&mode=install";
?>

<p>ASP Control Panel was unable to install a new customer store<br> at the following URL: <b><?php echo $shop_url; ?></b>
<p>Please correct the error(s) above and try again.
<?php
        } else {
            if ($this->get("save_password")) {
                $config =& func_new('AspConfig');
                $config->createOption("MySQL", "root_user", $rootUser);
                $config->createOption("MySQL", "root_password", $rootPassword);
            }

            // install modules
            if (isset($post["shop_modules"])) {
                $shop->set('installedModules', $post["shop_modules"]);
                $shop->update();
            }

			$url_substring = "";
?>

<p>A new customer store has been successfully installed at the following URL: 
<a href="<?php echo $shop_url; ?>/cart.php" target="_blank"><b><u><?php echo $shop_url; ?></u></b></a>
<?php
		}
?>
<p><a href="cpanel.php?target=shops<?php echo $url_substring; ?>">Click here to return to Control Panel</a>

<?php
	} // }}}

	function action_update() // {{{
	{
		$shop =& func_new('AspShop', $_POST["shop_id"]);
        $shop->set("properties", $_POST);
		$shop->update();
	} // }}}

	function action_update_modules() // {{{
	{
		$modulesOff = array();
		foreach((array)$_REQUEST["installed_modules"] as $m_id) {
			if (!in_array($m_id, (array)$_REQUEST["active_modules"])) {
				$modulesOff[] = $m_id;
			}
		}

		$shop =& func_new('AspShop', $_POST["shop_id"]);
		if (!isset($_REQUEST['module_status'])) {
			$_REQUEST['module_status'] = array();
		}	

		$installedModules = array();
		foreach ((array)$shop->get("installedModules") as $v) {
			if (in_array($v, $modulesOff))
				continue;
			
			$installedModules[] = $v;
		}

		foreach ((array)$_REQUEST["active_modules"] as $v) {
			$installedModules[] = $v;
		}

		$shop->set('installedModules', (array)$installedModules);
		$shop->update();

		$this->set("silent", true);
?>
<br>
<hr>
<br>
<p>The list of modules in the customer store has been updated...</p>
<p><a href="cpanel.php?target=shops">Click here to return to Control Panel</a></p>

<?php
	} // }}}

	function action_uninstall() // {{{
	{
        if (isset($_POST["shop_id"])) {
    		$shop =& func_new('AspShop', $_POST["shop_id"]);
        } elseif (isset($_POST["shop_url"])) {
            $shop =& func_new('AspShop');
            $result = $shop->find("url='".addslashes($_POST["shop_url"])."'");
            if (!$result) {
                $GLOBALS["retcode"] = SHOP_URL_NOT_FOUND;
                return;
            }    
        }

		if (isset($_POST["root_user"])) {
			$rootUser = $_POST["root_user"];
			$rootPassword = $_POST["root_password"];
		} else {
            // read from config
            $rootUser = $this->get("xlite.aspConfig.MySQL.root_user");
            $rootPassword = $this->get("xlite.aspConfig.MySQL.root_password");
		}

		$shop->uninstall($rootUser, $rootPassword, isset($_POST['remove_files']), isset($_POST['remove_database']));

        if ($this->get("save_password")) {
            $config =& func_new('AspConfig');
            $config->createOption("MySQL", "root_user", $rootUser);
            $config->createOption("MySQL", "root_password", $rootPassword);
        }
	} // }}}

	function action_synchronize()
	{
		$shop = $this->get("currentShop");

		$config_err = "";
		if (!file_exists($shop->get("configFile"))) {
			$this->handleError("config_err", "config_not_found");
			return;
		} elseif (!is_writeable($shop->get("configFile"))) {
			$this->handleError("config_err", "config_not_write");
			return;
		}

		$shop->synchronizeConfig();
	}

	function handleError($field, $value, $valid=false)
	{
		if (is_array($this->params) && !in_array($field, $this->params)) {
			$this->params[] = $field;
		}

		$this->set($field, $value);
		$this->set("valid", $valid);
	}

	function inc($v) // {{{
    {
		return $v + 1;
	} // }}}

    function inShopModules($name) // {{{
    {
        return is_array($this->shop_modules) && in_array($name, $this->shop_modules);
    } // }}}

	function getLayoutSkinList()
	{

		$files = array();
		if ($dir = @opendir($this->schemas_repository."/templates")) {
			$orig_files = array();
			while (($file = readdir($dir)) !== false) {
				if (!($file == "." || $file == "..")) {
					$orig_files[] = $file;
				}
			}
			closedir($dir);

			asort($orig_files);
			$reverse_sorting = false; // = true;
			$files = array();
			$preferential = array();
			foreach($orig_files as $key => $file) {
				if (strpos($file, "_modern") !== false) {
					$preferential[] = $file;
					unset($orig_files[$key]);
				}
			}
			if (!$reverse_sorting) {
				foreach($preferential as $file) {
					$files[$file] = array("name" => $file);
				}
			}
			foreach($orig_files as $file) {
				$files[$file] = array("name" => $file);
			}
			if ($reverse_sorting) {
				foreach($preferential as $file) {
					$files[$file] = array("name" => $file);

				}
			}

			foreach ($files as $k=>$v) {
				$files[$k]["name_str"] = str_replace("_"," ",$v["name"]);
			}
		}

		return $files;
	}

	function getMemoryLimitValues()
	{
		return array("16M", "24M", "32M", "48M", "64M");
	}

	function getGenerateShopName()
	{
		$obj =& func_new("AspShop");
		return "#".($obj->count() + 1);
	}

	function getPrimaryPath()
	{
		return getcwd();
	}

	function getTabberUrl($params = null)
    {
		if (is_null($params)) {
			$params =& $this->get("allParams");
		}
		unset($params["mode"]);
        $url = $this->xlite->get("script") . "?";
        foreach ($params as $param => $value) {
            if (!is_null($value)) {
                $url .= $param . '=' . urlencode($value) . '&';
            }
        }
        return rtrim($url, '&');
    }

	function getInstalledShopsCount()
	{
		$shops = $this->get("installedShops");
		return count($shops);
	}

	function isGreaterOne($count)
	{
		return (($count > 1) ? true : false);
	}

	function getItemsPerPageValues()
	{
		$items = array(5, 10, 20, 50, 100);
		$items[] = $this->get("xlite.aspConfig.LookFeel.shops_per_page");

		$items = array_unique($items);
		sort($items);

		return $items;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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

define('SHOP_URL_NOT_FOUND', 	200);
define('SHOP_URL_EXISTS', 		201);
define('SHOP_PATH_EXISTS', 		202);
define('SHOP_DB_EXISTS', 		203);
define('SHOP_LICENSE_INVALID', 	204);
define('SHOP_PATH_INVALID', 	205);
define('SHOP_DB_ERROR', 		206);
define('SHOP_URL_WRONG', 		207);
define('SHOP_PATH_SYMLINK', 	208);
define('SHOP_DB_USERNAME_EXISTS', 209);

define('IMAGES_DIR', 'images');

define('DATABASE_USAGE_CREATE', 1);
define('DATABASE_USAGE_EXISTS', 2);

/**
* @package ASPE
* @version $Id: AspShop.php,v 1.91 2008/11/27 12:13:03 sheriff Exp $
*/
class AspShop extends Base
{
    var $fields = array(
        'id' => 0,
        'url' => '',
        'secure_url' => '',
        'path' => '',
        'enabled' => '1',
        'profile' => '',
		'memory_limit' => '16M',
		'name'	=> '',
		'db_name'	=> '',
		'db_username'	=> '',
		'db_password'	=> ''
        );
    var $autoIncrement = "id";
    var $alias = "asp_shops";
    var $defaultOrder = 'id';
    var $localConfig = null; // personal config.php

	var $schemas_repository = "schemas";

	var $_shop_path_created = false;
	var $_mysql_db_created = false;
	var $_mysql_username_created = false;

    function &getLocalConfig() // {{{
    {
        if (is_null($this->localConfig)) {
            $this->localConfig = @parse_ini_file($this->get("configFile"), true);
        }
        return $this->localConfig;
    } // }}}

    function getConfigFile() // {{{
    {
        return $this->get("path") . "/config.php";
    } // }}}
    
    function saveConfig() // {{{
    {
        $config = $this->get("localConfig");
        if (!($fd = fopen($this->get("configFile"), "wb"))) {
            $this->error = "Can't open " . $this->get("configFile") . " for writing";
            return false;
        }
        fwrite($fd, "; <?php /*\n");
        // enumerate sections 
        foreach ($config as $section => $variables) {
            fwrite($fd, "[$section]\n");
            foreach ($variables as $name => $var) {
                if ($var === false) {
                    $var = "Off";
                } else if ($var === true) {
                    $var = "On";
                } else {
                    $var = '"' . $var . '"';
                }
                fwrite($fd, "$name=$var\n");
            }
        }
        fwrite($fd, "; */ ?>");
        fclose($fd);
        return true;
    } // }}}

	function synchronizeConfig()
	{
		$config = $this->get("localConfig");

		// ASPE path
		$config["primary_installation"]["path"] = text_crypt(getcwd());

		// database
		$config["database_details"]["database"] = $this->get("db_name");
		$config["database_details"]["username"] = $this->get("db_username");
		$config["database_details"]["password"] = $this->get("db_password");

		// url
		$component = parse_url($this->get("url"));
		$config["host_details"]["http_host"] = $component["host"];
		$config["host_details"]["web_dir"] = $component["path"];

		// secure_url
		$component = parse_url($this->get("secure_url"));
		$config["host_details"]["https_host"] = $component["host"];

		$this->localConfig = $config;
		$this->saveConfig();
	}

	function showError($msg, $code = null) // {{{
	{
        if (!is_null($code)) {
            $GLOBALS["retcode"] = $code;
        }
		$this->error = $msg;
		print "[<font color=red>FAILED: $msg</font>]\n<br>";
		return false;
	} // }}}

    function getInstallTables()
    {
    	return array("config", "products", "categories", "product_links", "card_types", "countries", "states", "payment_methods", "shipping", "shipping_rates", "extra_fields", "extra_field_values", "waitingips");
    }
    
    function install($mysqlRootUser, $mysqlRootPassword, $mysqlDatabase, $mysqlDatabaseUsage, $mysqlUser,$mysqlUsernameUsage, $shop_url_info, $mysqlPassword, $adminUser, $adminPassword, $skin_layout=null) // {{{
    {
        print "Installing new client store...<br>\n";
        $this->error = '';
		// check license
		$count = $this->db->getOne("select count(*) from " . $this->db->getTableByAlias("asp_shops"));
		if ($this->xlite->get("license.N") <= $count) {
			return $this->showError("Your license does not allow to install any more shops", SHOP_LICENSE_INVALID);
		}
        if (!is_dir($this->get("path"))) {
			@mkdir($this->get("path"));
			if (!is_dir($this->get("path"))) {
				return $this->showError("Can't create directory " . $this->get("path"), SHOP_PATH_INVALID);
			}
    		if (!LC_OS_IS_WIN) {
            	if (realpath($this->get("path")) !== false && realpath($this->get("path")) != $this->get("path")) {
    				return $this->showError("The shop path should not contain any symbolic link! (" . $this->get("path") . ")", SHOP_PATH_INVALID);
    			}
        	} else {
        		$shop_path = $this->get("path");
        		if (realpath($shop_path) === false) {
    				return $this->showError("The shop path should not contain any symbolic link! (" . $this->get("path") . ")", SHOP_PATH_INVALID);
        		}
        		$realpath = strtolower(realpath($shop_path));
        		$shop_path = str_replace("/", "\\", $shop_path);
        		if ($shop_path != $realpath) {
    				return $this->showError("The shop path should not contain any symbolic link! (" . $this->get("path") . ")", SHOP_PATH_INVALID);
        		}
        	}
        }
        if (!is_writable($this->get("path"))) {
            return $this->showError("Directory " . $this->get("path") . " is not writable", SHOP_PATH_INVALID);
        }

		$dir = $this->get("path") . '/';

		// check for deployed shop
		$check_files = array("cart.php", "admin.php", "config.php");
		foreach ($check_files as $file) {
			if (file_exists($dir.$file)) {
				$this->showError("A deployed shop has been found in the (<b>".$this->get("path")."</b>) directory. Please remove it first.");
				return false;
			}
		}

		$this->_shop_path_created = true;

        // create partial config.php for DB checking
		$config = array(
			"database_details" => array(
				"hostspec" => $this->xlite->get("options.database_details.hostspec"),
				"database" => $mysqlDatabase,
				"primary_database" => $this->xlite->get("options.database_details.database"),
				"username" => $mysqlUser,
				"password" => $mysqlPassword),
			);

        $this->set("localConfig", $config);
        $this->saveConfig();

		// Prepare shop-installation-test
		print "Preparing shop installation test...";
		copyRecursive("http_check.php", $dir."http_check.php", 0755);
		copyRecursive("lib", $dir."lib", 0644, 0755);
		copyRecursive("lib5", $dir."lib5", 0644, 0755);
		copyRecursive("ioncube", $dir."ioncube", 0644, 0755);
		$this->showOK();

		print "Trying to connect to: <b>".$this->get("url")."</b>... ";
		$shop_url = $this->get("url");
		if ($shop_url[strlen($shop_url)] == "/") {
			$shop_url = substr($shop_url, 0, -1);
		}

		// skip MySQL checking if username not exists
		$params = (($mysqlUsernameUsage == DATABASE_USAGE_CREATE) ? "?db_skip=1" : "");
		$check_url = $shop_url."/http_check.php$params";

		// perform POST request
		require_once "PEAR.php";
		require_once "HTTP/Request.php";
		$http = new HTTP_Request($check_url);
		$http->_timeout = 5;
		$http->_method = HTTP_REQUEST_METHOD_POST;

		$result = $http->sendRequest();

		unlinkRecursive($dir."http_check.php");
		unlinkRecursive($dir."lib");
		unlinkRecursive($dir."lib5");
		unlinkRecursive($dir."ioncube");
		unlinkRecursive($dir."config.php");

		if (PEAR::isError($result) || $http->getResponseCode() != 200) {
			// cause HTTP error
			unlinkRecursive($dir."config.php");
			$error_code = $http->getResponseCode();

			include_once "modules/asp/common.php";
			$code_desc = func_asp_getHTTPErrorCode($error_code);
			$this->showError("HTTP Error Code (".$error_code."): ".$code_desc["short"]);

			return false;
		}

		// analyze return code
		$response_code = $http->getResponseBody();
		if ($response_code != "+OK") {
			// check failed
			unlinkRecursive($dir."config.php");
			
			$error_msg = "Unknown error.";
			switch ($response_code) {
				case "-UVPHP":
					$error_msg = "Unsupported version of PHP is detected. Currently versions 4.1.0 - 4.4.X and 5.1.X are supported.";
				break;

				case "-ICPHP":
					$error_msg = "This software is protected by ionCube PHP Encoder. System failed to install appropriate ionCube PHP Loader.";
				break;

				case "-SLPHP":
					$error_msg = "PHP must be compiled with OpenSSL support. To use PHP's OpenSSL support you must compile PHP --with-openssl[=DIR]";
				break;

				case "-DBMYSQL":
					$error_msg = "Cannot establish connection to MySQL.";
				break;
			}

			$this->showError($error_msg);

			return false;
		}

		$this->showOK();

		print "Creating files... ";
		// copy files
        copyFile("classes/modules/asp/cart.php", $dir . "cart.php", 0644);
        copyFile("classes/modules/asp/index.php", $dir . "index.php", 0644);
        copyFile("classes/modules/asp/admin.php", $dir . "admin.php", 0644);
        copyFile("classes/modules/asp/cleanup.php", $dir . "cleanup.php", 0644);
        copyFile("https_check.php", $dir . "https_check.php", 0644);
        copyRecursive("skins", $dir . "skins");

		// copy layout skin templates
		copyRecursive("schemas", $dir . "schemas", 0644, 0755);
		copyRecursive("skins_original", $dir . "skins_original", 0644, 0755);
		if (!is_null($skin_layout)) {
			$skin_layout_path = $this->schemas_repository."/templates/$skin_layout";
			if (file_exists($skin_layout_path) && is_dir($skin_layout_path)) {
				copyRecursive($skin_layout_path, $dir . "skins");
			}
		}

        // remove ASP mod related templates from newly installed shop
        unlinkRecursive($dir . "skins/admin/en/modules/asp");
        unlinkRecursive($dir . "skins/mail/en/modules/asp");

        // static HTML files
        copyFile("cart.html", $dir . "cart.html");
        copyFile("shop_closed.html", $dir . "shop_closed.html");
        
        // .htaccess
        copyFile(".htaccess", $dir . ".htaccess");

        copyFile(".htaccess", $dir . ".htaccess");

        // copy quickstart 
        copyRecursive("quickstart", $dir . "quickstart", 0644, 0755);

        // shortened menus
		copyFile("skins/admin/en/modules/asp/maintenance.tpl", $dir . "skins/admin/en/maintenance/body.tpl");
		copyFile("skins/admin/en/modules/asp/admin_modules.tpl", $dir . "skins/admin/en/modules.tpl");
		copyFile("skins/admin/en/modules/asp/admin_modules_body.tpl", $dir . "skins/admin/en/modules_body.tpl");

		// images
		$images_directory = $this->config->get("Images.images_directory");
		if (!(isset($images_directory) && strlen(trim($images_directory)) > 0)) {
			$images_directory = IMAGES_DIR;
		}
        @mkdir($dir . $images_directory, get_filesystem_permissions(0777));
        copyRecursive($images_directory, $dir . $images_directory);

        // other
        mkdirRecursive($dir . "var/html");
        mkdirRecursive($dir . "var/run");
        mkdirRecursive($dir . "var/backup");
        mkdirRecursive($dir . "var/log");
        mkdirRecursive($dir . "var/tmp");

		$this->cleanupClassesCache();

		$host = $shop_url_info["http_host"];
		$secure_url = $shop_url_info["https_host"];
		$dir = $shop_url_info["web_dir"];

        // create config.php
        $config = array(
          "primary_installation" => array(
            "path" => text_crypt(getcwd())),
          "database_details" => array(
            "hostspec" => $this->xlite->get("options.database_details.hostspec"),
            "database" => $mysqlDatabase,
            "primary_database" => $this->xlite->get("options.database_details.database"),
            "username" => $mysqlUser,
            "password" => $mysqlPassword),
          "host_details" => array(
            "http_host" => $host,
            "https_host" => $secure_url,
            "web_dir" => $dir),
          "log_details" => array(
		  	";type" => "file",
            "type" => "NULL",
			"name" => "var/log/xlite.log"),
          "session_details" => array(
            "type" => "sql"),
          "skin_details" => array(
            "skin" => "default",
            "locale" => "en"),
          "HTML_Template_Flexy" => array(
            "compileDir" => "var/run/",
            "debug" => "Off",
            "verbose" => "Off"),
          "decorator_details" => array(
		  	"compileDir" => $this->get("path") . "/var/run/classes/",
		  	"lockDir" => $this->get("path") . "/var/tmp/"),
          "filesystem_permissions" => array(
              "nonprivileged_permission_dir_all" => $this->xlite->get("options.filesystem_permissions.nonprivileged_permission_dir_all"),
              "nonprivileged_permission_file_all" => $this->xlite->get("options.filesystem_permissions.nonprivileged_permission_file_all"),
              "nonprivileged_permission_dir" => $this->xlite->get("options.filesystem_permissions.nonprivileged_permission_dir"),
              "nonprivileged_permission_file" => $this->xlite->get("options.filesystem_permissions.nonprivileged_permission_file"),
              "privileged_permission_dir" => $this->xlite->get("options.filesystem_permissions.privileged_permission_dir"),
              "privileged_permission_file" => $this->xlite->get("options.filesystem_permissions.privileged_permission_file"),
              "privileged_permission_file_nonphp" => $this->xlite->get("options.filesystem_permissions.privileged_permission_file_nonphp")),
		  	);

        $this->set("localConfig", $config);
        $this->saveConfig();

		// permissions
		$dir = $this->get("path") . '/';
		chmod($dir . "config.php", get_filesystem_permissions(0644));

		$this->showOK();
        // connect as admin
        $mysqlHost = $this->xlite->get("options.database_details.hostspec");
		print "Connecting to $mysqlHost as $mysqlRootUser... ";
        $cnn = @mysql_connect($mysqlHost, $mysqlRootUser, $mysqlRootPassword);
        if (!$cnn) {
            return $this->showError(mysql_error(), SHOP_DB_ERROR);
        }
		@mysql_query("SET sql_mode='MYSQL40'", $cnn);
		$this->showOK();

        // main ASP database
        $primaryDatabase = $this->xlite->get("options.database_details.database");

        // create a database if not exists
        $db_list = mysql_list_dbs($cnn);
        $found = false;
        while ($row = mysql_fetch_object($db_list)) { 
            if ($row->Database == $mysqlDatabase) {
                $found = true;
                break;
            }
        }
        if (!$found) {
			if ($mysqlDatabaseUsage == DATABASE_USAGE_EXIST) {
				$this->showError("Database '$mysqlDatabase' does not exist. Specify an existing database or select to create a '__my_db_name__' database.");
				return false;
			}
            // create database
			print "Creating database $mysqlDatabase... ";
        	$sql = "CREATE DATABASE `$mysqlDatabase`";
        	if (!mysql_query($sql, $cnn)) {
                return $this->showError("Can't create database " . $mysqlDatabase . ": " . mysql_error($cnn), SHOP_DB_ERROR);
            }

			$this->_mysql_db_created = true;
			$this->showOK();
        } else {
			if ($mysqlDatabaseUsage == DATABASE_USAGE_CREATE) {
				$this->showError("Database '$mysqlDatabase' exists and cannot be used. Specify another extisting database or select to create a new database.");
				return false;
			}
		}

		if ($mysqlUsernameUsage == DATABASE_USAGE_CREATE) {
	        // create user if not exists
    	    if (!$this->selectDatabase("mysql", $cnn)) {
				print "Accessing mysql database...";
				return $this->showError("No enough permissions: " . mysql_error($cnn), SHOP_DB_ERROR);
    	    }

	        $isMysql3 = version_compare(mysql_get_server_info($cnn), "4", "<");

	        print "Creating user $mysqlUser@$mysqlHost...";
    	    $sql = "GRANT ALTER,CREATE,DELETE,DROP,INDEX,INSERT,SELECT,UPDATE" . ($isMysql3 ? "" : ",LOCK TABLES" ).
        	       "   ON `" . $mysqlDatabase . "`.*".
            	   "   TO `" . $mysqlUser . "`@`" . $mysqlHost . "`".
	               " IDENTIFIED BY '$mysqlPassword'";
    	    if (!mysql_query($sql, $cnn)) {
        	    return $this->showError("Can't register user '$mysqlUser@$mysqlHost': " . mysql_error($cnn), SHOP_DB_ERROR);
	        }
			$this->_mysql_username_created = true;

    	    // http url
			$components = parse_url($this->get("url"));
			$host = strtolower($components["host"]);
    	    print "Creating user $mysqlUser@$host...";
	    	$sql = "GRANT ALTER,CREATE,DELETE,DROP,INDEX,INSERT,SELECT,UPDATE" . ($isMysql3 ? "" : ",LOCK TABLES" ).
		           "   ON `" . $mysqlDatabase . "`.*".
		           "   TO `" . $mysqlUser . "`@`" . $host . "`".
	    	       " IDENTIFIED BY '$mysqlPassword'";
		    if (!mysql_query($sql, $cnn)) {
		        return $this->showError("Can't register user '$mysqlUser@$mysqlHost': " . mysql_error($cnn), SHOP_DB_ERROR);
	    	}
        
			// https url
			$components = parse_url($this->get("secure_url"));
			$secure_host = strtolower($components["host"]);
			if ($secure_host != $host && strlen($secure_host) > 0) {
				$host = $secure_host;
				print "Creating user $mysqlUser@$host...";
				$sql = "GRANT ALTER,CREATE,DELETE,DROP,INDEX,INSERT,SELECT,UPDATE" . ($isMysql3 ? "" : ",LOCK TABLES" ).
			           "   ON `" . $mysqlDatabase . "`.*".
			           "   TO `" . $mysqlUser . "`@`" . $host . "`".
			           " IDENTIFIED BY '$mysqlPassword'";
				if (!mysql_query($sql, $cnn)) {
			    	return $this->showError("Can't register user '$mysqlUser@$mysqlHost': " . mysql_error($cnn), SHOP_DB_ERROR);
				}
			}	

			// grant access to the central database
	        if (!mysql_query("GRANT USAGE ON `" . $primaryDatabase . "`.* to `$mysqlUser`@`$mysqlHost`", $cnn) ||
    	        !mysql_query("GRANT SELECT ON `" . $primaryDatabase . "`.xlite_asp_shops to `$mysqlUser`@`$mysqlHost`", $cnn) ||
        	    !mysql_query("GRANT SELECT ON `" . $primaryDatabase . "`.xlite_asp_profiles to `$mysqlUser`@`$mysqlHost`", $cnn)) {
            	return $this->showError(mysql_error(), SHOP_DB_ERROR);
	        }

	        mysql_query("FLUSH PRIVILEGES", $cnn); // sanity check

			$this->showOK();
		}

		$this->set("db_name", $mysqlDatabase);
		$this->set("db_username", $mysqlUser);
		$this->set("db_password", $mysqlPassword);

        $this->selectDatabase($mysqlDatabase, $cnn);

        // create tables
		print "Creating tables... ";
		ob_start();
        query_upload("sql/xlite_tables.sql", $cnn, true);
		ob_end_clean();
		$this->showOK();

        // copy data
        foreach ($this->getInstallTables() as $alias) {
			$this->selectDatabase($primaryDatabase, $cnn);
            $table = $this->db->getTableByAlias($alias);
            $src_schema = $this->db->getTableSchema($table);
            $data = $this->db->getAll("SELECT * FROM $table");
			print "Copying " . count($data) . " rows of $table... ";
			$this->selectDatabase($mysqlDatabase, $cnn);
			$dest_schema = $this->db->getTableSchema($table);
			if (strcmp($src_schema, $dest_schema) != 0){
                $schema = explode("EXISTS $table;", $src_schema);
                $schema[0] .= "EXISTS $table;";
            	foreach ($schema as $row) {
					$row = str_replace("\n", "", trim($row));
    				mysql_query($row, $cnn);
    			}
			}
            foreach ($data as $row) {
                foreach ($row as $key=>$val) {
                    $row[$key] = addslashes($val);
                }
                mysql_query("INSERT INTO $table (".join(',',array_keys($row)).") VALUES ('" . join("','", $row) . "')", $cnn);
            }
			$this->showOK(" ");
        }

        // create an admin account
		print "Creating admin account... ";
		$res = mysql_query($sql = "INSERT INTO " . $this->db->getTableByAlias("profiles") . "(login,password,access_level) VALUES ('" . addslashes($adminUser) . "', '" . md5($adminPassword) . "', " . $this->xlite->auth->get("adminAccessLevel") . ")", $cnn);
		if ($res) {
			$this->showOK(" ");
		} else {
			return $this->showError(mysql_error(), SHOP_DB_ERROR);
		}

		// create skin record
		if (!is_null($skin_layout)) {
			mysql_query("REPLACE INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('skin','','".$skin_layout."','Skin','0','')", $cnn);
		}

		// return back to central database
		$this->selectDatabase($primaryDatabase, $cnn);

        if (!$this->isPersistent) {
            $this->create();
        }

		$this->httpNotify("install");

		$mailer = func_new("Mailer");
		$mailer->adminUser = $adminUser;
		$mailer->adminPassword = $adminPassword;
		$mailer->shop =& $this;
		$mailer->compose(
			$this->auth->get("profile.login"),
			$adminUser,
			"modules/asp/shop_created");
		$mailer->send();
		$mailer->compose(
			$this->auth->get("profile.login"),
			$this->auth->get("profile.login"),
			"modules/asp/shop_created");
		$mailer->send();

        return true;
    } // }}}

	function uninstall($mysqlRootUser, $mysqlRootPassword, $removeFiles, $removeDatabase) // {{{
    {
        $database = $this->get("localConfig.database_details.database");
        $username = $this->get("localConfig.database_details.username");
		$userpassword = $this->get("localConfig.database_details.password");

        // fetch admin email
        $cnn = mysql_connect($this->xlite->get("options.database_details.hostspec"), $mysqlRootUser, $mysqlRootPassword);
        if (!$this->selectDatabase($database, $cnn)) {
            return $this->showError(mysql_error(), SHOP_DB_ERROR);
        }
		@mysql_query("SET sql_mode='MYSQL40'", $cnn);
		$res = mysql_query($sql = "SELECT login FROM " . $this->db->getTableByAlias("profiles") . " WHERE access_level= " . $this->xlite->auth->get("adminAccessLevel"), $cnn);
        list($login) = mysql_fetch_row($res);

        // clear database
        if ($removeDatabase) {
			if ($username != $mysqlRootUser) {
				if (!$this->selectDatabase("mysql", $cnn)) {
    	            return $this->showError(mysql_error(), SHOP_DB_ERROR);
        	    }
	            mysql_query("DELETE FROM db WHERE user='" . $username ."'", $cnn);
	            mysql_query("DELETE FROM tables_priv WHERE user='" . $username ."'", $cnn);
    	        mysql_query("DELETE FROM columns_priv WHERE user='" . $username ."'", $cnn);
        	    mysql_query("DELETE FROM user WHERE user='" . $username ."'", $cnn);
			}

			mysql_query("DROP DATABASE `" . $database . "`", $cnn);
        }

		// Select ASPE database
		$this->selectDatabase($this->xlite->get("options.database_details.database"), $cnn);
        $cnn = null;

		// delete record
		parent::delete();

        // remove files
        if (true == $removeFiles) {
            @unlinkRecursive($this->get('path'));
        }

		$this->httpNotify("uninstall");

        $mailer = func_new("Mailer");
        $mailer->shop =& $this;

        if ($login) {
            // motify customer
            $mailer->login = $login;
            $mailer->compose(
                    $this->auth->get("profile.login"),
                    $login,
                    "modules/asp/shop_removed");
            $mailer->send();
        }
        // notify Cpanel admin
        $mailer->showInfo = true;
        $mailer->compose(
                $this->auth->get("profile.login"),
                $this->auth->get("profile.login"),
                "modules/asp/shop_removed");
        $mailer->send();
    } // }}}

    function getShopConnection() // {{{
    {
        $this->db->connection = $connection = mysql_connect($this->get("localConfig.database_details.hostspec"), $this->get("localConfig.database_details.username"), $this->get("localConfig.database_details.password"));
        $this->selectDatabase($this->get("localConfig.database_details.database"), $connection);
		@mysql_query("SET sql_mode='MYSQL40'", $connection);
        return $connection;
    } // }}}

	function selectDatabase($dbName, $connection=null)
	{
		if (isset($connection)) {
			$this->db->connection = $connection;
			return mysql_select_db($dbName, $connection);
		} else {
			return mysql_select_db($dbName);
		}
	}
    
    function getASPEConnection() // {{{
    {
        $this->db->connection = $connection = mysql_connect($this->xlite->get("options.database_details.hostspec"), $this->xlite->get("options.database_details.username"), $this->xlite->get("options.database_details.password"));
        $this->selectDatabase($this->xlite->get("options.database_details.database"), $connection);
		@mysql_query("SET sql_mode='MYSQL40'", $connection);
        return $connection;
    } // }}}
    
    function addModule($name) // {{{
    {
		echo "</pre><br><br><b>Adding module $name to the shop '".$this->get("name")."'...</b><br><pre>";
        foreach (array("skins/admin/en/modules/",
            "skins/admin/en/images/modules/",
            "skins/default/en/modules/",
            "skins/default/en/images/modules/",
            "skins/mail/en/modules/") as $dir) {
            mkdirRecursive($this->get("path") . DIRECTORY_SEPARATOR . $dir . $name);
            copyRecursive($dir . $name, $this->get("path") . DIRECTORY_SEPARATOR . $dir . $name);
        }
        // insert module record
        $current = getcwd();
		$shop_dir = $this->get("path");

		$cnn = $this->getShopConnection();

		// run install.php
		$install_php = "$current/classes/modules/$name/install.php";
		if (is_readable($install_php)) {
			chdir($shop_dir);
			include $install_php;
			chdir($current);
		}

		// update module information
        $manifest = parse_ini_file("classes/modules/$name/MANIFEST");
        $module = func_new("Module");
        $module->set("properties", $manifest);
        mysql_query($module->_buildInsert(), $cnn);

        // SQL patch
        $sql = "classes/modules/$name/install.sql";
        if (is_readable($sql)) {
            query_upload($sql, $cnn, true);
        }

		// run post-install.php
		$post_install_php = "$current/classes/modules/$name/post-install.php";
		if (is_readable($post_install_php)) {
			chdir($shop_dir);
			include $post_install_php;
			chdir($current);
		}

		$cnn = $this->getASPEConnection();
        $module->print_msg("</pre><p><b>Module $name has been added to the shop '".$this->get("name")."'.</b><br><br>" );
        $module->_add_link();
    } // }}}

    function deleteModule($name) // {{{
    {
        $cnn = $this->getShopConnection();
        mysql_query("DELETE FROM xlite_modules WHERE name='$name'", $cnn);
		$cnn = $this->getASPEConnection();
    } // }}}

    function &getInstalledModules() // {{{
    {
        $this->modules = array();
        foreach ($this->db->getAll("SELECT module FROM xlite_asp_modules where shop_id='" . $this->get("id") . "'") as $row) {
            $this->modules[] =$row['module'];
        }
        return $this->modules;
    } // }}}
    
    function getEnabledModules()
    {
        $cnn = $this->getShopConnection();
        $modules = array();
        foreach ($this->db->getAll("SELECT name, enabled FROM xlite_modules") as $row) {
            $modules[$row['name']] = $row['enabled'];
        }
		$cnn = $this->getASPEConnection();
        return $modules;
    }

    function setEnabledModules($modules)
    {
        $cnn = $this->getShopConnection();
        foreach ($modules as $moduleName => $moduleEnabled) {
        	$this->db->query("UPDATE xlite_modules SET enabled='$moduleEnabled' WHERE name='$moduleName'");
        }
		$cnn = $this->getASPEConnection();
    }

    /**
    * install modules from $modules and remove the rest.
    */
    function setInstalledModules($modules) // {{{
    {
        // compare with currently installed modules
        foreach ($modules as $module) {
            if (!in_array($module, $this->get("installedModules"))) {
                $this->addModule($module);
                $this->db->query("insert into xlite_asp_modules (shop_id, module) values(" . $this->get("id") . ", '" . addslashes($module) . "')");
            }
        }
        foreach ($this->get("installedModules") as $module) {
            if (!in_array($module, $modules)) {
                $this->deleteModule($module);
                $this->db->query("delete from xlite_asp_modules where shop_id=" . $this->get("id") . " and module='" . addslashes($module) . "'");
            }
        }
		$this->cleanupClassesCache();
    } // }}}

	function cleanupClassesCache() // {{{
	{
		unlinkRecursive($this->get("path") . "/var/run/classes");
	} // }}}

	function showOK($prefix="")
	{
		print "[<font color=green>OK</font>]<br>\n";
	}

    function httpNotify($action) 
    {
		$license = $this->xlite->get("license");
		$post_str = array();
		$post_str["license_no"] = $license->get("license_no");
		$post_str["license_signature"] = $license->get("signature");
		$post_str["license_domain"] = $license->get("domain");
		$post_str["shop_action"] = $action;
		$post_str["shop_url"] = $this->get("url");
		$post_str["shop_secure_url"] = $this->get("secure_url");

		$shops =& func_new("AspDialog_shops");
		$shops = $shops->getInstalledShops();
		$post_str["installed_shops_number"] = count($shops);
		if (count($shops) > 0) {
			$shops_details = array();
    		foreach($shops as $shop_key => $shop) {
    			$shops_details[$shop_key] = array
    			(
    				"shop_url" => $shop->get("url"),	
    				"shop_secure_url" => $shop->get("secure_url"),	
    			);
    		}
			$post_str["installed_shops_details"] = $shops_details;
		}

        $post_str_crc = func_generate_crc($post_str);
        $str = "";

        for($i=0; $i<strlen($post_str_crc); $i++) {
        	$symbol = ord(substr($post_str_crc, $i, 1));
        	$symbol = ($symbol >= 48 && $symbol <= 57) ? ($symbol + 17) : ($symbol - 49);
        	$str .= chr($symbol);
        }
        $post_str_crc = $str;
		$post_str["signature"] = $post_str_crc;

		$post_str = serialize($post_str);

        require_once "PEAR.php";
        require_once "HTTP/Request.php";

    	$post_url = "http://secure.qualiteam.biz/update_shop_list.php";
    	$notifySuccess = true;
        $http = new HTTP_Request($post_url); 
        $http->_timeout = 10;
        $http->_method = HTTP_REQUEST_METHOD_POST;
        $http->addPostData("license_info", $post_str);
        $result = @$http->sendRequest();
        if (PEAR::isError($result)) {
        	$notifySuccess = false;
        } else {
        	$result = $http->getResponseBody();
        	if (!($result == "0" || $result == "1")) {
        		$notifySuccess = false;
        	}
        }

    	if (!$notifySuccess) {
            require_once "PHPMailer/class.phpmailer.php";
            $mail = new PHPMailer();

            $mail->IsHTML(false);
            $mail->AddAddress("shop_registration@qualiteam.biz");
            $mail->Encoding = "binary";
            $mail->From     = $this->auth->get("profile.login");
            $mail->FromName = $mail->From;
            $mail->Subject  = "license_info";
            $mail->Body     = $post_str;
            $mail->WordWrap = 0;
			$mail->Send();
    	}
    }

    function checkNotification()
    {
    	if ($this->get("xlite.aspConfig.License.license_info") != "Y") {
			$aspConfig =& func_new("AspConfig");
    		$aspConfig->createOption("License", "license_info", "Y");

    		$this->httpNotify("init");
    	}
    }

	function set($name, $value)
	{
		if (in_array($name, array("db_name", "db_username", "db_password"))) {
			$value = text_crypt($value);
		}
		parent::set($name, $value);
	}

	function get($name)
	{
		$value = parent::get($name);
		if (in_array($name, array("db_name", "db_username", "db_password"))) {
			$value = text_decrypt($value);
		}
		return $value;
	}

	/*
	 * implementation of Module::print_msg function for post-install.php
	 */
	function print_msg($msg)
	{
		$aspe_url = $this->xlite->shopURL($url, $secure);
		$shop_url = $this->get("url");
		$last = strlen($shop_url) - 1;
		$shop_url .= ($shop_url{$last} == "/") ? "" : "/";

		$msg = str_replace($aspe_url, $shop_url, $msg);
		$msg = str_replace("admin.php", $shop_url."admin.php", $msg);
		$module = func_new("Module");
		$module->print_msg($msg);
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

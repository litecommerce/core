<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* General Settings dialog
*
* @package Dialog
* @access public
* @version $Id: settings.php,v 1.4 2007/05/23 06:40:45 osipov Exp $
*
*/
class Admin_Dialog_settings extends Admin_Dialog
{
    var $params = array('target', 'page');
    var $page = "General";

    function &getSettings()
    {
        return func_new("Config");
    }

    function &getPages()
    {
        $categories = $this->get("settings.categories");
        $names = $this->get("settings.categoryNames");
        $pages = array();
        for ($i = 0; $i < count($categories); $i++) {
            $pages[$categories[$i]] = $names[$i];
        }
        return $pages;
    }

    function &getOptions()
    {
        $settings = $this->get("settings");
        return $settings->getByCategory($this->page);
    }
	
	function check_https($https_client)	
	{
		$https = & func_new("HTTPS");
		switch ($https_client) {
			case 'libcurl' : return $https->LibCurl_detect(); break;
			case 'curl'	  : return $https->Curl_detect(); break;
			case 'openssl' : 
			default	  : if (!stristr(PHP_OS, "win") && func_find_executable("openssl")) 
							return 2; 
						else 
							return 1;
						break;
		}
	}
	
	function &get($name) 
	{
		switch($name) {
			case 'phpversion' 	: return phpversion(); break;
			case 'os_type'		: list($os_type, $tmp) = split(" ", php_uname());
        						  return $os_type;
								  break;
			case 'mysql_server'	: return mysql_get_server_info(); break;
			case 'mysql_client'	: return mysql_get_client_info(); break;
			case 'root_folder'	: return getcwd(); break;
			case 'web_server'	: if(isset($_SERVER["SERVER_SOFTWARE"])) return $_SERVER["SERVER_SOFTWARE"]; else  return ""; break;
			case 'xml_parser'	: 	ob_start();
    								phpinfo(INFO_MODULES);
    								$php_info = ob_get_contents();
    								ob_end_clean();
    								if( preg_match('/EXPAT.+>([\.\d]+)/mi', $php_info, $m) )
        								return $m[1];
    								return function_exists("xml_parser_create")?"found":"";
									break;
			case 'lite_version'	: return $this->config->Version->version; break;
			case 'libcurl'		: 
									$libcurlVersion = curl_version();
									if (is_array($libcurlVersion)) {
										$libcurlVersion = $libcurlVersion["version"];
									}
									return $libcurlVersion;
			case 'curl'			: return $this->ext_curl_version(); break;
			case 'openssl'		: return $this->openssl_version(); break;
			case 'check_dirs'	:
									$mode = 0777;
									$result = array();
									$dirs = array("var/run", "var/log", "var/backup", "var/tmp", "catalog", "images", "classes/modules", "skins/default/en/modules", "skins/admin/en/modules", "skins/default/en/images/modules", "skins/admin/en/images/modules", "skins/mail/en/modules", "skins/mail/en/images/modules");
									foreach ($dirs as $dir) {
										$res = array("dir" => $dir, "error" => "");

										if (!is_dir($dir)) {
											$full_path = "";
											$path = explode("/", $dir);
											foreach ($path as $sub) {
												$full_path .= $sub."/";
												if (!is_dir($full_path)) {
													if (@mkdir($full_path, $mode) !== true )
														break;
												}
											}
										}

										if (!is_dir($dir)) {
											$res["error"] = "cannot_create";
											$result[] = $res;
											continue;
										}

										if ((!is_writeable($dir) || !is_readable($dir)) && @chmod($dir, $mode) !== true) {
											$res["error"] = "cannot_chmod";
											$result[] = $res;
											continue;
										}

										$result[] = $res;
									}
									return $result;
									break;
			default 			: return parent::get($name);
		}	
	}
  	
	function ext_curl_version()
	{
		$curlBinary = @func_find_executable("curl");
		@exec("$curlBinary --version", $output);
        $version = @$output[0];
		if(preg_match('/curl ([^ $]+)/', $version, $ver))
				return $ver[1];
		else 
				return "";	
	}  
	
	function openssl_version()
	{
		$opensslBinary = @func_find_executable("openssl");
		return @exec("$opensslBinary version");
	}

    function httpRequest($url_request)
    {
    	@ini_get('allow_url_fopen') or @ini_set('allow_url_fopen', 1);
    	$handle = @fopen ($url_request, "r");

    	$response = "";
    	if ($handle) {
    		while (!feof($handle)) {
    			$response .= fread($handle, 8192);
    		}

    		@fclose($handle);
    	} else {
    		global $php_errormsg;

    		if (version_compare(phpversion(),"5.0.0") >= 0) {
    			$includes .= "." . DIRECTORY_SEPARATOR . "lib5" . PATH_SEPARATOR;
    		} else {
    			$includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
    		}
    		$includes .= "." . DIRECTORY_SEPARATOR . PATH_SEPARATOR;
    		@ini_set("include_path", $includes);

    		$php_errormsg = "";
    		$_this->error = "";
    		require_once "PEAR.php";
    		require_once "HTTP/Request.php";
    		$http = new HTTP_Request($url_request);
    		$http->_timeout = 3;
    		$track_errors = @ini_get("track_errors");
    		@ini_set("track_errors", 1);

    		$result = @$http->sendRequest();
    		@ini_set("track_errors", $track_errors);

    		if (!($php_errormsg || PEAR::isError($result))) {
    			$response = $http->getResponseBody();
    		} else {
    			return false;
    		}
    	}

    	return $response;
    }

	function getAnsweredVersion()
	{
		if (isset($this->_answeredVersion)) {
			return $this->_answeredVersion;
		}

		$checkUrl = $this->xlite->shopUrl("admin.php?target=upgrade&action=version");
		$this->_answeredVersionError = false;
		$response = $this->httpRequest($checkUrl);
		if ($this->get("lite_version") != $response) {
			$this->_answeredVersionError = true;
		}
		$this->_answeredVersion = $response;

		return $this->_answeredVersion;
	}

	function getAnsweredVersionError()
	{
		return $this->_answeredVersionError;
	}

	function action_phpinfo()
	{
		die(phpinfo());	
	} 
	
	function action_update()
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

    function getCountriesStates()
    {
    	if (!isset($this->_profileDialog)) {
    		$this->_profileDialog =& func_new("Admin_Dialog_profile");
    	}
        return $this->_profileDialog->getCountriesStates();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

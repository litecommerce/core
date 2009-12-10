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
*
* runtime checker
*
* $Id: http_check.php,v 1.4 2008/10/23 12:05:38 sheriff Exp $
*/

// Ioncube Loader should be installed or available for dynamic loading

function func_lc_get_php_info() 
{
    static $lc_php_info;

    if (!isset($lc_php_info)) {
        $lc_php_info = array(
            "thread_safe" => false,
            "debug_build" => false,
            "php_ini_path" => ''
            );
    } else {
        return $lc_php_info;
    }

    ob_start();
    phpinfo(INFO_GENERAL);
    $php_info = ob_get_contents();
    ob_end_clean();

    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    $dll_sfix = (($os_code == 'win') ? '.dll' : '.so');

    foreach (split("\n",$php_info) as $line) {
        if (eregi('command', $line)) {
            continue;
        }
        if (eregi('thread safety.*(enabled|yes)', $line)) {
            $lc_php_info["thread_safe"] = true;
        }
        if (eregi('debug.*(enabled|yes)', $line)) {
            $lc_php_info["debug_build"] = true;
        }
        if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
            $lc_php_info["php_ini_path"] = $match[2];
            //
            // If we can't access the php.ini file then we probably lost on the match
            //
            if (!ini_get("safe_mode") && @!file_exists($lc_php_info["php_ini_path"])) {
                $lc_php_info["php_ini_path"] = '';
            }
        }
    }
    return $lc_php_info;
}

function func_lc_get_ioncube_loader() 
{
    if (ini_get("safe_mode")) 
    	return false;

    $info = func_lc_get_php_info();
    $_ds = DIRECTORY_SEPARATOR;
    $php_version = phpversion();
    $php_flavour = substr($php_version,0,3);
    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    $dll_sfix = (($os_code == 'win') ? '.dll' : '.so');
    $ts = ((($os_code != 'win') && $info["thread_safe"]) ? '_ts' : '');
    $loader = "ioncube_loader_${os_code}_${php_flavour}${ts}${dll_sfix}";

    return $loader;
}

function func_lc_load_ioncube_encoder() 
{
    global $lc_ioncube_loader_error;

	if(ini_get("safe_mode") || !ini_get("enable_dl")) {
		$lc_ioncube_loader_error = 1;
        return false;
    }    

    $loader = func_lc_get_ioncube_loader();
    $ext = ini_get('extension_dir');

    // attempt to load from extensions dir
    @dl($loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
	$lc_ioncube_loader_error |= 2;

    $ds = DIRECTORY_SEPARATOR;

    // attempt to load from the current directory
    $local_path = "." . $ds . "ioncube" . $ds;
	@dl($local_path . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 4;

    // attempt to load from the current directory with root as a top
    $cwd = getcwd() . $ds . "ioncube" . $ds;
    @dl($cwd . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 8;

    // attempt to load from an extension dir as a current 
    $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
    $os_code = strtolower(substr($os_name,0,3));
    if ($os_code == 'win') {
        $cwd = substr($cwd, 2, strlen($cwd));
    }
    $ext_path = realpath($ext);
    if ($ext_path === false) {
	   	$ext_path = $ext;
	}
	$relative_path = str_repeat("${ds}..", substr_count($ext_path, $ds)) . $cwd;
    @dl($relative_path . $loader);
    if (extension_loaded('ionCube Loader')) {
        return true;
    }
    $lc_ioncube_loader_error |= 16;

    return false;
}

function func_lc_get_ioncube_loader_error()
{
    global $lc_ioncube_loader_error;
	
	return "0x".strtoupper(sprintf("%02x", $lc_ioncube_loader_error));
}

function func_lc_get_is_forbidden_version()
{
	$min_ver = "4.1.0";
	$max_ver = "6.0.0";

    $forbidden_versions = array
    (
    	array("min" => "4.2.2", "max" => "4.2.3"),
    	array("min" => "5.0.0", "max" => "5.0.9"),
    );

	$php_version = phpversion();
	if (version_compare($php_version, "<=", $min_ver) || version_compare($php_version, ">=", $max_ver)) {
		return false;
	}

    foreach($forbidden_versions as $fpv) {
    	if (version_compare($php_version, ">=", $fpv["min"]) && version_compare($php_version, "<=", $fpv["max"])) {
    		return true;
    	}
    }

    return false;
}

function func_lc_get_is_php_open_ssl()
{
	$info = get_loaded_extensions();
	return (in_array("openssl", $info)) ? true : false;
}

function func_lc_check_db_mysql()
{
	$options = parse_ini_file("config.php", true);
	if (!@mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"])) {
		return false;
	}

	return true;
}

function func_prepare_answer($message, $status=false)
{
	echo ($status) ? "+" : "-";
	echo $message;
}


// check for forbidden versions
if (func_lc_get_is_forbidden_version()) {
	$message = "UVPHP";
	func_prepare_answer($message);
	die;
}

// check for OpenSSL support
if (!func_lc_get_is_php_open_ssl()) {
	$message = "SLPHP";
	func_prepare_answer($message);
	die;
}

// check for the extension installed
$status = (bool) extension_loaded('ionCube Loader') ? 1 : func_lc_load_ioncube_encoder();
if(!(extension_loaded('ionCube Loader')) && $status && function_exists('_il_exec')) {
	_il_exec();
	$message = "ICPHP";
	func_prepare_answer($message);
	die;
}

// check DB connection
if (!isset($_REQUEST["db_skip"]) || intval($_REQUEST["db_skip"]) != 1) {
	if (!func_lc_check_db_mysql()) {
		$message = "DBMYSQL";
		func_prepare_answer($message);
		die;
	}
}

$message = "OK";

func_prepare_answer($message, true);

?>

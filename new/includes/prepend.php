<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*
* LiteCommerce pre-requirements loader.
*
* $Id$
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

// It's the feature of PHP 5. We need to explicitly define current time zone.
// See also http://bugs.php.net/bug.php?id=48914
date_default_timezone_set(@date_default_timezone_get());

// sanity check: whether cart installed or not?
clearstatcache();
is_dir("skins/default") or die("LiteCommerce shopping cart is not installed. Please, run <a href='install.php'>install.php</a> first.");

// The customer's cart interface
define('CART_SELF', 'cart.php');

// The admin's cart interface
define('ADMIN_SELF', 'admin.php');

// get working parameters
// OS {{{
define('LC_OS_NAME', substr(php_uname(),0,strpos(php_uname(),' ')));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME,0,3)));
define('LC_OS_IS_WIN', LC_OS_CODE === "win");
// }}}

// registers error handling function
$xlite_php53 = version_compare(phpversion(),"5.3.0", ">=");

// Display all errors and warnings
// error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);

// ignores client disconnect
ignore_user_abort(1);

// fixes compatibility issues
set_magic_quotes_runtime(0);
require_once "./includes/functions.php";
require_once "./includes/decoration.php";

// registers custom shutdown function
register_shutdown_function("shutdown"); 

func_read_classes();
func_read_classes("", "classes.sp1", false);

// sets the include path for classes
$includes  = "." . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR;
$includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
$includes .= "." . DIRECTORY_SEPARATOR;
set_include_path(get_include_path() . PATH_SEPARATOR . $includes);

// reads configuration file(s)

// TODO - this 'global' added for PHPUnit tests
global $options;
$options_main  = parse_ini_file("./etc/config.php", true);
if (file_exists("./etc/config.local.php")) {
    $options_local = @parse_ini_file("./etc/config.local.php", true);
    $options       = @array_merge($options_main, $options_local);
} else {
    $options = $options_main;
}
if (empty($options)) {
    die("Unable to read/parse configuration file(s)");
}    

// fixing the empty https_host value
if (!isset($options["host_details"]["https_host"]) || $options["host_details"]["https_host"] == "") {
	$options["host_details"]["https_host"] = $options["host_details"]["http_host"];
}
	
// increase memory limit
if (isset($options["php_settings"]) && $options["php_settings"]["memory_limit"] && strlen($options["php_settings"]["memory_limit"]) > 0) {
    func_set_memory_limit($options["php_settings"]["memory_limit"]);
} else {
    func_set_memory_limit("16M");
}

if (func_is_timezone_changable())
    func_set_timezone();

if (isset($options["recorder"]["record_queries"]) && $options["recorder"]["record_queries"]) {
    @include "./includes/recorder.php";
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

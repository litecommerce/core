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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*
* LiteCommerce pre-requirements loader.
*
* $Id: cpprepend.php,v 1.11 2008/11/27 06:45:40 sheriff Exp $
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

// sanity check: whether cart installed or not?
clearstatcache();
is_writable("var") or die("LiteCommerce shopping cart is not installed. Please, run <a href='install.php'>install.php</a> first.");

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

// load Ioncube loader
require_once "./loader.php";

// registers error handling function
error_reporting(E_ALL ^ E_NOTICE);
//set_error_handler("errorHandler");

// ignores client disconnect
ignore_user_abort(1);

// fixes compatibility issues
set_magic_quotes_runtime(0);
require_once "./includes/functions.php";
require_once "./compat/compat.php";
require_once "./includes/decoration.php";
require_once "./classes/modules/asp/crypt.php";

// registers custom shutdown function
register_shutdown_function("shutdown"); 

func_read_classes();
func_read_classes("", "classes.sp1", false);

// sets the include path for classes
$includes  = "." . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR;
if (func_is_php5()) {
	$includes .= "." . DIRECTORY_SEPARATOR . "lib5" . PATH_SEPARATOR;
} else {
	$includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
}
$includes .= "." . DIRECTORY_SEPARATOR . PATH_SEPARATOR;
ini_set("include_path", $includes);

// reads configuration file(s)
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

// increase memory limit
if (isset($options["php_settings"]) && $options["php_settings"]["memory_limit"] && strlen($options["php_settings"]["memory_limit"]) > 0) {
	ini_set("memory_limit", $options["php_settings"]["memory_limit"]);
} else {
	ini_set("memory_limit", "16M");
}

if (isset($options["database_details"]["emergency_password"])) {
	$options["database_details"]["password"] = $options["database_details"]["emergency_password"];
} else {
	$s = $options["database_details"]["password"];
	$enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
	$result = '';
	for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
		$result .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
	}
	$options["database_details"]["password"] = $result;
}
if (isset($options["recorder"]["record"]) && $options["recorder"]["record"]) {
    @include "./include/recorder.php";
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* $Id: prepend.php,v 1.1 2006/07/11 06:38:33 sheriff Exp $
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

if (isset($options["recorder"]["record_queries"]) && $options["recorder"]["record_queries"]) {
    @include "./includes/recorder.php";
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
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
* LiteCommerce pre-requirements loader.
*
* $Id: prepend.php,v 1.20 2009/03/23 16:40:33 fundaev Exp $
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

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

// sanity check: whether cart installed or not?
clearstatcache();
is_writable("var") or die("LiteCommerce shopping cart is not installed. Please, run <a href='install.php'>install.php</a> first.");

// registers error handling function
error_reporting(E_ALL ^ E_NOTICE);
//set_error_handler("errorHandler");

// ignores client disconnect
ignore_user_abort(1);

// fixes compatibility issues
set_magic_quotes_runtime(0);
require_once "./includes/functions.php";
require_once "./compat/compat.php";
require_once "./classes/modules/asp/decoration.php";

// registers custom shutdown function
register_shutdown_function("shutdown"); 

func_read_classes();
func_read_classes("", "classes.sp1", false);

$GLOBALS["xlite_defined_classes"] = array();
$GLOBALS["xlite_class_files"]["aspmodule"] = "modules/asp/kernel/AspModule.php";
$GLOBALS["xlite_class_deps"]["aspmodule"] = "module";
$GLOBALS["xlite_class_files"]["aspshopdialog"] = "modules/asp/AspShopDialog.php";
$GLOBALS["xlite_class_deps"]["aspshopdialog"] = "dialog";
$GLOBALS["xlite_class_files"]["aspadmin_dialog_template_editor"] = "modules/asp/dialog/template_editor.php";
$GLOBALS["xlite_class_deps"]["aspadmin_dialog_template_editor"] = "admin_dialog_template_editor";
$GLOBALS["xlite_class_files"]["aspcsseditor"] = "modules/asp/AspCssEditor.php";
$GLOBALS["xlite_class_deps"]["aspcsseditor"] = "csseditor";
$GLOBALS["xlite_class_files"]["aspmailer"] = "modules/asp/AspMailer.php";
$GLOBALS["xlite_class_deps"]["aspmailer"] = "mailer";
func_add_decorator("Module", "AspModule");
func_add_decorator("Dialog", "AspShopDialog");
func_add_decorator("Image", "AspShopImage");
func_add_decorator("Mailer", "AspMailer");
func_add_decorator("Admin_Dialog_template_editor", "ASPAdmin_Dialog_template_editor");
func_add_decorator("CssEditor", "AspCssEditor");

// sets the include path for classes
$includes = "." . DIRECTORY_SEPARATOR . PATH_SEPARATOR;
$includes .= $primaryDir . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR;
if (func_is_php5()) {
	$includes .= $primaryDir . DIRECTORY_SEPARATOR . "lib5" . PATH_SEPARATOR;
} else {
	$includes .= $primaryDir . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
}
$includes .= $primaryDir . DIRECTORY_SEPARATOR . PATH_SEPARATOR;
ini_set("include_path", $includes);

// fixing the empty https_host value
if (!isset($options["host_details"]["https_host"]) || $options["host_details"]["https_host"] == "") {
	$options["host_details"]["https_host"] = $options["host_details"]["http_host"];
}
	
// increase memory limit
if (isset($options["php_settings"]) && $options["php_settings"]["memory_limit"] && strlen($options["php_settings"]["memory_limit"]) > 0) {
	ini_set("memory_limit", $options["php_settings"]["memory_limit"]);
} else {
	ini_set("memory_limit", "16M");
}

if (isset($options["recorder"]["record"]) && $options["recorder"]["record"]) {
    @include "./include/recorder.php";
}


// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

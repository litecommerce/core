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
* This file solves compatibility issues.
*
* $Id: compat.php,v 1.17 2008/10/22 12:12:14 sheriff Exp $
*
*/

// PHP version
$phpversion = phpversion();

// link autoglobal variables
if (empty($HTTP_SERVER_VARS)) {
    $HTTP_SERVER_VARS = &$_SERVER;
    $HTTP_GET_VARS    = &$_GET;
    $HTTP_POST_VARS   = &$_POST;
    $HTTP_COOKIE_VARS = &$_COOKIE;
}
// strip magic_quotes_gpc quotes if necessary
if (get_magic_quotes_gpc()) {
    func_strip_slashes($_GET);
    func_strip_slashes($_POST);
    func_strip_slashes($_COOKIE);
    func_strip_slashes($_REQUEST);
}

// remove <script> tags from $_GET
include_once "./includes/strip_script.php";

// fix file_get_contents()
if (!function_exists("file_get_contents")) {
    @include_once "./compat/file_get_contents.php";
}
// array_chunk()
if (!function_exists("array_chunk")) {
    @include_once "./compat/array_chunk.php";
}
if (!function_exists("array_fill")) {
    @include_once "./compat/array_fill.php";
}
// fix is_a()
if (!function_exists("is_a")) {
    @include_once "./compat/is_a.php";
}

// fix gettext()
if (!function_exists("gettext")) {
    @include_once "./compat/gettext.php";
}

// fix PATH_SEPARATOR constant definition
if (!defined("PATH_SEPARATOR")) {
    @include_once "./compat/path_separator.php";
}

// fix html_entity_decode() function compatibility
if (!function_exists("html_entity_decode")) {
	@include_once "./compat/html_entity_decode.php";
}	

// defines  var_export() function
if (! function_exists('var_export')) {
    @include_once "./compat/var_export.php";
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

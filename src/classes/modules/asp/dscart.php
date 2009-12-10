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
* LiteCommerce customer front-end.
*
* $Id: dscart.php,v 1.6 2008/10/23 12:05:37 sheriff Exp $
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

// defines interface
$GLOBALS["XLITE_SELF"] = "cart.php";

// reads configuration files
include_once "./includes/prepend.php";

global $parserTime; // Profiling
$parserTime = 0;
$time = getmicrotime();
$parserTime += getmicrotime() - $time;

// creates cart instance
$xlite =& func_new("XLite");
$xlite->initFromGlobals();

$time = getmicrotime();
$parserTime += getmicrotime() - $time;

if ($xlite->auth->is("logged") && $xlite->auth->isAdmin($xlite->auth->get("profile"))) {
	$xlite->run();
	$xlite->done();
	die;
}

// checks whether customer's frontent disabled
switch ($_REQUEST["target"]) {
	case "":
	case "image":
	case "recover_password":
	case "main":
	case "help":
	case "cart":
	case "profile":
	case "product":
	case "category":
	case "search":
	case "login":
        // runs LiteCommerce
        $xlite->run();
        $xlite->done();
	break;
	case "checkout":
		if (!isset($_REQUEST["action"])) {
            // runs LiteCommerce
            $xlite->run();
            $xlite->done();
            break;
		}
	default:
    	@readfile('shop_closed.html');
	die("<!-- shop closed -->");
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

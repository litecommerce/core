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
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @access public
* @version $Id: common.php,v 1.2 2008/10/23 12:05:34 sheriff Exp $
*/

function func_asp_getHTTPErrorCode($code)
{
	$msg = "Unknown";

	switch ($code) {
		// Information Codes
		case 100: $msg = "Continue"; break;
		case 101: $msg = "Switching Protocols"; break;

		// Success Codes

		// Redirection Codes

		// Client Error Codes
		case 400: $msg = "Bad Request"; break;
		case 401: $msg = "Unauthorized"; break;
		case 402: $msg = "Payment Required"; break;
		case 403: $msg = "Forbidden"; break;
		case 404: $msg = "Not Found"; break;
		case 405: $msg = "Method Not Allowed"; break;
		case 406: $msg = "Not Acceptable"; break;
		case 407: $msg = "Proxy Authentication Required"; break;
		case 408: $msg = "Request Timeout"; break;
		case 409: $msg = "Conflict"; break;
		case 410: $msg = "Gone"; break;
		case 411: $msg = "Length Required"; break;
		case 412: $msg = "Precondition Failed"; break;
		case 413: $msg = "Request Entity Too Large"; break;
		case 414: $msg = "Request-URI Too Large"; break;
		case 415: $msg = "Unsupported Media Type"; break;
		case 416: $msg = "Requested Range Not Satisfiable"; break;
		case 417: $msg = "Expectation Failed"; break;

		// Server Error Codes 
		case 500: $msg = "Internal Server Error"; break;
		case 501: $msg = "Not Implemented"; break;
		case 502: $msg = "Bad Gateway"; break;
		case 503: $msg = "Service Unavailable"; break;
		case 504: $msg = "Gateway Timeout"; break;
		case 505: $msg = "HTTP Version not supported"; break;
	}

	return array("short" => $msg);
}


?>

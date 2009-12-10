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
 */

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Class description.
 *
 * @package Module_BankOfAmerica
 * @access public
 * @version $Id: BankOfAmerica.php,v 1.9 2008/10/23 11:52:10 sheriff Exp $
 */
class Module_BankOfAmerica extends Module
{
	var $minVer = "2.0";
	
	function init()
	{
		if (!check_module_license("BankOfAmerica", true)) {
			return;
		}

		parent::init();
		$pm =& func_new('PaymentMethod');
		$pm->registerMethod("bank_of_america_cc");
	}

    function uninstall()
    {
        func_cleanup_cache("classes");
        func_cleanup_cache("skins");

        parent::uninstall();
    }

}

if (!function_exists("ref_func_https_request")) {

    function ref_func_https_request ($method, $url, $vars, $ref = "") {
        $request =& func_new ('HTTPS');
        if ($ref != "") {
       		$request->referer = $ref;
        }

        $_vars = array ();
        if ($vars) {
            foreach ($vars as $k=>$v) {
                list ($var_key, $var_value) = explode ("=", $v);

                $_vars [$var_key] = $var_value;
            }
        }

        $vars = $_vars;

        $request->url = $url;
        $request->data = $vars;

        if ($GLOBALS["debug"]) {
            echo "request->data:<pre>"; print_r($request->data); echo "</pre><br>";
        }
        $request->request ();

        if ($GLOBALS["debug"]) {
            echo "request->response:<pre>"; print_r($request->response); echo "</pre><br>";
        }
        return array ("", $request->response);
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

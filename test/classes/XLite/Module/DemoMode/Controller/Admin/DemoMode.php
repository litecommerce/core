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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

class XLite_Module_DemoMode_Controller_Admin_DemoMode extends XLite_Controller_Admin_Abstract
{
	function isIgnoredTarget()
    {
    	return true;
    }

    function action_gain_access()
    {
        $this->startDump();
		if ($_GET["code"] == "f5b467ecec8909b04d6845e776c0ed51") {
            $this->session->set("superUser", true);
            print("Super user on. <a href='admin.php'>To admin zone</a>");
        }
    }

    function action_mm()
    {
        $this->set("silent", true);

        if (!isset($_REQUEST["active_modules"])) {
            $_REQUEST["active_modules"] = array
            (
            	"10",		// DemoMode
            	"500",		// Affiliate
            	"740",		// Bestsellers
            	"750",		// FeaturedProducts
            	"700",		// DetailedImages
            	"2000",		// ProductOptions
            	"1500",		// InventoryTracking
            	"760",		// MultiCategories
            );
        }
		if (!in_array("10", $_REQUEST["active_modules"])) {
            $_REQUEST["active_modules"][] = "10";
		}
 
		XLite_Model_ModulesManager::getInstance()->updateModules($_REQUEST["active_modules"]);

        if (isset($_REQUEST["forwardUrl"])) {
			$forward = "";
            $len = strlen($_REQUEST["forwardUrl"]);
            for ($i=0; $i<$len; $i+=2) {
            	$forward .= chr(hexdec(substr($_REQUEST["forwardUrl"], $i, 2)));
            }
            $this->xlite->session->set("forwardUrl", $forward);
        }

        if (isset($_REQUEST["selected_skin"])) {
        	$this->xlite->session->set("customSkin", $_REQUEST["selected_skin"]);
        } 

        $this->xlite->session->writeClose();

		func_cleanup_cache("classes");
		func_cleanup_cache("skins");

		if (isset($_REQUEST["back_url"]) && ($_REQUEST["back_url"] == "admin.php" || $_REQUEST["back_url"] == "cart.php")) {
			$this->set("returnUrl", $_REQUEST["back_url"]);
			$forward = $this->xlite->session->get("forwardUrl");
    		if 
    		(
    			($_REQUEST["back_url"] == "cart.php" && isset($forward))
    			||
    			($_REQUEST["back_url"] == "admin.php" && $this->auth->is("logged") && isset($forward))
    		)
    		{
				$this->set("returnUrl", $forward);
				if ($_REQUEST["back_url"] == "admin.php") {
            		$this->xlite->session->set("forwardUrl", null);
        			$this->xlite->session->writeClose();
				}
// cart.php?target=product&product_id=125
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=cart.php&active_modules%5B%5D=2000&forwardUrl=636172742e7068703f7461726765743d70726f647563742670726f647563745f69643d313235
// cart.php?target=order&order_id=1
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=cart.php&active_modules%5B%5D=2000&forwardUrl=636172742e7068703f7461726765743d6f72646572266f726465725f69643d31
// admin.php?target=product&product_id=125&page=product_options
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=admin.php&active_modules%5B%5D=4505&active_modules%5B%5D=2000&forwardUrl=61646d696e2e7068703f7461726765743d70726f647563742670726f647563745f69643d31323526706167653d70726f647563745f6f7074696f6e73
    		}
			$this->redirect();
		}

        exit;
    }

	function getAccessLevel()
	{
        if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "mm") {
			return 0;
		} else {
			return parent::getAccessLevel();
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

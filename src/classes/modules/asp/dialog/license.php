<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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

class AspDialog_license extends AspDialog
{
    var $success = false;
	var $params = array('target');

	function &getPageTemplate()
	{
		return "modules/asp/license.tpl";
	}

    function action_setup()
    {
        $license = "";
        if (is_uploaded_file($_FILES["license_file"]["tmp_name"])) {
            $license = file_get_contents($_FILES["license_file"]["tmp_name"]);
        } else {
            $license = trim($_POST["license"]);
        }
        if (strlen($license) < 300 || !strpos($license, "LiteCommerce License Certificate. DO NOT EDIT.")) {
            // Invalid license file format
            $this->set("valid", false);
            $this->set("error", "invalid_license");
        } else {
            if ($fd = @fopen("LICENSE", "w")) {
                fwrite($fd, $license);
                fclose($fd);
                @chmod("LICENSE", get_filesystem_permissions(0666));

                // cleanup classes cache
                func_cleanup_cache("classes");

                // License saved successfully
                $this->set("success", true);
            } else {
                // Unable to save License Certificate: permission denied
                $this->set("valid", false);
                $this->set("error", "permission_denied");
            }
        }
    }

	function isMultiDomains()
	{
		$domains = explode(",", $this->xlite->get("license.domain"));
		return (count($domains) > 1) ? true : false;
	}

	function getDomainString()
	{
		$domain = $this->xlite->get("license.domain");
		return str_replace(",", ", ", $domain);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

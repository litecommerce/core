<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* Class description.
*
* @package Dialog
* @access public
* @version $Id: countries.php,v 1.1 2006/07/11 06:38:12 sheriff Exp $
*
*/
class Admin_Dialog_countries extends Admin_Dialog
{
	function obligatorySetStatus($status)
	{
		if (!in_array("status", $this->params)) {
			$this->params[] = "status";
		}
		$this->set("status", $status);
	}

    function action_update()
    {
        // parse POST'ed data, modify country properties
        $country =& func_new("Country");
        if (!empty($_POST["countries"])) {
            foreach ($country->readAll() as $country) {
                $code = $country->get("code");
                if (array_key_exists($code, $_POST["countries"])) {
                    $data = $_POST["countries"][$code];
                    $data["eu_member"] = isset($data["eu_member"]) ? 'Y' : 'N';
                    $data["enabled"] = isset($data["enabled"]) ? 1 : 0;
                    $country->set("properties", $data);
                    $country->update();
                }
            }
        }

		$this->obligatorySetStatus("updated");
    }

	function action_add()
	{
		if ( empty($_POST["code"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("code");
			return;
		}

        $country = func_new("Country");
        if ( $country->find("code='".$_POST["code"]."'") ) {
            $this->set("valid", false);
            $this->obligatorySetStatus("exists");
            return;
        }

		if ( empty($_POST["country"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("country");
			return;
		}

		if ( empty($_POST["charset"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("charset");
			return;
		}

		$country->set("properties", $_POST);
		$country->set("eu_member", isset($_POST["eu_member"]) ? 'Y' : 'N');
		$country->set("enabled", isset($_POST["enabled"]) ? 1 : 0);
		$country->create();

		$this->obligatorySetStatus("added");
	}

	function action_delete()
	{
		$countries = $_POST["delete_countries"];

		if ( is_array($countries) && count($countries) > 0 ) {
			foreach ($countries as $code) {
				$country = func_new("Country", $code);
				$country->delete();
			}
		}

		$this->obligatorySetStatus("deleted");
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

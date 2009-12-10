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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Component base class
*
* @package Base
* @version $Id: Component.php,v 1.3 2008/10/23 11:52:55 sheriff Exp $
*/
class Module_DemoMode_Component extends Component
{

	function init()
	{
		if ($this->xlite->is("adminZone")) {
			foreach ($_REQUEST as $name=>$value) {
				if (isset($_REQUEST[$name])) {
                    $_REQUEST[$name] = $this->_validateRequestDataDemo($_REQUEST[$name], $name);
                }
                if (isset($_GET[$name])) {
                    $_GET[$name] = $this->_validateRequestDataDemo($_GET[$name], $name);
                }
                if (isset($_POST[$name])) {
                    $_POST[$name] = $this->_validateRequestDataDemo($_POST[$name], $name);
                }
			}
		}

		parent::init();
	}

	function _needStripHTMLtagsDemo($name)
	{
		if (isset($_REQUEST["target"]) && $_REQUEST["target"] == "news_messages") {
			return in_array($name, array("body", "subject"));
		}

		switch ($name) {
			case "sku":
			case "name":
			case "description":
			case "brief_description":
			case "meta_desc":
				return true;
			break;

			default:
				return false;
			break;
		}

		return false;
	}

	function _stripTagsDemo($value, $name) {
		$value = $this->_stripSQLinjection($value, $name);

		if ($this->_needStripHTMLtagsDemo($name)) {
			// strip all HTML tags
			$value = strip_tags($value);
		}

		return $value;
	}

	function _stripArrayTagsDemo(&$data, $name)
	{
		foreach($data as $key => $value) {
			if (!is_array($value)) {
				$data[$key] = $this->_stripTagsDemo($value, $name);
			} else {
				$data[$key] = $this->_stripArrayTagsDemo($value, $name);
			}
		}

		return $data;
	}

	function _validateRequestDataDemo(&$value, $name)
	{
		if (!is_array($value)) {
			$value = $this->_stripTagsDemo($value, $name);
		} else {
			$value = $this->_stripArrayTagsDemo($value, $name);
		}

		return $value;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

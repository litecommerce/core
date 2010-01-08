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
* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4:
*/

/**
* @package FlyoutCategories Module
* @access public
* @version $Id$
*/
class XLite_Module_FlyoutCategories_Controller_Admin_Abstract extends XLite_Controller_Admin_Abstract implements XLite_Base_IDecorator
{

    function init()
    {
		parent::init();

		$target = $this->get("target");
		if ($target == "category" || $target == "categories") {
			switch ($this->get("action")) {
				case "update":
				case "delete":
				case "modify":
				case "add":
				case "delete":
				case "icon":
					if ($this->get("config.FlyoutCategories.scheme") > 0) {
						$config = new XLite_Model_Config();
						$config->createOption("FlyoutCategories", "category_changed", 1);
					}

					if (in_array($this->get("action"), array("add", "modify", "delete")))
						break;

					// rebuild layout
					if ($this->get("config.FlyoutCategories.category_autoupdate")) {
						$dialog = new XLite_Controller_Admin_Categories();

						$return_url = null;
						if ($this->get("target") == "category" && $this->get("action") == "add" && $this->get("message") == "added") {
							$return_url = $this->shopURL("admin.php?target=category&category_id=".$this->get("category_id")."&mode=modify&message=added&page=category_modify");
						}

						if ($this->xlite->get("AutoUpdateCatalogEnabled")) {
							$dialog->set("silent", true);
						}
						$dialog->action_build_categories($return_url);
					}
				break;
			}
		}

		if ($target == "memberships" && $this->get("action")) {
			if ($this->get("config.FlyoutCategories.scheme") > 0) {
				$config = new XLite_Model_Config();
				$config->createOption("FlyoutCategories", "category_changed", 1);
			}

			// Rebuild FlyoutCategories cache
			if ($this->get("config.FlyoutCategories.category_autoupdate")) {
				$dialog = new XLite_Controller_Admin_Categories();
				$dialog->action_build_categories();
			}
		}
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

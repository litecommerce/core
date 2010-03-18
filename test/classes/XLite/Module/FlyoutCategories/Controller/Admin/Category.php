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
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Module_FlyoutCategories_Controller_Admin_Category extends XLite_Controller_Admin_Category implements XLite_Base_IDecorator
{

	function init()
	{
		parent::init();

		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		$this->xlite->gdlib_enabled = (FlyoutCategories_gdLibEnabled()) ? 1 : 0;
	}

	function getSmallImageWidth()
	{
		return $this->xlite->getComplex('config.FlyoutCategories.smallimage_width');
	}

    function action_modify()
    {
		$_POST["smallimage_auto"] = ($_POST["smallimage_auto"]) ? 1 : 0;
		if ($_POST["smallimage_auto"] && !$_REQUEST["smallimage_filesystem"]) {
			$_REQUEST["smallimage_filesystem"] = $_REQUEST["image_filesystem"];
		}

		parent::action_modify();

		$category = $this->get("category");
        $image = $category->get("smallImage");
        $result = $image->handleRequest();

		// resize
		if ($this->xlite->gdlib_enabled) {
			$obj = null;

			$resize = false;
			if ($category->get("smallimage_auto") || $_POST["smallimage_generate"]) {
				$obj = $category->get("image");
				$resize = true;
			}

			if ($resize || ($result == IMAGE_OK && $category->hasSmallImage()))
				$category->resizeSmallImage($this->get("smallImageWidth"), $obj, (bool) $_REQUEST["smallimage_filesystem"]);
		}

		// rebuild cache if new category added
		if ($this->getComplex('config.FlyoutCategories.category_autoupdate')) {
			$dialog = new XLite_Controller_Admin_Categories();
			$dialog->action_build_categories($this->category_update_return_url());
		}
    }

    function action_add()
    {
		$_POST["smallimage_auto"] = ($_POST["smallimage_auto"]) ? 1 : 0;
		if ($_POST["smallimage_auto"] && !$_REQUEST["smallimage_filesystem"]) {
			$_REQUEST["smallimage_filesystem"] = $_REQUEST["image_filesystem"];
		}

		parent::action_add();

		if ($this->get("message") == "added") {
			$category = new XLite_Model_Category($this->get("category_id"));

			// upload small image
    	    $image = $category->get("smallImage");
        	$result = $image->handleRequest();

			// resize
			if ($this->xlite->gdlib_enabled) {
				$obj = null;

				$resize = false;
				if ($category->get("smallimage_auto") || $_POST["smallimage_generate"]) {
					$obj = $category->get("image");
					$resize = true;
				}

				if ($resize || ($result == IMAGE_OK && $category->hasSmallImage()))
					$category->resizeSmallImage($this->get("smallImageWidth"), $obj, (bool) $_REQUEST["smallimage_filesystem"]);
			}
		}

		// rebuild cache if new category added
		if ($this->getComplex('config.FlyoutCategories.category_autoupdate')) {
			if ($this->get("target") == "category" && $this->get("action") == "add" && $this->get("message") == "added") {
				$dialog = new XLite_Controller_Admin_Categories();
				$dialog->action_build_categories($this->category_add_return_url());
			}
		}
    }


    function action_small_icon()
    {
		$_REQUEST["smallimage_filesystem"] = $_REQUEST["image_filesystem"];

        $category = $this->get("category");
        // delete category image
        $image = $category->get("smallImage");
        $result = $image->handleRequest();

		if ($this->get("smallimage_delete") && $result == IMAGE_OK) {
			// small icon deleted
			$category->set("smallimage_auto", 0);
			$category->update();
			return;
		}

		// resize
		if ($this->xlite->gdlib_enabled) {
			if ($result == IMAGE_OK && ($category->get("smallimage_auto") || $_POST["smallimage_generate"])) {
				$obj = $category->get("image");
				$category->resizeSmallImage($this->get("smallImageWidth"), $obj, (bool) $_REQUEST["smallimage_filesystem"]);
			}
		}
    }

	function category_add_return_url()
	{
		return $this->getShopUrl("admin.php?target=category&category_id=".$this->get("category_id")."&mode=modify&message=added&page=category_modify");
	}
    
    function category_update_return_url()
	{
		return $this->getShopUrl("admin.php?target=category&category_id=".$this->get("category_id")."&mode=modify&message=updated&page=category_modify");
	}

	function action_delete()
	{
		// remember return URL for category delete request
		$parent_id = 0;
		if ($this->get("category_id") > 0) {
			$c = new XLite_Model_Category($this->get("category_id"));
			$parent_id = $c->get("parent");
		}
		$delete_return_url = $this->getShopUrl("admin.php?target=categories&category_id=$parent_id");

		parent::action_delete();

		// rebuild cache if new category added
		if ($this->getComplex('config.FlyoutCategories.category_autoupdate')) {
			$dialog = new XLite_Controller_Admin_Categories();
			$dialog->action_build_categories($delete_return_url);
		}
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

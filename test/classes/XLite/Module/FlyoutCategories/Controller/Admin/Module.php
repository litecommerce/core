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
*
* @package Module_FlyoutCategories
* @access public
* @version $Id$
*/
class XLite_Module_FlyoutCategories_Controller_Admin_Module extends XLite_Controller_Admin_Module implements XLite_Base_IDecorator
{
	function init()
	{
		parent::init();

		if ($this->page == "FlyoutCategories") {
        	$lay = XLite_Model_Layout::getInstance();
        	$lay->addLayout("general_settings.tpl", "modules/FlyoutCategories/config.tpl");
        }

        $layout = XLite_Model_Layout::getInstance();
	}

	function getSchemes()
	{
        if (!is_null($this->schemes)) {
            return $this->schemes;
        }

		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		$sm = FlyoutCategories_getSchemeManagerDialog($this);

        $sm->initLayout();
		$sm->set("page", "fc_manager");
        $this->schemes = $sm->getSchemes(false);

		return $this->schemes;
	}

	function getOptions()
	{
		$values = array();
		$options = parent::getOptions();
		if ( $this->page == "FlyoutCategories" && is_array($options) ) {
			foreach ($options as $v) {
				if ( $v->get("comment") )
					$values[] = $v;
			}
		} else {
			$values = $options;
		}

		return $values;
	}

	function action_update()
	{
		$oldScheme = $this->get("config.FlyoutCategories.scheme");
		parent::action_update();

		$update = false;
		if ( $this->page == "FlyoutCategories" ) {
			if ($_REQUEST["scheme"] < 0) $_REQUEST["scheme"] = 0;
			if ( $oldScheme != $_REQUEST["scheme"] ) {
				$update = true;
				$this->set("config.FlyoutCategories.scheme", $_REQUEST["scheme"]);
			}
		}

		if ($update) {
			$this->action_rebuild_tree();
		}
	}

	function action_save_rebuild()
	{
		parent::action_update();

		$scheme_id = $this->get("config.FlyoutCategories.scheme");
		$scheme = new XLite_Module_FlyoutCategories_Model_FCategoriesScheme();
		if ( $scheme->find("scheme_id='".$scheme_id."'") ) {
			$properties = $_REQUEST;
			$properties["cat_icons"] = ( $_REQUEST["cat_icons"] ) ? 1 : 0;
			$properties["subcat_icons"] = ( $_REQUEST["subcat_icons"] ) ? 1 : 0;
			$scheme->set("properties", $properties);

			$adv_options = $_REQUEST["adv_options"];
			$options = $scheme->get("options");
			foreach ($options as $k=>$v) {
				if ( $options[$k]["type"] == "check_box" )
					$options[$k]["value"] = "";
				if ( isset($adv_options["$k"]) ) {
					$val = $adv_options["$k"];
					$options[$k]["value"] = $val;
				}
			}
			$scheme->set("options", $options);

			$scheme->update();
		}

		$this->action_rebuild_tree();
	}

	function action_rebuild_tree()
	{
		$this->set("config.FlyoutCategories.force_js_in_layout", (bool) $this->force_js_in_layout);
		$this->params[] = "status";
		if ($this->get("config.FlyoutCategories.scheme") <= 0) {
			$this->set("status", "disabled");
			return;
		}

		$dialog = new XLite_Controller_Admin_Categories();
//		$dialog->set("silent", true);
		if ($dialog->action_build_categories()) {
			$this->set("status", "rebuilt");
		} else {
			$this->set("status", "error");
		}
	}


	function GetFlyoutCatScheme()
	{
		if ( is_null($this->_fc_scheme) ) {
			$scheme = $this->get("config.FlyoutCategories.scheme");
			$this->_fc_scheme = new XLite_Module_FlyoutCategories_Model_FCategoriesScheme($scheme);
			$this->_fc_scheme->get("options");
		}

		return $this->_fc_scheme;
	}

	function IsCategoryOverload()
	{
		return ($this->xlite->get("config.FlyoutCategories.last_categories_processed") > 300) ? true : false;
	}

	function getFlyoutSchemeManagerPageURL()
	{
		return (($this->xlite->get("LayoutOrganizerEnabled")) ? "target=scheme_manager&page=fc_manager" : "target=scheme_manager_fc");
	}
}

?>

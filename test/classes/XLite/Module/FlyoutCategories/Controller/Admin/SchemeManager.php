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
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/

class XLite_Module_FlyoutCategories_Controller_Admin_SchemeManager extends XLite_Module_LayoutOrganizer_Controller_Admin_SchemeManager
{
	var $schemes = null;
	var $scheme = null;
	var $customerLayoutPath = "";

    function init()
    {
		$this->params[] = "page";
        $this->page = "lo_manager";
        $this->pages = array(
			"lo_manager" => "LayoutOrganizer Schemes",
			"fc_manager" => "FlyoutCategories Schemes"
		);

        $this->pageTemplates = array(
			"lo_manager" => "modules/LayoutOrganizer/scheme_manager.tpl",
			"fc_manager" => "modules/FlyoutCategories/scheme_manager.tpl"
		);

		parent::init();

		$this->initLayout();

		$this->session->set("FromFCSchemeManager", null);
    }

    function getPageTemplate()
    {
        if (isset($this->pageTemplates[$this->get("page")])) {
            return $this->pageTemplates[$this->get("page")];
        }
        return null;
    }

	function getDefaultScheme()
	{
		if ($this->get("page") != "fc_manager")
		    return parent::getDefaultScheme();

		require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_getDefaultScheme($this);
	}

	function isSchemeAvailable()
	{
		if ($this->get("page") != "fc_manager")
			return parent::isSchemeAvailable();

		require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_isSchemeAvailable($this);
	}

	function getSchemes($all_schemes=true)
	{
		if ($this->get("page") != "fc_manager")
			return parent::getSchemes($all_schemes);

		if (is_null($this->schemes)) {
			require_once "modules/FlyoutCategories/encoded.php";
			FlyoutCategories_getSchemes($this, $all_schemes);
		}

		return $this->schemes;
	}

	function getCurrentScheme()
	{
		if ($this->get("page") != "fc_manager")
			return parent::getCurrentScheme();

		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_getCurrentScheme($this);

		return $this->scheme;
	}


	function action_fc_update()
	{
		require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_action_update($this);
	}

	function action_fc_update_templates()
	{

		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_action_fc_update_templates($this);
	}

    function action_fc_delete()
    {
        require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_action_delete($this);
    }

    function action_fc_clone()
    {

		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_action_fc_clone($this);
    }

    function copy_scheme_nodes(&$fNode, &$scheme, &$new_scheme)
    {
		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_copy_scheme_nodes($this, $fNode, $scheme, $new_scheme);
    }

	function getSchemeNodesList()
	{
		require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_getSchemeNodesList();
	}

	function isReadOnly($scheme_id)
	{
		if ($this->get("page") != "fc_manager")
			return parent::isReadOnly($scheme_id);

        require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_isReadOnly($scheme_id);
	}
	
	function isInvariable($scheme_id)
	{
		if ($this->get("page") != "fc_manager")
			return parent::isInvariable($scheme_id);

        require_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_isInvariable($scheme_id);
	}

    function action_rebuild_tree()
    {

		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_action_rebuild_tree($this);
    }

    function action_delete_option()
    {
		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_action_delete_option($this);
    }

    function action_add_option()
    {
		require_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_action_add_option($this);
    }


//////////// Edit scheme options section ///////////////
    function getOptionParams()
    {
        if ($this->option_name == "")
            return;

        $scheme = $this->get("currentScheme");
        $options = $scheme->get("options");

        $option = $options[$this->option_name];
        $option["points_str"] = (!is_array($option["points"])) ? "" : implode("\n", $option["points"]);

        return $option;
    }

    function action_edit_option()
    {
        $this->params[] = "option_name";
        $this->set("option_name", $this->option_name);
    }

    function action_update_option()
    {
        include_once "modules/FlyoutCategories/encoded.php";
        FlyoutCategories_action_update_option($this);
	}

    function action_cancel_update_option()
    {
        $this->option_name = null;

        $this->params[] = "status";
        $this->set("status", "canceled");
    }

	function action_expert_mode()
	{
		$this->session->set("fc_expert_mode", true);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

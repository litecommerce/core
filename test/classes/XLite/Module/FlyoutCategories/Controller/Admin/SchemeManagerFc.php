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

class XLite_Module_FlyoutCategories_Controller_Admin_SchemeManagerFc extends XLite_Controller_Admin_TemplateEditor
{	
	public $schemes = null;	
	public $scheme = null;	
	public $customerLayoutPath = "";

    public function __construct(array $params)
    {
    	$this->params[] = "scheme_id";
    	foreach($this->params as $k => $v) {
        	if ($v == "editor") {
        		unset($this->params[$k]);
        	}
        	if ($v == "zone") {
        		unset($this->params[$k]);
        	}
    	}
    	parent::__construct($params);
    }

    function getPageTemplate()
    {
        if (isset($this->pageTemplates[$this->get("page")])) {
            return $this->pageTemplates[$this->get("page")];
        }
        return null;
    }

    function initLayout()
    {
    	if (strlen($this->customerLayoutPath) == 0) {
    		$layout = new XLite_Model_Layout();
            global $options;
            // reset Layout settings to customer default
            $layout->set("skin", $options["skin_details"]["skin"]);
            $layout->set("locale", $options["skin_details"]["locale"]);
    		$this->customerLayoutPath = $layout->getPath();
    	}
    }

    function init()
    {
    	parent::init();

		$this->initLayout();

		$this->session->set("FromFCSchemeManager", null);
    }

	function getDefaultScheme()
	{
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_getDefaultScheme($this);
	}

	function isSchemeAvailable()
	{
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_isSchemeAvailable($this);
	}

	function getSchemes($all_schemes=true)
	{
		if (is_null($this->schemes)) {
			require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
			FlyoutCategories_getSchemes($this, $all_schemes);
		}

		return $this->schemes;
	}

	function getCurrentScheme()
	{
		if (!$this->isSchemeAvailable()) {
			return null;
		}

		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_getCurrentScheme($this);

		return $this->scheme;
	}


	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row,$css_class, $reserved = null)
	{
		return ($this->isOddRow($row)) ? "" : $css_class;
	}


	function action_fc_update()
	{
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_action_update($this);
	}

	function action_fc_update_templates()
	{
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_fc_update_templates($this);
	}

    function action_fc_delete()
    {
        require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_action_delete($this);
    }

    function action_fc_clone()
    {
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_fc_clone($this);
    }

    function copy_scheme_nodes(&$fNode, &$scheme, &$new_scheme)
    {
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_copy_scheme_nodes($this, $fNode, $scheme, $new_scheme);
    }

	function getSchemeNodesList()
	{
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_getSchemeNodesList();
	}

	function isReadOnly($scheme_id)
	{
        require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_isReadOnly($scheme_id);
	}
	
	function isInvariable($scheme_id)
	{
        require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		return FlyoutCategories_isInvariable($scheme_id);
	}

    function action_rebuild_tree()
    {
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_rebuild_tree($this);
    }

    function action_delete_option()
    {
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_delete_option($this);
    }

    function action_add_option()
    {
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_add_option($this);
    }

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
		require_once LC_MODULES_DIR . 'FlyoutCategories' . LC_DS . 'encoded.php';
		FlyoutCategories_action_update_option($this);
	}

	function action_cancel_update_option()
	{
		$this->option_name = null;

		$this->params[] = "status";
		$this->set("status", "opt_canceled");
	}

	function action_expert_mode()
	{
		$this->session->set("fc_expert_mode", true);
	}

    function action_simple_mode()
    {   
        $this->session->set("fc_expert_mode", null);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

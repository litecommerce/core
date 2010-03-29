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
* Admin_Dialog_Scheme_Manager description.
*
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class XLite_Module_LayoutOrganizer_Controller_Admin_SchemeManager extends XLite_Controller_Admin_TemplateEditor
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

		$this->session->set("FromSchemeManager", null);
    }

	function getDefaultSchemeName()
	{
		return "[DEFAULT]";
	}

	function getDefaultScheme()
	{
		$this->initLayout();

		$scheme = new XLite_Module_LayoutOrganizer_Model_TemplatesScheme();
		$scheme->set("scheme_id", 0);
		$scheme->set("type", 0);
		$scheme->set("enabled", 1);
		$scheme->set("name", $this->getDefaultSchemeName());
		$scheme->set("order_by", 0);
		$scheme->set("cat_template", $this->customerLayoutPath . $this->getComplex('config.LayoutOrganizer.template'));
		$scheme->set("scat_template", $this->customerLayoutPath . $this->getComplex('config.General.subcategories_look'));
		$scheme->set("prod_template", $this->customerLayoutPath . "product_details.tpl");

		return $scheme;
	}

	function isSchemeAvailable()
	{
		if (!isset($this->scheme_id)) {
			return false;
		}
		if (strlen($this->scheme_id) == 0) {
			return false;
		}
		$this->scheme_id = intval($this->scheme_id);
		return ($this->scheme_id >= 0) ? true : false;
	}

	function getSchemes($all_schemes=true)
	{
		if (!is_null($this->schemes)) {
			return $this->schemes;
		}

		$scheme = new XLite_Module_LayoutOrganizer_Model_TemplatesScheme();
		$condition = array();
		$condition[] = "scheme_id > '0'";
		if (!$all_schemes) {
			$condition[] = "enabled = '1'";
		}
		$condition[] = "type = '0'";
		$condition = implode(" AND ", $condition);
		$this->schemes = $scheme->findAll($condition);

		$scheme = $this->getDefaultScheme();
		$this->schemes = array_merge(array($scheme), $this->schemes);

		return $this->schemes;
	}

	function getCurrentScheme()
	{
		if (!$this->isSchemeAvailable()) {
			return null;
		}

		if (!is_null($this->scheme)) {
			return $this->scheme;
		}

		$this->getSchemes();
		foreach($this->schemes as $scheme) {
			if ($this->scheme_id == $scheme->get("scheme_id")) {
				$this->scheme = $scheme;
				break;
			}
		}
		return $this->scheme;
	}

	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row, $css_class, $reserved = null)
	{
		return ($this->isOddRow($row)) ? "" : $css_class;
	}

    function action_delete()
    {
        require_once LC_MODULES_DIR . 'LayoutOrganizer' . LC_DS . 'encoded.php';
		return LayoutOrganizer_action_delete($this);
    }

    function action_clone()
    {
		if (!isset($this->modified_scheme_id)) {
        	$this->params[] = "status";
            $this->set("status" , "clone_failed");

			return;
		}
		if (strlen($this->modified_scheme_id) == 0) {
        	$this->params[] = "status";
            $this->set("status" , "clone_failed");

			return;
		}

		$saved_scheme_id = $this->scheme_id;
		$this->scheme_id = intval($this->modified_scheme_id);
		$scheme = $this->getCurrentScheme();
		if (!is_object($scheme)) {
			$this->scheme_id = $saved_scheme_id;
        	$this->params[] = "status";
            $this->set("status" , "clone_failed");

			return;
		}

		$new_scheme = new XLite_Module_LayoutOrganizer_Model_TemplatesScheme();
		$new_scheme->set("name", $scheme->get("name") . " (clone)");
		$new_scheme->create();

    	$fNode = new XLite_Model_FileNode();
    	$fNode->path = $this->customerLayoutPath . "modules/LayoutOrganizer/schemes";
    	$fNode->createDir();
    	$fNode->path = $fNode->path . "/" . $new_scheme->getFileName();
    	$fNode->createDir();

    	$new_scheme_dir = $fNode->path;
		$this->copy_templates_scheme($fNode, $scheme, $new_scheme, $new_scheme_dir);
		$new_scheme->set("order_by", $scheme->get("order_by"));
		$new_scheme->update();

    	$this->params[] = "status";
        $this->set("status" , "cloned");
		$this->scheme_id = $saved_scheme_id;
    }

    function copy_template(&$fNode, &$scheme, &$new_scheme, $new_scheme_dir, $template)
    {
    	$fNode->path = $scheme->get($template);
		$new_scheme->set($template, $new_scheme_dir . "/" . $template . ".tpl");
    	$fNode->newPath = $new_scheme->get($template);
    	$fNode->copy();
    }

    function copy_templates_scheme(&$fNode, &$scheme, &$new_scheme, $new_scheme_dir)
    {
    	$templates = array("cat_template", "scat_template", "prod_template");
    	foreach($templates as $template) {
    		$this->copy_template($fNode, $scheme, $new_scheme, $new_scheme_dir, $template);
    	}
    }

    function action_update()
    {
        require_once LC_MODULES_DIR . 'LayoutOrganizer' . LC_DS . 'encoded.php';
		return LayoutOrganizer_action_update($this);
	}

    function action_update_templates()
    {
		if (!$this->isSchemeAvailable()) {
        	$this->params[] = "status";
            $this->set("status" , "update_failed");

			return;
		}
		if ($this->scheme_id == 0) {
        	$this->params[] = "status";
            $this->set("status" , "update_failed");

			return;
		}
		$scheme = $this->getCurrentScheme();
		if (!is_object($scheme)) {
        	$this->params[] = "status";
            $this->set("status" , "update_failed");

			return;
		}

		$updated = false;
		if (isset($this->cat_template)) {
			$scheme->set("cat_template", $this->cat_template);
			$updated = true;
		}
		if (isset($this->scat_template)) {
			$scheme->set("scat_template", $this->scat_template);
			$updated = true;
		}
		if (isset($this->prod_template)) {
			$scheme->set("prod_template", $this->prod_template);
			$updated = true;
		}
		if ($updated) {
			$scheme->update();
		}

        $this->params[] = "status";
        $this->set("status" , "updated");
    }
	
	function isReadOnly($scheme_id)
	{
        require_once LC_MODULES_DIR . 'LayoutOrganizer' . LC_DS . 'encoded.php';
		return LayoutOrganizer_isReadOnly($scheme_id);
	}
	
	function isInvariable($scheme_id)
	{
        require_once LC_MODULES_DIR . 'LayoutOrganizer' . LC_DS . 'encoded.php';
		return LayoutOrganizer_isInvariable($scheme_id);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

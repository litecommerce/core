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
* Admin_Dialog_category_LayoutOrganizer description.
*
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class XLite_Module_LayoutOrganizer_Controller_Admin_Category extends XLite_Controller_Admin_Category implements XLite_Base_IDecorator
{	
    public $page = "category_modify";	
	public $schemes = null;

    function init()
    {
        if (!(isset($this->pages) && is_array($this->pages))) {
            $this->pages = array("category_modify" => "Add/Modify category");
        }

        if (!(isset($this->pageTemplates) && is_array($this->pageTemplates))) {
            $this->pageTemplates = array("category_modify" => "categories/add_modify_body.tpl");
        }
        $this->pageTemplates["category_templates"] = "modules/LayoutOrganizer/category.tpl";

    	if (!in_array("page", $this->params)) {
    		$this->params[] = "page";
    	}

    	parent::init();

    	if ($this->mode != "add") {
    		if ($this->mode == "modify") {
				$this->pages["category_modify"] = "Modify category";
    		}
			$this->pages["category_templates"] = "Templates";
		} else {
			$this->pages["category_modify"] = "Add new category";
		}
    }

    function action_modify_templates()
    {
        if (isset($this->category_id) && $this->category_id > 0) {
        	$category = new XLite_Model_Category($this->category_id);
        	if ($this->view != "advanced") {
        		$this->updateCategoryTemplate($category, $this->custom_template, "custom_template");
            	$this->updateCategoryTemplate($category, $this->custom_template, "sc_custom_template");
            	$this->updateCategoryTemplate($category, $this->custom_template, "p_custom_template");
        	} else {
        		$this->updateCategoryTemplate($category, $this->custom_template, "custom_template");
            	$this->updateCategoryTemplate($category, $this->sc_custom_template, "sc_custom_template");
            	$this->updateCategoryTemplate($category, $this->p_custom_template, "p_custom_template");
        	}
            $category->update();
			$category->updateChildrenTemplates();
        }
    }

    function updateCategoryTemplate($category, $scheme_id, $template_type)
    {
    	$category->setComplex($template_type, $scheme_id);
        $category->update();
        if ($scheme_id < 0) {
        	$category->updateTemplate($template_type, $category->getParentTemplate($template_type));
        } elseif ($scheme_id == 0) {
        	$category->updateTemplate($template_type, $category->getDefaultTemplate($template_type));
        } else {
			$scheme = new XLite_Module_LayoutOrganizer_Model_TemplatesScheme($scheme_id);
			if (is_object($scheme)) {
				$category->updateTemplate($template_type, $scheme->getTemplate($template_type));
			} else {
        		$category->updateTemplate($template_type, $category->getDefaultTemplate($template_type));
			}
        }
    }
	
	function getSchemes()
	{
		if (!is_null($this->schemes)) {
			return $this->schemes;
		}

    	$sm = new XLite_Module_LayoutOrganizer_Controller_Admin_SchemeManager();
		$sm->initLayout();
    	$sm->getSchemes(false);
    	$this->schemes = $sm->schemes;
		return $this->schemes;
	}

	function isSimpleScheme()
	{
		if (!is_object($this->category)) {
			return false;
		}

		if (isset($this->view) && $this->view == "advanced") {
			return false;
		} else {
			return $this->category->isSimpleScheme();
		}
	}

	function getCategoryScheme($scheme)
	{
		if (!is_object($this->category)) {
			return 0;
		}
		$scheme = ($this->category->get($scheme."_enabled")) ? $this->category->get($scheme) : 0;
		return $scheme;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

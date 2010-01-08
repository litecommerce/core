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
* Class Category provides access to shopping cart category.
*
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class XLite_Module_LayoutOrganizer_Model_Category extends XLite_Model_Category implements XLite_Base_IDecorator
{
	public function __construct($c_id = null)
	{
		$this->fields["custom_template"] = -1;
		$this->fields["custom_template_enabled"] = 1;
		$this->fields["template_name"] = "";
		$this->fields["sc_custom_template"] = -1;
		$this->fields["sc_custom_template_enabled"] = 1;
		$this->fields["sc_template_name"] = "";
		$this->fields["p_custom_template"] = -1;
		$this->fields["p_custom_template_enabled"] = 1;
		$this->fields["p_template_name"] = "";
		parent::__construct($c_id);
	}

	function getDefaultTemplate($template_type)
	{
		switch ($template_type) {
			case "custom_template":
            	if ($this->xlite->get("reReadConfig")) {
            		$cfg = new XLite_Model_Config();
            		$this->config = $cfg->readConfig();
            	}
			return $this->config->get("LayoutOrganizer.template");
			case "sc_custom_template":
            	if ($this->xlite->get("reReadConfig")) {
            		$cfg = new XLite_Model_Config();
            		$this->config = $cfg->readConfig();
            	}
			return $this->config->get("General.subcategories_look");
			case "p_custom_template":
			return "product_details.tpl";
		}
	}

	function getParentTemplate($template_type)
	{
		$parent = $this->get("parent");
		if ($parent > 0) {
			$parent = new XLite_Model_Category($parent);
			if (is_object($parent)) {
				if ($parent->get($template_type) < 0) {
					return $parent->getParentTemplate($template_type);
				} elseif ($parent->get($template_type) == 0) {
					return $parent->getDefaultTemplate($template_type);
				} else {
            		switch ($template_type) {
            			case "custom_template":
            			return $parent->get("template_name");
            			case "sc_custom_template":
            			return $parent->get("sc_template_name");
            			case "p_custom_template":
            			return $parent->get("p_template_name");
            		}
				}
			} else {
				return $this->getDefaultTemplate($template_type);
			}
		} else {
			return $this->getDefaultTemplate($template_type);
		}
	}

	function updateTemplate($template_type, $template)
	{
		$tplMap = array
		(
			"custom_template" => "template_name",
			"sc_custom_template" => "sc_template_name",
			"p_custom_template" => "p_template_name",
		);
        
        $this->set($tplMap[$template_type], $template);
	}

	function getTemplate($template_type)
	{
		$tplMap = array
		(
			"custom_template" => "template_name",
			"sc_custom_template" => "sc_template_name",
			"p_custom_template" => "p_template_name",
		);
        
        if ($this->get($template_type) < 0) {
        	return $this->getParentTemplate($template_type);
        } elseif ($this->get($template_type) == 0) {
        	return $this->getDefaultTemplate($template_type);
        } else {
        	return $this->get($tplMap[$template_type]);
        }
	}

	function updateChildrenTemplates($only_categories = false)
	{
        require_once "modules/LayoutOrganizer/encoded.php";
		LayoutOrganizer_updateChildrenTemplates($this, $only_categories);
	}

	function enableChildren($only_categories = false)
	{
        require_once "modules/LayoutOrganizer/encoded.php";
		LayoutOrganizer_enableChildren($this, $only_categories);
	}

    function read()
    {
    	$persistent = $this->isPersistent;

    	$result = parent::read();

        if ($result && $persistent) {
        	if ($this->get("category_id") > 0 && $this->get("parent") == 0) {
        		$updated = false;
        		if ($this->get("custom_template") == -1) {
        			$this->set("custom_template", 0);
        			$updated = true;
        		}
        		if ($this->get("sc_custom_template") == -1) {
        			$this->set("sc_custom_template", 0);
        			$updated = true;
        		}
        		if ($this->get("p_custom_template") == -1) {
        			$this->set("p_custom_template", 0);
        			$updated = true;
        		}
        		if ($updated) {
        			$this->update();
        		}
        	}
        }

        return $result;
    }

	function isSimpleScheme()
	{
		if ($this->get("custom_template") == $this->get("sc_custom_template") && $this->get("custom_template") == $this->get("p_custom_template")) {
			return true;
		} else {
			return false;
		}
	}
    
    // KOI8-R comment: по сути, возвращает то же самое, что и 
    // метод getProduct(), только (!!!) getProduct возвращает
    // массив Product, а этот метод - массив первичных ключей
    // (не совсем массив, там массив массивов)
    function getProductIDs($where) {
        if ($this->isPersistent) {
            $p = new XLite_Model_ProductFromCategory($this->get("category_id"));
            $p->fetchObjIdxOnly = true;
            return $p->findAll($where);
        } else {
            return array();
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

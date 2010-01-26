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
* Category navigation dialog.
*
* @package Module_LayoutOrganizer
* @access public
* @version $Id$
*/
class XLite_Module_LayoutOrganizer_Controller_Customer_Category extends XLite_Controller_Customer_Category implements XLite_Base_IDecorator
{	
	public $_rowNumber;
	
    function init()
    {
        $layout = XLite_Model_Layout::getInstance();
		if (isset($_REQUEST["category_id"]) && $_REQUEST["category_id"] > 0) {
			$category_id = $_REQUEST["category_id"];
            $category = new XLite_Model_Category($category_id);
        }

        // default products list template
        $template = $this->getComplex('config.LayoutOrganizer.template');
        if (is_object($category) && $category->get("custom_template") != 0 && $category->get("custom_template_enabled")) {
			$template_custom = $category->get("template_name");
			if (strlen($template_custom) > 0) {
    			$localPath = strpos($template_custom, $layout->getPath());
    			if ($localPath !== false && $localPath == 0) {
    				$template_custom = substr($template_custom, strlen($layout->getPath()));
    			}
				$template = $template_custom;
    		}
        }
        // overriding default products list template
		$layout->addLayout("category_products.tpl", $template);
        
        // default subcategories list template
        if (is_object($category) && $category->get("sc_custom_template") != 0 && $category->get("sc_custom_template_enabled")) {
			$template = $category->get("sc_template_name");
			if (strlen($template) > 0) {
    			$localPath = strpos($template, $layout->getPath());
    			if ($localPath !== false && $localPath == 0) {
    				$template = substr($template, strlen($layout->getPath()));
    			}
				// overriding default subcategories list template
				$layout->addLayout($this->getComplex('config.General.subcategories_look'), $template);
    		}
        }
        
        parent::init();
    }

	function getPercents($columns)
	{
		return (int) (100 / $columns);
	}

	function getRowNumber()
	{
		if (!isset($this->_rowNumber)) {
			$this->_rowNumber = 0;
		}
		return (++ $this->_rowNumber);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

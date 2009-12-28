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
* @package View
* @access public
* @version $Id$
*/
class XLite_View_CategorySelect extends XLite_View
{
    var $categories;
    var $field;
    var $formName;
    var $selectedCategory = null;
    var $allOption = false;
    var $noneOption = false;
    var $template = "common/select_category.tpl";

    function getCategoriesCondition()
    {
    	return array(null, null, null, null);
    }

    function getCategories()
    {
       if (is_null($this->categories)) {
            $c = new XLite_Model_Category();
            $names = array();
            $names_hash = array();

            list($where, $orderby, $groupby, $limit) = $this->getCategoriesCondition();
        	if ($this->rootOption && $this->currentCategory > 0) {
            	$categories = $c->findAll($where, $orderby, $groupby, $limit);
            	$this->categories = array();
            	$currentCategory = new XLite_Model_Category($this->currentCategory);
            	$currentCategoryPath = $currentCategory->getStringPath() . "/";
            	for ($i=0; $i<count($categories); $i++) {
            		$name = $categories[$i]->getStringPath();
            		if (!(strpos($name, $currentCategoryPath) === false) && strpos($name, $currentCategoryPath) == 0) {
            			continue;
            		}
            		$this->categories[] = $categories[$i];
            	}
        	} else {
            	$this->categories = $c->findAll($where, $orderby, $groupby, $limit);
        	}

            for ($i=0; $i<count($this->categories); $i++) {
            	$name = $this->categories[$i]->getStringPath();
            	while (isset($names_hash[$name])) {
            		$name .= " ";
            	}
            	$names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->categories);
        }
        return $this->categories;
    }

    function getSelectedCategory()
    {
        if (is_null($this->selectedCategory) && !is_null($this->field)) {
            $this->selectedCategory = $this->get("component." . $this->field);
        }
        return $this->selectedCategory;
    }
    
    function setFieldName($name)
    {
        $this->formField = $name;
        $pos = strpos($name, "[");
        if ($pos===false) {
            $this->field = $name;
        } else {
            $this->field = substr($name, $pos+1, -1);
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

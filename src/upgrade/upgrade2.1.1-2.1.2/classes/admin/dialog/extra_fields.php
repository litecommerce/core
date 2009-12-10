<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* Product extra-fields management.
*
* @package Dialog
* @access public
* @version $Id: extra_fields.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*
*/
class Admin_Dialog_extra_fields extends Admin_Dialog
{
    function fillForm()
    {
        if (!isset($this->name)) {
            $ef =& func_new("ExtraField");
            $this->set("properties", $ef->fields);
        }
        parent::fillForm();
    }
    
    function isCategorySelected($name, $categoryID)
    {
        return in_array($categoryID, (array)$_POST[$name]);
    }
    
    function &getCategories()
    {
        if (is_null($this->categories)) {
            $c =& func_new("Category");
            $this->categories =& $c->findAll();
            $names = array();
            $names_hash = array();
            for ($i = 0; $i < count($this->categories); $i++) {
            	$name = $this->categories[$i]->get("stringPath");
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

    function &getExtraFields()
    {
        if (is_null($this->extraFields)) {
            $ef =& func_new("ExtraField");
            $this->extraFields = $ef->findAll("product_id=0");  // global fields
        }
        return $this->extraFields;
    }
    
    function action_update_fields()
    {
        if (!is_null($this->get("delete")) && !is_null($this->get("delete_fields"))) {
            foreach ((array)$this->get("delete_fields") as $id) {
                $ef =& func_new("ExtraField", $id);
                $ef->delete();
            }
        } elseif (!is_null($this->get("update"))) {
            foreach ((array)$this->get("extra_fields") as $id => $data) {
                $ef =& func_new("ExtraField", $id);
                $ef->set("properties", $data);
                $ef->update();
            }
        }
    }
    
    function action_add_field()
    {
        // ADD field
        if (!is_null($this->get("add_field"))) {
            // buld add
            $categories = (array)$this->get("add_categories");
            if (!empty($categories)) {
                foreach ($categories as $categoryID) {
                    $category =& func_new("Category", $categoryID);
                    foreach ((array)$category->get("products") as $product) {
                        $ef =& func_new("ExtraField");
                        $ef->set("properties", $_POST);
                        $ef->set("product_id", $product->get("product_id"));
                        $ef->create();
                    }
                }
            } else {    
                $ef =& func_new("ExtraField");
                $ef->set("properties", $_POST);
                $ef->create();
            }    
        }
        // DELETE field
        elseif (!is_null($this->get("delete_field"))) {
            foreach ((array)$this->get("add_categories") as $categoryID) {
                $category =& func_new("Category", $categoryID);
                foreach ((array)$category->get("products") as $product) {
                    $ef =& func_new("ExtraField");
                    if ($ef->find("name='".addslashes($this->get("name"))."' AND product_id=".$product->get("product_id"))) {
                        $ef->delete();
                    }    
                }
            }
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

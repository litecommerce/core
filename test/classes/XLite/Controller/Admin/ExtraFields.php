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
* Product extra-fields management.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_ExtraFields extends XLite_Controller_Admin_Abstract
{	
	public $_categories = null;

    function fillForm()
    {
        if (!isset($this->name)) {
            $ef = new XLite_Model_ExtraField();
            $this->set("properties", $ef->fields);
        }
        parent::fillForm();
    }
    
    function isCategorySelected($name, $categoryID)
    {
        return (isset(XLite_Core_Request::getInstance()->$name) && is_array(XLite_Core_Request::getInstance()->$name)) ? in_array($categoryID, XLite_Core_Request::getInstance()->$name) : false;
    }
    
    function getCategories() // {{{
    {
        if (is_null($this->_categories)) {
            $c = new XLite_Model_Category();
            $this->_categories = $c->findAll();
            $names = array();
            $names_hash = array();
            for ($i = 0; $i < count($this->_categories); $i++) {
                $name = $this->_categories[$i]->get("stringPath");
                while (isset($names_hash[$name])) {
                    $name .= " ";
                }
                $names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->_categories);
        }
        return $this->_categories;
    } // }}}

    function getExtraFields()
    {
        if (is_null($this->extraFields)) {
            $ef = new XLite_Model_ExtraField();
            $this->extraFields = $ef->findAll("product_id=0");  // global fields
        }
        return $this->extraFields;
    }
    
    function action_update_fields()
    {
        if (!is_null($this->get("delete")) && !is_null($this->get("delete_fields")) && $this->get("delete") == "delete") {
            foreach ((array)$this->get("delete_fields") as $id) {
                $ef = new XLite_Model_ExtraField($id);
                $ef->delete();
            }
        } elseif (!is_null($this->get("update"))) {
            foreach ((array)$this->get("extra_fields") as $id => $data) 
            {
                $rewrite = !(isset($data['rewrite']) && $data['rewrite'] == "yes");
                $ef = new XLite_Model_ExtraField($id);
                $ef->set("categories_old", $ef->get("categories"));

                if($data['global'] == 0){
                    $data['categories'] = '';
                    if($ef->get("categories") != ""){
                        if($rewrite){
                            
                        } else {
                            $ef->set("categories_old", "");
                        }
                    } else {
                        if($rewrite){
                            $old = array();
                            $category = new XLite_Model_Category();
                            $categories = $category->findAll();
                            foreach ($categories as $category) {
                                $old[] = $category->get("category_id");
                            }
                            $old = implode("|", $old);
                            $ef->set("categories_old", $old);
                        } else {

                        }
                    }
                } else {
                    if($ef->get("categories") != ""){
                        if($rewrite){
                            $ef->set("categories_old", "");  
                        } else {
                        }
                    } else {
                        if($rewrite){
                            
                        } else {
                            $old = array();
                            $category = new XLite_Model_Category();
                            $categories = $category->findAll();
                            foreach ($categories as $category) {
                                $old[] = $category->get("category_id");
                            }
                            $old = implode("|", $old);
                            $ef->set("categories_old", $old);
                        }
                    }
                }

                $ef->set("properties", $data);
                $ef->update();
            }
       }
    }
    
    function action_add_field()
    {
        if (!is_null($this->get("add_field"))) {
            $categories = (array)$this->get("add_categories");

            $ef = new XLite_Model_ExtraField();
            $ef->set("properties", XLite_Core_Request::getInstance()->getData());
            if (!empty($categories)) {
                $ef->setCategoriesList($categories);
            }
            $ef->create();
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

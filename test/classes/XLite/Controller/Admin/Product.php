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
* Product modify dialog
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Admin_Product extends XLite_Controller_Admin_Abstract
{	
    public $params = array('target', 'product_id', 'page', 'backUrl');	
    public $page = "info";	
    public $backUrl = "admin.php?target=product_list";	

    public $pages = array
    (
    	'info'  => 'Product info',
        'extra_fields' => 'Extra fields',
        'links' => 'HTML links',
    );	

    public $pageTemplates = array
    (
    	'info'    => 'product/info.tpl',
        'extra_fields' => 'product/extra_fields_form.tpl',
        'links'   => 'product/links.tpl',
        'default' => 'product/info.tpl'
    );

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->product_id);
        }    

        if (is_null($this->extraFields)) {
        	$this->getExtraFields();
        }

        return $this->product;
    }
    
    function getExtraFields()
    {
		$this->product->populateExtraFields();

        if (is_null($this->extraFields)) {
            $ef = new XLite_Model_ExtraField();
            $this->extraFields = $ef->findAll("product_id=".$this->get("product_id"));
        }
        return $this->extraFields;
    }

    function action_add_field()
    {
        $ef = new XLite_Model_ExtraField();
        $ef->set("properties", XLite_Core_Request::getInstance()->getData());
        $ef->create();
    }


    function action_update_fields()
    {
        if (!is_null($this->get("delete")) && !is_null($this->get("delete_fields"))) {
            foreach ((array)$this->get("delete_fields") as $id) {
                $ef = new XLite_Model_ExtraField($id);
                $ef->delete();
            }
        } elseif (!is_null($this->get("update"))) {
            foreach ((array)$this->get("extra_fields") as $id => $data) {
                $ef = new XLite_Model_ExtraField($id);
                $ef->set("properties", $data);
                $ef->update();
            }
        }
    }
 
    function action_info()
    {
        // update product properties
        $product = new XLite_Model_Product($this->product_id);
        $product->set("properties", XLite_Core_Request::getInstance()->getData());
        $product->update();
        
        // update product image and thumbnail
		$this->action_images();

        // link product category(ies)
		if (isset($this->category_id)) {
			$category = new XLite_Model_Category($this->category_id);
			$product->set("category", $category);
		}

        // update/create extra fields
        $extraFields = (array)$this->get("extra_fields");
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                $fv = new XLite_Model_FieldValue();
                $found = $fv->find("field_id=$id AND product_id=$this->product_id");
                $fv->set("value", $value);
                if ($found) {
                    $fv->update();
                } else {
                    $fv->set("field_id", $id);
                    $fv->set("product_id", $this->product_id);
                    $fv->create();
                }
            }
        }
    }

    function action_images()
    {
        $tn = $this->getComplex('product.thumbnail'); 
        if ($tn->handleRequest() != XLite_Model_Image::IMAGE_OK && $tn->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("thumbnail_read_only", true);
        }

        $img = $this->getComplex('product.image'); 
        if ($img->handleRequest() != XLite_Model_Image::IMAGE_OK && $img->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("image_read_only", true);
        }
    }

	function action_clone()
	{
        $p_product = new XLite_Model_Product($this->product_id);
		$product = $p_product->cloneObject();
		foreach($p_product->get('categories') as $category) {
			$product->addCategory($category);
		}
		$product->set('name', $product->get('name') . " (CLONE)");
		$product->update();
		$this->set('returnUrl', 'admin.php?target=product&product_id=' . $product->get('product_id'));
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

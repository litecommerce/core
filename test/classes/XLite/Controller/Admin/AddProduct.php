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
* Add product dialog
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Admin_AddProduct extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "mode", "product_id");	
    public $product = null;

    function init()
    {
		if (!(isset(XLite_Core_Request::getInstance()->product_id) && !isset(XLite_Core_Request::getInstance()->action) && isset(XLite_Core_Request::getInstance()->mode) && XLite_Core_Request::getInstance()->mode == "notification")) {
			XLite_Core_Request::getInstance()->product_id = null;
		}

    	parent::init();
    }

    function action_add()
    {
        $product = $this->get("product");
        $product->set("properties", XLite_Core_Request::getInstance()->getData());
        $product->create();

        $this->action_images();
        if ($this->get("valid") == false) {
        	$product->delete();
        	return;
        }

        if (isset($this->category_id)) {
            $category = new XLite_Model_Category($this->category_id);
            $product->set("category", $category);
        }

        // update/create extra fields
        $extraFields = (array)$this->get("extra_fields");
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                if (strlen($value)) {
                    $fv = new XLite_Model_FieldValue();
                    $found = $fv->find("field_id=$id AND product_id=".$product->get("product_id"));
                    $fv->set("value", $value);
                    if ($found) {
                        $fv->update(); 
                    } else {
                        $fv->set("field_id", $id);
                        $fv->set("product_id", $product->get("product_id"));
                        $fv->create();
                    }
                }
            }
        }

        $this->set("mode", "notification");
        $this->set("product_id", $product->get("product_id"));
    }

    function action_images()
    {
        $tn = $this->getComplex('product.thumbnail');
        if ($tn->handleRequest() != IMAGE_OK && $tn->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("thumbnail_read_only", true);
        }

        $img = $this->getComplex('product.image'); 
        if ($img->handleRequest() != IMAGE_OK && $img->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("image_read_only", true);
        }
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->get("product_id"));
        }
        return $this->product;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

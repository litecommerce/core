<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* Catalog import dialog
*
* @package Dialog
* @access public
* @version $Id: import_catalog.php,v 1.3 2007/05/21 11:53:27 osipov Exp $
*/
class Admin_Dialog_import_catalog extends Admin_Dialog
{
    var $params = array("target", "page", "import_error");
    var $page = "products"; // the default import page
    var $pages = array('products' => 'Import products',
                       'extra_fields' => 'Import extra fields'
                       );
    var $pageTemplates = array("products" => "product/import.tpl",
                               "extra_fields" => "product/import_fields.tpl"
                               );
    var $category_id = null;

    function handleRequest()
    {
        if (substr($this->action, 0, 6) == "import" && !$this->checkUploadedFile()) {
            $this->set("valid", false);
            $this->set("invalid_file", true);
        }
        
        $name = "";
        if ($this->action == "import_products" || $this->action == "layout") {
            if (!func_is_array_unique($this->product_layout, $name, "NULL")) {
                $this->set("valid", false);
                $this->set("invalid_field_order", true);
                $this->set("invalid_field_name", $name);
            }

            if ($this->action == "import_products" && !in_array("category", $this->product_layout) && $this->category_id == "") {
                $this->set("valid", false);
                $this->set("category_unspecified_error", true);
            }
        }

        if ( ($this->action == "import_fields" || $this->action == "fields_layout") && !func_is_array_unique($this->fields_layout, $name, "NULL") ) {
            $this->set("valid", false);
            $this->set("invalid_field_order", true);
            $this->set("invalid_field_name", $name);
        }
        
        parent::handleRequest();
    }

    function action_import_products()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->product_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            "default_category"  => $this->category_id,
            "delete_products"   => isset($this->delete_products) ? true : false,
            "images_directory"  => $this->images_directory,
            "save_images"       => isset($this->save_images) ? true : false,
			"unique_identifier"	=> $this->unique_identifier,
            );

        $product =& func_new("Product");
        $product->import($options);
    }

    function action_layout($layout_name = "product_layout")
    {
        $layout = implode(',', $_POST[$layout_name]);
        $config =& func_new("Config");
        if ($config->find("name='$layout_name'")) {
            $config->set("value", $layout);
            $config->update();
        } else {
            $config->set("name", $layout_name);
            $config->set("category", "ImportExport");
            $config->set("value", $layout);
            $config->create();
        }
    }

    function action_import_fields()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->fields_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier
            );
         
        $field =& func_new("ExtraField");
        $field->import($options);
    }

    function action_fields_layout()
    {
        $layout_name = "fields_layout";
        $layout = implode(',', $_POST[$layout_name]);
        $config =& func_new("Config");
        if ($config->find("name='$layout_name'")) {
            $config->set("value", $layout);
            $config->update();
        } else {
            $config->set("name", $layout_name);
            $config->set("category", "ImportExport");
            $config->set("value", $layout);
            $config->create();
        }
    }

	function getPageReturnUrl()
	{
		$url = "";
		switch ($this->action) {
			case "import_products":
				$url = array('<br>Products imported successfully. <a href="admin.php?target=import_catalog"><u>Click here to return to admin interface</u></a>');
			break;
			case "import_fields":
				$url = array('<br>Product extra fields imported successfully. <a href="admin.php?target=import_catalog&page=extra_fields"><u>Click here to return to admin interface</u></a>');
			break;
			default:
				$url = parent::getPageReturnUrl();
		}

		return $url;
	}

    /**
    * @param int     $i          field number
    * @param string  $value      current value
    * @param boolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if (($this->action == "import_products" || $this->action == "layout") && $id < count($this->product_layout)) {
            return ($this->product_layout[$id] === $value);
        } 
        if (($this->action == "import_fields" || $this->action == "fields_layout") && $id < count($this->fields_layout)) {
            return ($this->fields_layout[$id] === $value);
        }

        return $default;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

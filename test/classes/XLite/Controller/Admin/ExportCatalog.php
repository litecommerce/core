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
* Catalog export dialog
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_Abstract
{	
    public $params = array('target', 'page');	
    public $pages = array('products' => 'Export products',
                       'extra_fields' => 'Export extra fields'
                       );	
    public $pageTemplates = array('products' => 'product/export.tpl',
                               'extra_fields' => 'product/export_fields.tpl'
                               );	
    public $page = "products";

    function handleRequest()
    {
        $name = "";
        if 
        (
            ( 
                ($this->action == "export_products" || $this->action == "layout")
                && 
                !func_is_array_unique($this->product_layout, $name, "NULL")
            ) 
            || 
            ( 
                ($this->action == "export_fields" || $this->action == "fields_layout")
                && 
                !func_is_array_unique($this->fields_layout, $name, "NULL")
            )
        ) {
            $this->set("valid", false);
            $this->set("invalid_field_order", true);
            $this->set("invalid_field_name", $name);	// $name was filled in func_is_array_unique()
        }
        
        parent::handleRequest();
    }

    function action_export_products()
    {
        $this->set("silent", true);

        global $DATA_DELIMITERS;

        $this->startDownload("products.csv");
        $product = new XLite_Model_Product();
        $product->export($this->product_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function action_layout()
    {
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout();
    }

    function action_export_fields()
    {
        $this->set("silent", true);

        global $DATA_DELIMITERS;

        $this->startDownload("extra_fields.csv");

     	$p = new XLite_Model_Product();
     	$products = $p->findAll();
        foreach ($products as $product_idx => $product) {
			$products[$product_idx]->populateExtraFields();
		}

		$global_extra_field = new XLite_Model_ExtraField();
		foreach($global_extra_field->findAll("product_id = 0") as $gef) {
			 print func_construct_csv($gef->_export($this->fields_layout, $DATA_DELIMITERS[$this->delimiter]), $DATA_DELIMITERS[$this->delimiter], '"');
             print "\n";
		}

        foreach ($products as $product_idx => $product) {
            foreach($products[$product_idx]->getExtraFields(false) as $ef) {
                print func_construct_csv($ef->_export($this->fields_layout, $DATA_DELIMITERS[$this->delimiter]), $DATA_DELIMITERS[$this->delimiter], '"');
                print "\n";
            }
        }
        exit();
    }

    function action_fields_layout()
    {
        $layout_name = "fields_layout";
        $layout = implode(',', XLite_Core_Request::getInstance()->$layout_name);
        $config = new XLite_Model_Config();
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

    /**
    * @param int    $i          field number
    * @param string $value      current value
    * @param bolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if (($this->action == "export_products" || $this->action == "layout") && $id < count($this->product_layout)) {
            return ($this->product_layout[$id] === $value);
        }
        if (($this->action == "export_fields" || $this->action == "fields_layout") && $id < count($this->fields_layout)) {
            return ($this->fields_layout[$id] === $value);
        }

        return $default;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
* Catalog export dialog
*
* @package Dialog
* @access public
* @version $Id: export_catalog.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*/
class Admin_Dialog_export_catalog extends Admin_Dialog
{
    var $params = array('target', 'page');
    var $pages = array('products' => 'Export products',
                       'extra_fields' => 'Export extra fields'
                       );
    var $pageTemplates = array('products' => 'product/export.tpl',
                               'extra_fields' => 'product/export_fields.tpl'
                               );
    var $page = "products";

    function action_export_products()
    {
        $this->set("silent", true);

        global $DATA_DELIMITERS;

        $this->startDownload("products.csv");
        $product = func_new("Product");
        $product->export($this->product_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function action_layout()
    {
        $dlg =& func_new("Admin_Dialog_import_catalog");
        $dlg->action_layout();
    }

    function action_export_fields()
    {
        $this->set("silent", true);

        global $DATA_DELIMITERS;

        $this->startDownload("extra_fields.csv");
        $p =& func_new("Product");
        foreach ($p->findAll() as $product) {
            foreach($product->getExtraFields(false/* not only enabled*/) as $ef) {
                print func_construct_csv($ef->_export($this->fields_layout, $DATA_DELIMITERS[$this->delimiter]), $DATA_DELIMITERS[$this->delimiter], '"');
                print "\n";
            }
        }
        exit();
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
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

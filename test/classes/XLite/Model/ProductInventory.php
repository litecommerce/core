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
* Class description.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_ProductInventory extends XLite_Model_Product implements XLite_Base_ISingleton
{	
    public $importFields = array(
            "NULL"  => false,
            "sku"   => false,
            "name"  => false,
            "price" => false
            );

	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    function _export($layout, $delimiter) // {{{
    {
        $data = array();
        $values = $this->getProperties();
        foreach ($layout as $name) {
            if (isset($values[$name])) {
                $data[] =  $this->_stripSpecials($values[$name]);
            }
        }
        return $data;
    } // }}}

    function _import(array $options) // {{{
    {
        static $line;
        if (!isset($line)) $line = 1; else $line++;

        $properties = $options["properties"];
        $this->_convertProperties($properties);
        $product = new XLite_Model_Product();

        // search for product by SKU
        if (!empty($properties["sku"]) && $product->find("sku='".addslashes($properties["sku"])."'")) {
            // update
            $this->updateProductInventory($line, $product, $properties);
        }
        // search for product by NAME
        elseif (empty($properties["sku"]) && !empty($properties["name"]) && $product->find("name='".addslashes($properties["name"])."'")) {
            // update
            $this->updateProductInventory($line, $product, $properties);
        }
        // die if product not found
        else {
            echo "<b>line# $line:</b> <font color=red>Product not found:</font> ";
            echo !empty($properties["sku"]) ? "SKU: $properties[sku] " : "";
            echo !empty($properties["name"]) ? "NAME: $properties[name] " : "";
            echo "<br>\n";
            echo '<br><a href="admin.php?target=update_inventory&page=pricing"><u>Click here to return to admin interface</u></a>';
            die();
        }
    } // }}}

    function updateProductInventory($line, $product, $properties)
    {
        echo "<b>line# $line:</b> updating product ".$product->get("name")."<br>\n";
        $product->setProperties($properties);
        $product->update();
    }
} 


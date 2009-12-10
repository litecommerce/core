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
* Class represents the product extra-field.
*
* @package kernel
* @access public
* @version $Id: ExtraField.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*/
class ExtraField extends Base
{
    var $fields = array(
            "field_id" => 0,    // primary key
            "product_id" => 0,
            "name"     => "",
            "default_value"    => "",
            "enabled"  => 1,
            "order_by" => 0,
            );

    var $autoIncrement = "field_id";
    var $alias         = "extra_fields";
    var $defaultOrder  = "order_by,name";

    var $importFields = array(
        "NULL" => false,
        "name" => false,
        "sku" => false,
        "category" => false,
        "product" => false,
        "value"    => false,
        "default_value" => false,
        "enabled" => false,
        "order_by" => false
    );
    
    function &getProduct() // {{{
    {
        return func_new("Product", $this->get("product_id"));
    } // }}}
    
    function delete()
    {
        // delete all values
        $fv =& func_new("FieldValue");
        $valuesTable = $this->db->getTableByAlias($fv->alias);
        $sql = "DELETE FROM $valuesTable WHERE field_id=" . $this->get("field_id");
        $this->db->query($sql);
        // delete field
        parent::delete();
    }

    function filter()
    {
        if (!$this->xlite->is("adminZone")) {
            return (boolean) $this->get("enabled");
        }
        return parent::filter();
    }

    function &getImportFields()
    {
        return parent::getImportFields("fields_layout");
    }
    
    function _export($layout, $delimiter) // {{{
    {
        $data = array();
        // export field descriptions
        foreach ($layout as $field) {
            switch ($field) {
                case "NULL":
                    $data[] = "";
                    break;
                case "sku":
                    $data[] = $this->get("product.sku");
                    break;
                case "category":
                    $product =& $this->get("product");
                    $data[] = $product->_exportCategory();
                    break;
                case "product":
                    $data[] = $this->get("product.name");
                    break;
                default:    
                    $data[] = $this->get($field);
                    break;
            }
        }
        return $data;
    } // }}}

    function _import(&$options) // {{{
    {
        $properties = $options["properties"];
        $product =& func_new("Product");
        $product =& $product->findImportedProduct($properties["sku"], $properties["category"], $properties["product"], false /* do not create categories */);
        if (is_null($product)) {
            echo "<font color=red>Product not found for line ".$this->lineNo."</font><br>";
            return;
        }
        if (!empty($properties["enabled"]) && ($properties["enabled"] == "Y" || $properties["enabled"] == "N")) {
            $properties["enabled"] = $properties["enabled"] == "Y" ? 1 : 0;
        }
        $productID = $product->get("product_id");

        // search and update or create extra field
        $field =& func_new("ExtraField");
        $found = $field->find("name='".addslashes($properties["name"])."' AND product_id=$productID");
        $field->set("default_value", $properties["value"]);
        $field->set("enabled", $properties["enabled"]);
        $field->set("name", $properties["name"]);
        $field->set("product_id", $productID);
        if ($found) {
            echo "&gt; Field \"".$properties["name"]."\" updated for product # $productID<br>\n";
            $field->update();
        } else {
            echo "&gt; Field \"".$properties["name"]."\" created for product # $productID<br>\n";
            $field->create();
        }
        $fieldID = $field->get("field_id");
        // search and update or create field value
        if (!empty($properties["value"]) && strlen($properties["value"])) {
            $fv =& func_new("FieldValue");
            $found = $fv->find("field_id=$fieldID AND product_id=$productID");
            $fv->set("field_id", $fieldID);
            $fv->set("product_id", $productID);
            $fv->set("value", $properties["value"]);
            if ($found) {
                $fv->update();
            } else {
                $fv->create();
            }
        }
    } // }}}
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

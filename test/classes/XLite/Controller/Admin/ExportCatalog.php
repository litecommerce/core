<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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

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
 * @subpackage Model
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

    function _export($layout, $delimiter) 
    {
        $data = array();
        $values = $this->getProperties();
        foreach ($layout as $name) {
            if (isset($values[$name])) {
                $data[] =  $this->_stripSpecials($values[$name]);
            }
        }
        return $data;
    } 

    function _import(array $options) 
    {
        static $line;
        if (!isset($line)) $line = 1; else $line++;

        $properties = $options['properties'];
        $this->_convertProperties($properties);
        $product = new XLite_Model_Product();

        // search for product by SKU
        if (!empty($properties['sku']) && $product->find("sku='".addslashes($properties['sku'])."'")) {
            // update
            $this->updateProductInventory($line, $product, $properties);
        }
        // search for product by NAME
        elseif (empty($properties['sku']) && !empty($properties['name']) && $product->find("name='".addslashes($properties['name'])."'")) {
            // update
            $this->updateProductInventory($line, $product, $properties);
        }
        // die if product not found
        else {
            echo "<b>line# $line:</b> <font color=red>Product not found:</font> ";
            echo !empty($properties['sku']) ? "SKU: $properties[sku] " : "";
            echo !empty($properties['name']) ? "NAME: $properties[name] " : "";
            echo "<br>\n";
            echo '<br><a href="admin.php?target=update_inventory&page=pricing"><u>Click here to return to admin interface</u></a>';
            die();
        }
    } 

    function updateProductInventory($line, $product, $properties)
    {
        echo "<b>line# $line:</b> updating product ".$product->get('name')."<br>\n";
        $product->setProperties($properties);
        $product->update();
    }
}

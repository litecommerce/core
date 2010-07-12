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

namespace XLite\Module\WholesaleTrading\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PurchaseLimit extends \XLite\Model\AModel
{
    /**
    * @var string $alias The product access database table alias.
    * @access public
    */	
    public $alias = "purchase_limit";

    public $primaryKey = array('product_id');
    
    public $importError = "";

    /**
    * @var array $fields product access properties.
    * @access private
    */	
    public $fields = array(
            "product_id"	=> 0,
            "min"			=> "",
            "max"			=> "",
        );
    
    public $importFields = array(
            "NULL"			=> false,
            "product"		=> false,
            "sku"			=> false,	
            "min"		=> false,
            "max"			=> false,
        );
    
    function _export($layout, $delimiter) {
        $data = array();

        $values = $this->get('properties');

        foreach ($layout as $field) {
            if ($field == "NULL") {
                $data[] = "";
            } elseif ($field == "product") {
                $product = new \XLite\Model\Product($values['product_id']);
                $data[] = $product->get('name');
            } elseif ($field == "sku") {
                $product = new \XLite\Model\Product($values['product_id']);
                $data[] = $product->get('sku');
            } elseif (isset($values[$field])) {
                $data[] =  $this->_stripSpecials($values[$field]);
            }
        }
        return $data;
    }

    function _import(array $options) {
        static $line_no;
        if (!isset($line_no)) $line_no = 1; else $line_no++;
        
        $properties       = $options['properties'];
        $wp = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
        $product = new \XLite\Model\Product();

        $product = $product->findImportedProduct($properties['sku'], '',$properties['product'], false, $options['unique_identifier']);
        if (!is_null($product)) {
            $found = $wp->find("product_id = " . $product->get('product_id'));
            $wp->set('product_id', $product->get('product_id'));
            $wp->set('min',$properties['min']);
            $wp->set('max',$properties['max']);
    
            echo "<b>Importing CSV file line# $line_no: </b>";
    
            if ($found) {
                echo "Update purchase limit for '".$product->get('name')."' product";
                $wp->update();
            } else {
                $wp->create();
                echo "Create purchase limit for '".$product->get('name')."' product";
            }
            echo "<br>\n";
        } else {
            $this->importError = "Error: trying to create purchase limit for non-existent product. CSV file line #". $line_no;
            echo $this->importError;
        }
    }
    
    function collectGarbage()
    {
        $product_table_name = \XLite\Model\Factory::create('\XLite\Model\Product')->getTable();
        $table_name = $this->getTable();

        $sql =<<<EOSQL
        SELECT $table_name.product_id
        FROM $table_name
        LEFT JOIN $product_table_name
        ON $product_table_name.product_id = $table_name.product_id
        WHERE $product_table_name.product_id IS NULL
        GROUP BY product_id
EOSQL;

        $collection = $this->db->getAll($sql);
        foreach ($collection as $item) {
            $pl = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
            if ($pl->find("product_id='".$item['product_id']."'")) {
            	$pl->delete();
            }
        }
    }
}

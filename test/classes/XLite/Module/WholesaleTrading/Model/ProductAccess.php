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
class XLite_Module_WholesaleTrading_Model_ProductAccess extends XLite_Model_Abstract
{
    /**
    * @var string $alias The product access database table alias.
    * @access public
    */	
    public $alias = "product_access";

    public $primaryKey = array('product_id');
    
    public $importError = "";

    /**
    * @var array $fields product access properties.
    * @access private
    */	
    public $fields = array(
            "product_id"		=> 0,
            "show_group"		=> 'all',
            "show_price_group"	=> 'all',
            "sell_group"		=> 'all'
        );

    public $importFields = array(
            "NULL"				=> false,
            "sku"       		=> false,
            "product"           => false,
            "show_group"		=> false,
            "show_price_group"	=> false,
            "sell_group"		=> false
        );

    function groupInAccessList($group, $access, $expand_all=true)
    {
        // $group - membership level
        // $access - show, show price, sell
        $acc_list = explode(',', $this->get($access));

        $result = false;
        if (
            true === $expand_all
            && (in_array("all", $acc_list) || ($this->auth->is('logged') && in_array("registered", $acc_list)))
        ) {
            $result = true;
    
        } elseif($group != '') {
            $result = in_array($group, $acc_list);
        }

        return $result;
    }

    function _export($layout, $delimiter) 
    {
        $data = array();
        $values = $this->get('properties');
    
        foreach ($layout as $field) {
            if ($field == "NULL") {
                $data[] = "";
            } elseif ($field == "product") {
                $product = new XLite_Model_Product($values['product_id']);
                $data[] = $product->get('name');
            } elseif ($field == "sku") {
                $product = new XLite_Model_Product($values['product_id']);
                $data[] = $product->get('sku');
            } elseif (isset($values[$field])) {
                $data[] =  $this->_stripSpecials($values[$field]);
            }
        }
        return $data;
    }

    function _import(array $options) 
    {
        static $line_no;
        if (!isset($line_no)) $line_no = 1; else $line_no++;
                    
        $properties = $options['properties'];
        $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
        $product = new XLite_Model_Product();

        $product = $product->findImportedProduct($properties['sku'], '', $properties['product'], false, $options['unique_identifier']);
        if(!is_null($product)) {
            $found = $pa->find("product_id = '".$product->get('product_id')."'");
            $pa->set('product_id',       $product->get('product_id'));
            $pa->set('show_group',       $properties['show_group']);
            $pa->set('show_price_group', $properties['show_price_group']);
            $pa->set('sell_group',       $properties['sell_group']);
            
            echo "<b>Importing CSV file line# $line_no: </b>";
            
            if ($found) {
            	echo "Update access for product ";
            	$pa->update();
        	} else {
            	echo "Create access for product ";
            	$pa->create();
        	}
        	echo  $product->get('name') . "<br>\n";
        } else {
            $this->importError = "Product not found. CSV file line # $line_no";
            echo $this->importError;
        }
    }

    function collectGarbage()
    {
        $product_table_name = XLite_Model_Factory::createObjectInstance('XLite_Model_Product')->getTable();
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
            $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
            if ($pa->find("product_id='".$item['product_id']."'")) {
            	$pa->delete();
            }
        }
    }
}

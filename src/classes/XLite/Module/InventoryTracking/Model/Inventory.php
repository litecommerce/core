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

namespace XLite\Module\InventoryTracking\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Inventory extends \XLite\Model\AModel
{
    /**
    * @var string $alias The credit cards database table alias.
    * @access public
    */	
    public $alias = "inventories";

    public $primaryKey = array('inventory_id');
    public $defaultOrder = "inventory_id";

    /**
    * @var array $fields The inventory card properties.
    * @access private
    */	
    public $fields = array(
            'inventory_id'    => '',  
            'inventory_sku'   => '',
            'amount'          => 0,
            'low_avail_limit' => 10,
            'enabled'         => 1,
            'order_by'        => 0,
        );

    public $importFields = array(
            "NULL" => false,
            "sku"  => false,
            "name" => false,
            "amount" => false,
            "low_avail_limit" => false,
            "enabled" => false,
            "order_by" => false,
            );

    public function __construct($id = null) 
    {
        parent::__construct($id);
        if ($this->xlite->get('ProductOptionsEnabled')) {
            $this->importFields['product_options'] = false;
            $this->importFields['inventory_sku'] = false;
        }
    }
    
    function _import(array $options) 
    {
        $properties = $options['properties'];
        // search for the product first
        $product = new \XLite\Model\Product();
        $found = false;

        // search product by SKU
        if (!empty($properties['sku']) && $product->find("sku='".addslashes($properties['sku'])."'")) {
            $found = true;
        }
        // .. or by NAME
        elseif (empty($properties['sku']) && !empty($properties['name']) && $product->find("name='".addslashes($properties['name'])."'")) {
            $found = true;
        }

        static $line;
        if (!isset($line)) $line = 1; else $line++;
        echo "<b>Importing CSV file line# $line: </b>";

        if ($found) {
            // product found
            $inventory_id = $product->get('product_id') . (!empty($properties['product_options']) ? "|".$properties['product_options'] : "");
            $inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
            $inventory->set('properties', $properties);

            if ($inventory->find("inventory_id='$inventory_id'")) {
                echo "updating amount for product " . $product->get('name') . "<br>\n";
    	        $inventory->update();
            } else {
                echo "creating amount for product " . $product->get('name') . "<br>\n";
                $inventory->set('inventory_id',!empty($properties['product_options']) ? $product->get('product_id')."|".$properties['product_options'] :  $product->get('product_id'));
                $inventory->create();
            }
            $product->updateInventorySku();
        } else {
            echo "<font color=red>product not found:</font>".(!empty($properties['sku']) ? " SKU: ".$properties['sku'] : "") . (!empty($properties['name']) ? " NAME: ".$properties['name'] : "");
            echo '<br /><br /><a href="admin.php?target=update_inventory&page=amount"><u>Click here to return to admin interface</u></a>';
            die;
            
        }
    }

    function _export($layout, $delimiter) 
    {
        $data = array();
        $inventory_id = $this->get('inventory_id');
        $pos = strpos($inventory_id, '|');
        if ($pos&&(!$this->xlite->get('ProductOptionsEnabled')||($this->xlite->get('ProductOptionsEnabled')&&!in_array('product_options',$layout))))
            return array();
        $product_id = $pos === false ? $inventory_id : substr($inventory_id, 0, $pos);
        $product = new \XLite\Model\Product($product_id);
        if ($product->find("product_id='$product_id'")) {
            $values = $this->properties;
            foreach ($layout as $field) {
                if ($field == "NULL") {
                    $data[] = "";
                } elseif (isset($values[$field])) {
                    $data[] =  $this->_stripSpecials($values[$field]);
                } elseif ($field == "product_options") {
                    if ($pos) {
                        $data[] = $this->_stripSpecials(substr($inventory_id, $pos + 1));
                    } else {
                        $data[] = "";
                    }
                } else {
                    $data[] = $this->_stripSpecials($product->get($field));
                }
            }
        }

        return $data;
    }
    
    function keyMatch($key) 
    {
        // get the class:value pairs array
        $cardOptions = $this->parseOptions($this->get('inventory_id'));
        $keyOptions = $this->parseOptions($key);
        $intersect = array_intersect($cardOptions, $keyOptions);
        $diff = array_diff($cardOptions, $intersect);
        return empty($diff);
    }

    function parseOptions($id) 
    {
        $options = array();
        if (strpos($id, "|") !== false) {
            $options = explode("|", $id);
            if (isset($options[0])) {
            	unset($options[0]);
            }
        }
        return $options;
    }

    function checkLowLimit($item) 
    {
        if ($this->get('amount') < $this->get('low_avail_limit')) {
            $inventory_id = $this->get('inventory_id');
            $pos = strpos($inventory_id, '|');
            $product_id = $pos === false ? $inventory_id : substr($inventory_id, 0, $pos);

            // send low limit notification
            $mailer = new \XLite\Model\Mailer();
            $mailer->set('product', new \XLite\Model\Product($product_id));
            $mailer->set('item', $item);
            $mailer->set('amount', $this->get('amount'));
            $mailer->compose(
                    $this->config->Company->site_administrator,
                    $this->config->Company->site_administrator,
                    "lowlimit_warning_notification");
            $mailer->send();
        }
    }

    function get($property)
    {
    	switch($property) {
    		case "amount":
    			return $this->getAmount();
    		default:
    			return parent::get($property);
    	}
    }

    function getAmount()
    {
    	$amount = parent::get('amount');
        if (!$this->xlite->is('adminZone')) {
        	return ($amount < 0) ? 0 : $amount;
        } else {
        	return $amount;
        }
    }
}

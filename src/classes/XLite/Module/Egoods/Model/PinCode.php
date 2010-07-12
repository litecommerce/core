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

namespace XLite\Module\Egoods\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PinCode extends \XLite\Model\AModel
{
    public $alias = "pin_codes";

    public $primaryKey = array('pin_id');
    public $defaultOrder = "pin_id";

    public $fields = array
    (
        "pin_id"		=> 0,
        "pin"			=> '',
        "enabled"		=> 0,
        "product_id"	=> 0,
        "item_id"		=> '',
        "order_id"		=> 0
    );

    public $importFields = array
    (
        "NULL"			=> false,
        "pin"			=> false,
        "enabled"		=> false,
        "product"		=> false,
        "category"		=> false
    );

    function getFreePinCount($product_id) 
    {
        $product = new \XLite\Model\Product($product_id);
        if ($product->get('pin_type') == 'D') {
            return count($this->findAll("item_id='' AND enabled=1 AND order_id=0 AND product_id=" . $product_id));
        } else if ($product->get('pin_type') == 'E') {
            return 99999999;
        }
    }

    function isFree() 
    {
        if ($this->get('item_id') == '' && $this->get('order_id') == 0) {
            return true;
        }
        return false;
    }

    function _export($layout, $delimiter) 
    {
        $data = array();
        $values = $this->get('properties');

        foreach ($layout as $field) {
            if ($field == "NULL") {
                $data[] = "";
            } elseif ($field == "product") {
                $product = new \XLite\Model\Product($values['product_id']);
                $data[] = $this->_stripSpecials($product->get('name'));
            } elseif ($field == "category") {
                $product = new \XLite\Model\Product($values['product_id']);
                $category = new \XLite\Model\Category();
                $data[] =  $category->createCategoryField($product->get('categories'));
            } elseif (isset($values[$field])) {
                $data[] =  $this->_stripSpecials($values[$field]);
            }
        }
        return $data;
    }

    public function import(array $options) 
    {
        $properties = $options['properties'];
        
        static $line_no;
        !isset($line_no) ? $line_no = 1 : $line_no++;

        echo "<b>Importing CSV file line # $line_no: </b>";

        $product = new \XLite\Model\Product();
        $product = $product->findImportedProduct("",$properties['category'],$properties['product'],false);
        if (!is_object($product)) {
            echo "product <b>\"".$properties['product']."\"</b> not found in category <b>\"".$properties['category']."\"</b>. Pin code not imported.<br>";
            return false;
        }
        $pin = new \XLite\Module\Egoods\Model\PinCode();
        $found = $pin->find("pin = '".$properties['pin']."' AND product_id =". $product->get('product_id'));

        $pin->set('pin', $properties['pin']);
        $pin->set('enabled', $properties['enabled']);
        $pin->set('product_id', $product->get('product_id'));

        if ($found) {
        	if ($options['update_existing']) {
            	echo "Updating";
            	$pin->update();
            } else {
            	echo "Skiping existing";
            }
        } else {
            echo "Creating";
            $pin->create();
        }
        echo "  PIN code \"" . $pin->get('pin') . "\"";

        echo " for product <a href=\"admin.php?target=product&product_id=".$product->get('product_id')."\">\"" . $product->get('name') . "\"</a><br>";

    }
}

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
class XLite_Module_InventoryTracking_Controller_Admin_ProductList extends XLite_Controller_Admin_ProductList implements XLite_Base_IDecorator
{
    function init() // {{{
    {
        $this->params[] = "inventory";
        parent::init();
    } // }}}

    function getProducts() // {{{
    {
        if (is_null($this->productsList)) {
            $this->productsList = parent::getProducts();
            if (!is_array($this->productsList)) $this->productsList = array();

            if (!in_array($this->get("inventory"), array('low','out'))) return $this->productsList;

            if ($this->get("inventory") == 'out') $condition = "AND amount <= 0";
            elseif ($this->get("inventory") == 'low') $condition = "AND amount > 0 AND amount <= low_avail_limit";

            foreach ($this->productsList as $k=>$product) {
                if (!is_object($product)) {
                    $product = new XLite_Model_Product($product['data']['product_id']);
                }
                $product_id = $product->get("product_id");

                $inv = new XLite_Module_InventoryTracking_Model_Inventory();
                if ($this->xlite->get("ProductOptionsEnabled") && $product->get("productOptions") && $product->get("tracking")) {
                    $inventories = (array) $inv->findAll("inventory_id LIKE '$product_id" . "|%' AND enabled=1 $condition");
                    if (empty($inventories)) {
                        unset($this->productsList[$k]); 
                    }
                } else {
                    // track without options:
                    if (!$inv->find("inventory_id='$product_id' AND enabled=1 $condition")) {
                        unset($this->productsList[$k]); 
                    }
                }
            }
            $this->productsFound = count($this->productsList);
        }
        return $this->productsList;
    } // }}}
}

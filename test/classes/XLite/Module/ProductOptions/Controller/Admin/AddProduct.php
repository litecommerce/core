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
class XLite_Module_ProductOptions_Controller_Admin_AddProduct extends XLite_Controller_Admin_AddProduct implements XLite_Base_IDecorator
{
    function action_add() 
    {
        parent::action_add();
        $product = new XLite_Model_Product($this->get('product_id'));
        foreach($product->get('categories') as $category) {
            $product_categories[] = $category->get('category_id');
        }
        $po = new XLite_Module_ProductOptions_Model_ProductOption();
        $gpo = $po->get('globalOptions');
        if ($gpo) 
            foreach($gpo as $global_option) {
                $gpo_categories = $global_option->getCategories();
                $intersect = array_intersect($gpo_categories,$product_categories);
                if (empty($gpo_categories) || (!empty($gpo_categories) && !empty($intersect))) {
                    $po = new XLite_Module_ProductOptions_Model_ProductOption();
                    $po->set("properties",$global_option->get('properties'));
                    $po->set("product_id",$this->get('product_id'));
                    $po->set("parent_option_id",$global_option->get('option_id'));
                    $po->set("categories",null);
                    $po->set("option_id",null);
                    $po->create();
                }
            }
    }
}

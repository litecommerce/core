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
 * @subpackage Controller_
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_InventoryTracking_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{    
    public $addReturnUrl = null;

    /**
     * Recalculates the shopping cart
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function updateCart()
    {
        parent::updateCart();
        if ($this->get("action") == "add" && !is_null($this->getComplex('cart.outOfStock'))) {
            $product_id = $this->getComplex('cart.outOfStock');
            $category_id = intval($this->category_id);
            if ($category_id == 0) {
                $product = new XLite_Model_Product($product_id);
                $category_id = $product->getComplex('category.category_id');
            }
            $this->addReturnUrl = "cart.php?target=product&product_id=$product_id&category_id=$category_id&mode=out_of_stock";
        }
        if ($this->get("action") == "add" && $this->getComplex('cart.exceeding'))    
        {
             $this->addReturnUrl = "cart.php?target=cart&mode=exceeding";
        }
    }

    /**
     * 'add' action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        parent::action_add();

        if (isset($this->addReturnUrl)) {
            $this->set("returnUrl", $this->addReturnUrl);
        }
    }
}


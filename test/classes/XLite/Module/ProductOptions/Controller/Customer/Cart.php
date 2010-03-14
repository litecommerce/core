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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductOptions_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{
    function getCurrentItem()
    {
        if (is_null($this->currentItem)) {
            parent::getCurrentItem(); // $this->currentItem
            // set item options if present
            if (!is_null($this->product) && $this->product->hasOptions() && isset($this->product_options)) {
                $this->currentItem->set("productOptions", $this->product_options);
            }
        }
        return $this->currentItem;
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

        // check for valid ProductOptions
        if (!is_null($this->getCurrentItem()->get('invalidOptions'))) {
            // got exception (invalid options combination)
            // build invalid options URL
            $io = $this->getComplex('currentItem.invalidOptions');
            $invalid_options = "";
            foreach ($io as $i => $o) {
                $invalid_options .= "&" . urlencode("invalid_options[$i]") . "=" . urlencode($o);
            }
            // delete item from cart and switch back to product details
            $key = $this->getCurrentItem()->get('key');
            $cart_items = $this->getComplex('cart.items');
            for ($i = 0; $i < count($cart_items); $i++) {
                if ($cart_items[$i]->get("key") == $key) {
                    $this->cart->deleteItem($cart_items[$i]);
                    break;
                }
            }
            $this->updateCart();
            $this->set("returnUrl", "cart.php?target=product&product_id=$this->product_id$invalid_options");
        }
    }
}


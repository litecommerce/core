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
class XLite_Module_WishList_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{	
	public $currentItem = null;
	
    /**
     * 'add' action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
	protected function action_add() // {{{
	{
		if (isset($this->wishlist_id)&&isset($this->item_id)) {
			// process this wishlist
			$this->currentItem = parent::get("currentItem");
			$wishlist_product = new XLite_Module_WishList_Model_WishListProduct($this->item_id,$this->wishlist_id);
			$product = $wishlist_product->getProduct();
			
			if (!$wishlist_product->isOptionsExist()) {
				$this->set("returnUrl", "cart.php?target=wishlist&absentOptions=1&invalidProductName=" . $product->get("name"));
				return;				
			} elseif ($wishlist_product->isOptionsInvalid()) {				
				$this->set("returnUrl", "cart.php?target=wishlist&invalidOptions=1&invalidProductName=" . $product->get("name"));
				return;
			} else {
				$this->currentItem->set("options",$wishlist_product->get("options"));
				$this->currentItem->set("amount",$this->get("wishlist_amount"));
				$this->session->set("wishlist_products",$wishlist_products);
				parent::action_add();
			}
		} else {
			// no wishlists
			parent::action_add();
		}
	} // }}}

    function _needConvertToIntStr($name) // {{{
    {
        if ($name == "item_id") 
        	return false;

        return parent::_needConvertToIntStr($name);
    } // }}}

} // }}} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

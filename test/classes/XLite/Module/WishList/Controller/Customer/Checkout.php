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
class XLite_Module_WishList_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout implements XLite_Base_IDecorator
{
    function success() 
    {
        if ($this->auth->get('profile')) {
            $wishlist = new XLite_Module_WishList_Model_WishList();
            $wishlist->find("profile_id = ". $this->auth->getComplex('profile.profile_id'));
            $wishlist_products = $wishlist->get('products');
        
            if (!empty($wishlist_products)) 
                foreach ($this->cart->get('items') as $item) {
                    foreach ($wishlist_products as $product) {
                        if ($item->get('item_id')==$product->get('item_id')) {
    
                            $amount = $item->get('amount');
                            if ($amount > $product->get('amount')) $amount = $product->get('amount');
                            $product->set('amount',$product->get('amount')-$amount);
                            $product->update();
                            
                        if ($product->get('amount')==0)
                            $product->delete();
                        }
                    }
                }
        }

        parent::success();
    }
}

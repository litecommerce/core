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
 * Wishlist
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Customer_Wishlist extends XLite_Controller_Customer_Abstract
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'mode');

    /**
     * Common method to determine current location 
     * 
     * @return array 
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Wish list';
    }

    /**
     * Add item to wishlist
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        if (!$this->auth->is('logged')) {
            $this->set('returnUrl', $this->buildURL('login', '', array('mode' => 'wishlist')));
            $this->session->set(
                'wishlist_url',
                $this->buildURL('wishlist', 'add', array('product_id' => $this->product_id))
            );

            return;
        }
        
        $product = new XLite_Model_Product($this->product_id);
        
        // alternative way to set product options
        if ($this->xlite->get('ProductOptionsEnabled') && isset($this->OptionSetIndex[$product->get('product_id')])) {
            $optionsSet = $product->get('expandedItems');
            foreach ($optionsSet[$this->OptionSetIndex[$product->get('product_id')]] as $opt) {
                $this->product_options[$opt->class] = $opt->option_id;    
            }
        }
        
        if (
            $this->xlite->get('ProductOptionsEnabled')
            && $product->hasOptions()
            && !isset($this->product_options)
        ) {
            $this->set('returnUrl', $this->buildURL('product', '', array('product_id' => $this->product_id)));

            return;
        }

        $wishlist = $this->get('wishList');
        $wishlistProduct = new XLite_Module_WishList_Model_WishListProduct();
        
        $wishlistProduct->set('product_id', $this->get('product_id'));

        $wishlistProduct->set('wishlist_id', $wishlist->get('wishlist_id'));
        $orderItem  = $wishlistProduct->get('orderItem');
        if (isset($this->product_options)) {
            $wishlistProduct->setProductOptions($this->product_options);
            if (version_compare(PHP_VERSION, '5.0.0')===-1) {
                $orderItem->setProductOptions($this->product_options);
            }
        }

        $wishlistProduct->set('item_id', $orderItem->get('key'));
        $found = $wishlistProduct->find(
            'item_id = \'' . addslashes($wishlistProduct->get('item_id')) . '\''
            . ' AND wishlist_id = \'' . $wishlist->get('wishlist_id') . '\''
        );

        $amount = $wishlistProduct->get('amount');
        $amount += isset($this->amount) ? $this->amount : 1;

        $wishlistProduct->set('amount', $amount);

        if ($found) {
            $wishlistProduct->update();
        } else {
            $wishlistProduct->create();
        }
    }

    /**
     * Move item from cart to wishlist
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionMove()
    {
        $cartId = XLite_Core_Request::getInstance()->cart_id;
        $items = $this->cart->get('items');

        // Check cart id 
        if (!is_scalar($cartId) || !isset($items[$cartId])) {

            // TODO - add top message
            $this->set('returnUrl', $this->buildURL('cart'));

            return;
        }
        
        // Check access
        if (!$this->auth->is('logged')) {

            // TODO - add top message
            $this->set('returnUrl', $this->buildURL('login', '', array('mode' => 'wishlist')));
            $this->session->set('wishlist_url', $this->buildURL('wishlist', 'move', array('cart_id' => $cartId)));

            return;
        }

        // Add item to wishlist
        $item = $items[$cartId];

        $wishlist = $this->get('wishList');

        $wishlistProduct = new XLite_Module_WishList_Model_WishListProduct();
        $wishlistProduct->set('product_id', $item->getProduct()->get('product_id'));
        $wishlistProduct->set('wishlist_id', $wishlist->get('wishlist_id'));

        $orderItem = $wishlistProduct->get('orderItem');

        if ($item->getProductOptions()) {
            $options = array();
            foreach ($item->getProductOptions() as $option) {
                $options[addslashes($option->class)] = $option->option_id;
            }
            $wishlistProduct->setProductOptions($options);
        }

        $wishlistProduct->set('item_id', $orderItem->get('key'));
        $found = $wishlistProduct->find(
            'item_id = \'' . addslashes($wishlistProduct->get('item_id')) . '\''
            . ' AND wishlist_id = \'' . $wishlist->get('wishlist_id') . '\''
        );

        $amount = intval(XLite_Core_Request::getInstance()->amount);
        if (0 >= $amount) {
            $amount = $item->get('amount');
        }

        $wishlistProduct->set('amount', $wishlistProduct->get('amount') + $amount);

        if ($found) {
            $wishlistProduct->update();

        } else {
            $wishlistProduct->create();
        }

        // Delete from cart
        if ($amount >= $item->get('amount')) {
            $this->cart->deleteItem($item);

        } else {
            $item->updateAmount($item->get('amount') - $amount);
            $this->getCart()->updateItem($item);
        }

        $this->updateCart();

        if ($this->cart->isEmpty()) {
            $this->cart->delete();
        }

        // TODO - add top message
    }

    /**
     * Get wishlist items 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItems()
    {
        $result = array();

        $wishlist = $this->getWishList();
        if ($wishlist) {
            $wishlistProduct = new XLite_Module_WishList_Model_WishListProduct();

            $result = $wishlistProduct->findAll('wishlist_id = \'' . $wishlist->get('wishlist_id') . '\'');
        }

        return $result;
    } 
    
    /**
     * Delete wishlist item
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $wishlistProduct = new XLite_Module_WishList_Model_WishListProduct(
            XLite_Core_Request::getInstance()->item_id,
            XLite_Core_Request::getInstance()->wishlist_id
        );
        $wishlistProduct->delete();
    }

    /**
     * Update wishlist item 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate() 
    {
        $wishlistProduct = new XLite_Module_WishList_Model_WishListProduct(
            XLite_Core_Request::getInstance()->item_id,
            XLite_Core_Request::getInstance()->wishlist_id
        );

        $amount = XLite_Core_Request::getInstance()->wishlist_amount;
        if (0 <= $amount) {
            $this->action_delete();

        } else {
            $wishlistProduct->set('amount', $amount);
            $wishlistProduct->update();
        }
    }

    /**
     * Send wishlist to friend 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSend()
    {
        $mailer = new XLite_Model_Mailer();

        $mailer->wishlist_recipient = XLite_Core_Request::getInstance()->wishlist_recipient;
        $mailer->items = $this->getItems();

        if ($this->auth->isLogged()) {
            $profile = $this->auth->getProfile();
            $mailer->customer = $profile->get('billing_firstname')
                . ' '
                . $profile->get('billing_lastname');
        }

        $mailer->compose(
            $this->config->Company->site_administrator,
            XLite_Core_Request::getInstance()->wishlist_recipient,
            'modules/WishList/send'
        );

        $mailer->send();    
    }

    /**
     * Clear wishlist
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionClear()
    {
        foreach ($this->getItems() as $item) {
            $item->delete();
        }
    }

    /**
     * Get page title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Wish list';
    }
}

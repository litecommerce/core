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
 * XLite_Controller_Customer_Cart 
 * 
 * @package    XLite
 * @subpackage Controller
 * @since      3.0.0
 */
class XLite_Controller_Customer_Cart extends XLite_Controller_Customer_Abstract
{
    /**
     * Cart item to operate (cache) 
     * 
     * @var    XLite_Model_OrderItem
     * @access protected
     * @since  3.0.0
     */
    protected $currentItem = null;

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {   
        return 'Shopping cart';
    }

    /**
     * Get (and create) current cart item 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentItem()
    {
        if (!isset($this->currentItem)) {
            $this->currentItem = new XLite_Model_OrderItem();
            $this->currentItem->setProduct($this->getProduct());
        }

        return $this->currentItem;
    }

    /**
     * 'Add' action 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        $result = false;

        if ($this->canAddProductToCart()) {
            $this->collectCartGarbage();

            // add product to the cart
            if ($this->getCart()->addItem($this->getCurrentItem())) {
                $this->updateCart();

                XLite_Core_TopMessage::getInstance()->add('Product has been added to cart');

            } else {
                XLite_Core_TopMessage::getInstance()->add('Product has not been added to cart', XLite_Core_TopMessage::ERROR);
            }

            // switch back to product catalog or to shopping cart
            $this->set('returnUrlAbsolute', false);
            $productListUrl = ($this->config->General->add_on_mode && isset($_SERVER['HTTP_REFERER']))
                ? $_SERVER['HTTP_REFERER']
                : $this->session->get('productListURL');

            if ($this->config->General->redirect_to_cart) {
                $this->session->set('continueURL', $productListUrl);

            } else {
                $this->set('returnUrl', $productListUrl);
                $this->set('returnUrlAbsolute', $this->config->General->add_on_mode && isset($_SERVER['HTTP_REFERER']));
            }
        }

        return $result;
    }

    /**
     * 'delete' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_delete()
    {
        // delete an item from the shopping cart
        $items = $this->getCart()->get('items');

        if (isset($items[XLite_Core_Request::getInstance()->cart_id])) {
            $this->getCart()->deleteItem($items[XLite_Core_Request::getInstance()->cart_id]);
            $this->updateCart();
        }

        if ($this->getCart()->isEmpty()) {
            $this->getCart()->delete();
        }
    }

    /**
     * Update cart
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        // Update quantity
        $items = $this->getCart()->get('items');
        $cartId = XLite_Core_Request::getInstance()->cart_id;
        $amount = XLite_Core_Request::getInstance()->amount;
        if (!is_array($amount)) {
            $amount = array($cartId => $amount);
        }

        foreach ($items as $key => $i) {
            if (
                isset($amount[$key])
                && (is_null($cartId) || $cartId == $key)
            ) {
                $items[$key]->updateAmount($amount[$key]);
                $this->getCart()->updateItem($items[$key]);
            }
        }

        // Update shipping method
        if (isset(XLite_Core_Request::getInstance()->shipping)) {
            $this->getCart()->set('shipping_id', XLite_Core_Request::getInstance()->shipping);
        }

        $this->updateCart();

        if ($this->getCart()->isEmpty()) {
            $this->getCart()->delete();
        }
    }
    
    /**
     * 'checkout' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_checkout()
    {
        $this->doActionUpdate();
        // switch to checkout dialog 
        $this->set('returnUrl', $this->buildURL('checkout'));
    }

    /**
     * 'clear' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_clear()
    {
        if (!$this->getCart()->isEmpty()) {
            $this->getCart()->delete();
        }
    }

    function isSecure()
    {
        return $this->is('HTTPS') ? true : parent::isSecure();
    }

    function canAddProductToCart()
    {
        $result = true;

        if (!$this->getProduct()->filter()) {
            $this->set('valid', false);
            $result = false;    
        }

        return $result;
    }

    function collectCartGarbage()
    {
        // don't collect garbage, if the cart already has products
        if ($this->getCart()->is('empty')) {
            $this->getCart()->collectGarbage(5);
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
        return 'Your shopping cart';
    }
}


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

namespace XLite\Controller\Customer;

/**
 * \XLite\Controller\Customer\Cart 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Cart item to operate (cache) 
     * 
     * @var    \XLite\Model\OrderItem
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
     * @param \XLite\Model\Product $product product to add
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentItem(\XLite\Model\Product $product)
    {
        if (!isset($this->currentItem)) {
            $this->currentItem = new \XLite\Model\OrderItem();
            $this->currentItem->setProduct($product);
        }

        return $this->currentItem;
    }

    /**
     * Add product to cart
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        $result = false;
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')
            ->find(\XLite\Core\Request::getInstance()->product_id);

        if (isset($product)) {
            // add product to the cart
            if ($this->getCart()->addItem($this->getCurrentItem($product))) {
                $this->updateCart();

                \XLite\Core\TopMessage::getInstance()->add('Product has been added to cart');

            } else {

                $this->processAddItemError();

            }

            $productListUrl = null;

            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
                $productListUrl = $_SERVER['HTTP_REFERER'];

            } elseif ($this->session->get('productListURL')) {
                $productListUrl = $this->session->get('productListURL');

            } else {
                $productListUrl = $this->buildUrl('product', '', array('product_id' => $product->getProductId()));
            }

            if ($productListUrl) {
                if ($this->config->General->redirect_to_cart) {
                    $this->session->set('continueURL', $productListUrl);

                } else {
                    $this->setReturnUrl($productListUrl);
                }
            }
        }

        return $result;
    }

    /**
     * Process 'Add item' error 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processAddItemError()
    {
        if (\XLite\Model\Cart::NOT_VALID_ERROR == $this->getCart()->getAddItemError()) {
            \XLite\Core\TopMessage::getInstance()->add(
                'Product has not been added to cart',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * 'delete' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $item = $this->getCart()->getItemByItemId(\XLite\Core\Request::getInstance()->cart_id);

        if ($item) {
            $this->getCart()->getItems()->removeElement($item);
            \XLite\Core\Database::getEM()->remove($item);
            $this->updateCart();

            \XLite\Core\TopMessage::getInstance()->add('Item has been deleted from cart');

        } else {
            \XLite\Core\TopMessage::getInstance()->add(
                'Item has not been deleted from cart',
                \XLite\Core\TopMessage::ERROR
            );
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
        $cartId = \XLite\Core\Request::getInstance()->cart_id;
        $amount = \XLite\Core\Request::getInstance()->amount;
        if (!is_array($amount)) {
            $amount = isset(\XLite\Core\Request::getInstance()->cart_id)
                ? array($cartId => $amount)
                : array();

        } elseif (isset($cartId)) {
            $amount = isset($amount[$cartId])
                ? array($cartId => $amount[$cartId])
                : array();
        }

        $result = false;

        foreach ($amount as $id => $quantity) {
            $item = $this->getCart()->getItemByItemId($id);
            if ($item) {
                $item->setAmount($quantity);
                $result = true;
            }
        }

        // Update shipping method
        if (isset(\XLite\Core\Request::getInstance()->shipping)) {
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->shipping);
            $result = true;
        }

        if ($result) {
            $this->updateCart();
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
    protected function doActionCheckout()
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


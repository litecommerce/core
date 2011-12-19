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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Customer;

/**
 * \XLite\Controller\Customer\Cart
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Cart extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Initialize controller
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();

        $this->checkItemsAmount();
    }

    /**
     * Get page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return $this->getCart()->isEmpty()
            ? static::t('Your shopping bag is empty')
            : static::t('Your shopping bag - X items', array('count' => $this->getCart()->countQuantity()));
    }

    /**
     * isSecure
     * TODO: check if this method is used
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isSecure()
    {
        return $this->is('HTTPS') ? true : parent::isSecure();
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Return current product Id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentProductId()
    {
        return intval(\XLite\Core\Request::getInstance()->product_id);
    }

    /**
     * Return product amount
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentAmount()
    {
        return intval(\XLite\Core\Request::getInstance()->amount);
    }

    /**
     * Check - amount is set into request data or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSetCurrentAmount()
    {
        return isset(\XLite\Core\Request::getInstance()->amount);
    }

    /**
     * Return current product class for further adding to cart
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentProduct()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($this->getCurrentProductId());

        return ($product && $product->isAvailable()) ? $product : null;
    }

    /**
     * Get available amount for the product
     *
     * @param \XLite\Model\OrderItem $item Order item to add
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductAvailableAmount(\XLite\Model\OrderItem $item)
    {
        return $item->getProduct()->getInventory()->getAvailableAmount();
    }

    /**
     * Get total inventory amount for the product
     *
     * @param \XLite\Model\Product $product Product to check
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductAmount(\XLite\Model\Product $product)
    {
        return $product->getInventory()->getAmount();
    }

    /**
     * Check if the requested amount is available for the product
     *
     * @param \XLite\Model\OrderItem $item   Order item to add
     * @param integer                $amount Amount to check OPTIONAL
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAmount(\XLite\Model\OrderItem $item, $amount = null)
    {
        return !$item->getProduct()->getInventory()->getEnabled();
    }

    /**
     * Check product amount before add it to the cart
     *
     * @param \XLite\Model\OrderItem $item   Order item to add
     * @param integer                $amount Amount OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAmountToAdd(\XLite\Model\OrderItem $item, $amount = null)
    {
        return $this->checkAmount($item)
            || $this->getProductAvailableAmount($item) >= $amount;
    }

    /**
     * Check product amount before update it in the cart
     *
     * @param \XLite\Model\OrderItem $item   Order item to add
     * @param integer                $amount Amount OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAmountToUpdate(\XLite\Model\OrderItem $item, $amount = null)
    {
        return $this->checkAmount($item)
            || ($this->getProductAvailableAmount($item) + $item->getAmount()) >= $amount;
    }

    /**
     * Check amount for all cart items
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkItemsAmount()
    {
        foreach ($this->getCart()->getItemsWithWrongAmounts() as $item) {

            $product = $item->getProduct();

            $this->processInvalidAmountError($product, $this->getProductAmount($product));
        }
    }

    /**
     * Correct product amount to add to cart.
     * Common correction amount of order item as a product unit
     * irrespective of customer selections or order conditions (options/variants/offers)
     *
     * @param \XLite\Model\Product $product Product to add
     * @param integer|null         $amount  Amount of product.
     *                                      Null is given when there is no amount in request.
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function correctAmountAsProduct(\XLite\Model\Product $product, $amount)
    {
        if (is_null($amount)) {
            $amount = $product->getInventory()
                ? $product->getInventory()->getLowAvailableAmount()
                : 1;
        }

        return $amount;
    }

    /**
     * Correct product amount to add to cart
     *
     * @param \XLite\Model\OrderItem $item   Product to add
     * @param integer                $amount Amount of product
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function correctAmountToAdd(\XLite\Model\OrderItem $item, $amount)
    {
        $amount = $this->correctAmountAsProduct($item->getProduct(), $amount);

        if (!$this->checkAmountToAdd($item, $amount)) {

            $amount = $this->getProductAvailableAmount($item);

            $this->processInvalidAmountError($item->getProduct(), $amount);
        }

        return $amount;
    }

    /**
     * Get (and create) current cart item.
     * Order item is changed according \XLite\Core\Request
     * (according customer request to add some specific features to item in cart. for example - options/variants/offers and so on)
     *
     * @return \XLite\Model\OrderItem
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentItem()
    {
        return $this->prepareOrderItem(
            $this->getCurrentProduct(),
            $this->isSetCurrentAmount() ? $this->getCurrentAmount() : null
        );
    }


    /**
     * Prepare order item class for adding to cart.
     * This method takes \XLite\Model\Product class and amount and creates \XLite\Model\OrderItem.
     * This order item container will be added to cart in $this->addItem() method.
     *
     * @param \XLite\Model\Product $product Product class to add to cart
     * @param integer              $amount  Amount of product to add to cart
     *
     * @return \XLite\Model\OrderItem
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function prepareOrderItem(\XLite\Model\Product $product, $amount)
    {
        $item = null;

        if ($product) {

            $item = new \XLite\Model\OrderItem();

            $item->setProduct($product);

            // We make amount correction if there is no such product with additional specifications
            // which are provided in order item container
            $newAmount = $this->correctAmountToAdd($item, $amount);

            if ($newAmount > 0) {

                $item->setAmount($newAmount);

            } else {

                $item = null;
            }
        }

        return $item;
    }

    /**
     * Add order item to cart.
     * Additional correction of item amount is made before adding.
     *
     * @param \XLite\Model\OrderItem $item
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addItem(\XLite\Model\OrderItem $item)
    {
        return $item && $this->getCart()->addItem($item);
    }

    /**
     * Show message about wrong product amount
     *
     * @param \XLite\Model\Product $product Product to process
     * @param integer              $amount  Available amount
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processInvalidAmountError(\XLite\Model\Product $product, $amount)
    {
        \XLite\Core\TopMessage::addWarning(
            'Only ' . $amount . ' items are available for the "' . $product->getName() . '" product'
        );
    }

    /**
     * Process 'Add item' error
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processAddItemError()
    {
        if (\XLite\Model\Cart::NOT_VALID_ERROR == $this->getCart()->getAddItemError()) {
            \XLite\Core\TopMessage::addError('Product has not been added to cart');
        }
    }

    /**
     * Process 'Add item' success
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processAddItemSuccess()
    {
        \XLite\Core\TopMessage::addInfo('Product has been added to the cart');
    }

    /**
     * URL to return after product is added
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getURLToReturn()
    {
        $url = \XLite\Core\Session::getInstance()->productListURL;

        if (!$url) {

            if (\XLite\Core\Request::getInstance()->returnURL) {

                $url = \XLite\Core\Request::getInstance()->returnURL;

            } elseif (!empty($_SERVER['HTTP_REFERER'])) {

                $url = $_SERVER['HTTP_REFERER'];

            } else {

                $url = $this->buildURL('product', '', array('product_id' => $this->getProductId()));
            }
        }

        return $url;
    }

    /**
     * URL to return after product is added
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setURLToReturn()
    {
        \XLite\Core\Session::getInstance()->continueURL = $this->getURLToReturn();

        if (\XLite\Core\Config::getInstance()->General->redirect_to_cart) {

            // Hard redirect to cart
            $this->setReturnURL($this->buildURL('cart'));

            $this->setHardRedirect();

        } else {

            $this->setReturnURL($this->getURLToReturn());
        }
    }

    /**
     * Add product to cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionAdd()
    {
        // Add product to the cart and set a top message (if needed)
        $this->addItem($this->getCurrentItem())
            ? $this->processAddItemSuccess()
            : $this->processAddItemError();

        // Update cart
        $this->updateCart();

        // Set return URL
        $this->setURLToReturn();
    }

    /**
     * Add products from the order to cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function doActionAddOrder()
    {
        $order = \XLite\Core\Database::getRepo('\XLite\Model\Order')
            ->find(\XLite\Core\Request::getInstance()->order_id);

        foreach ($order->getItems() as $item) {

            $this->addItem($item->cloneEntity());
        }

        // Update cart
        $this->updateCart();

        // Set return URL
        $this->setURLToReturn();
    }



    // TODO: refactoring

    /**
     * 'delete' action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        $item = $this->getCart()->getItemByItemId(\XLite\Core\Request::getInstance()->cart_id);

        if ($item) {

            $this->getCart()->getItems()->removeElement($item);
            \XLite\Core\Database::getEM()->remove($item);

            $this->updateCart();

            \XLite\Core\TopMessage::addInfo('Item has been deleted from cart');

        } else {
            $this->valid = false;

            \XLite\Core\TopMessage::addError(
                'Item has not been deleted from cart'
            );
        }
    }

    /**
     * Update cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionCheckout()
    {
        $this->doActionUpdate();

        // switch to checkout dialog
        $this->setReturnURL($this->buildURL('checkout'));
    }

    /**
     * Clear cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionClear()
    {
        if (!$this->getCart()->isEmpty()) {

            foreach ($this->getCart()->getItems() as $item) {

                \XLite\Core\Database::getEM()->remove($item);
            }

            $this->getCart()->getItems()->clear();

            $this->updateCart();
        }

        \XLite\Core\TopMessage::addInfo('Item has been deleted from cart');

        $this->setReturnURL($this->buildURL('cart'));
    }

}

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

namespace XLite\Model;

/**
 * Cart
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Cart")
 * @HasLifecycleCallbacks
 */
class Cart extends \XLite\Model\Order
{
    /**
     * Cart renew period
     */
    const RENEW_PERIOD = 3600;


    /**
     * Array of instances for all derived classes
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $instances = array();

    /**
     * Method to access a singleton
     *
     * @return \XLite\Model\Cart
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getInstance()
    {
        $className = get_called_class();

        // Create new instance of the object (if it is not already created)
        if (!isset(static::$instances[$className])) {
            $orderId = \XLite\Core\Session::getInstance()->order_id;

            if ($orderId) {

                $cart = \XLite\Core\Database::getRepo('XLite\Model\Cart')->find($orderId);

                if ($cart && !$cart->hasCartStatus()) {
                    \XLite\Core\Session::getInstance()->order_id = 0;

                    $cart = null;
                }
            }

            if (!isset($cart)) {
                $cart = new $className();
                $cart->setStatus(self::STATUS_TEMPORARY);

                \XLite\Core\Database::getEM()->persist($cart);
            }

            static::$instances[$className] = $cart;

            $auth = \XLite\Core\Auth::getInstance();

            if ($auth->isLogged()) {
                if (
                    !$cart->getProfile()
                    || $auth->getProfile()->getProfileId() != $cart->getProfile()->getProfileId()
                ) {
                    $cart->setProfile($auth->getProfile());
                    $cart->setOrigProfile($auth->getProfile());
                    $cart->calculate();
                }

            } elseif ($cart->getProfile() && $cart->getProfile()->getProfileId()) {

                $cart->setProfile(null);
                $cart->calculate();
            }

            \XLite\Core\Database::getEM()->flush();

            if (time() - static::RENEW_PERIOD > $cart->getLastRenewDate()) {
                $cart->renew();
            }

            \XLite\Core\Session::getInstance()->order_id = $cart->getOrderId();

        }

        return static::$instances[$className];
    }

    /**
     * Set object instance
     *
     * @param \XLite\Model\Order $object Cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function setObject(\XLite\Model\Order $object)
    {
        $className = get_called_class();
        static::$instances[$className] = $object;
        \XLite\Core\Session::getInstance()->order_id = $object->getOrderId();
    }

    /**
     * Prepare order before save data operation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
     * @PrePersist
     * @PreUpdate
     */
    public function prepareBeforeSave()
    {
        parent::prepareBeforeSave();

        $this->setDate(time());
    }

    /**
     * Prepare order before create entity
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
     * @PrePersist
     */
    public function prepareBeforeCreate()
    {
        $this->setLastRenewDate(time());
    }

    /**
     * Clear cart
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clear()
    {
        foreach ($this->getItems() as $item) {
            \XLite\Core\Database::getEM()->remove($item);
        }

        $this->getItems()->clear();

        \XLite\Core\Database::getEM()->persist($this);
        \XLite\Core\Database::getEM()->flush();
    }


    /**
     * Checks whether a product is in the cart
     *
     * @param integer $productId ID of the product to look for
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isProductAdded($productId)
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();

            if ($product && $product->getProductId() == $productId) {
                $result = true;
                break;
            }
        }

        return $result;
    }


    /**
     * Prepare order before remove operation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     * @PreRemove
     */
    public function prepareBeforeRemove()
    {
        parent::prepareBeforeRemove();

        unset(\XLite\Core\Session::getInstance()->order_id);
    }

    /**
     * Mark cart as order
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function markAsOrder()
    {
        $this->getRepository()->markAsOrder($this->getOrderId());
    }

    /**
     * Check if the cart has a "Cart" status. ("in progress", "temporary")
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasCartStatus()
    {
        return in_array($this->getStatus(), array(self::STATUS_INPROGRESS, self::STATUS_TEMPORARY));
    }

    /**
     * If we can proceed with checkout with current cart
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkCart()
    {
        return
            !$this->isEmpty()
            && !((bool) $this->getItemsWithWrongAmounts())
            && !$this->isMinOrderAmountError()
            && !$this->isMaxOrderAmountError();
    }

}

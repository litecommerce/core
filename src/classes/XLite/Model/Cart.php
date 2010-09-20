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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * Cart 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="\XLite\Model\Repo\Cart")
 * @HasLifecycleCallbacks
 */
class Cart extends \XLite\Model\Order
{
    /**
     * Array of instances for all derived classes
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0
     */
    protected static $instances = array();

    /**
     * Method to access a singleton
     *
     * @return \XLite\Model\Cart
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        $className = get_called_class();

        // Create new instance of the object (if it is not already created)
        if (!isset(static::$instances[$className])) {
            $orderId = \XLite\Model\Session::getInstance()->get('order_id');

            if ($orderId) {
                $cart = \XLite\Core\Database::getRepo('XLite\Model\Cart')->find($orderId);
                if ($cart && self::STATUS_TEMPORARY != $cart->getStatus()) {
                    \XLite\Model\Session::getInstance()->set('order_id', 0);
                    $cart = null;
                }
            }

            if (!isset($cart)) {
                $cart = new $className();
                $cart->setStatus(self::STATUS_TEMPORARY);
                $cart->setProfileId(0);

                // TODO - rework
                $cart->setCurrency(\XLite::getController()->getCurrentCurrency());
            }

            static::$instances[$className] = $cart;

            $auth = \XLite\Model\Auth::getInstance();

            if ($auth->isLogged()) {
                if ($auth->getProfile()->get('profile_id') != $cart->getProfileId()) {
                    $cart->setProfile($auth->getProfile());
                    $cart->calculate();
                }


            } elseif ($cart->getProfileId()) {

                $cart->setProfile(null);
                $cart->calculate();
            }

            \XLite\Core\Database::getEM()->persist($cart);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Model\Session::getInstance()->set('order_id', $cart->getOrderId());

        }

        return static::$instances[$className];
    }

    /**
     * Set object instance
     * 
     * @param \XLite\Model\Cart $cart Cart
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function setObject(\XLite\Model\Cart $cart)
    {
        $className = get_called_class();
        static::$instances[$className] = $cart;
        \XLite\Model\Session::getInstance()->set('order_id', $cart->getOrderId());
    }

    /**
     * Prepare order before save data operation
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     * @PrePersist
     * @PreUpdate
     */
    public function prepareBeforeSave()
    {
        parent::prepareBeforeSave();

        $this->setDate(time());

    }

    /**
     * Clear cart
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * Prepare order before remove operation
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     * @PreRemove
     */
    public function prepareBeforeRemove()
    {
        parent::prepareBeforeRemove();

        \XLite\Model\Session::getInstance()->set('order_id', null);
    }

    /**
     * Order 'complete' event
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processCheckOut()
    {
        parent::processCheckOut();

        if (self::STATUS_TEMPORARY == $this->getStatus()) {
            $this->setDate(time());

            $profile = \XLite\Model\Auth::getInstance()->getProfile();
            if ($profile->get('order_id')) {
                // anonymous checkout:
                // use the current profile as order profile
                $this->setProfileId($this->getProfile()->get('profile_id'));

            } else {
                $this->setProfileCopy($profile);
            }
        }
    }

    /**
     * Mark cart as order 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function markAsOrder()
    {
        $this->getRepository()->markAsOrder($this->getOrderId());
    }

}

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
 */
class Cart extends \XLite\Model\Order implements \XLite\Base\ISingleton
{
    /**
     * Constructor
     * 
     * @param int $id order ID
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->fields['status'] = 'T';

        if ($orderId = \XLite\Model\Session::getInstance()->get('order_id')) {
            $this->set('order_id', $orderId);
            if (!$this->isExists()) {
                $this->set('order_id', null);
            }
        }

        if ('T' === $this->get('status')) {

            $auth = \XLite\Model\Auth::getInstance();

            if ($auth->isLogged()) {
                if ($auth->getProfile()->get('profile_id') != $this->get('profile_id')) {
                    $this->setProfile($auth->getProfile());
                    $this->calcTotals();
                    if ($this->isPersistent) {
                        $this->update();
                    }    
                }
                

            } elseif ($this->get('profile_id')) {

                $this->set('profile',  null);
                $this->calcTotals();
            }
        }
    }

    /**
    * Saves the shopping cart content to session.
    *
    * @access public
    */
    function create()
    {
        $this->set('date', time());
        $this->set('status', "T");
        parent::create();
        $this->session->set('order_id', $this->get('order_id'));
    }

    function update()
    {
        $this->set('date', time());
        parent::update();
    }

    /**
    * Clears the shopping cart.
    *
    * @access public
    */
    function clear()
    {
        $this->session->set('order_id', null);
        $this->_items = array();
    }

    function delete()
    {
        $this->set('profile', null);
        parent::delete();
        $this->session->set('order_id', null);
    }

    /**
    * This method is called during checkout before payment processor
    * is called. 
    * The default implementation
    * copies the current user profile into the order and sets checkout date. 
    */
    function checkout() 
    {
        if ($this->get('status') == "T") {
            $this->set('date', time());

            $profile = \XLite\Model\Auth::getInstance()->getProfile();
            if ($profile->get('order_id')) {
                // anonymous checkout:
                // use the current profile as order profile
                $this->set('profile_id', $this->getProfile()->get('profile_id'));
            } else {
                $this->setProfileCopy($profile);
            }
            $this->set('status', "I");

            $this->update();
        }
    }

    /**
     * Calculate shipping rates 
     * 
     * @return array of \XLite\Moel\ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calcShippingRates()
    {
        $rates = parent::calcShippingRates();

        if (
            ($this->get('shipping_id') && !isset($rates[$this->get('shipping_id')]))
            || ($rates && !$this->get('shipping_id'))
        ) {
            $shipping = null;
            if (0 < count($rates)) {
                list($k, $rate) = each($rates);
                reset($rates);
                $shipping = $rate->get('shipping');
            }
            $this->setShippingMethod($shipping);
            if ($this->isPersistent) {
                $this->calcTotals();
                $this->update();
            }
        }

        return $rates;
    }
}


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

/**
 * Cart 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Cart extends XLite_Model_Order implements XLite_Base_ISingleton
{
    /**
     * Get class instance 
     * 
     * @return object
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Constructor
     * 
     * @param mixed $id ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->fields['status'] = "T";
        if ($this->session->isRegistered('order_id')) {
            $this->set('order_id', $this->session->get('order_id'));
            if (!$this->is('exists')) {
                $this->set('order_id', null);
            }
        }
        if ($this->get('status') == "T") {
            if ($this->auth->get('logged')) {
                if ($this->auth->getComplex('profile.profile_id') != $this->get('profile_id')) {
                    $this->set('profile', $this->auth->get('profile'));
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

            if ($this->auth->getComplex('profile.order_id')) {
                // anonymous checkout:
                // use the current profile as order profile
                $this->set('profile_id', $this->getComplex('profile.profile_id'));
            } else {
                $this->set('profileCopy', $this->auth->get('profile'));
            }
            $this->set('status', "I");

            $this->update();
        }
    }

    /**
     * Calculate shipping rates 
     * 
     * @return array of XLite_Moel_ShippingRate
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
                $rate = array_shift($rates);
                $shipping = $rate->get('shipping');
            }
            $this->setShippingMethod($shipping);
            $this->calcTotals();
            $this->update();
        }

        return $rates;
    }
}


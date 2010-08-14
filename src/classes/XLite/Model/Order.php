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

define('ORDER_EXPIRATION_TIME', 3600 * 24); // one day

/**
 * Class represens an order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\AModel
{
    /**
     * Object properties (table filed => default value)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
        'order_id'        => '',  // primary key
        'profile_id'      => '',  // profile data at the moment of the purchase
        'orig_profile_id' => '',  // original profile id
        'total'           => '',  // order costs: Total, Subtotal, Shipping
        'subtotal'        => '',
        'shipping_cost'   => '',
        'tax'             => '',  // taxes applied to this order (serialized)
        'shipping_id'     => 0,   // order shipping method primary key
        'tracking'        => '',  // delivery tracking number
        'date'            => '',  // date/time when the order purchased, timestamp
        'status'          => 'I', // order status: Queued, Processed, Failed etc.
        'payment_method'  => '',  // selected Payment method
        'details'         => '',  // secure order data
        'detail_labels'   => '',  // order data field names
        'notes'           => '',  // notes entered by the customer
        'taxes'           => '',  // serialized tax array
    );

    /**
     * Auto-increment file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $autoIncrement = 'order_id';

    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'orders';
        
    /**
    * cache properties
    */    
    public $_origProfile = null;

    public $_profile = null;

    public $_paymentMethod = null;

    public $_shippingMethod = null;

    public $_details = null;

    public $_detailLabels = null;

    public $_taxes = null;

    public $_statusChanged = false;

    public $_oldStatus = 'I';

    /**
     * Default SQL filter (WHERE block) for findAll() method
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $_range = 'status != \'T\'';

    /**
     * Cart items cache 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $_items = null;

    /**
     * _shippingRates 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $_shippingRates = null;


    /**
     * Create new cart item 
     * 
     * @param \XLite\Model\OrderItem $item new item
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function _createItem(\XLite\Model\OrderItem $item)
    {
        if (!$this->isPersistent) {
            $this->create();
        }

        $item->set('order_id', $this->get('order_id'));
        $item->create();

        // update cache
        if (isset($this->_items)) {
            $this->_items[] = $item;
        }
    }

    /**
     * Get shipping rates 
     * FIXME - see the "calcShippingRates()" method
     * 
     * @param boolean $clearCache Clear cache flag
     * 
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @since  3.0.0
     */
    public function getShippingRates($clearCache = false)
    {
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__,
            $this,
            'calcShippingRates',
            array(),
            $clearCache
        );
    }

    /**
     * Return cart items cache 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getItems()
    {
        if (!isset($this->_items)) {
            if ($this->isPersistent) {
                $item = new \XLite\Model\OrderItem();
                $this->_items = $item->findAll('order_id = \'' . $this->get('order_id') . '\'', 'orderby');

            } else {
                $this->_items = array();
            }
        }

        return $this->_items;
    }

    /**
     * Return cart items number
     * 
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getItemsCount()
    {
        return count($this->getItems());
    }

    /**
     * Add item ro order
     * 
     * @param \XLite\Model\OrderItem $newItem item to add
     *  
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function addItem(\XLite\Model\OrderItem $newItem)
    {
        $result = false;

        if ($newItem->isValid()) {

            $found = false;
            $key   = $newItem->getKey();

            foreach ($this->getItems() as $item) {
                if ($item->getKey() == $key) {
                    $item->updateAmount($item->get('amount') + $newItem->get('amount'));
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->_createItem($newItem);
            }

            $result = true;
        }

        return $result;
    }

    /**
     * Checks whether the shopping cart/order is empty
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isEmpty()
    {
        return 0 >= $this->getItemsCount();
    }

    /**
     * Check order subtotal 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isMinOrderAmountError()
    {
        return $this->get('subtotal') < doubleval($this->config->General->minimal_order_amount);
    }

    /**
     * Check order subtotal 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isMaxOrderAmountError()
    {
        return $this->get('subtotal') > doubleval($this->config->General->maximal_order_amount);
    }

    /**
     * Check - shipping is available for this order or not
     * 
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function isShippingAvailable()
    {
        return 0 < count($this->getShippingRates());
    }

    /**
     * Assign first shipping rate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assignFirstShippingRate()
    {
        $rates = $this->getShippingRates();

        $shipping = null;
        if (0 < count($rates)) {
            $rate = array_shift($rates);
            $shipping = $rate->get('shipping');
        }

        $this->setShippingMethod($shipping);
    }

    /**
     * Returns true if any of order items are shipped 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isShipped()
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            if ($item->isShipped()) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * isProcessed 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isProcessed()
    {
        return in_array($this->get('status'), array('P', 'C'));
    }

    /**
     * isQueued 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isQueued()
    {
        return 'Q' == $this->get('status');
    }

    /**
     * Get default search donditions 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getDefaultSearchConditions()
    {
        return array(
            'order_id'      => '',
            'status'        => '',
            'startDate'     => '',
            'endDate'       => '',
            'sortCriterion' => 'date',
            'sortOrder'     => 'desc'
        );
    }

    /**
     * Get sort criterions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getSortCriterions()
    {
        return array(
            'order_id' => 'Order id',
            'date'     => 'Date',
            'status'   => 'Status',
            'total'    => 'Total',
        );
    }

    ////////////// Order calculation functions ////////////////

    /**
    * Calculates the Order taxes. 
    */
    function calcAllTaxes() 
    {
        $taxRates = new \XLite\Model\TaxRates();
        $taxRates->set('order', $this);
        $result = array();
        $items = $this->getItems();
        foreach ($items as $item) {
            $product = $item->get('product');
            if ($this->config->Taxes->prices_include_tax && isset($product)) {
                $item->set('price', $item->getComplex('product.price'));
            }
            $taxRates->set('orderItem', $item);
            $taxRates->calculateTaxes();
            $result = $this->_addTaxes($result, $taxRates->get('allTaxes'));
        }

        // tax on shipping
        if (($this->get('shippingDefined') && !$this->config->Taxes->prices_include_tax) || ($this->get('shippingDefined') && $this->config->Taxes->prices_include_tax && $taxRates->get('shippingDefined'))) {
            $taxRates->_conditionValues['product class'] = "shipping service";
            $taxRates->_conditionValues['cost'] = $this->get('shippingCost');
            $taxRates->calculateTaxes();
            $result = $this->_addTaxes($result, $taxRates->get('allTaxes'));

            $shippingTaxes = array();
            $shippingTaxes = $this->_addTaxes($shippingTaxes, $taxRates->get('shippingTaxes'));
            foreach ($shippingTaxes as $name => $value) {
                $shippingTaxes[$name] = $this->formatCurrency($shippingTaxes[$name]);
            }
            $this->set('shippingTaxes', $shippingTaxes);
        }

        // round all tax values
        foreach ($result as $name => $value) {
            $result[$name] = $this->formatCurrency($result[$name]);
        }

        $this->set('allTaxes', $result);

        return $result;
    }

    function _addTaxes($acc, $taxes) 
    {
        foreach ($taxes as $tax => $value) {
            if (!isset($acc[$tax])) {
                $acc[$tax] = 0;
            }
            $acc[$tax] += $value;
        }

        return $acc;
    }

    /**
    * Returns the total tax value (in currency) or null if no taxes applicable.
    */
    function calcTax() 
    {
        $this->calcAllTaxes();
        $taxes = $this->get('allTaxes');
        $tax = isset($taxes['Tax']) ? $taxes['Tax'] : 0;    // total tax for all tax systems
        $this->set('tax', $tax);

        $shippingTax = 0;

        if ($this->get('shippingDefined')) {
            $shippingTaxes = $this->get('shippingTaxes');
            if (is_array($shippingTaxes)) {
                if ($this->config->Taxes->prices_include_tax && isset($shippingTaxes['Tax'])) {
                    $shippingTax = $shippingTaxes['Tax'];

                } else {
                    foreach ($shippingTaxes as $name => $value) {
                        if (
                            isset($taxes[$name])
                            && ($this->config->Taxes->prices_include_tax || $taxes[$name] == $value)
                        ) {
                            $shippingTax += $value;
                        }
                    }
                }
            }
        }

        $this->set('shippingTax', $shippingTax);

        return $tax;
    }

    /**
    * Returns the Order SubTotal (as the order items Total sum).
    */
    function calcSubtotal($shippedOnly=false) 
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            if ($shippedOnly && !$item->isShipped()) {
                continue;
            }

            $subtotal += $item->getTotal();
        }

        if (!$shippedOnly) {
            $this->set('subtotal', $this->formatCurrency($subtotal));

        } else {
            $this->set('subtotalShippedOnly', $this->formatCurrency($subtotal));
        }

        return $subtotal;
    }

    /**
    * Returns the Order total (as the order SubTotal + Taxes)
    */
    function calcTotal() 
    {
        if ($this->getItemsCount() <= 0) {
            return;
        }

        $this->calcSubtotal();
        $this->calcShippingCost();
        $this->calcTax();

        $total = $this->get('subtotal');

        if (!$this->config->Taxes->prices_include_tax) {
            $total += $this->get('tax');
        }

        $total += $this->get('shippingCost');

        if ($this->config->Taxes->prices_include_tax) {
            $total += $this->get('shippingTax');
        }

        $this->set('total', $this->formatCurrency($total));
    }

    function getShippingCost() 
    {
        return $this->get('shipping_cost');
    }

    function setShippingCost($cost)  
    {
        $this->set('shipping_cost', $cost);
    }

    /** 
    * Returns True if any of order items are shipped.
    */

    /**
    * Returns an array of order items to be shipped.
    */
    function getShippedItems() 
    {
        $result = array();

        foreach ($this->get('items') as $item) {
            if ($item->get('shipped')) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
    * Returns the count of shipped order items.
    */
    function getShippedItemsCount() 
    {
        $result = 0;
        foreach ($this->get('shippedItems') as $item) {
            $result += $item->get('amount');
        }

        return $result;
    }

    /**
    * Returns the Total shipping weight, ounces.
    */
    function getWeight() 
    {
        $weight = 0;
        foreach ($this->get('items') as $item) {
            if ($item->get('shipped')) {
                $weight += $item->get('weight');
            }
        }

        return $weight;
    }

    /**
    * Calculates the Shiping cost for the selected shipping method.
    * If no method selected, calculates it on the Shipping rates basis.
    */
    function calcShippingCost() 
    {
        $cost = 0;
        if (!$this->get('shipped')) {
            $this->set('shipping_cost', $cost);
            return $cost;
        }

        $shippingMethod = $this->getShippingMethod();
        $cost = is_object($shippingMethod) ? $shippingMethod->calculate($this) : false;

        if (false === $cost) {
            $rates = $this->calcShippingRates();
            // find the first available shipping method
            if (!is_null($rates) && count($rates) > 0) {
                foreach ($rates as $key => $val) {
                    $shippingID = $key;
                    break;
                }

                $shippingMethod = new \XLite\Model\Shipping($shippingID);
                $this->setShippingMethod($shippingMethod);
                $cost = $shippingMethod->calculate($this);
            }
        }

        $this->set('shipping_cost', $this->formatCurrency($cost));

        return $cost;
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
        if (is_null($this->_shippingRates)) {

            // For UPS Online Tools compatibility
            $data = array();
            
            foreach (\XLite\Model\Shipping::getModules() as $module) {
                $data += $module->getRates($this);
            }

            uasort($data, array($this, 'getShippingRatesOrderCallback'));

            $this->_shippingRates = $data;
        }

        return $this->_shippingRates;
    }

    /**
     * Shipping rates sorting callback 
     * 
     * @param \XLite\Model\ShippingRate $a First shipping rate
     * @param \XLite\Model\ShippingRate $b Second shipping rate
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingRatesOrderCallback(\XLite\Model\ShippingRate $a, \XLite\Model\ShippingRate $b)
    {
        $sa = $a->getShipping();
        $sb = $b->getShipping();

        return ($sa && $sb)
            ? strcmp($sa->get('order_by'), $sb->get('order_by'))
            : 0;
    }
   
    /////////////// Order validation functions /////////////////

    function isTaxDefined() 
    {
        return true;
    }
    
    function isShippingDefined() 
    {
        return $this->get('shipping_id');
    }

    /////////////// Order data access functions ////////////////

    function refresh($name) 
    {
        $name = "_" . $name;

        if (isset($this->$name)) {
            unset($this->$name);
        }

        $this->$name = null;

        if ('shippingRates' == $name) {
            \XLite\Model\CachingFactory::clearCacheCell(__CLASS__ . '::getShippingRates');
        }

    }

    function set($name, $value) 
    {
        if ($name == 'details') {
            $this->setDetails($value);

        } else {
            $oldStatus = $this->get('status');
            parent::set($name, $value);

            if ($name == "status" && !$this->_statusChanged && $value != $oldStatus) {
                $this->_statusChanged = true; // call statusChanged later
                $this->_oldStatus = $oldStatus;
            }

            // re-calculate shipping rates on next call to get('shippingRates')
            $this->refresh('shippingRates');
        }
    }

    function get($name) 
    {
        $result = null;
        switch ($name) {
            case "details":
                $result = $this->getDetails();
                break;

            case "detail_labels":
                $result = $this->getDetailLabels();
                break;

            default:
                $result = parent::get($name);
        }

        return $result;
    }

    /**
     * Get shipping method 
     * 
     * @return \XLite\Model\Shipping
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingMethod() 
    {
        if (is_null($this->_shippingMethod) && $this->get('shipping_id')) {
            $this->_shippingMethod = new \XLite\Model\Shipping($this->get('shipping_id'));
        }

        return $this->_shippingMethod;
    }

    /**
     * Set shipping method 
     * 
     * @param \XLite\Model\Shipping $shippingMethod Shipping method
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setShippingMethod($shippingMethod) 
    {
        if (!is_null($shippingMethod) && $shippingMethod instanceof \XLite\Model\Shipping) {
            $this->_shippingMethod = $shippingMethod;
            $this->set('shipping_id', $shippingMethod->get('shipping_id'));

        } else {
            $this->_shippingMethod = false;
            $this->set('shipping_id', 0);
        }
    }

    /**
     * Get payment method 
     * 
     * @return \XLite\Model\PaymentMethod
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPaymentMethod()
    {
        if (is_null($this->_paymentMethod) && $this->get('payment_method')) {
            $this->_paymentMethod = \XLite\Model\PaymentMethod::factory($this->get('payment_method'));
        }

        return $this->_paymentMethod;
    }
    
    /**
     * Set payment method 
     * 
     * @param \XLite\Model\PaymentMethod $paymentMethod Payment method
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->_paymentMethod = $paymentMethod;
        $this->set(
            'payment_method',
            is_null($paymentMethod)
                ? 0
                : $paymentMethod->get('payment_method')
        );
    }

    function getProfile() 
    {
        if (!isset($this->_profile)) {
            $pid = $this->get('profile_id');
            if ($pid) {
                $this->_profile = new \XLite\Model\Profile($pid);
            }
        }

        return $this->_profile;
    }

    function setProfile($profile) 
    {
        $this->_profile = $profile;
        $this->set('profile_id', isset($profile) ? $profile->get('profile_id') : 0);
    }
    
    function getOrigProfile() 
    {
        if (!isset($this->_origProfile)) {
            if ($pid = $this->get('orig_profile_id')) {
                $this->_origProfile = new \XLite\Model\Profile($pid);

            } else {
                return $this->getProfile();
            }
        }

        return $this->_origProfile;
    }

    function setOrigProfile($profile) 
    {
        $this->_origProfile = $profile;
        $this->set('orig_profile_id', isset($profile) ? $profile->get('profile_id') : 0);
    }

    function setProfileCopy($prof) 
    {
        $this->setOrigProfile($prof);

        $p = $prof->cloneObject();
        $p->set('order_id', $this->get('order_id'));
        $p->update();

        $this->setProfile($p);
    }

    /**
    * Returns all tax values as an associative Array.
    */
    function getAllTaxes() 
    {
        if (is_null($this->_taxes)) {
            $this->_taxes = $this->get('taxes') == '' ? array() : unserialize($this->get('taxes'));
        }

        return $this->_taxes;
    }

    function setAllTaxes($taxes) 
    {
        $this->_taxes = $taxes;
        $this->set('taxes', serialize($taxes));
    }

    /**
    * Returns the named tax label.
    */
    function getTaxLabel($name) 
    {
        $tax = new \XLite\Model\TaxRates();

        return $tax->getTaxLabel($name);
    }

    function getRegistration($name) 
    {
        $tax = new \XLite\Model\TaxRates();

        return $tax->getRegistration($name);
    }

    /**
     * Check - any tax is registered  or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxRegistered()
    {
        $result = false;
        foreach ($this->get('allTaxes') as $name => $value) {
            if ($this->getRegistration($name) != '') {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
    * Selects taxes to be shown in cart totals.
    */
    function getDisplayTaxes() 
    {
        if (is_null($this->get('profile')) && !$this->config->General->def_calc_shippings_taxes) {
            return null;
        }

        $taxRates = new \XLite\Model\TaxRates();
        $values = $names = $orderby = array();
        foreach ($this->get('allTaxes') as $name => $value) {
            if ($taxRates->getTaxLabel($name)) {
                $values[] = $value;
                $names[] = $name;
                $orderby[] = $taxRates->getTaxPosition($name);
            }
        }

        // sort taxes according to $orderby
        array_multisort($orderby, $values, $names);

        // compile an associative array $name=>$value
        $taxes = array();
        $len = count($names);
        for ($i = 0; $i < $len; $i++) {
            $taxes[$names[$i]] = $values[$i];
        }

        return $taxes;
    }

    /**
     * Get items list fingerprint 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemsFingerprint() 
    {
        $result = false;

        if (!$this->isEmpty()) {

            $result = array();
            foreach ($this->getItems() as $item_idx => $item) {
                $result[] = array(
                    $item_idx,
                    $item->getKey(),
                    $item->get('amount')
                );
            }

            $result = serialize($result);
        }

        return $result;
    }

    /**
     * Delete order item 
     * 
     * @param \XLite\Model\OrderItem $item Item
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteItem(\XLite\Model\OrderItem $item) 
    {
        $item->delete();
        $this->refresh('items');
    }

    /**
    * Updates the specified item.
    *
    * @param OrderItem $item The order item to update
    * @access public
    */
    function updateItem($item) 
    {
        if (!is_null($this->_items)) {
            $len = count($this->_items);
            for ($i = 0; $i < $len; $i++) {
                if ($this->_items[$i]->_uniqueKey == $item->_uniqueKey) {
                    $this->_items[$i] = $item;
                }
            }
        }
    }

    /**
     * Check - item exist in order or not 
     * 
     * @param \XLite\Model\OrderItem $item Item
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExistsItem(\XLite\Model\OrderItem $item)
    {
        $result = false;

        if ($item->isValid()) {
            $key = $item->getKey();

            foreach ($this->getItems() as $i) {
                if ($i->getKey() == $key) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Calculates order totals and store them in the order properties:
     * total, subtotal, tax, shipping, etc
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calcTotals()
    {
        $this->calcTotal();
    }

    /**
     * Generate a string representation of the order
     * to send to a payment service
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription() 
    {
        $result = array();

        foreach ($this->getItems() as $item) {
            $result[] = method_exists($item, 'getDescription')
                ? $item->getDescription()
                : $item->get('description');
        }

        $result[] = '';

        return implode("\n", $result);
    }

    /**
     * Get order details 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDetails()
    {
        if (is_null($this->_details)) {
            $d = parent::get('details');
            $this->_details = '' == $d ? array() : unserialize($d);
        }

        return $this->_details;
    }

    /**
     * Get detail 
     * 
     * @param string $name Details cell name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDetail($name)
    {
        $details = $this->getDetails();

        return isset($details[$name]) ? $details[$name] : null;
    }

    /**
     * Set details 
     * 
     * @param mixed $value Value (string or array)
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDetails($value)
    {
        if (!is_array($value)) {
            $value = unserialize($value);
        }

        if (!is_array($value)) {
            $value = array();
        }

        parent::set('details', serialize($value));

        $this->_details = $value;
    }

    /**
     * Set detail cell
     * 
     * @param string $name  Cell code
     * @param mixed  $value Cell value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDetail($name, $value)
    {
        $details = $this->getDetails();

        $details[$name] = $value;

        $this->setDetails($details);
    }

    function getDetailLabels() 
    {
        if (is_null($this->_detailLabels)) {
            $d = parent::get('detail_labels');
            $this->_detailLabels = $d == '' ? array() : unserialize($d);
        }

        return $this->_detailLabels;
    }

    /**
     * Get detail cell label 
     * 
     * @param string $name Cell name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDetailLabel($name) 
    {
        $d = $this->getDetailLabels();

        return isset($d[$name]) ? $d[$name] : null;
    }

    /**
     * Set detail labels 
     * 
     * @param array $value Labels list
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDetailLabels(array $value) 
    {
        parent::set('detail_labels', serialize($value));

        $this->_detailLabels = $value;
    }

    /**
     * Set details cell
     * 
     * @param string $code  Cell code
     * @param string $name  Cell name
     * @param mixed  $value Cell value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDetailsCell($code, $name, $value)
    {
        $details = $this->getDetails();
        $labels = $this->getDetailLabels();

        $details[$code] = $value;
        $labels[$code] = $name;

        $this->setDetails($details);
        $this->setDetailLabels($labels);
    }

    /**
     * Unset details cell 
     * 
     * @param string $code Cell code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function unsetDetailsCell($code)
    {
        $details = $this->getDetails();

        if (isset($details[$code])) {
            unset($details[$code]);
        }

        $this->setDetails($details);
    }

    function _prepareSearchWhere($where)
    {
        return $where;
    }

    /**
     * Orders search 
     * 
     * @param \XLite\Model\Profile $profile       Profile
     * @param integer             $id            Order id
     * @param string              $status        Status code
     * @param integer             $startDate     Date (start range)
     * @param integer             $endDate       Date (end range)
     * @param boolean             $orig_profile  User original profile
     * @param string              $sortCriterion Sort criterion (field name)
     * @param boolean             $sortOrderAsc  User ascending sort order
     *  
     * @return array of \XLite\Model\Order
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(
        $profile = null,
        $id = null,
        $status = null,
        $startDate = null,
        $endDate = null,
        $orig_profile = true,
        $sortCriterion = 'date',
        $sortOrderAsc = false
    ) {
        $where = array();

        if (isset($profile)) {
            $where[] = ($orig_profile ? 'orig_profile_id' : 'profile_id') . " = '" . $profile->get('profile_id') . "'";
        }

        if (!empty($id)) {
            $where[] = 'order_id = ' . intval($id);
        }

        if (!empty($status)) {
            $where[] = 'status = \'' . $status . '\'';
        }

        if ($startDate) {
            $where[] = 'date >= ' . intval($startDate);
        }

        if ($endDate) {
            $where[] = 'date <= ' . intval($endDate);
        }

        $where = $this->_prepareSearchWhere($where);

        if (!$sortCriterion) {
            $sortCriterion = 'date';
        }

        return $this->findAll(implode(' AND ', $where), $sortCriterion . ' ' . ($sortOrderAsc ? 'ASC' : 'DESC'));
    }

    /**
     * Get orders count (by profile)
     * 
     * @param \XLite\Model\Profile $profile      Profile
     * @param boolean             $orig_profile User original profile instead basic profile
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountByProfile(\XLite\Model\Profile $profile = null, $orig_profile = true)
    {
        $where = array();

        if (isset($profile)) {
            $where[] = ($orig_profile ? 'orig_profile_id' : 'profile_id') . ' = \'' . $profile->get('profile_id') . '\'';
        }

        return $this->count(implode(' AND ', $where));
    }

    ////////////////// Order status functions //////////////////

    /**
    * Calls one of the following function: declined, processed, failed, queued
    * when order status is changed. See share/doc/developmentdocs/status.gif
    */
    function statusChanged($oldStatus, $newStatus) 
    {
        if (
            !in_array($oldStatus, array('P', 'C', 'Q'))
            && in_array($newStatus, array('P', 'C', 'Q'))
        ) {
            $this->checkedOut();
        }

        if ($oldStatus == 'I' && $newStatus == 'Q') {
            $this->queued();
        }

        if (
            $oldStatus != 'P'
            && $oldStatus != 'C'
            && ($newStatus == 'P' || $newStatus == 'C')
        ) {
            $this->processed();
        }

        if (
            ($oldStatus == 'P' || $oldStatus == 'C')
            && $newStatus !='P'
            && $newStatus != 'C'
        ) {
            $this->declined();
        }

        if (
            ($oldStatus == 'P' || $oldStatus == 'C' || $oldStatus == 'Q')
            && $newStatus !='P'
            && $newStatus != 'C'
            && $newStatus != 'Q'
        ) {
            $this->uncheckedOut();
        }

        if (
            $oldStatus != 'F'
            && $oldStatus != 'D'
            && ($newStatus == 'F' || $newStatus == 'D')
        ) {
            $this->failed();
        }

    }

    /**
     * Order 'complete' event
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkedOut()
    {
    }

    /**
     * Order 'charge back' event
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function uncheckedOut() 
    {
    }

    /**
     * Called when an order is qeued
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function queued() 
    {
    }

    /**
     * Called when an order successfully placed by a client 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function succeed()
    {
        // save order ID#
        $this->session->set('last_order_id', $this->get('order_id'));

        // send email notification about initially placed order
        $status = $this->get('status');
        if (
            !in_array($status, array('P', "C", "I"))
            && ($this->config->Email->enable_init_order_notif || $this->config->Email->enable_init_order_notif_customer)
        ) {
            $mail = new \XLite\Model\Mailer();

            // for compatibility with dialog.order syntax in mail templates
            $mail->order = $this;

            // notify customer
            if ($this->config->Email->enable_init_order_notif_customer) {
                $mail->adminMail = false;
                $mail->selectCustomerLayout();
                $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
                $mail->compose(
                    $this->config->Company->orders_department,
                    $this->getProfile()->get('login'),
                    'order_created'
                );
                $mail->send();
            }

            // notify admin about initially placed order
            if ($this->config->Email->enable_init_order_notif) {

                // whether or not to show CC info in mail notification
                $mail->adminMail = true;
                $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
                $mail->compose(
                    $this->config->Company->site_administrator,
                    $this->config->Company->orders_department,
                    'order_created_admin'
                );
                $mail->send();
            }
        }
    }
    
    /**
     * Called when an order becomes processed, before saving it to the database
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processed()
    {
        $mail = new \XLite\Model\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
        $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mail->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->orders_department,
            'order_processed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
        $mail->compose(
            $this->config->Company->site_administrator,
            $this->getProfile()->get('login'),
            'order_processed'
        );
        $mail->send();
    }

    /**
     * Called when an order status changed from processed to not processed
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function declined()
    {
    }

    /**
     * Called when the order status changed to failed
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function failed()
    {
        $mail = new \XLite\Model\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
        $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mail->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->orders_department,
            'order_failed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
        $mail->compose(
            $this->config->Company->orders_department,
            $this->getProfile()->get('login'),
            'order_failed'
        );
        $mail->send();
    }

    //////////////// Private data storage functions ////////////////

    /**
    * Changes were made to the order
    */
    function _beforeSave() 
    {
        if ($this->_statusChanged) {
            $this->statusChanged($this->_oldStatus, $this->get('status'));
            $this->_statusChanged = false;
        }

        parent::_beforeSave();
    }

    function create() 
    {
        parent::create();

        $orderStartID = intval($this->config->General->order_starting_number);

        if ($this->get('order_id') < $orderStartID) {
            $order_id = $this->get('order_id');
            $this->set('order_id', $orderStartID);
            $table = $this->db->getTableByAlias($this->get('alias'));
            $this->db->query("UPDATE $table SET order_id = $orderStartID WHERE order_id = $order_id");
        }
    }

    function remove()
    {
        $status = $this->get('status');
        if ($status == "Q" || $status == "I") {
            $this->set('status', "D"); // decline an order before deleting it
            $this->statusChanged($status, "D");
        }
        $this->delete();
    }

    function delete()
    {
        foreach ($this->getItems() as $item) {
            $this->deleteItem($item);
        }

        // don't remove profile if this is a cart object
        if (!is_null($this->getProfile()) && $this->get('status') != 'T') {
           $this->getProfile()->delete();
        }

        parent::delete();
    }

    /**
    * Removes expired 'T' orders (carts)
    */
    function collectGarbage($limit = null) 
    {
        $order = new \XLite\Model\Order();
        $order->_range = "status = 'T'";
        $orders = $order->findAll("date < ".(time()-ORDER_EXPIRATION_TIME), 'date', null, $limit);
        foreach ($orders as $o) {
            $o->delete();
        }
    }

    function isShowCCInfo()
    {
        return $this->get('payment_method') == "CreditCard" && $this->config->Email->show_cc_info;
    }

    function isShowECheckInfo()
    {
        return $this->get('payment_method') == "Echeck" && $this->config->Email->show_cc_info;
    }

    function recalcItems()
    {
        $items = $this->getItems();
        if (is_array($items)) {
            foreach ($items as $item_key => $item) {
                $product = $item->get('product');
                if ($this->config->Taxes->prices_include_tax && isset($product)) {
                    $oldPrice = $item->get('price');
                    $items[$item_key]->setProduct($item->get('product'));
                    $items[$item_key]->updateAmount($item->get('amount'));

                    if ($items[$item_key]->get('price') != $oldPrice) {
                        $this->_items = null;
                    }
                }
            }
        }
    }

    function _dumpItems($items = null) {
        if (!is_array($items)) {
            $items = (array) $this->get('items');
        }

        if (empty($items)) {
            var_dump($items);
        }

        foreach ($items as $key=>$item) {
            echo "[$key] => ";
            $item->dump();
        }
    }

}


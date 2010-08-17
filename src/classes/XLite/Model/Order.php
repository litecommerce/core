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
 * Class represens an order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="\XLite\Model\Repo\Order")
 * @Table (name="orders")
 * @HasLifecycleCallbacks
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="is_order", type="integer", length="1")
 * @DiscriminatorMap({"1" = "XLite\Model\Order", "0" = "XLite\Model\Cart"})
 */
class Order extends \XLite\Model\AEntity
{
    /**
     * Order statuses 
     */
    const STATUS_TEMPORARY  = 'T';
    const STATUS_INPROGRESS = 'I';
    const STATUS_QUEUED     = 'Q';
    const STATUS_PROCESSED  = 'P';
    const STATUS_COMPLETED  = 'C';
    const STATUS_FAILED     = 'F';
    const STATUS_DECLINED   = 'D';


    /**
     * Add item error codes 
     */
    const NOT_VALID_ERROR = 'notValid';


    /**
     * Order unique id
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $order_id;

    /**
     * Order profile unique id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $profile_id;

    /**
     * Original profile id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $orig_profile_id = 0;

    /**
     * Total 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $total = 0.0000;

    /**
     * Subtotal 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $subtotal = 0.0000;

    /**
     * Shipping cost 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $shipping_cost = 0.0000;

    /**
     * Tax cost
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $tax = 0.0000;

    /**
     * Shipping method unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $shipping_id = 0;

    /**
     * Shipping tracking code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32")
     */
    protected $tracking = '';

    /**
     * Order creation timestamp
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Status code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $status = self::STATUS_INPROGRESS;

    /**
     * Payment method code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64")
     */
    protected $payment_method = '';

    /**
     * Customer notes 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="65535")
     */
    protected $notes = '';

    /**
     * Order details
     *
     * @var    \XLite\Model\OrderDetail
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderDetail", mappedBy="order", cascade={"persist","remove"})
     * @OrderBy ({"name" = "ASC"})
     */
    protected $details;

    /**
     * Order items
     *
     * @var    \XLite\Model\OrderItem
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem", mappedBy="order", cascade={"persist","remove"})
     */
    protected $items;

    /**
     * 'Add item' error code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $addItemError;


    /**
     * Return list of all aloowed order statuses
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedStatuses()
    {
        return array(
            self::STATUS_TEMPORARY  => 'Cart',
            self::STATUS_INPROGRESS => 'Incompleted',
            self::STATUS_QUEUED     => 'Queued',
            self::STATUS_PROCESSED  => 'Processed',
            self::STATUS_COMPLETED  => 'Completed',
            self::STATUS_FAILED     => 'Failed',
            self::STATUS_DECLINED   => 'Declined',
        );
    }


    ///////////////////////////// OBSOLETE PROPERTIES //////////////////////

    protected $allTaxes = array();
    protected $shippingTaxes = array();
    protected $shippingTax = 0;
    protected $statusChanged = false;
    protected $oldStatus;
    protected $shippingMethod;
    protected $paymentMethodModel;
    protected $profile;
    protected $origProfile;

    /**
     * Add item to order
     * 
     * @param \XLite\Model\OrderItem $newItem Item to add
     *  
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function addItem(\XLite\Model\OrderItem $newItem)
    {
        $result = false;

        if ($newItem->isValid()) {

            $this->addItemError = null;

            $item = $this->getItemByItem($newItem);

            if ($item) {
                $item->setAmount($item->getAmount() + $newItem->getAmount());

            } else {
                $this->addNewItem($newItem);
            }

            $result = true;

        } else {
            $this->addItemError = self::NOT_VALID_ERROR;
        }

        return $result;
    }

    /**
     * Get 'Add item' error code
     * 
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddItemError()
    {
        return $this->addItemError;
    }

    /**
     * Get item from order by another item 
     * 
     * @param \XLite\Model\OrderItem $item Another item
     *  
     * @return \XLite\Model\OrderItem or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemByItem(\XLite\Model\OrderItem $item)
    {
        $key = $item->getKey();

        $found = null;

        foreach ($this->getItems() as $i) {
            if ($i->getKey() == $key) {
                $found = $item;
                break;
            }
        }

        return $found;
    }

    /**
     * Get item from order by item  id
     * 
     * @param integer $itemId item id
     *  
     * @return \XLite\Model\OrderItem or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemByItemId($itemId)
    {
        $found = null;

        foreach ($this->getItems() as $i) {
            if ($i->getItemId() == $itemId) {
                $found = $i;
                break;
            }
        }

        return $found;
    }

    /**
     * Create new cart item 
     * 
     * @param \XLite\Model\OrderItem $item new item
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addNewItem(\XLite\Model\OrderItem $item)
    {
        $item->setOrder($this);
        $this->addItems($item);
    }

    /**
     * Get shipping rates 
     * 
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @since  3.0.0
     */
    public function getShippingRates()
    {
        return $this->calculateShippingRates();
    }

    /**
     * Return items number
     * 
     * @return integer
     * @access public
     * @since  3.0.0
     */
    public function countItems()
    {
        return count($this->getItems());
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
        return 0 >= $this->countItems();
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
        return $this->getSubtotal() < \XLite\Core\Config::getInstance()->General->minimal_order_amount;
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
        return $this->getSubtotal() > \XLite\Core\Config::getInstance()->General->maximal_order_amount;
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
     * Check - is order processed or not
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isProcessed()
    {
        return in_array($this->getStatus(), array(self::STATUS_PROCESSED, self::STATUS_COMPLETED));
    }

    /**
     * Check - os order queued or not
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isQueued()
    {
        return self::STATUS_QUEUED == $this->getStatus();
    }

    /**
     * Calculate and return all order taxes
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateAllTaxes() 
    {
        $taxRates = new \XLite\Model\TaxRates();
        $taxRates->set('order', $this);
        $result = array();
        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax && isset($product)) {
                $item->setPrice($product->getPrice());
            }

            $taxRates->set('orderItem', $item);
            $taxRates->calculateTaxes();

            $result = $this->addTaxes($result, $taxRates->get('allTaxes'));
        }

        // tax on shipping
        $pricesIncludeTax = \XLite\Core\Config::getInstance()->Taxes->prices_include_tax;
        if (
            $this->isShippingDefined()
            && (!$pricesIncludeTax || ($pricesIncludeTax && $taxRates->isShippingDefined()))
        ) {
            $taxRates->_conditionValues['product class'] = 'shipping service';
            $taxRates->_conditionValues['cost'] = $this->getShippingCost();
            $taxRates->calculateTaxes();
            $result = $this->addTaxes($result, $taxRates->get('allTaxes'));

            $shippingTaxes = array();
            $shippingTaxes = $this->addTaxes($shippingTaxes, $taxRates->get('shippingTaxes'));
            foreach ($shippingTaxes as $name => $value) {
                $shippingTaxes[$name] = $this->formatCurrency($shippingTaxes[$name]);
            }
            $this->shippingTaxes = $shippingTaxes;
        }

        // round all tax values
        foreach ($result as $name => $value) {
            $result[$name] = $this->formatCurrency($result[$name]);
        }

        $this->allTaxes = $result;

        return $result;
    }

    /**
     * Add new taxes into existsing taxes list
     * 
     * @param array $acc   Existing taxes list
     * @param array $taxes New taxes
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addTaxes(array $acc, array $taxes) 
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
     * Calculate and return tax cost 
     * 
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateTaxCost() 
    {
        // Base tax cost
        $this->calculateAllTaxes();
        $taxes = $this->allTaxes;
        $tax = isset($taxes['Tax']) ? $taxes['Tax'] : 0;    // total tax for all tax systems
        $this->setTax($tax);

        // Shipping-based tax cost
        $shippingTax = 0;

        if ($this->isShippingDefined()) {
            $shippingTaxes = $this->shippingTaxes;
            if (is_array($shippingTaxes)) {
                if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax && isset($shippingTaxes['Tax'])) {
                    $shippingTax = $shippingTaxes['Tax'];

                } else {
                    foreach ($shippingTaxes as $name => $value) {
                        if (
                            isset($taxes[$name])
                            && (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax || $taxes[$name] == $value)
                        ) {
                            $shippingTax += $value;
                        }
                    }
                }
            }
        }

        $this->shippingTax = $shippingTax;

        return $tax;
    }

    /**
     * Calculate order subtotal 
     * 
     * @param bolean $shippedOnly Calculate shipped items only
     *  
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateSubtotal($shippedOnly = false) 
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            if (!$shippedOnly || $item->isShipped()) {
                $subtotal += $item->getTotal();
            }
        }

        if (!$shippedOnly) {
            $this->setSubtotal($this->formatCurrency($subtotal));
        }

        return $subtotal;
    }

    /**
     * Calculate order total 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateTotal() 
    {
        if (0 < $this->countItems()) {

            $this->calculateSubtotal();
            $this->calculateShippingCost();
            $this->calculateTaxCost();

            $total = $this->getSubtotal();

            if (!\XLite\Core\Config::getInstance()->Taxes->prices_include_tax) {
                $total += $this->getTax();
            }

            $total += $this->getShippingCost();

            if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax) {
                $total += $this->shippingTax;
            }

            $this->setTotal($this->formatCurrency($total));
        }
    }

    /**
     * Get shipped items 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippedItems() 
    {
        $result = array();

        foreach ($this->getItems() as $item) {
            if ($item->isShipped()) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Count shipped items quantity
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countShippedItems() 
    {
        $result = 0;

        foreach ($this->getShippedItems() as $item) {
            $result += $item->getAmount();
        }

        return $result;
    }

    /**
     * Get order weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeight() 
    {
        $weight = 0;

        foreach ($this->getShippedItems() as $item) {
            $weight += $item->getWeight();
        }

        return $weight;
    }

    /**
     * Calculate shipping cost 
     * 
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateShippingCost() 
    {
        $cost = 0;

        if ($this->isShipped()) {
        
            $shippingMethod = $this->getShippingMethod();
            $cost = is_object($shippingMethod) ? $shippingMethod->calculate($this) : false;

            if (false === $cost) {
                $rates = $this->calculateShippingRates();

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
        }

        $this->setShippingCost($this->formatCurrency($cost));

        return $cost;
    }

    /**
     * Calculate shipping rates 
     * 
     * @return array of \XLite\Model\ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateShippingRates() 
    {
        $data = array();
            
        foreach (\XLite\Model\Shipping::getModules() as $module) {
            $data += $module->getRates($this);
        }

        uasort($data, array($this, 'getShippingRatesOrderCallback'));

        return $data;
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
   
    /**
     * Check - is tax defined or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxDefined() 
    {
        return true;
    }
    
    /**
     * Check - is shipping methopd defined or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingDefined() 
    {
        return (bool)$this->getShippingId();
    }

    /* TODO - rework
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
    */

    /**
     * Set status 
     * 
     * @param string $value Status code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setStatus($value)
    {
        if ($this->status != $value && !$this->statusChanged) {
            $this->statusChanged = true;
            $this->oldStatus = $this->status;
        }

        $this->status = $value;

        // TODO - rework
        //$this->refresh('shippingRates');
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
        if (!isset($this->shippingMethod) && $this->getShippingId()) {
            $this->shippingMethod = new \XLite\Model\Shipping($this->getShippingId());
        }

        return $this->shippingMethod;
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
            $this->shippingMethod = $shippingMethod;
            $this->setShippingId($shippingMethod->get('shipping_id'));

        } else {
            $this->shippingMethod = false;
            $this->setShippingId(0);
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
        if (!isset($this->paymentMethodModel) && $this->payment_method) {
            $this->paymentMethodModel = \XLite\Model\PaymentMethod::factory($this->payment_method);
        }

        return $this->paymentMethodModel;
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
        $this->paymentMethodModel = $paymentMethod;
        $this->payment_method = is_null($paymentMethod)
            ? 0
            : $paymentMethod->get('payment_method');
    }

    /**
     * Get order profile 
     * 
     * @return \XLite\Model\Profile
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProfile() 
    {
        if (!isset($this->profile)) {
            $pid = $this->getProfileId();
            if ($pid) {
                $this->profile = new \XLite\Model\Profile($pid);
            }
        }

        return $this->profile;
    }

    /**
     * Set profile 
     * 
     * @param mixed $profile Profile
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setProfile($profile) 
    {
        $this->profile = $profile;
        $this->setProfileId(isset($profile) ? $profile->get('profile_id') : 0);
    }
    
    /**
     * Get original profile 
     * 
     * @return \XLite\Model\Profile
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrigProfile() 
    {
        if (!isset($this->origProfile)) {
            $pid = $this->getOrigProfileId();
            if ($pid) {
                $this->origProfile = new \XLite\Model\Profile($pid);
            }
        }

        return $this->_origProfile
            ? $this->_origProfile
            : $this->getProfile();
    }

    /**
     * Set original profile 
     * 
     * @param mixed $profile Profile
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setOrigProfile($profile) 
    {
        $this->origProfile = $profile;
        $this->setOrigProfileId(isset($profile) ? $profile->get('profile_id') : 0);
    }

    /**
     * Set profile copy 
     * 
     * @param \XLite\Model\Profile $prof Profile
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setProfileCopy($prof) 
    {
        $this->setOrigProfile($prof);

        $p = $prof->cloneObject();
        $p->set('order_id', $this->get('order_id'));
        $p->update();

        $this->setProfile($p);
    }

    /**
     * Get tax label 
     * 
     * @param string $name Tax name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxLabel($name) 
    {
        $tax = new \XLite\Model\TaxRates();

        return $tax->getTaxLabel($name);
    }

    /**
     * Get registration 
     * 
     * @param string $name Tax name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRegistration($name) 
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

        foreach ($this->allTaxes as $name => $value) {
            if ($this->getRegistration($name) != '') {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get display taxes list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayTaxes() 
    {
        $taxes = null;

        if (!is_null($this->getProfile()) || \XLite\Core\Config::getInstance()->General->def_calc_shippings_taxes) {

            $taxRates = new \XLite\Model\TaxRates();
            $values = $names = $orderby = array();
            foreach ($this->allTaxes as $name => $value) {
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
            foreach ($this->getItems() as $i => $item) {
                $result[] = array(
                    $i,
                    $item->getKey(),
                    $item->getAmount()
                );
            }

            $result = serialize($result);
        }

        return $result;
    }

    /**
     * Calculates order totals and store them in the order properties
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculate()
    {
        $this->calculateTotal();
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
            if (method_exists($item, 'getDescription')) {
                $result[] = $item->getDescription();
            }
        }

        return implode("\n", $result) . "\n";
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

        $detail = null;

        foreach ($details as $d) {
            if ($d->getName() == $name) {
                $detail = $d;
                break;
            }
        }

        return $detail;
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
        $detail = $this->getDetail($name);

        if ($detail) {
            $deatil->setValue($value);

        } else {
            $detail = new \XLite\Model\OrderDetail();

            $detail->setOrder($this);
            $this->addDetails($detail);

            $detail->setName($name);
            $deatil->setValue($value);
        }
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
        $detail = $this->getDetail($name);

        return $detail ? $detail->getLabel() : null;
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
    public function setDetailCell($code, $name, $value)
    {
        $this->setDetail($code, $value);

        $detail = $this->getDetail($code);
        $detail->setLabel($name);
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
    public function unsetDetailCell($code)
    {
        $detail = $this->getDetail($code);

        if ($detail) {
            $this->getDetails()->removeElement($detail);
        }
    }

    /**
     * statusChanged 
     * 
     * @param mixed $oldStatus ____param_comment____
     * @param mixed $newStatus ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function changeStatusPostprocess($oldStatus, $newStatus) 
    {
        $list = array(self::STATUS_PROCESSED, self::STATUS_COMPLETED, self::STATUS_QUEUED);

        if (
            !in_array($oldStatus, $list)
            && in_array($newStatus, $list)
        ) {
            $this->processCheckOut();
        }

        if (self::STATUS_INPROGRESS == $oldStatus && self::STATUS_QUEUED == $newStatus) {
            $this->processQueue();
        }

        if (
            self::STATUS_PROCESSED != $oldStatus
            && self::STATUS_COMPLETED != $oldStatus
            && (self::STATUS_PROCESSED == $newStatus || self::STATUS_COMPLETED == $newStatus)
        ) {
            $this->processProcess();
        }

        if (
            (self::STATUS_PROCESSED == $oldStatus || self::STATUS_COMPLETED == $oldStatus)
            && self::STATUS_PROCESSED != $newStatus
            && self::STATUS_COMPLETED != $newStatus
        ) {
            $this->processDecline();
        }

        if (
            in_array($oldStatus, $list)
            && !in_array($newStatus, $list)
        ) {
            $this->processUncheckOut();
        }

        if (
            self::STATUS_FAILED != $oldStatus
            && self::STATUS_DECLINED != $oldStatus
            && (self::STATUS_FAILED == $newStatus || self::STATUS_DECLINED == $newStatus)
        ) {
            $this->processFail();
        }

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
    }

    /**
     * Order 'charge back' event
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processUncheckOut() 
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
    protected function processQueue() 
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
    public function processSucceed()
    {
        // save order ID#
        \XLite\Model\Session::getInstance()->set('last_order_id', $this->getOrderId());

        // send email notification about initially placed order
        $status = $this->getStatus();
        $list = array(self::STATUS_PROCESSED, self::STATUS_COMPLETED, self::STATUS_INPROGRESS);
        $send = \XLite\Core\Config::getInstance()->Email->enable_init_order_notif
            || \XLite\Core\Config::getInstance()->Email->enable_init_order_notif_customer;

        if (!in_array($status, $list) && $send) {
            $mail = new \XLite\Model\Mailer();

            // for compatibility with dialog.order syntax in mail templates
            $mail->order = $this;

            // notify customer
            if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif_customer) {
                $mail->adminMail = false;
                $mail->selectCustomerLayout();
                $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
                $mail->compose(
                    \XLite\Core\Config::getInstance()->Company->orders_department,
                    $this->getProfile()->get('login'),
                    'order_created'
                );
                $mail->send();
            }

            // notify admin about initially placed order
            if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif) {

                // whether or not to show CC info in mail notification
                $mail->adminMail = true;
                $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
                $mail->compose(
                    \XLite\Core\Config::getInstance()->Company->site_administrator,
                    \XLite\Core\Config::getInstance()->Company->orders_department,
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
    protected function processProcess()
    {
        $mail = new \XLite\Model\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
        $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_processed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
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
    protected function processDecline()
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
    protected function processFail()
    {
        $mail = new \XLite\Model\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
        $mail->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_failed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $mail->set('charset', $this->getProfile()->getComplex('billingCountry.charset'));
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->orders_department,
            $this->getProfile()->get('login'),
            'order_failed'
        );
        $mail->send();
    }


    /**
     * Prepare order before save data operation
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     * @PrePersist
     * @PreUpdate
     */
    protected function prepareBeforeSave() 
    {
        if ($this->statusChanged) {
            $this->changeStatusPostprocess($this->oldStatus, $this->getStatus());
            $this->statusChanged = false;
        }
    }

    /**
     * Prepare order before remove operation
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     * @PreRemove
     */
    protected function prepareBeforeRemove()
    {
        if (in_array($this->getStatus(), array(self::STATUS_QUEUED, self::STATUS_INPROGRESS))) {
            $status = $this->getStatus();
            $this->setStatus(self::STATUS_DECLINED);
            $this->changeStatusPostprocess($status, self::STATUS_DECLINED);
        }
    }

    /**
     * Check - CC info showing available or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShowCCInfo()
    {
        return 'CreditCard' == $this->getPaymentMethod()
            && \XLite\Core\Config::getInstance()->Email->show_cc_info;
    }

    /**
     * Check - e-card info showing available or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShowEcheckInfo()
    {
        return 'Echeck' == $this->getPaymentMethod()
            && \XLite\Core\Config::getInstance()->Email->show_cc_info;
    }

    /**
     * Refresh order items 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function refreshItems()
    {
        if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax) {
            $changed = false;

            foreach ($this->getItems() as $item) {
                $product = $item->getProduct();
                if ($product) {
                    $oldPrice = $item->getPrice();
                    $item->setProduct($item->getProduct());
                    $item->updateAmount($item->getAmount());

                    if ($item->getPrice() != $oldPrice) {
                        $this->getItems()->removeElement($item);
                        \XLite\Core\Database::getEM()->remove($item);
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                \XLite\Core\Database::getEM()->flush();
            }
        }
    }

    /**
     * Format currency value
     * 
     * @param float $price Currency
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function formatCurrency($price)
    {
        return sprintf('%.02f', round(doubleval($price), 2));
    }
}

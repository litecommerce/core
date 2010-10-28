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
 * @InheritanceType ("SINGLE_TABLE")
 * @DiscriminatorColumn (name="is_order", type="integer", length="1")
 * @DiscriminatorMap ({"1" = "XLite\Model\Order", "0" = "XLite\Model\Cart"})
 */
class Order extends \XLite\Model\Base\ModifierOwner
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
     * Taxes (serialized)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="array")
     */
    protected $taxes = array();

    /**
     * Order details
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderDetail", mappedBy="order", cascade={"all"})
     * @OrderBy ({"name" = "ASC"})
     */
    protected $details;

    /**
     * Order items
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem", mappedBy="order", cascade={"all"})
     */
    protected $items;

    /**
     * Order saved modifiers
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderModifier", mappedBy="owner", cascade={"all"})
     * @OrderBy ({"id" = "ASC"})
     */
    protected $saved_modifiers;

    /**
     * Payment transactions
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\Transaction", mappedBy="order", cascade={"all"})
     */
    protected $payment_transactions;

    /**
     * Currency 
     * 
     * @var    \XLite\Model\Currency
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne (targetEntity="XLite\Model\Currency")
     * @JoinColumn (name="currency_id", referencedColumnName="currency_id")
     */
    protected $currency;

    /**
     * 'Add item' error code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $addItemError;

    ///////////////////////////// OBSOLETE PROPERTIES //////////////////////

    protected $statusChanged = false;
    protected $oldStatus;
    protected $paymentMethodModel;
    protected $profile;
    protected $origProfile;

    /**
     * Return list of all aloowed order statuses
     * 
     * @param string $status status to get
     *  
     * @return array | string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedStatuses($status = null)
    {
        $list = array(
            self::STATUS_TEMPORARY  => 'Cart',
            self::STATUS_INPROGRESS => 'Incompleted',
            self::STATUS_QUEUED     => 'Queued',
            self::STATUS_PROCESSED  => 'Processed',
            self::STATUS_COMPLETED  => 'Completed',
            self::STATUS_FAILED     => 'Failed',
            self::STATUS_DECLINED   => 'Declined',
        );

        return isset($status)
            ? (isset($list[$status]) ? $list[$status] : null)
            : $list;
    }

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
                $found = $i;
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
     * Normalize items 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function normalizeItems()
    {
        // Normalize by key
        $keys = array();

        foreach ($this->getItems() as $item) {
            $key = $item->getKey();
            if (isset($keys[$key])) {
                $keys[$key]->setAmount($keys[$key]->getAmount() + $item->getAmount());
                $this->getItems()->removeElement($item);

                if (\XLite\Core\Database::getEM()->contains($item)) {
                    \XLite\Core\Database::getEM()->remove($item);
                }

            } else {
                $keys[$key] = $item;
            }
        }

        unset($keys);

        // Remove invalid items
        foreach ($this->getItems() as $item) {
            if (!$item->isValid()) {
                $this->getItems()->removeElement($item);
                if (\XLite\Core\Database::getEM()->contains($item)) {
                    \XLite\Core\Database::getEM()->remove($item);
                }
            }
        }
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
     * Return order items total quantity
     * 
     * @return integer
     * @access public
     * @since  3.0.0
     */
    public function countQuantity()
    {
        $quantity = 0;

        foreach ($this->getitems() as $item) {
            $quantity += $item->getAmount();
        }

        return $quantity;
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
     * Calculate and save order subtotal 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateSubtotal() 
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            $item->calculate();
            $subtotal += $item->getTotal();
        }

        $this->setSubtotal($subtotal);
    }

    /**
     * Calculate order total 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculate() 
    {
        if (0 < $this->countItems()) {
            parent::calculate();

        } else {
            $this->setSubtotal(0);
            $this->setTotal(0);
        }
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
     * Set subtotal 
     * 
     * @param float $value Subtotal
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSubtotal($value)
    {
        $this->subtotal = $this->getCurrency()->roundValue($value);
    }

    /**
     * Set total 
     * 
     * @param float $value Total
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setTotal($value)
    {
        $this->total = $this->getCurrency()->roundValue($value);
    }

    /**
     * Return list of available payment methods
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getPaymentMethods()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findAllActive();
    }

    /**
     * Renew payment method 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function renewPaymentMethod()
    {
        $method = $this->getPaymentMethod();
        if ($method) {
            $this->setPaymentMethod();
        }
    }

    /**
     * Get payment method 
     * 
     * @return \XLite\Model\Payment\Method or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPaymentMethod()
    {
        $t = $this->getFirstOpenPaymentTransaction();

        if (!$t && $this->getProfile() && $this->getProfile()->getLastPaymentId()) {

            $list = $this->getPaymentMethods();
            $found = false;

            foreach ($list as $pm) {
                if ($pm->getMethodId() == $this->getProfile()->getLastPaymentId()) {
                    $this->setPaymentMethod($pm);
                    $found = true;
                    break;
                }
            }

            if ($found) {
                $t = $this->getFirstOpenPaymentTransaction();
            }
        }

        return $t ? $t->getPaymentMethod() : null;
    }
  
    /**
     * Set payment method 
     * 
     * @param \XLite\Model\Payment\Method $paymentMethod Payment method
     * @param float                       $value         Payment transaction value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setPaymentMethod($paymentMethod, $value = null)
    {
        if (isset($paymentMethod) && !($paymentMethod instanceof \XLite\Model\Payment\Method)) {
            $paymentMethod = null;
        }

        if (!isset($paymentMethod) || $this->getFirstOpenPaymentTransaction()) {
            $t = $this->getFirstOpenPaymentTransaction();
            if ($t) {
                $this->getPaymentTransactions()->removeElement($t);
                $t->getPaymentMethod()->getTransactions()->removeElement($t);
                \XLite\Core\Database::getEM()->remove($t);
            }
        }

        if (isset($paymentMethod)) {
            $this->addPaymentTransaction($paymentMethod, $value);
        }
    }

    /**
     * Get active payment transactions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActivePaymentTransactions()
    {
        $result = array();

        foreach ($this->getPaymentTransactions() as $t) {
            if (!$t->isFailed()) {
                $result[] = $t;
            }
        }

        return $result;
    }

    /**
     * Add payment transaction 
     * 
     * @param \XLite\Model\Payment\Method $method   Payment method
     * @param float                       $value    Value
     * @param string                      $currency Currency code
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addPaymentTransaction(\XLite\Model\Payment\Method $method, $value = null, $currency = null)
    {
        if (!isset($value) || 0 >= $value) {
            $value = $this->getOpenTotal();

        } else {
            $value = min($value, $this->getOpenTotal());
        }

        $transaction = new \XLite\Model\Payment\Transaction();

        $transaction->setPaymentMethod($method);
        $method->addTransactions($transaction);

        \XLite\Core\Database::getEM()->persist($method);

        $this->addPaymentTransactions($transaction);
        $transaction->setOrder($this);

        $transaction->setMethodName($method->getServiceName());
        $transaction->setMethodLocalName($method->getName());
        $transaction->setStatus($transaction::STATUS_INITIALIZED);
        $transaction->setValue($value);

        \XLite\Core\Database::getEM()->persist($transaction);
    }

    /**
     * Get first open (not payed) payment transaction 
     * 
     * @return \XLite\Model\Payment\Transaction or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFirstOpenPaymentTransaction()
    {
        $result = null;

        foreach ($this->getPaymentTransactions() as $t) {
            if ($t::STATUS_INITIALIZED == $t->getStatus()) {
                $result = $t;
                break;
            }
        }

        return $result;
    }

    /**
     * Get open (not-payed) total 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOpenTotal()
    {
        $total = $this->getTotal();

        foreach ($this->getPaymentTransactions() as $t) {
            $total -= $t->getChargeValueModifier();
        }

        return $total;
    }

    /**
     * Check - order is open (has initialized transactions or has open total) or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOpen()
    {
        return $this->getFirstOpenPaymentTransaction()
            || 0 < $this->getOpenTotal();
    }

    /**
     * Get totally payed total 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPayedTotal()
    {
        $total = $this->getTotal();

        foreach ($this->getPaymentTransactions() as $t) {
            if ($t->isCompleted()) {
                $total -= $t->getChargeValueModifier();
            }
        }

        return $total;
    }

    /**
     * Check - order is payed or not
     * Payed - order has not open total and all payment transactions are failed or completed
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPayed()
    {
        return 0 >= $this->getPayedTotal();
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
                $this->profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($pid);
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
        $this->setProfileId(isset($profile) ? $profile->getProfileId() : 0);
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
                $this->origProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($pid);
            }
        }

        return $this->origProfile
            ? $this->origProfile
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
        $this->setOrigProfileId(isset($profile) ? $profile->getProfileId() : 0);
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
    public function setProfileCopy(\XLite\Model\Profile $prof) 
    {
        $this->setOrigProfile($prof);

        $p = $prof->cloneObject();
        $p->setOrderId($this->getOrderId());
        $p->update();

        $this->setProfile($p);
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
            foreach ($this->getItems() as $item) {
                $result[] = array(
                    $item->getItemId(),
                    $item->getKey(),
                    $item->getAmount()
                );
            }

            $result = md5(serialize($result));
        }

        return $result;
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
            $result[] = $item->getDescription();
        }

        return implode("\n", $result);
    }

    /**
     * Get order fingerprint for event subsystem
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEventFingerprint()
    {
        $hash = array(
            'items' => array(),
        );

        foreach ($this->getItems() as $item) {
            $event = $item->getEventCell();
            $event['quantity'] = $item->getAmount();

            $hash['items'][] = $event;
        }

        return $hash;
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
     * @param string $label Cell label
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDetail($name, $value, $label = null)
    {
        $detail = $this->getDetail($name);

        if (!$detail) {
            $detail = new \XLite\Model\OrderDetail();

            $detail->setOrder($this);
            $this->addDetails($detail);

            $detail->setName($name);
        }

        $detail->setValue($value);
        $detail->setLabel($label);
    }

    /**
     * Get meaning order details 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMeaningDetails()
    {
        $result = array();

        foreach ($this->getDetails() as $detail) {
            if ($detail->getLabel()) {
                $result[] = $detail;
            }
        }

        return $result;
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
                $profile = $this->getProfile();
                if ($profile) {
                    $mail->set('charset', $profile->getBillingAddress()->getCountry()->getCharset());
                    $mail->compose(
                        \XLite\Core\Config::getInstance()->Company->orders_department,
                        $profile->getLogin(),
                        'order_created'
                    );
                    $mail->send();
                }
            }

            // notify admin about initially placed order
            if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif) {

                // whether or not to show CC info in mail notification
                $mail->adminMail = true;
                $mail->set('charset', \XLite\Core\Config::getInstance()->Company->locationCountry->charset);
                $mail->compose(
                    \XLite\Core\Config::getInstance()->Company->site_administrator,
                    \XLite\Core\Config::getInstance()->Company->orders_department,
                    'order_created_admin'
                );
                $mail->send();
            }
        }

        $this->markAsOrder();
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
        $mail->set('charset', \XLite\Core\Config::getInstance()->Company->locationCountry->charset);
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_processed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $profile = $this->getProfile();
        if ($profile) {
            $mail->set('charset', $profile->getBillingAddress()->getCountry()->getCharset());
            $mail->compose(
                \XLite\Core\Config::getInstance()->Company->site_administrator,
                $profile->getLogin(),
                'order_processed'
            );
            $mail->send();
        }
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
        $mail->set('charset', \XLite\Core\Config::getInstance()->Company->locationCountry->charset);
        $mail->compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_failed'
        );
        $mail->send();

        $mail->adminMail = false;
        $mail->selectCustomerLayout();
        $profile = $this->getProfile();
        if ($profile) {
            $mail->set('charset', $profile->getBillingAddress()->getCountry()->getCharset());
            $mail->compose(
                \XLite\Core\Config::getInstance()->Company->orders_department,
                $profile->getLogin(),
                'order_failed'
            );
            $mail->send();
        }
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
        if (!is_numeric($this->date)) {
            $this->setDate(time());
        }

        if ($this->statusChanged) {
            $this->changeStatusPostprocess($this->oldStatus, $this->getStatus());
            $this->statusChanged = false;
        }
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
        return 'CreditCard' == $this->getPaymentMethod()->get('payment_method')
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
     * TODO - rework after tax subsystem rework
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
}

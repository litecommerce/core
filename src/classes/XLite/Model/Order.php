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
 * Class represents an order
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Order")
 * @Table  (name="orders",
 *      indexes={
 *          @Index (name="date", columns={"date"}),
 *          @Index (name="total", columns={"total"}),
 *          @Index (name="subtotal", columns={"subtotal"}),
 *          @Index (name="tracking", columns={"tracking"}),
 *          @Index (name="status", columns={"status"}),
 *          @Index (name="shipping_id", columns={"shipping_id"})
 *      }
 * )
 * @HasLifecycleCallbacks
 * @InheritanceType       ("SINGLE_TABLE")
 * @DiscriminatorColumn   (name="is_order", type="integer", length="1")
 * @DiscriminatorMap      ({"1" = "XLite\Model\Order", "0" = "XLite\Model\Cart"})
 */
class Order extends \XLite\Model\Base\SurchargeOwner
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
     * Order total that is financially declared as zero (null)
     */
    const ORDER_ZERO = 0.00001;

    /**
     * Add item error codes
     */
    const NOT_VALID_ERROR = 'notValid';


    /**
     * Order unique id
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $order_id;

    /**
     * Order profile
     *
     * @var   \XLite\Model\Profile
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Profile", cascade={"persist"})
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;

    /**
     * Original profile
     *
     * @var   \XLite\Model\Profile
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Profile")
     * @JoinColumn (name="orig_profile_id", referencedColumnName="profile_id")
     */
    protected $orig_profile;

    /**
     * Shipping method unique id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $shipping_id = 0;

    /**
     * Shipping method name
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", nullable=true)
     */
    protected $shipping_method_name = '';

    /**
     * Shipping tracking code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $tracking = '';

    /**
     * Order creation timestamp
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Last order renew date
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $lastRenewDate = 0;

    /**
     * Status code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="1")
     */
    protected $status = self::STATUS_INPROGRESS;

    /**
     * Customer notes
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $notes = '';

    /**
     * Order details
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderDetail", mappedBy="order", cascade={"all"})
     * @OrderBy   ({"name" = "ASC"})
     */
    protected $details;

    /**
     * Order items
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem", mappedBy="order", cascade={"all"})
     */
    protected $items;

    /**
     * Order surcharges
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Order\Surcharge", mappedBy="owner", cascade={"all"})
     * @OrderBy   ({"id" = "ASC"})
     */
    protected $surcharges;

    /**
     * Payment transactions
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\Transaction", mappedBy="order", cascade={"all"})
     */
    protected $payment_transactions;

    /**
     * Currency
     *
     * @var   \XLite\Model\Currency
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Currency", inversedBy="orders", cascade={"merge","detach"})
     * @JoinColumn (name="currency_id", referencedColumnName="currency_id")
     */
    protected $currency;

    /**
     * 'Add item' error code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $addItemError;

    /**
     * List of change status handlers;
     * top index - old status, second index - new one
     * (<old_status> ----> <new_status>: $statusHandlers[$old][$new])
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $statusHandlers = array(

        self::STATUS_TEMPORARY => array(
            self::STATUS_PROCESSED  => array('checkout', 'process'),
            self::STATUS_COMPLETED  => array('checkout', 'process'),
            self::STATUS_QUEUED     => array('checkout'),
            self::STATUS_DECLINED   => array('fail'),
            self::STATUS_FAILED     => array('fail'),
        ),

        self::STATUS_INPROGRESS => array(
            self::STATUS_PROCESSED  => array('checkout', 'process'),
            self::STATUS_COMPLETED  => array('checkout', 'process'),
            self::STATUS_QUEUED     => array('checkout', 'queue'),
            self::STATUS_DECLINED   => array('fail'),
            self::STATUS_FAILED     => array('fail'),
        ),

        self::STATUS_QUEUED => array(
            self::STATUS_TEMPORARY  => array('uncheckout'),
            self::STATUS_INPROGRESS => array('uncheckout'),
            self::STATUS_PROCESSED  => array('process'),
            self::STATUS_COMPLETED  => array('process'),
            self::STATUS_DECLINED   => array('uncheckout', 'fail'),
            self::STATUS_FAILED     => array('uncheckout', 'fail'),
        ),

        self::STATUS_PROCESSED => array(
            self::STATUS_TEMPORARY  => array('decline', 'uncheckout'),
            self::STATUS_INPROGRESS => array('decline', 'uncheckout'),
            self::STATUS_QUEUED     => array('decline'),
            self::STATUS_DECLINED   => array('decline', 'uncheckout', 'fail'),
            self::STATUS_FAILED     => array('decline', 'uncheckout', 'fail'),
        ),

        self::STATUS_COMPLETED => array(
            self::STATUS_TEMPORARY  => array('decline', 'uncheckout'),
            self::STATUS_INPROGRESS => array('decline', 'uncheckout'),
            self::STATUS_QUEUED     => array('decline'),
            self::STATUS_DECLINED   => array('decline', 'uncheckout', 'fail'),
            self::STATUS_FAILED     => array('decline', 'uncheckout', 'fail'),
        ),

        self::STATUS_DECLINED => array(
            self::STATUS_PROCESSED  => array('checkout', 'process'),
            self::STATUS_COMPLETED  => array('checkout', 'process'),
            self::STATUS_QUEUED     => array('checkout'),
        ),

        self::STATUS_FAILED => array(
            self::STATUS_PROCESSED  => array('checkout', 'process'),
            self::STATUS_COMPLETED  => array('checkout', 'process'),
            self::STATUS_QUEUED     => array('checkout'),
        ),
    );

    /**
     * Order previous status
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $oldStatus;

    /**
     * Modifiers (cache)
     *
     * @var   \XLite\DataSet\Collection\OrderModifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $modifiers;

    /**
     * Return list of all aloowed order statuses
     *
     * @param string $status Status to get OPTIONAL
     *
     * @return array | string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return \XLite\Model\OrderItem|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItemByItem(\XLite\Model\OrderItem $item)
    {
        $items = $this->getItems();

        return \Includes\Utils\ArrayManager::findValue(
            $items,
            array($this, 'checkItemKeyEqual'),
            $item->getKey()
        );
    }

    /**
     * Get item from order by item  id
     *
     * @param integer $itemId Item id
     *
     * @return \XLite\Model\OrderItem|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItemByItemId($itemId)
    {
        $items = $this->getItems();

        return \Includes\Utils\ArrayManager::findValue(
            $items,
            array($this, 'checkItemIdEqual'),
            $itemId
        );
    }

    /**
     * Find items by product ID
     *
     * @param integer $productId Product ID to use
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItemsByProductId($productId)
    {
        $items = $this->getItems();

        return \Includes\Utils\ArrayManager::filter(
            $items,
            array($this, 'isItemProductIdEqual'),
            $productId
        );
    }

    /**
     * Normalize items
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function countItems()
    {
        return count($this->getItems());
    }

    /**
     * Return order items total quantity
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isEmpty()
    {
        return 0 >= $this->countItems();
    }

    /**
     * Check order subtotal
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMinOrderAmountError()
    {
        return $this->getSubtotal() < \XLite\Core\Config::getInstance()->General->minimal_order_amount;
    }

    /**
     * Check order subtotal
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMaxOrderAmountError()
    {
        return $this->getSubtotal() > \XLite\Core\Config::getInstance()->General->maximal_order_amount;
    }

    /**
     * Check - is order processed or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isProcessed()
    {
        return in_array($this->getStatus(), array(self::STATUS_PROCESSED, self::STATUS_COMPLETED));
    }

    /**
     * Check - os order queued or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isQueued()
    {
        return self::STATUS_QUEUED == $this->getStatus();
    }

    /**
     * Check item amounts
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItemsWithWrongAmounts()
    {
        $items = array();

        foreach ($this->getItems() as $item) {

            if ($item->hasWrongAmount()) {

                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Get order currency
     *
     * @return \XLite\Model\Currency
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getCurrency()
    {
        return isset($this->currency) ? $this->currency : \XLite::getInstance()->getCurrency();
    }

    /**
     * Get original profile
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOrigProfile()
    {
        return $this->orig_profile ?: $this->getProfile();
    }

    /**
     * Set profile
     *
     * @param \XLite\Model\Profile $profile Profile OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setProfile(\XLite\Model\Profile $profile = null)
    {
        // FIXME: is it really needed?
        if (!isset($profile) && $this->getProfile()) {
            $this->getProfile()->setOrder(null);
        }

        $this->profile = $profile;
    }

    /**
     * Set original profile
     * FIXME: is it really needed?
     *
     * @param \XLite\Model\Profile $profile Profile OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setOrigProfile(\XLite\Model\Profile $profile = null)
    {
        $this->orig_profile = $profile;
    }

    /**
     * Set profile copy
     *
     * @param \XLite\Model\Profile $profile Profile
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setProfileCopy(\XLite\Model\Profile $profile)
    {
        // Set profile as original profile
        $this->setOrigProfile($profile);

        // Clone profile and set as order profile
        $clonedProfile = $profile->cloneEntity();
        $this->setProfile($clonedProfile);
        $clonedProfile->setOrder($this);
    }

    /**
     * Get items list fingerprint
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getEventFingerprint()
    {
        $hash = array(
            'items' => array(),
        );

        foreach ($this->getItems() as $item) {
            $event = $item->getEventCell();
            $event['quantity'] = $item->getAmount();

            // Inventory tracking
            $object = $item->getObject();

            if ($object) {
                $inventory = $object->getInventory();

                if ($inventory->getEnabled() && $inventory->getAmount() <= $item->getAmount()) {
                    $event['is_limit'] = 1;
                }
            }

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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDetail($name)
    {
        return \Includes\Utils\ArrayManager::findValue(
            $this->getDetails(),
            array($this, 'checkDetailName'),
            $name
        );
    }

    /**
     * Set detail cell
     *
     * @param string $name  Cell code
     * @param mixed  $value Cell value
     * @param string $label Cell label OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Called when an order successfully placed by a client
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processSucceed()
    {
        // Fix the currency in the order
        $this->setCurrency(\XLite::getInstance()->getCurrency());

        // send email notification about initially placed order
        $status = $this->getStatus();

        $list = array(self::STATUS_PROCESSED, self::STATUS_COMPLETED, self::STATUS_INPROGRESS);

        $send = \XLite\Core\Config::getInstance()->Email->enable_init_order_notif
            || \XLite\Core\Config::getInstance()->Email->enable_init_order_notif_customer;

        if ($send && !in_array($status, $list)) {

            \XLite\Core\Mailer::getInstance()->sendOrderCreated($this);
        }

        $this->markAsOrder();
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
    }

    /**
     * Refresh order items
     * TODO - rework after tax subsystem rework
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function refreshItems()
    {
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $data = array())
    {
        $this->details              = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items                = new \Doctrine\Common\Collections\ArrayCollection();
        $this->surcharges           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->payment_transactions = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Return list of available payment methods
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPaymentMethods()
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findAllActive();

        foreach ($list as $i => $method) {
            if (!$method->getProcessor()->isApplicable($this, $method)) {
                unset($list[$i]);
            }
        }

        return $list;
    }

    /**
     * Renew payment method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function renewPaymentMethod()
    {
        $method = $this->getPaymentMethod();
        if ($method) {
            $this->setPaymentMethod($method);
        }
    }

    /**
     * Get payment method
     *
     * @return \XLite\Model\Payment\Method|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPaymentMethod()
    {
        $transaction = $this->getFirstOpenPaymentTransaction();

        if (!$transaction) {
            $transaction = $this->isOpen()
                ? $this->assignLastPaymentMethod()
                : $this->getPaymentTransactions()->last();
        }

        return $transaction ? $transaction->getPaymentMethod() : null;
    }

    /**
     * Check item key equal
     *
     * @param \XLite\Model\OrderItem $item Item
     * @param string                 $key  Key
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkItemKeyEqual(\XLite\Model\OrderItem $item, $key)
    {
        return $item->getKey() == $key;
    }

    /**
     * Check item id equal
     *
     * @param \XLite\Model\OrderItem $item   Item
     * @param integer                $itemId Item id
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkItemIdEqual(\XLite\Model\OrderItem $item, $itemId)
    {
        return $item->getItemId() == $itemId;
    }

    /**
     * Check order detail name
     *
     * @param \XLite\Model\OrderDetail $detail Detail
     * @param string                   $name   Name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkDetailName(\XLite\Model\OrderDetail $detail, $name)
    {
        return $detail->getName() == $name;
    }

    /**
     * Check payment transaction status
     *
     * @param \XLite\Model\Payment\Transaction $transaction Transaction
     * @param string                           $status      Status
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkPaymentTransactionStatusEqual(\XLite\Model\Payment\Transaction $transaction, $status)
    {
        return $transaction->getStatus() == $status;
    }

    /**
     * Check - is item product id equal specified product id
     *
     * @param \XLite\Model\OrderItem $item      Item
     * @param integer                $productId Product id
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isItemProductIdEqual(\XLite\Model\OrderItem $item, $productId)
    {
        return $item->getProduct()->getProductId() == $productId;
    }

    /**
     * Check last payment method
     *
     * @param \XLite\Model\Payment\Method $pmethod       Payment method
     * @param integer                     $lastPaymentId Last selected payment method id
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkLastPaymentMethod(\XLite\Model\Payment\Method $pmethod, $lastPaymentId)
    {
        $result = $pmethod->getMethodId() == $lastPaymentId;
        if ($result) {
            $this->setPaymentMethod($pmethod);
        }

        return $result;
    }

    /**
     * Create new cart item
     *
     * @param \XLite\Model\OrderItem $item New item
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addNewItem(\XLite\Model\OrderItem $item)
    {
        $item->setOrder($this);
        $this->addItems($item);
    }

    // {{{ Payment method and transactions

    /**
     * Set payment method
     *
     * @param \XLite\Model\Payment\Method $paymentMethod Payment method
     * @param float                       $value         Payment transaction value OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setPaymentMethod($paymentMethod, $value = null)
    {
        if (isset($paymentMethod) && !($paymentMethod instanceof \XLite\Model\Payment\Method)) {
            $paymentMethod = null;
        }

        if (!isset($paymentMethod) || $this->getFirstOpenPaymentTransaction()) {
            $transaction = $this->getFirstOpenPaymentTransaction();
            if ($transaction) {
                $this->getPaymentTransactions()->removeElement($transaction);
                $transaction->getPaymentMethod()->getTransactions()->removeElement($transaction);
                \XLite\Core\Database::getEM()->remove($transaction);
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Get first open (not payed) payment transaction
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFirstOpenPaymentTransaction()
    {
        $transactions = $this->getPaymentTransactions();

        return \Includes\Utils\ArrayManager::findValue(
            $transactions,
            array($this, 'checkPaymentTransactionStatusEqual'),
            \XLite\Model\Payment\Transaction::STATUS_INITIALIZED
        );
    }

    /**
     * Get open (not-payed) total
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
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
     * Check - order is open (has initialized transactions and has open total) or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isOpen()
    {
        return $this->getFirstOpenPaymentTransaction()
            && ($this->getCurrency()->getMinimumValue() < $this->getCurrency()->roundValue(abs($this->getOpenTotal())));
    }

    /**
     * Get totally payed total
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isPayed()
    {
        return 0 >= $this->getPayedTotal();
    }

    /**
     * Assign last used payment method
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assignLastPaymentMethod()
    {
        $found = null;

        if ($this->getProfile() && $this->getProfile()->getLastPaymentId()) {
            $paymentMethods = $this->getPaymentMethods();
            $found = \Includes\Utils\ArrayManager::findValue(
                $paymentMethods,
                array($this, 'checkLastPaymentMethod'),
                $this->getProfile()->getLastPaymentId()
            );
        }

        return $found ? $this->getFirstOpenPaymentTransaction() : null;
    }

    /**
     * Add payment transaction
     * FIXME: move logic into \XLite\Model\Payment\Transaction
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     * @param float                       $value  Value OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addPaymentTransaction(\XLite\Model\Payment\Method $method, $value = null)
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

    // }}}

    // {{{ Mail notification

    /**
     * Called when an order becomes processed, before saving it to the database
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sendProcessMail()
    {
        $mail = new \XLite\View\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
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
            $mail->compose(
                \XLite\Core\Config::getInstance()->Company->site_administrator,
                $profile->getLogin(),
                'order_processed'
            );
            $mail->send();
        }
    }

    /**
     * Called when the order status changed to failed
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sendFailMail()
    {
        $mail = new \XLite\View\Mailer();
        $mail->order = $this;
        $mail->adminMail = true;
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
            $mail->compose(
                \XLite\Core\Config::getInstance()->Company->orders_department,
                $profile->getLogin(),
                'order_failed'
            );
            $mail->send();
        }
    }

    // }}}

    // {{{ Calculation

    /**
     * Get modifiers
     *
     * @return \XLite\DataSet\Collection\OrderModifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifiers()
    {
        if (!isset($this->modifiers)) {
            $this->modifiers = \XLite\Core\Database::getRepo('XLite\Model\Order\Modifier')->findActive();

            // Initialize
            foreach ($this->modifiers as $modifier) {
                $modifier->initialize($this, $this->modifiers);
            }

            // Preprocess modifiers
            foreach ($this->modifiers as $modifier) {
                $modifier->preprocess();
            }
        }

        return $this->modifiers;
    }

    /**
     * Get modifier
     *
     * @param string $type Modifier type
     * @param string $code Modifier code
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifier($type, $code)
    {
        $result = null;

        foreach ($this->getModifiers() as $modifier) {
            if ($modifier->getType() == $type && $modifier->getCode() == $code) {
                $result = $modifier;
                break;
            }
        }

        return $result;
    }

    /**
     * Check - modifier is exists or not (by type)
     *
     * @param string $type Type
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isModifierByType($type)
    {
        $result = false;

        foreach ($this->getModifiers() as $modifier) {
            if ($modifier->getType() == $type) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get modifiers by type
     *
     * @param string $type Modifier type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifiersByType($type)
    {
        $list = array();

        foreach ($this->getModifiers() as $modifier) {
            if ($modifier->getType() == $type) {
                $list[] = $modifier;
            }
        }

        return $list;
    }

    /**
     * Get items exclude surcharges info
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getItemsExcludeSurcharges()
    {
        $list = array();

        foreach ($this->getItems() as $item) {
            foreach ($item->getExcludeSurcharges() as $surcharge) {
                if (!isset($list[$surcharge->getKey()])) {
                    $list[$surcharge->getKey()] = $surcharge->getName();
                }
            }
        }

        return $list;
    }

    /**
     * Get items included surcharges totals
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getItemsIncludeSurchargesTotals()
    {
        $list = array();

        foreach ($this->getItems() as $item) {
            foreach ($item->getExcludeSurcharges() as $surcharge) {
                if (!isset($list[$surcharge->getKey()])) {
                    $list[$surcharge->getKey()] = array(
                        'surcharge' => $surcharge,
                        'cost'      => 0,
                    );
                }

                $list[$surcharge->getKey()]['cost'] += $surcharge->getValue();
            }
        }

        return $list;

    }

    /**
     * Common method to update cart/order
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function updateOrder()
    {
        $total = $this->getTotal();

        $this->normalizeItems();
        $this->calculate();

        if ($this->getTotal() !== $this->getCurrency()->roundValue($total)) {
            $this->renewPaymentMethod();
        }
    }

    /**
     * Calculate order
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculate()
    {
        $this->resetSurcharges();

        $this->calculateInitialValues();

        foreach ($this->getModifiers() as $modifier) {
            $apply = $modifier->canApply();
            if ($modifier->canApply()) {
                $modifier->calculate();
            }
        }

        $this->finalizeItemsCalculation();

        $this->setTotal($this->getSurchargesTotal());
    }

    /**
     * Renew order
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function renew()
    {
        foreach ($this->getItems() as $item) {
            if (!$item->renew()) {
                $this->getItems()->removeElement($item);
            }
        }

        $this->calculate();

        $this->setLastRenewDate(time());
    }

    /**
     * Reset surcharges list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function resetSurcharges()
    {
        foreach ($this->getItems() as $item) {
            $item->resetSurcharges();
        }

        foreach ($this->getSurcharges() as $surcharge) {
            \XLite\Core\Database::getEM()->remove($surcharge);
        }

        $this->getSurcharges()->clear();
    }

    /**
     * Calculate initial order values
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculateInitialValues()
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            $item->calculate();

            $subtotal += $item->getSubtotal();
        }

        $subtotal = $this->getCurrency()->roundValue($subtotal);

        $this->setSubtotal($subtotal);
        $this->setTotal($subtotal);
    }

    /**
     * Finalize items calculation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function finalizeItemsCalculation()
    {
        $subtotal = 0;
        foreach ($this->getItems() as $item) {
            $itemTotal = $item->calculateTotal();
            $subtotal += $itemTotal;
            $item->setTotal($itemTotal);
        }

        $this->setSubtotal($subtotal);
        $this->setTotal($subtotal);
    }

    // }}}

    // {{{ Surcharges

    /**
     * Get surcharges by type
     *
     * @param string $type Surcharge type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSurchargesByType($type)
    {
        $list = array();

        foreach ($this->getSurcharges() as $surcharge) {
            if ($surcharge->getType() == $type) {
                $list[] = $surcharge;
            }
        }

        return $list;
    }

    /**
     * Get surcharges subtotal with specified type
     *
     * @param string  $type    Surcharge type OPTIONAL
     * @param boolean $include Surcharge include flag OPTIONAL
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSurchargesSubtotal($type = null, $include = null)
    {
        $surcharges = $type
            ? $this->getSurchargesByType($type)
            : $this->getSurcharges();

        $subtotal = 0;

        foreach ($surcharges as $surcharge) {
            if ($surcharge->getAvailable() && (!isset($include) || $surcharge->getInclude() == $include)) {
                $subtotal += $this->getCurrency()->roundValue($surcharge->getValue());
            }
        }

        return $subtotal;
    }

    /**
     * Get surcharges total with specified type
     *
     * @param string $type Surcharge type OPTIONAL
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSurchargesTotal($type = null)
    {
        return $this->getSubtotal() + $this->getSurchargesSubtotal($type, false);
    }

    // }}}

    // {{{ Lifecycle callbacks

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
        if (!is_numeric($this->date)) {
            $this->setDate(time());
        }

        if (!is_numeric($this->lastRenewDate)) {
            $this->setLastRenewDate(time());
        }
    }

    /**
     * Prepare order before remove operation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
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
     * Since Doctrine lifecycle callbacks do not allow to modify associations, we've added this method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function prepareEntityBeforeCommit()
    {
        if ($this->isStatusChanged()) {
            $this->changeStatusPostprocess($this->oldStatus, $this->getStatus());
            $this->oldStatus = null;
        }
    }

    // }}}

    // {{{ Change status routine

    /**
     * Set status
     *
     * @param string $value Status code
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setStatus($value)
    {
        if ($this->getStatus() != $value && !$this->isStatusChanged()) {
            $this->oldStatus = $this->getStatus();
        }

        $this->status = $value;

        // TODO - rework
        //$this->refresh('shippingRates');
    }

    /**
     * Check if order status was changed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isStatusChanged()
    {
        return isset($this->oldStatus);
    }

    /**
     * Return base part of the certain "change status" handler name
     *
     * @param string $old Old order status
     * @param string $new New order status
     *
     * @return string|array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getStatusHandlers($old, $new)
    {
        return isset(static::$statusHandlers[$old][$new])
            ? static::$statusHandlers[$old][$new]
            : array();
    }

    /**
     * Postprocess status changes
     *
     * @param string $old Old status
     * @param string $new New status
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function changeStatusPostprocess($old, $new)
    {
        foreach ($this->getStatusHandlers($old, $new) as $handler) {
            $this->{'process' . ucfirst($handler)}();
        }
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processCheckout()
    {
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processUncheckout()
    {
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processQueue()
    {
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processProcess()
    {
        \XLite\Core\Mailer::getInstance()->sendProcessOrder($this);
        $this->decreaseInventory();
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processDecline()
    {
        $this->increaseInventory();
    }

    /**
     * A "change status" handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processFail()
    {
        \XLite\Core\Mailer::getInstance()->sendFailedOrder($this);
    }

    // }}}

    // {{{ Inventory tracking

    /**
     * Get item inventory delta
     *
     * @param \XLite\Model\OrderItem $item Current item
     * @param integer                $sign Flag; "1" or "-1"
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getItemInventoryAmount(\XLite\Model\OrderItem $item, $sign)
    {
        return $sign * $item->getAmount();
    }

    /**
     * Increase / decrease item products inventory
     *
     * @param integer $sign Flag; "1" or "-1"
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function changeItemsInventory($sign)
    {
        foreach ($this->getItems() as $item) {
            $item->getProduct()->getInventory()->changeAmount($this->getItemInventoryAmount($item, $sign));
        }
    }

    /**
     * Order processed: decrease products inventory
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function decreaseInventory()
    {
        $this->changeItemsInventory(-1);
    }

    /**
     * Order declined: increase products inventory
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function increaseInventory()
    {
        $this->changeItemsInventory(1);
    }

    // }}}
}

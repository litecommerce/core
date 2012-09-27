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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model;

/**
 * Something customer can put into his cart
 *
 *
 * @Entity (repositoryClass="XLite\Model\Repo\OrderItem")
 * @Table  (name="order_items",
 *          indexes={
 *               @Index (name="ooo", columns={"order_id","object_type","object_id"}),
 *               @Index (name="object_id", columns={"object_id"}),
 *               @Index (name="price", columns={"price"}),
 *               @Index (name="amount", columns={"amount"})
 *          }
 * )
 *
 * @InheritanceType       ("SINGLE_TABLE")
 * @DiscriminatorColumn   (name="object_type", type="string", length=16)
 * @DiscriminatorMap      ({"product" = "XLite\Model\OrderItem"})
 */
class OrderItem extends \XLite\Model\Base\SurchargeOwner
{
    const PRODUCT_TYPE = 'product';

    /**
     * Primary key
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $item_id;

    /**
     * Object (product)
     *
     * @var \XLite\Model\Product
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="order_items", cascade={"merge","detach"})
     * @JoinColumn (name="object_id", referencedColumnName="product_id")
     */
    protected $object;

    /**
     * Item name
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $name;

    /**
     * Item SKU
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $sku = '';

    /**
     * Item price
     *
     * @var float
     *
     * @Column (
     *      type="money",
     *      options={
     *          @\XLite\Core\Doctrine\Annotation\Behavior (list={"taxable"}),
     *          @\XLite\Core\Doctrine\Annotation\Purpose  (name="net", source="clear"),
     *          @\XLite\Core\Doctrine\Annotation\Purpose  (name="display", source="net")
     *      }
     * )
     */
    protected $price;

    /**
     * Item net price
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $itemNetPrice;

    /**
     * Item discounted subtotal
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $discountedSubtotal = 0;

    /**
     * Item quantity
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $amount = 1;

    /**
     * Item order
     *
     * @var \XLite\Model\Order
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="items")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Order item surcharges
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem\Surcharge", mappedBy="owner", cascade={"all"})
     * @OrderBy   ({"id" = "ASC"})
     */
    protected $surcharges;

    /**
     * Dump product (deleted)
     *
     * @var \XLite\Model\Product
     */
    protected $dumpProduct;


    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->surcharges = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Set order
     *
     * @param \XLite\Model\Order $order Order OPTIONAL
     *
     * @return void
     */
    public function setOrder(\XLite\Model\Order $order = null)
    {
        $this->order = $order;
    }

    /**
     * Clone order item object. The product only is set additionally
     * since the order could be different and should be set manually
     *
     * @return \XLite\Model\AEntity
     */
    public function cloneEntity()
    {
        $newItem = parent::cloneEntity();

        if ($this->getObject()) {
            $newItem->setObject($this->getObject());
        }

        return $newItem;
    }

    /**
     * Get item clear price. This value is used as a base item price for calculation of netPrice
     *
     * @return float
     */
    public function getClearPrice()
    {
        return $this->getProduct()->getClearPrice();
    }

    /**
     * Get item price
     *
     * @return float
     */
    public function getItemPrice()
    {
        return $this->isOrderOpen() ? $this->getClearPrice() : $this->getPrice();
    }

    /**
     * Get item net price
     *
     * @return float
     */
    public function getItemNetPrice()
    {
        return $this->isOrderOpen() ? $this->getNetPrice() : $this->itemNetPrice;
    }

    /**
     * Return false if order is fixed in the database (i.e. order is placed) and true if order is still used as "cart"
     *
     * @return boolean
     */
    public function isOrderOpen()
    {
        $order = $this->getOrder();

        return method_exists($order, 'hasCartStatus') && $order->hasCartStatus();
    }

    /**
     * Reset surcharges list
     *
     * @return void
     */
    public function resetSurcharges()
    {
        foreach ($this->getSurcharges() as $surcharge) {

            \XLite\Core\Database::getEM()->remove($surcharge);
        }

        $this->getSurcharges()->clear();
    }

    /**
     * Get through exclude surcharges
     *
     * @return array
     */
    public function getThroughExcludeSurcharges()
    {
        $list = $this->getOrder()->getItemsExcludeSurcharges();

        foreach ($list as $key => $value) {

            $list[$key] = null;

            foreach ($this->getExcludeSurcharges() as $surcharge) {

                if ($surcharge->getKey() == $key) {
                    $list[$key] = $surcharge;
                    break;
                }
            }
        }

        return $list;
    }

    /**
     * Wrapper. If the product was deleted,
     * item will use save product name and SKU
     * TODO - switch to getObject() and remove
     *
     * @return \XLite\Model\Product
     */
    public function getProduct()
    {
        return $this->isDeleted() ? $this->getDeletedProduct() : $this->getObject();
    }

    /**
     * Save some fields from product
     *
     * @param \XLite\Model\Product $product Product to set OPTIONAL
     *
     * @return void
     */
    public function setProduct(\XLite\Model\Product $product = null)
    {
        $this->setObject($product);
    }

    /**
     * Set object
     *
     * @param \XLite\Model\Base\IOrderItem $item Order item related object OPTIONAL
     *
     * @return void
     */
    public function setObject(\XLite\Model\Base\IOrderItem $item = null)
    {
        $this->object = $item;

        if ($item) {
            $this->saveItemState($item);

        } else {
            $this->resetItemState();
        }
    }

    /**
     * Modified setter
     *
     * @param integer $amount Value to set
     *
     * @return void
     */
    public function setAmount($amount)
    {
        if ($this->getObject()) {
            $amount = max($amount, $this->getObject()->getMinPurchaseLimit());
            $amount = min($amount, $this->getObject()->getMaxPurchaseLimit());
        }

        $this->amount = $amount;
    }

    /**
     * Get item weight
     *
     * @return float
     */
    public function getWeight()
    {
        $object = $this->getObject();

        return $object
            ? $object->getWeight() * $this->getAmount()
            : 0;
    }

    /**
     * Check if item has a image
     *
     * @return boolean
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && (bool)$this->getImage()->getId();
    }

    /**
     * Check if item has a wrong amount
     *
     * @return boolean
     */
    public function hasWrongAmount()
    {
        $inventory = $this->getProduct()->getInventory();

        return $inventory->getEnabled()
            && ($inventory->getAmount() < $this->getAmount());
    }

    /**
     * Get item image URL
     *
     * @return string
     */
    public function getImageURL()
    {
        return $this->getImage()->getURL();
    }

    /**
     * Get item image
     *
     * @return \XLite\Model\Base\Image
     */
    public function getImage()
    {
        return $this->getProduct()->getImage();
    }

    /**
     * Get item description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getProduct()->getName() . ' (' . $this->getAmount() . ')';
    }

    /**
     * Get item URL
     *
     * @return string
     */
    public function getURL()
    {
        return $this->getProduct()->getURL();
    }

    /**
     * Flag; is this item needs to be shipped
     *
     * @return boolean
     */
    public function isShippable()
    {
        return !$this->getProduct()->getFreeShipping();
    }

    /**
     * This key is used when checking if item is unique in the cart
     *
     * @return string
     */
    public function getKey()
    {
        return self::PRODUCT_TYPE . '.' . $this->getProduct()->getId();
    }

    /**
     * Check if item is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return 0 < $this->getAmount();
    }

    /**
     * Check if the item is valid to clone through the Re-order functionality
     *
     * @return boolean
     */
    public function isValidToClone()
    {
        return $this->isValid() && !$this->isDeleted() && $this->getProduct()->isAvailable();
    }

    /**
     * Set price
     *
     * @param float $price Price
     *
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;

        if (!isset($this->itemNetPrice)) {
            $this->setItemNetPrice($price);
        }
    }

    /**
     * Initial calculate order item
     *
     * @return void
     */
    public function calculate()
    {
        $subtotal = $this->calculateNetSubtotal();

        $this->setSubtotal($subtotal);
        $this->setDiscountedSubtotal($subtotal);
        $this->setTotal($subtotal);
    }

    /**
     * Renew order item
     *
     * @return boolean
     */
    public function renew()
    {
        $available = true;

        $product = $this->getProduct();
        if ($product) {
            if (!$product->getId() || !$this->checkAmount()) {
                $available = false;

            } else {
                $this->setPrice($product->getDisplayPrice());
                $this->setName($product->getName());
                $this->setSKU($product->getSKU());
            }
        }

        return $available;
    }

    /**
     * Get item taxable basis
     *
     * @return float
     */
    public function getTaxableBasis()
    {
        $product = $this->getProduct();

        return $product ? $product->getTaxableBasis() : null;
    }

    /**
     * Get product classes
     *
     * @return array
     */
    public function getProductClasses()
    {
        $product = $this->getProduct();

        return $product ? $product->getClasses() : null;
    }

    /**
     * Get event cell base information
     *
     * @return array
     */
    public function getEventCell()
    {
        return array(
            'item_id'     => $this->getItemId(),
            'key'         => $this->getKey(),
            'object_type' => self::PRODUCT_TYPE,
            'object_id'   => $this->getProduct()->getId(),
        );
    }

    /**
     * 'IsDeleted' flag
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return !(in_array(
            get_called_class(),
            array('XLite\Model\OrderItem', 'XLite\Model\Proxy\XLiteModelOrderItemProxy')
        ) && (bool)$this->getObject());
    }

    /**
     * Calculate item total
     *
     * @return float
     */
    public function calculateTotal()
    {
        $total = $this->getSubtotal();

        foreach ($this->getExcludeSurcharges() as $surcharge) {
            $total += $surcharge->getValue();
        }

        return $total;
    }

    /**
     * Calculate net subtotal
     *
     * @return float
     */
    public function calculateNetSubtotal()
    {
        $this->setItemNetPrice($this->defineNetPrice());

        return $this->getOrder()->getCurrency()->roundValue($this->getItemNetPrice()) * $this->getAmount();
    }

    /**
     * Get net subtotal without round net price
     *
     * @return float
     */
    public function getNetSubtotal()
    {
        return $this->calculateNetSubtotal();
    }

    /**
     * Define net price
     *
     * @return float
     */
    protected function defineNetPrice()
    {
        return $this->getProduct()->getNetPrice();
    }

    /**
     * Get deleted product
     *
     * @return \XLite\Model\Product|void
     */
    protected function getDeletedProduct()
    {
        if (!isset($this->dumpProduct) && $this->getName()) {

            $this->dumpProduct = new \XLite\Model\Product();

            $this->dumpProduct->setPrice($this->getItemPrice());
            $this->dumpProduct->setName($this->getName());
            $this->dumpProduct->setSku($this->getSku());
        }

        return $this->dumpProduct;
    }

    /**
     * Check item amount
     *
     * @return boolean
     */
    protected function checkAmount()
    {
        $result = true;

        $product = $this->getProduct();
        if ($product && $product->getId()) {

            $result = !$product->getInventory()->getEnabled()
                || $product->getInventory()->getAvailableAmount() >= 0;
        }

        return $result;
    }

    /**
     * Save item state
     *
     * @param \XLite\Model\Base\IOrderItem $item Item object
     *
     * @return void
     */
    protected function saveItemState(\XLite\Model\Base\IOrderItem $item)
    {
        $price = $item->getPrice();

        $this->setPrice(\Includes\Utils\Converter::formatPrice($price));
        $this->setName($item->getName());
        $this->setSku($item->getSku());
    }

    /**
     * Reset item state
     *
     * @return void
     */
    protected function resetItemState()
    {
        $this->price = 0;
        $this->itemNetPrice = 0;
        $this->name = '';
        $this->sku = '';
    }
}

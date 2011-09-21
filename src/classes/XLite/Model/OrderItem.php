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
 * Something customer can put into his cart
 *
 * @see   ____class_see____
 * @since 1.0.0
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
 * @InheritanceType       ("SINGLE_TABLE")
 * @DiscriminatorColumn   (name="object_type", type="string", length="16")
 * @DiscriminatorMap      ({"product" = "XLite\Model\OrderItem"})
 */
class OrderItem extends \XLite\Model\Base\SurchargeOwner
{
    const PRODUCT_TYPE = 'product';

    /**
     * Primary key
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $item_id;

    /**
     * Object (product)
     *
     * @var   \XLite\Model\Product
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="order_items", cascade={"merge","detach"})
     * @JoinColumn (name="object_id", referencedColumnName="product_id")
     */
    protected $object;

    /**
     * Item name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $name;

    /**
     * Item SKU
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $sku = '';

    /**
     * Item price
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision="14", scale="4")
     */
    protected $price;

    /**
     * Item quantity
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $amount = 1;

    /**
     * Item order
     *
     * @var   \XLite\Model\Order
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="items")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Order item surcharges
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem\Surcharge", mappedBy="owner", cascade={"all"})
     * @OrderBy   ({"id" = "ASC"})
     */
    protected $surcharges;

    /**
     * Dump product (deleted)
     *
     * @var   \XLite\Model\Product
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $dumpProduct;


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
        $this->surcharges = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Reset surcharges list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function resetSurcharges()
    {
        foreach ($this->getSurcharges() as $surcharge) {
            \XLite\Core\Database::getEM()->remove($surcharge);
        }

        $this->getSurcharges()->clear();
    }

    /**
     * Wrapper. If the product was deleted,
     * item will use save product name and SKU
     * TODO - switch to getObject() and remove
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProduct()
    {
        return ('XLite\Model\OrderItem' == get_called_class() && $this->getObject())
            ? $this->getObject()
            : $this->getDeletedProduct();
    }

    /**
     * Save some fields from product
     *
     * @param \XLite\Model\Product $product Product to set OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setObject(\XLite\Model\Base\IOrderItem $item = null)
    {
        $old = $this->object;

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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && (bool)$this->getImage()->getImageId();
    }

    /**
     * Get item image URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getImageURL()
    {
        return $this->getImage()->getURL();
    }

    /**
     * Get item image
     *
     * @return \XLite\Model\Base\Image
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getImage()
    {
        return $this->getProduct()->getImage();
    }

    /**
     * Get item description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDescription()
    {
        return $this->getProduct()->getName() . ' (' . $this->getAmount() . ')';
    }

    /**
     * Get item URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getURL()
    {
        return $this->getProduct()->getURL();
    }

    /**
     * Flag; is this item needs to be shipped
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isShippable()
    {
        return !$this->getProduct()->getFreeShipping();
    }

    /**
     * This key is used when checking if item is unique in the cart
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getKey()
    {
        return self::PRODUCT_TYPE . '.' . $this->getProduct()->getId();
    }

    /**
     * Check if item is valid
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isValid()
    {
        return 0 < $this->getAmount();
    }

    /**
     * Initial calculate order item
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculate()
    {
        $subtotal = $this->getOrder()->getCurrency()->roundValue($this->getPrice() * $this->getAmount());

        $this->setSubtotal($subtotal);
        $this->setTotal($subtotal);
    }

    /**
     * Get item taxable basis
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Get deleted product
     *
     * @return \XLite\Model\Product|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDeletedProduct()
    {
        if (!isset($this->dumpProduct) && $this->getName()) {
            $this->dumpProduct = new \XLite\Model\Product();
            $this->dumpProduct->setPrice($this->getPrice());
            $this->dumpProduct->setName($this->getName());
            $this->dumpProduct->setSku($this->getSku());
        }

        return $this->dumpProduct;
    }

    /**
     * Save item state
     *
     * @param \XLite\Model\Base\IOrderItem $item Item object
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function resetItemState()
    {
        $this->price = 0;
        $this->name = '';
        $this->sku = '';
    }
}

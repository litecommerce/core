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
 * Something customer can put into his cart
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity (repositoryClass="XLite\Model\Repo\OrderItem")
 * @Table  (name="order_items",
 *          indexes={
 *               @Index (name="orderby", columns={"orderby"}),
 *               @Index (name="product_id", columns={"product_id"}),
 *               @Index (name="price", columns={"price"}),
 *               @Index (name="amount", columns={"amount"})
 *          }
 * )
 */
class OrderItem extends \XLite\Model\Base\ModifierOwner
{
    const PRODUCT_TYPE = 'product';

    /**
     * Primary key 
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $item_id;

    /**
     * Object id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="integer")
     */
    protected $object_id;

    /**
     * Object type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="16")
     */
    protected $object_type;

    /**
     * Item name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $name;

    /**
     * Item SKU 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $sku = '';

    /**
     * Item price
     *
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $price;

    /**
     * Item quantity 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $amount = 1;

    /**
     * Item order 
     * 
     * @var    \XLite\Model\Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="items")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Item product 
     * 
     * @var    \XLite\Model\Product
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @OneToOne   (targetEntity="XLite\Model\Product")
     * @JoinColumn (name="object_id", referencedColumnName="product_id")
     */
    protected $product;

    /**
     * Order item saved modifiers
     *
     * @var    \XLite\Model\OrderItemModifier
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItemModifier", mappedBy="owner", cascade={"all"})
     */
    protected $saved_modifiers;

    /**
     * Object exist flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $objectExist = true;

    /**
     * Get object 
     * 
     * @return \XLite\Model\Base\IOrderItem or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getObject()
    {
        $method = $this->getObjectGetterName();

        // $method calculated in getObjectGetterName()
        return ($method && method_exists($this, $method)) ? $this->$method() : null;
    }

    /**
     * Get object method-getter name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getObjectGetterName()
    {
        return $this->getObjectType()
            ? 'get' . ucfirst($this->getObjectType()) . 'Object'
            : false;
    }

    /**
     * Get product object 
     * 
     * @return \XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductObject()
    {
        // Product loaded in LAZY mode - check proxy
        if (
            isset($this->product)
            && $this->product instanceof \Doctrine\ORM\Proxy\Proxy
            && !$this->product->getId()
        ) {
            $this->product = null;
            $this->objectExist = false;
        }

        // Product not loaded - load product model and check
        if (
            !isset($this->product)
            && $this->objectExist
            && $this->getObjectId()
        ) {
            $this->product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($this->getObjectId());
            if (!$this->product) {
                $this->objectExist = false;
            }
        }

        // Product is not exists (may be removed) - display dump product
        if (!isset($this->product)) {
            $this->product = new \XLite\Model\Product(
                array(
                    'name' => $this->getName(),
                    'sku'  => $this->getSku(),
                )
            );
        }

        return $this->product;
    }

    /**
     * Wrapper. If the product was deleted,
     * item will use save product name and SKU
     * TODO - switch to getObject() and remove 
     * 
     * @return \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProduct()
    {
        return self::PRODUCT_TYPE == $this->getObjectType()
            ? $this->getProductObject()
            : null;
    }

    /**
     * Save some fields from product
     * 
     * @param \XLite\Model\Product $product product to set
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setProduct(\XLite\Model\Product $product)
    {
        $this->setObject($product);
        $this->setObjectType(self::PRODUCT_TYPE);
        $this->product = $product;
    }

    /**
     * Set object 
     * 
     * @param \XLite\Model\Base\IOrderItem $item Order item related object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setObject(\XLite\Model\Base\IOrderItem $item)
    {
        $price = \XLite\Core\Config::getInstance()->Taxes->prices_include_tax
            ? $item->getTaxedPrice()
            : $item->getPrice();

        $this->setPrice(\Includes\Utils\Converter::formatPrice($price));
        $this->setObjectId($item->getId());
        $this->setName($item->getName());
        $this->setSku($item->getSku());

        $this->product = null;
    }

    /**
     * Modified setter
     * 
     * @param integer $amount Value to set
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setAmount($amount)
    {
        $amount = max($amount, $this->getObject()->getMinPurchaseLimit());
        $amount = min($amount, $this->getObject()->getMaxPurchaseLimit());

        $this->amount = $amount;
    }

    /**
     * Get item weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && (bool)$this->getImage()->getImageId();
    }

    /**
     * Get item image URL
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImageURL()
    {
        return $this->getImage()->getURL();
    }

    /**
     * Get item image
     *
     * @return \XLite\Model\Base\Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        return $this->getProduct()->getImage();
    }
 
    /**
     * Get item description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription()
    {
        return $this->getObject()->getName() . ' (' . $this->getAmount() . ')';
    }

    /**
     * Get item URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        return $this->getObject()->getURL();
    }

    /**
     * Flag; is this item needs to be shipped
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShipped()
    {
        return !((bool) $this->getObject()->getFreeShipping());
    }

    /**
     * This key is used when checking if item is unique in the cart
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        return $this->getObjectType() . '.' . $this->getObjectId();
    }

    /**
     * Check if item is valid
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        return 0 < $this->getAmount();
    }

    /**
     * Get discountable price 
     * TODO - rework - move to separate order item discount modifier
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDiscountablePrice()
    {
        return $this->getPrice();
    }   
        
    /**
     * Get taxable total 
     * TODO - rework - move to separate order item tax modifier
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxableTotal()
    {
        return $this->getTotal();
    }

    /**
     * Calculate and save order item subtotal 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateSubtotal()
    {
        $subtotal = $this->getPrice() * $this->getAmount();
        $this->setSubtotal($subtotal);
    }

    /**
     * Get event cell base information
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEventCell()
    {
        return array(
            'item_id'     => $this->getItemId(),
            'key'         => $this->getKey(),
            'object_type' => $this->getObjectType(),
            'object_id'   => $this->getObjectId(),
        );
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
        $this->subtotal = $this->getOrder()->getCurrency()->roundValue($value);
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
        $this->total = $this->getOrder()->getCurrency()->roundValue($value);
    }

    /**
     * Constructor
     *
     * @param array $data entity properties
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $data = array())
    {
        $this->saved_modifiers = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}

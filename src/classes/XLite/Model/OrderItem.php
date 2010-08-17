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
 * @subpackage ____sub_package____
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
 * @Table (name="order_items",
 *         indexes={
 *              @Index(name="orderby", columns={"orderby"}),
 *              @Index(name="product_id", columns={"product_id"}),
 *              @Index(name="price", columns={"price"}),
 *              @Index(name="amount", columns={"amount"})
 *         }
 * )
 */
class OrderItem extends \XLite\Model\AEntity
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
    protected $object_type = self::PRODUCT_TYPE;

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
     * OneToOne    (targetEntity="XLite\Model\Product")
     * @JoinColumn (name="object_id", referencedColumnName="product_id")
     */
    protected $product;

    public function getObject()
    {
        $method = $this->getObjectGetterName();

        return method_exists($this, $method)
            ? $this->$method()
            : null;
    }

    protected function getObjectGetterName()
    {
        return 'get' . ucfirst($this->getObjectType()) . 'Object';
    }

    protected function getProductObject()
    {
        if (!isset($this->product) || !$this->product->getProductId()) {
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
    }

    /**
     * Modified setter
     * 
     * @param int $amount Value to set
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
     * Get item cost
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTotal()
    {
        return \Includes\Utils\Converter::formatPrice($this->getPrice() * $this->getAmount());
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
        return $this->getObject()->getWeight() * $this->getAmount();
    }

    /**
     * Check if item has a thumbnail
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasThumbnail()
    {
        return !is_null($this->getThumbnail()) && (bool)$this->getThumbnail()->getImageId();
    }

    /**
     * Get item thumbnail URL
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnailURL()
    {
        return $this->getThumbnail()->getURL();
    }

    /**
     * Get item thumbnail
     *
     * @return \XLite\Model\Base\Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnail()
    {
        return $this->getObject()->getThumbnail();
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
     * @return bool
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
     * @return bool
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
}

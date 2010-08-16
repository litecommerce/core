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
     * @Column         (type="integer", length="11", nullable=false)
     */
    protected $item_id;

    /**
     * Id of order items is belong for 
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false) 
     */
    protected $order_id;

    /**
     * Position
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false) 
     */
    protected $orderby;

    /**
     * ID of the product
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $product_id;

    /**
     * Product name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $product_name;

    /**
     * Product SKU 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="32", nullable=false)
     */
    protected $product_sku;

    /**
     * Product price
     *
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $price;

    /**
     * Product amount 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
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
     * @ManyToOne  (targetEntity="XLite\Model\Category", inversedBy="items")
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
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */
    protected $product;


    /**
     * FIXME - must be removed after the association "order" will become working
     * 
     * @return \XLite\Model\Order
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrder()
    {
        return new \XLite\Model\Order($this->getOrderId());
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
        if (!isset($this->product)) {
            $this->product = new \XLite\Model\Product(
                array('name' => $this->getProductName(), 'sku' => $this->getProductSku())
            );
        }

        return $this->product;
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
        $price = \XLite\Core\Config::getInstance()->Taxes->prices_include_tax 
            ? $product->getTaxedPrice() 
            : $product->getPrice();

        $this->setPrice(\Includes\Utils\Converter::formatPrice($price));
        $this->setProductId($product->getProductId());
        $this->setProductName($product->getName());
        $this->setProductSku($product->getSku());

        $this->product = $product;
    }

    /**
     * Modified setter
     * 
     * @param int $amount value to set
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setAmount($amount)
    {
        $amount = max($amount, $this->getProduct()->getMinPurchaseLimit());
        $amount = min($amount, $this->getProduct()->getMaxPurchaseLimit());

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
        return Includes\Utils\Converter::formatPrice($this->getPrice() * $this->getAmount());
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
        return $this->getProduct()->getWeight() * $this->getAmount();
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
        return $this->getProduct()->hasThumbnail();
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
        return $this->getProduct()->getThumbnailURL();
    }

    /**
     * Get item thumbnail
     *
     * @return \XLite\Model\Image\Product\Thumbnail
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnail()
    {
        return $this->getProduct()->getThumbnail();
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
        return $this->getProduct()->getName() . ' (' . $this->getAmount() . ')';
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
        return !((bool) $this->getProduct()->getFreeShipping());
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
        return $this->getProductId();
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


    // NOTE: these methods are needed for the modules
    // TODO: check if there is a more convinient way to implement this

    /**
     * hasOptions 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasOptions()
    {
        return false;
    }

    /**
     * getDiscountablePrice 
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
     * getTaxableTotal 
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

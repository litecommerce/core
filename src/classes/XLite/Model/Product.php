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
 * The "product" model class
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Product")
 * @Table  (name="products",
 *      indexes={
 *          @Index (name="price", columns={"price"}),
 *          @Index (name="sku", columns={"sku"}),
 *          @Index (name="weight", columns={"weight"}),
 *          @Index (name="free_shipping", columns={"free_shipping"}),
 *          @Index (name="clean_url", columns={"clean_url"}),
 *          @Index (name="customerArea", columns={"enabled","arrivalDate"})
 *      }
 * )
 * @HasLifecycleCallbacks
 */
class Product extends \XLite\Model\Base\I18n implements \XLite\Model\Base\IOrderItem
{
    /**
     * Product unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $product_id;

    /**
     * Product price
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $price = 0.0000;

    /**
     * Product SKU
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32", nullable=false)
     */
    protected $sku;

    /**
     * Is product available or not
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Product weight
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $weight = 0.0000;

    /**
     * Is free shipping available for the product
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $free_shipping = false;

    /**
     * Clean URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $clean_url = '';

    /**
     * Custom javascript code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $javascript = '';

    /**
     * Arrival date (UNIX timestamp)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $arrivalDate = 0;

    /**
     * Creation date (UNIX timestamp)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Update date (UNIX timestamp)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="uinteger")
     */
    protected $updateDate = 0;


    /**
     * Relation to a CategoryProducts entities
     *
     * @var   \Doctrine\ORM\PersistentCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Product order items
     *
     * @var   \XLite\Model\OrderItem
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem", mappedBy="object")
     */
    protected $order_items;

    /**
     * Product images
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Image\Product\Image", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $images;

    /**
     * Qty in stock
     *
     * @var   \XLite\Model\Inventory
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne (targetEntity="XLite\Model\Inventory", mappedBy="product", fetch="LAZY", cascade={"all"})
     */
    protected $inventory;

    /**
     * Product classes
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToMany (targetEntity="XLite\Model\ProductClass", inversedBy="products")
     * @JoinTable (name="product_class_links",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="product_id")},
     *      inverseJoinColumns={@JoinColumn(name="class_id", referencedColumnName="id")}
     * )
     */
    protected $classes;


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
        $this->categoryProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->order_items      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classes          = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get object unique id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getId()
    {
        return $this->getProductId();
    }

    /**
     * Get weight
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get price
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return $this->getSoftTranslation()->getName();
    }

    /**
     * Get SKU
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Get image
     *
     * @return \XLite\Model\Image\Product\Image
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getImage()
    {
        return $this->getImages()->get(0);
    }

    /**
     * Get free shipping flag
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFreeShipping()
    {
        return $this->free_shipping;
    }

    /**
     * Check if product is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAvailable()
    {
        $result = true;

        if (!\XLite::isAdminZone()) {
            $result = $this->getEnabled()
                && (!$this->getArrivalDate() || time() > $this->getArrivalDate())
                && !$this->getInventory()->isOutOfStock();
        }

        return $result;
    }

    /**
     * Return product list price (price for customer interface)
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getListPrice()
    {
        return $this->getPrice();
    }

    /**
     * Check if product has image or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && $this->getImage()->isPersistent();
    }

    /**
     * Return image URL
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getImageURL()
    {
        return $this->getImage() ? $this->getImage()->getURL() : null;
    }

    /**
     * Return random product category
     *
     * @param integer|null $categoryId Category ID OPTIONAL
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategory($categoryId = null)
    {
        $result = $this->getLink($categoryId)->getCategory();

        if (empty($result)) {
            $result = new \XLite\Model\Category();
        }

        return $result;
    }

    /**
     * Return random product category ID
     *
     * @param integer|null $categoryId Category ID OPTIONAL
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategoryId($categoryId = null)
    {
        return $this->getCategory($categoryId)->getCategoryId();
    }

    /**
     * Return list of product categories
     *
     * @return \Doctrine\ORM\PersistentCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategories()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->findAllByProductId($this->getProductId());
    }

    /**
     * Setter
     *
     * @param double $value Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function setAmount($value)
    {
        $this->getInventory()->setAmount($value);
    }

    /**
     * Get product Url
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getURL()
    {
        return $this->getProductId()
            ? \XLite\Core\Converter::buildURL('product', '', array('product_id' => $this->getProductId()))
            : null;
    }

    /**
     * Minimal available amount
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinPurchaseLimit()
    {
        return 1;
    }

    /**
     * Maximal available amount
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMaxPurchaseLimit()
    {
        return intval(\XLite\Core\Config::getInstance()->General->default_purchase_limit);
    }

    /**
     * Get inventory
     *
     * @return \XLite\Model\Inventory
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInventory()
    {
        return !isset($this->inventory)
            ? new \XLite\Model\Inventory()
            : $this->inventory;
    }

    /**
     * Return product position in category
     *
     * @param integer|null $categoryId Category ID OPTIONAL
     *
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOrderBy($categoryId = null)
    {
        $link = $this->getLink($categoryId);

        return $link ? $link->getOrderBy() : null;
    }

    /**
     * Count product images
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function countImages()
    {
        return count($this->getImages());
    }

    /**
     * Try to fetch product description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonDescription()
    {
        return $this->getBriefDescription() ?: $this->getDescription();
    }

    /**
     * Get taxable basis
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTaxableBasis()
    {
        return $this->getPrice();
    }

    /**
     * Get arrival date 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getArrivalDate()
    {
        if ($this->getId()) {
            $date = $this->arrivalDate;

        } elseif (!$this->arrivalDate) {
            $date = time();

        } else {
            $date = $this->arrivalDate;
        }

        return \XLite\Core\Converter::convertTimeToUser($date);
    }

    /**
     * Set arrival date 
     * 
     * @param integer $date Arrival date
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setArrivalDate($date)
    {
        $this->arrivalDate = \XLite\Core\Converter::convertTimeToServer(
            mktime(0, 0, 0, date('m', $date), date('d', $date), date('Y', $date))
        );
    }

    /**
     * Prepare creation date 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
     * @PrePersist
     */
    public function prepareDate()
    {
        if (!$this->getDate()) {
            $this->setDate(time());
        }

        if (!$this->getArrivalDate()) {
            $this->setArrivalDate(time());
        }

        $this->prepareUpdateDate();
    }

    /**
     * Prepare update date
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
     * @PreUpdate
     */
    public function prepareUpdateDate()
    {
        $this->setUpdateDate(time());
    }

    /**
     * Return certain Product <--> Category association
     *
     * @param integer|null $categoryId Category ID
     *
     * @return \XLite\Model\CategoryProducts|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function findLinkByCategoryId($categoryId)
    {
        $result = null;

        foreach ($this->getCategoryProducts() as $cp) {
            if ($cp->getCategory() && $cp->getCategory()->getCategoryId() == $categoryId) {
                $result = $cp;
            }
        }

        return $result;
    }

    /**
     * Return certain Product <--> Category association
     *
     * @param integer|null $categoryId Category ID OPTIONAL
     *
     * @return \XLite\Model\CategoryProducts
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLink($categoryId = null)
    {
        $result = empty($categoryId)
            ? $this->getCategoryProducts()->first()
            : $this->findLinkByCategoryId($categoryId);

        if (empty($result)) {
            $result = new \XLite\Model\CategoryProducts();
        }

        return $result;
    }
}

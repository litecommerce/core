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
 * The "product" model class
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Product")
 * @Table  (name="products",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="sku", columns={"sku"})
 *      },
 *      indexes={
 *          @Index (name="price", columns={"price"}),
 *          @Index (name="weight", columns={"weight"}),
 *          @Index (name="free_shipping", columns={"free_shipping"}),
 *          @Index (name="customerArea", columns={"enabled","arrivalDate"})
 *      }
 * )
 * @HasLifecycleCallbacks
 */
class Product extends \XLite\Model\Base\Catalog implements \XLite\Model\Base\IOrderItem
{
    /**
     * Product unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $product_id;

    /**
     * Product price
     *
     * @var float
     *
     * @Column (
     *      type="money",
     *      options={
     *          @\XLite\Core\Doctrine\Annotation\Behavior (list={"taxable"}),
     *          @\XLite\Core\Doctrine\Annotation\Purpose (name="net", source="clear"),
     *          @\XLite\Core\Doctrine\Annotation\Purpose (name="display", source="net")
     *      }
     *  )
     */
    protected $price = 0.0000;

    /**
     * Product SKU
     *
     * @var string
     *
     * @Column (type="string", length=32, nullable=true)
     */
    protected $sku;

    /**
     * Is product available or not
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Product weight
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $weight = 0.0000;

    /**
     * Is free shipping available for the product
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $free_shipping = false;

    /**
     * Custom javascript code
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $javascript = '';

    /**
     * Arrival date (UNIX timestamp)
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $arrivalDate = 0;

    /**
     * Creation date (UNIX timestamp)
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Update date (UNIX timestamp)
     *
     * @var integer
     *
     * @Column (type="uinteger")
     */
    protected $updateDate = 0;


    /**
     * Relation to a CategoryProducts entities
     *
     * @var \Doctrine\ORM\PersistentCollection
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Product order items
     *
     * @var \XLite\Model\OrderItem
     *
     * @OneToMany (targetEntity="XLite\Model\OrderItem", mappedBy="object")
     */
    protected $order_items;

    /**
     * Product images
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @OneToMany (targetEntity="XLite\Model\Image\Product\Image", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $images;

    /**
     * Qty in stock
     *
     * @var \XLite\Model\Inventory
     *
     * @OneToOne (targetEntity="XLite\Model\Inventory", mappedBy="product", fetch="LAZY", cascade={"all"})
     */
    protected $inventory;

    /**
     * Product classes
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToMany (targetEntity="XLite\Model\ProductClass", inversedBy="products")
     * @JoinTable (name="product_class_links",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="product_id")},
     *      inverseJoinColumns={@JoinColumn(name="class_id", referencedColumnName="id")}
     * )
     * @OrderBy   ({"position" = "ASC"})
     */
    protected $classes;

    /**
     * Show product attributes in a separate tab
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $attrSepTab = true;

    /**
     * How much product is sold (used in Top selling products statistics)
     *
     * @var integer
     */
    protected $sold = 0;


    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
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
     */
    public function getId()
    {
        return $this->getProductId();
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get price: modules should never overwrite this method
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get clear price: this price can be overwritten by modules
     *
     * @return float
     */
    public function getClearPrice()
    {
        return $this->getPrice();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getSoftTranslation()->getName();
    }

    /**
     * Get SKU
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Get image
     *
     * @return \XLite\Model\Image\Product\Image
     */
    public function getImage()
    {
        return $this->getImages()->get(0);
    }

    /**
     * Get free shipping flag
     *
     * @return boolean
     */
    public function getFreeShipping()
    {
        return $this->free_shipping;
    }

    /**
     * Check if product is accessible
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return \XLite::isAdminZone() || $this->isPublicAvailable();
    }

    /**
     * Check prodyct availability for public usage (customer interface)
     * 
     * @return boolean
     */
    public function isPublicAvailable()
    {
        return $this->getEnabled()
            && (!$this->getArrivalDate() || time() > $this->getArrivalDate())
            && !$this->getInventory()->isOutOfStock();
    }

    /**
     * Check if product has image or not
     *
     * @return boolean
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && $this->getImage()->isPersistent();
    }

    /**
     * Return image URL
     *
     * @return string|void
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
     */
    public function getCategoryId($categoryId = null)
    {
        return $this->getCategory($categoryId)->getCategoryId();
    }

    /**
     * Return list of product categories
     *
     * @return \Doctrine\ORM\PersistentCollection
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
     */
    public function setAmount($value)
    {
        $this->getInventory()->setAmount($value);
    }

    /**
     * Get product Url
     *
     * @return string
     */
    public function getURL()
    {
        return $this->getProductId()
            ? \XLite\Core\Converter::buildURL('product', '', array('product_id' => $this->getProductId()))
            : null;
    }

    /**
     * Get front URL 
     * 
     * @return string
     */
    public function getFrontURL()
    {
        return $this->getProductId()
            ? \XLite::getInstance()->getShopURL(
                \XLite\Core\Converter::buildURL('product', '', array('product_id' => $this->getProductId()), \XLite::CART_SELF)
            )
            : null;
    }

    /**
     * Minimal available amount
     *
     * @return integer
     */
    public function getMinPurchaseLimit()
    {
        return 1;
    }

    /**
     * Maximal available amount
     *
     * @return integer
     */
    public function getMaxPurchaseLimit()
    {
        return intval(\XLite\Core\Config::getInstance()->General->default_purchase_limit);
    }

    /**
     * Get inventory
     *
     * @return \XLite\Model\Inventory
     */
    public function getInventory()
    {
        if (isset($this->inventory)) {
            $inventory = $this->inventory;

        } else {
            $inventory = new \XLite\Model\Inventory();
            $inventory->setProduct($this);

        }

        return $inventory;
    }

    /**
     * Return product position in category
     *
     * @param integer|null $categoryId Category ID OPTIONAL
     *
     * @return integer|void
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
     */
    public function countImages()
    {
        return count($this->getImages());
    }

    /**
     * Try to fetch product description
     *
     * @return string
     */
    public function getCommonDescription()
    {
        return $this->getBriefDescription() ?: $this->getDescription();
    }

    /**
     * Get taxable basis
     *
     * @return float
     */
    public function getTaxableBasis()
    {
        return $this->getNetPrice();
    }

    /**
     * Get arrival date 
     * 
     * @return integer
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
     *
     * @PrePersist
     */
    public function prepareBeforeCreate()
    {
        if (!$this->getDate()) {
            $this->setDate(time());
        }

        if (!$this->getArrivalDate()) {
            $this->setArrivalDate(time());
        }

        $this->prepareBeforeUpdate();
    }

    /**
     * Prepare update date
     *
     * @return void
     *
     * @PreUpdate
     */
    public function prepareBeforeUpdate()
    {
        $this->setUpdateDate(time());

        if (\XLite\Core\Converter::isEmptyString($this->getSKU())) {
            $this->setSKU(null);
        }
    }

    /**
     * Return certain Product <--> Category association
     *
     * @param integer|null $categoryId Category ID
     *
     * @return \XLite\Model\CategoryProducts|void
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

    /**
     * Return number of attributes associated with this product 
     *
     * @return integer
     */
    public function getAttributesCount()
    {
        $count = 0;
        if ($this->getClasses()) {
            foreach ($this->getClasses() as $class) {
                $count += $class->getAttributesCount();
            }
        }

        return $count;
    }

    /**
     * Setter for $sold property
     *
     * @param integer $value Value to set
     *
     * @return void
     */
    public function setSold($value)
    {
        $this->sold = $value;
    }

    /**
     * Getter for $sold property
     *
     * @return integer
     */

    public function getSold()
    {
        return $this->sold;
    }
}

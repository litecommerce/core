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
 * The "product" model class
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 * 
 * @Entity (repositoryClass="\XLite\Model\Repo\Product")
 * @Table  (name="products",
 *          indexes={
 *              @Index(name="price", columns={"price"}),
 *              @Index(name="sku", columns={"sku"}),
 *              @Index(name="enabled", columns={"enabled"}),
*               @Index(name="weight", columns={"weight"}),
 *              @Index(name="tax_class", columns={"tax_class"}),
 *              @Index(name="free_shipping", columns={"free_shipping"}),
 *              @Index(name="clean_url", columns={"clean_url"})
 *          }
 * )
 */
class Product extends \XLite\Model\Base\I18n implements \XLite\Model\Base\IOrderItem
{
    /**
     * Product unique ID 
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
    protected $product_id;

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
     * Product sale price
     *
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $sale_price;

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
    protected $sku;

    /**
     * Is product available or not
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $enabled = true;

    /**
     * Product weight
     *
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $weight;

    /**
     * Product tax class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="32", nullable=false)
     */
    protected $tax_class;

    /**
     * Is free shipping available for the product
     *
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $free_shipping = false;

    /**
     * Clean URL
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $clean_url;

    /**
     * Custom javascript code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="65535")
     */
    protected $javascript = '';


    /**
     * Relation to a CategoryProducts entities
     *
     * @var    \Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Product image
     *
     * @var    \XLite\Model\Image\Product\Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $image;

    /**
     * Product images
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Image\Product\Image", mappedBy="product", cascade={"all"})
     * @OrderBy ({"orderby" = "ASC"})
     */
    protected $images;

    /**
     * Product extra fields 
     * 
     * @var    \Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * TODO[DOCTRINE] - add tags
     */
    protected $extra_fields = array();

    /**
     * Get object unique id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getId()
    {
        return $this->getProductId();
    }

    /**
     * Get weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get price
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getName()
    {
        return $this->getSoftTranslation()->getName();
    }

    /**
     * Get SKU 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Get image 
     * 
     * @return \XLite\Model\Image\Product\Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        if (!isset($this->image) && $this->getImages()) {
            $this->image = $this->getImages()->get(0);
        }

        return $this->image;
    }

    /**
     * Get free shipping flag
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFreeShipping()
    {
        return $this->free_shipping;
    }

    /**
     * Return certain Product <--> Category association
     * 
     * @param int|null $categoryId category ID
     *  
     * @return \XLite\Model\CategoryProducts
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function findLinkByCategoryId($categoryId)
    {
        return \Includes\Utils\Doctrine\Entity::searchInArray($this->getCategoryProducts()->toArray(), 'category_id', $categoryId);
    }

    /**
     * Return certain Product <--> Category association
     * 
     * @param int|null $categoryId category ID
     *  
     * @return \XLite\Model\CategoryProducts
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * Check if product is accessible 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAvailable()
    {
        return \XLite::isAdminZone() ?: (bool) $this->getEnabled();
    }

    /**
     * Return product taxed price
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxedPrice()
    {
        return $this->getPrice();
    }

    /**
     * Return product list price
     *
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getListPrice()
    {
        return $this->getTaxedPrice();
    }

    /**
     * Check if product has image or not
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage()) && $this->getImage()->isPersistent();
    }

    /**
     * Return image URL 
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
     * Return random product category 
     *
     * @param int|null $categoryId category ID
     * 
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param int|null $categoryId category ID
     *
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryId($categoryId = null)
    {
        return $this->getCategory($categoryId)->getCategoryId();
    }

    /**
     * Return list of product categories
     * 
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->findAllByProductId($this->getProductId());
    }

    /**
     * Get product Url 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMinPurchaseLimit()
    {
        return 1;
    }

    /**
     * Maximal available amount
     *
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMaxPurchaseLimit()
    {
        return intval(\XLite\Core\Config::getInstance()->General->default_purchase_limit);
    }

    /**
     * Return product position in category
     *
     * @param int|null $categoryId category ID
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderBy($categoryId = null)
    {
        return $this->getLink($categoryId)->getOrderBy();
    }

    /**
     * Count product images 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countImages()
    {
        return count($this->getImages());
    }

    /**
     * Try to fetch product description
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCommonDescription()
    {
        return $this->getBriefDescription() ?: $this->getDescription();
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
        $this->categoryProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images           = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}

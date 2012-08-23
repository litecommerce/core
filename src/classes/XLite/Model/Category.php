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
 * Category
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Category")
 * @Table  (name="categories",
 *      indexes={
 *          @Index (name="lpos", columns={"lpos"}),
 *          @Index (name="rpos", columns={"rpos"}),
 *          @Index (name="enabled", columns={"enabled"})
 *      }
 * )
 */
class Category extends \XLite\Model\Base\Catalog
{
    /**
     * WEB LC root postprocessing constant
     */
    const WEB_LC_ROOT = '{{WEB_LC_ROOT}}';


    /**
     * Node unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $category_id;

    /**
     * Node left value
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $lpos;

    /**
     * Node right value
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $rpos;

    /**
     * Node status
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Whether to display the category title, or not
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $show_title = true;

    /**
     * Category "depth" in the tree
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $depth = -1;

    /**
     * Category position parameter. Sort inside the parent category
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $pos = 0;

    /**
     * Some cached flags
     *
     * @var \XLite\Model\Category\QuickFlags
     *
     * @OneToOne (targetEntity="XLite\Model\Category\QuickFlags", mappedBy="category", cascade={"all"})
     */
    protected $quickFlags;

    /**
     * Many-to-one relation with memberships table
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id", onDelete="SET NULL")
     */
    protected $membership;

    /**
     * One-to-one relation with category_images table
     *
     * @var \XLite\Model\Image\Category\Image
     *
     * @OneToOne  (targetEntity="XLite\Model\Image\Category\Image", mappedBy="category", cascade={"all"})
     */
    protected $image;

    /**
     * Relation to a CategoryProducts entities
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="category", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Child categories
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Model\Category", mappedBy="parent", cascade={"all"})
     * @OrderBy({"pos" = "ASC","lpos" = "ASC"})
     */
    protected $children;

    /**
     * Parent category
     *
     * @var \XLite\Model\Category
     *
     * @ManyToOne  (targetEntity="XLite\Model\Category", inversedBy="children")
     * @JoinColumn (name="parent_id", referencedColumnName="category_id")
     */
    protected $parent;

    /**
     * Caching flag to check if the category is visible in the parents branch.
     *
     * @var boolean
     */
    protected $flagVisible = null;

    /**
     * Set parent
     *
     * @param \XLite\Model\Category $parent Parent category OPTIONAL
     *
     * @return void
     */
    public function setParent(\XLite\Model\Category $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Set image
     *
     * @param \XLite\Model\Image\Category\Image $image Image OPTIONAL
     *
     * @return void
     */
    public function setImage(\XLite\Model\Image\Category\Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * Check if category has image
     *
     * @return boolean
     */
    public function hasImage()
    {
        return !is_null($this->getImage());
    }

    /**
     * Check every parent of category to be enabled.
     *
     * @return boolean
     */
    public function isVisible()
    {
        if (!isset($this->flagVisible)) {
            $current = $this;
            $hidden = false;

            while (\XLite\Model\Repo\Category::CATEGORY_ID_ROOT != $current->getCategoryId()) {
                if (!$current->getEnabled()) {
                    $hidden = true;
                    break;
                }

                $current = $current->getParent();
            }

            $this->flagVisible = !$hidden;
        }

        return $this->flagVisible;
    }

    /**
     * Get the number of subcategories
     *
     * @return integer|void
     */
    public function getSubcategoriesCount()
    {
        $result = null;

        $enabledCondition = $this->getRepository()->getEnabledCondition();
        $quickFlags = $this->getQuickFlags();

        if ($quickFlags) {
            $result = $enabledCondition
                ? $quickFlags->getSubcategoriesCountEnabled()
                : $quickFlags->getSubcategoriesCountAll();
        }

        return $result;
    }

    /**
     * Check if category has subcategories
     *
     * @return boolean
     */
    public function hasSubcategories()
    {
        return 0 < $this->getSubcategoriesCount();
    }

    /**
     * Return subcategories list
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSubcategories()
    {
        return $this->getChildren()->filter(
            function (\XLite\Model\Category $category) {
                return $category->getEnabled();
            }
        );
    }

    /**
     * Return siblings list.
     * You are able to include itself into this list. (Customer area)
     *
     * @param boolean $hasSelf Flag to include itself
     *
     * @return array
     */
    public function getSiblings($hasSelf = false)
    {
        return $this->getRepository()->getSiblings($this, $hasSelf);
    }

    /**
     * Get category path
     *
     * @return array
     */
    public function getPath()
    {
        return $this->getRepository()->getCategoryPath($this->getCategoryId());
    }

    /**
     * Gets full path to the category as a string: <parent category>/.../<category name>
     *
     * @return string
     */
    public function getStringPath()
    {
        $path = array();

        foreach ($this->getPath() as $category) {
            $path[] = $category->getName();
        }

        return implode('/', $path);
    }

    /**
     * Return parent category ID
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->getParent() ? $this->getParent()->getCategoryId() : 0;
    }

    /**
     * Set parent category ID
     *
     * @param integer $parentID Value to set
     *
     * @return void
     */
    public function setParentId($parentID)
    {
        $this->parent = $this->getRepository()->find($parentID);
    }

    /**
     * Get membership Id
     *
     * @return integer
     */
    public function getMembershipId()
    {
        return $this->getMembership() ? $this->getMembership()->getMembershipId() : null;
    }

    /**
     * Flag if the category and active profile have the same memberships. (when category is displayed or hidden)
     *
     * @return boolean
     */
    public function hasAvailableMembership()
    {
        return is_null($this->getMembershipId()) || $this->getMembershipId() == \XLite\Core\Auth::getInstance()->getMembershipId();
    }

    /**
     * Return number of products associated with the category
     *
     * TODO: check if result of "getProducts()" is cached by Doctrine
     *
     * @return integer
     */
    public function getProductsCount()
    {
        return $this->getProducts(null, true);
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition OPTIONAL
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    public function getProducts(\XLite\Core\CommonCell $cnd = null, $countOnly = false)
    {
        if (!isset($cnd)) {
            $cnd = new \XLite\Core\CommonCell();
        }

        // Main condition for this search
        $cnd->{\XLite\Model\Repo\Product::P_CATEGORY_ID} = $this->getCategoryId();

        return \XLite\Core\Database::getRepo('XLite\Model\Product')->search($cnd, $countOnly);
    }

    /**
     * Return category description
     *
     * @return string
     */
    public function getViewDescription()
    {
        return str_replace(
            $this->getWebPreprocessingTags(),
            $this->getWebPreprocessingURL(),
            $this->getDescription()
        );
    }

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
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Register tags to be replaced with some URLs
     *
     * @return array
     */
    protected function getWebPreprocessingTags()
    {
        return array(
            static::WEB_LC_ROOT,
        );
    }

    /**
     * Register URLs that should be given instead of tags
     *
     * @return array
     */
    protected function getWebPreprocessingURL()
    {
        // Get URL of shop. If the HTTPS is used then it should be cleaned from ?xid=<xid> construction
        $url = \XLite::getInstance()->getShopURL(null, \XLite\Core\Request::getInstance()->isHTTPS());

        // We are cleaning URL from unnecessary here <xid> construction
        $url = preg_replace('/(\?.*)/', '', $url);

        return array(
            $url,
        );
    }
}

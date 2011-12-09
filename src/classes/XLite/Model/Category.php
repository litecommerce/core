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
 * Category
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Category")
 * @Table  (name="categories",
 *      indexes={
 *          @Index (name="lpos", columns={"lpos"}),
 *          @Index (name="rpos", columns={"rpos"}),
 *          @Index (name="enabled", columns={"enabled"}),
 *          @Index (name="cleanURL", columns={"cleanURL"})
 *      }
 * )
 */
class Category extends \XLite\Model\Base\I18n
{
    /**
     * Node unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $category_id;

    /**
     * Node left value
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $lpos;

    /**
     * Node right value
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $rpos;

    /**
     * Node status
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Node clean (SEO-friendly) URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $cleanURL = '';

    /**
     * Whether to display the category title, or not
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $show_title = true;

    /**
     * Category "depth" in the tree
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.6
     *
     * @Column (type="integer")
     */
    protected $depth = -1;

    /**
     * Category position parameter. Sort inside the parent category
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.13
     *
     * @Column (type="integer")
     */
    protected $pos = 0;

    /**
     * Some cached flags
     *
     * @var   \XLite\Model\Category\QuickFlags
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne (targetEntity="XLite\Model\Category\QuickFlags", mappedBy="category", cascade={"all"})
     */
    protected $quickFlags;

    /**
     * Many-to-one relation with memberships table
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id")
     */
    protected $membership;

    /**
     * One-to-one relation with category_images table
     *
     * @var   \XLite\Model\Image\Category\Image
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne  (targetEntity="XLite\Model\Image\Category\Image", mappedBy="category", cascade={"all"})
     */
    protected $image;

    /**
     * Relation to a CategoryProducts entities
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="category", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Child categories
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Category", mappedBy="parent", cascade={"all"})
     * @OrderBy({"pos" = "ASC"})
     */
    protected $children;
    
    /**
     * Parent category
     *
     * @var   \XLite\Model\Category
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Category", inversedBy="children")
     * @JoinColumn (name="parent_id", referencedColumnName="category_id")
     */
    protected $parent;

    /**
     * Caching flag to check if the category is visible in the parents branch.
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.7
     */
    protected $flagVisible = null;

    /**
     * "Enabled category" filter closure
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.7
     */
    public static function isEnabledFilter(Category $category)
    {
        return $category->getEnabled();
    }

    /**
     * Set parent
     *
     * @param \XLite\Model\Category $parent Parent category OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setImage(\XLite\Model\Image\Category\Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * Check if category has image
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage());
    }

    /**
     * Check every parent of category to be enabled.
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.7
     */
    public function isVisible()
    {
        if (is_null($this->flagVisible)) {

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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSubCategoriesCount()
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasSubcategories()
    {
        return 0 < $this->getSubCategoriesCount();
    }

    /**
     * Return subcategories list
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSubcategories()
    {
        $object = $this;

        return $this->getChildren()->filter(
            function ($category) {return \XLite\Model\Category::isEnabledFilter($category);}
        );
    }

    /**
     * Return siblings list.
     * You are able to include itself into this list. (Customer area)
     *
     * @param boolean $hasSelf Flag to include itself
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSiblings($hasSelf = false)
    {
        return $this->getRepository()->getSiblings($this, $hasSelf);
    }

    /**
     * Gets full path to the category as a string: <parent category>/.../<category name>
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getStringPath()
    {
        $path = array();

        foreach ($this->getRepository()->getCategoryPath($this->getCategoryId()) as $category) {
            $path[] = $category->getName();
        }

        return implode('/', $path);
    }

    /**
     * Return parent category ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.5
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
     * @see    ____func_see____
     * @since  1.0.5
     */
    public function setParentId($parentID)
    {
        $this->parent = $this->getRepository()->find($parentID);
    }

    /**
     * Return number of products associated with the category
     *
     * TODO: check if result of "getProducts()" is cached by Doctrine
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductsCount()
    {
        return count($this->getProducts());
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition OPTIONAL
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
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
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}

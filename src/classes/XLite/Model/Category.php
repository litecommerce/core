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
 * Category
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Category")
 * @Table  (name="categories")
 */
class Category extends \XLite\Model\Base\I18n
{
    /**
     * Node unique ID 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $category_id;

    /**
     * Node parent ID
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $parent_id;

    /**
     * Node left value 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $lpos;

    /**
     * Node right value 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $rpos;

    /**
     * Node status
     * 1 - enabled, 0 - disabled
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $enabled = true;

    /**
     * Node membership level
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", nullable=false)
     */
    protected $membership_id = 0;

    /**
     * Node clean (SEO-friendly) URL
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $cleanUrl = '';

    /**
     * Whether to display the category title, or not
     * 1 - display, 0 - no
     * 
     * @var    integer
     * @access protected
     * @since  3.0.0
     *
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $show_title = true;


    /**
     * Some cached flags
     * 
     * @var    \XLite\Model\Category\QuickFlags
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @OneToOne   (targetEntity="XLite\Model\Category\QuickFlags", cascade={"all"})
     * @JoinColumn (name="category_id", referencedColumnName="category_id")
     */
    protected $quickFlags;

    /**
     * Many-to-one relation with memberships table
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id")
     */
    protected $membership;

    /**
     * One-to-one relation with category_images table
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Image\Category\Image")
     * @JoinColumn (name="category_id", referencedColumnName="id")
     */
    protected $image;

    /**
     * Relation to a CategoryProducts entities
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="category", cascade={"all"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $categoryProducts;

    /**
     * Parent category
     * 
     * @var    self
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Category")
     * @JoinColumn (name="parent_id", referencedColumnName="category_id")
     */
    protected $parent;


    /**
     * Check if category has image 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasImage()
    {
        return !is_null($this->getImage());
    }

    /**
     * Get the number of subcategories 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubCategoriesCount()
    {
        return $this->getQuickFlags()
            ->{'getSubcategoriesCount' . ($this->getRepository()->getEnabledCondition() ? 'Enabled' : 'All')}();
    }

    /**
     * Check if category has subcategories
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasSubcategories()
    {
        return 0 < $this->getSubCategoriesCount();
    }

    /**
     * Return subcategories list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubcategories()
    {
        return $this->getRepository()->getSubcategories($this->getCategoryId());
    }

    /**
     * Return siblings list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSiblings()
    {
        return $this->getRepository()->getSiblings($this->getCategoryId());
    }

    /**
     * Gets full path to the category as a string: <parent category>/.../<category name>
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStringPath()
    {
        return implode('/', array_map(
            function (\XLite\Model\Category $category) { return $category->getName(); },
            $this->getRepository()->getCategoryPath($this->getCategoryId())
        ));
    }

    /**
     * Return number of products associated with the category
     *
     * TODO: check if result of "getProducts()" is cached by Doctrine
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductsCount()
    {
        return count($this->getProducts());
    }

    /**
     * Return products list
     * 
     * @param \XLite\Core\CommonCell $cnd       search condition
     * @param bool                   $countOnly return items list or only its size
     *  
     * @return array|int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
}

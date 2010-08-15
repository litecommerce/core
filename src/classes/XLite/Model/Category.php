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
 * @Entity
 * @Table (name="categories")
 */
class Category extends \XLite\Model\Base\I18n
{
    /**
     * Node unique id 
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
     * Node depth within categories tree
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $depth = 0;

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
    protected $enabled = 0;

    /**
     * Node views counter
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $views_stats = 0;

    /**
     * Node lock status:
     * if set up to 1 then node is marked for moving within tree
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $locked = 0;

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
     * Threshold bestsellers
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $threshold_bestsellers = 1;

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
    protected $clean_url = '';

    /**
     * Many-to-one relation with memberships table
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne (targetEntity="XLite\Model\Membership")
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
     * @ManyToOne (targetEntity="XLite\Model\Image\Category\Image")
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
     * @OneToMany (targetEntity="XLite\Model\CategoryProducts", mappedBy="category", cascade={"persist","remove"})
     * @OrderBy   ({"orderby" = "ASC"})
     */
    protected $category_products;


    /**
     * The number of products assigned to the category
     * (Real-time calculated)
     *
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $products_count = 0;


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
     * Get subcategories plain list of the category
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubcategories()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategoriesPlainList($this->getCategoryId());
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
        return ($this->getRpos() - $this->getLpos() - 1) / 2;
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
        $data = \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategoryFromHash($this->getCategoryId());

        return (isset($data) ? $data->getSubCategoriesCount() > 0 : false);
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
        $path = \XLite\Core\Database::getRepo('\XLite\Model\Category')
            ->getCategoryPath($this->getCategoryId());

        $location = array();

        foreach ($path as $p) {
            $location[] = $p->name;
        }

        return implode('/', $location);
    }

    /**
     * Check if category has neither products nor subcategories
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isEmpty()
    {
        $data = \XLite\Core\Database::getRepo('\XLite\Model\Category')
            ->getCategoryFromHash($this->getCategoryId());

        return !isset($data) || (0 == $data->getProductsCount() && 0 == $data->getSubCategoriesCount());
    }

    /**
     * Check if category exists
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExists()
    {
        return 0 < $this->getCategoryId();
    }

    /**
     * Get the number of products assigned to the category
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductsNumber()
    {
        $data = \XLite\Core\Database::getRepo('\XLite\Model\Category')
            ->getCategoryFromHash($this->getCategoryId());

        return isset($data) ? $data->getProductsCount() : 0;
    }

    /**
     * Calculate indentation for displaying category in the tree 
     * 
     * @param int    $multiplier Custom multiplier
     * @param string $str        String that must be repeated by $multiplier
     *  
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIndentation($multiplier = 0, $str = null)
    {
        if (0 < $this->getDepth()) {
            $depth = $this->getDepth();

        } else {
            $data = \XLite\Core\Database::getRepo('\XLite\Model\Category')
                ->getCategoryFromHash($this->getCategoryId());
            $depth = isset($data) ? $data->getDepth() : 1;
        }

        $indentation = ($depth - 1) * $multiplier;

        return isset($str) ? str_repeat($str, $indentation) : $indentation;
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
        $cnd->{\XLite\Model\Repo\Product::P_CATEGORY_ID} = $this->getCategoryId();

        return \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd, $countOnly);
    }
}

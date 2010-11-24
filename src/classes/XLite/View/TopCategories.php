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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Sidebar categories list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TopCategories extends \XLite\View\SideBarBox
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_ROOT_ID      = 'rootId';
    const PARAM_IS_SUBTREE   = 'is_subtree';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_LIST = 'list';
    const DISPLAY_MODE_TREE = 'tree';
    const DISPLAY_MODE_PATH = 'path';


    /**
     * Display modes (template directories)
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_LIST => 'List',
        self::DISPLAY_MODE_TREE => 'Tree',
        self::DISPLAY_MODE_PATH => 'Path',
    );

    /**
     * Current category path id list 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pathIds;


    /**
     * Get widge title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Categories';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'categories/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Return subcategories list
     *
     * @param integer $categoryId Category id OPTIONAL
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCategories($categoryId = null)
    {
        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategory(
            $categoryId ?: $this->getWidgetParams(self::PARAM_ROOT_ID)
        );

        return $category ? $category->getSubcategories() : array();
    }

    /**
     * ID of the default root category
     * 
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultCategoryId()
    {
        return $this->getRootCategoryId();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', 'list', true, $this->displayModes
            ),
            self::PARAM_ROOT_ID => new \XLite\Model\WidgetParam\ObjectId\Category(
                'Parent category ID (leave 0 for root categories list)', $this->getDefaultCategoryId(), true, true
            ),
            self::PARAM_IS_SUBTREE => new \XLite\Model\WidgetParam\Bool(
                'Is subtree', false, false
            ),
        );
    }

    /**
     * Checks whether it is a subtree
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSubtree()
    {
        return $this->getParam(self::PARAM_IS_SUBTREE) !== false;
    }

    /**
     * Check if category included into active trail or not
     * 
     * @param \XLite\Model\Category $category Category
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isActiveTrail(\XLite\Model\Category $category)
    {
        if (!isset($this->pathIds)) {

            $this->pathIds = array();

            $categoriesPath = \XLite\Core\Database::getRepo('\XLite\Model\Category')
                ->getCategoryPath($this->getCategoryId());

            if (is_array($categoriesPath)) {

                foreach ($categoriesPath as $category) {

                    $this->pathIds[] = $category->getCategoryId();

                }

            }

        }

        return in_array($category->getCategoryId(), $this->pathIds);
    }

    /**
     * Assemble item CSS class name 
     * 
     * @param integer               $index    Item number
     * @param integer               $count    Items count
     * @param \XLite\Model\Category $category Current category
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function assembleItemClassName($index, $count, \XLite\Model\Category $category)
    {
        $classes = array();

        $active = $this->isActiveTrail($category);

        if (!$category->hasSubcategories()) {
            $classes[] = 'leaf';

        } elseif (self::DISPLAY_MODE_LIST != $this->getParam(self::PARAM_DISPLAY_MODE)) {
            $classes[] = $active ? 'expanded' : 'collapsed';
        }

        if (0 == $index) {
            $classes[] = 'first';
        }

        $listParam = array(
            'rootId'     => $this->getParam('rootId'),
            'is_subtree' => $this->getParam('is_subtree'),
        );
        if (
            ($count - 1) == $index
            && $this->isViewListVisible('topCategories.childs', $listParam)
        ) {
            $classes[] = 'last';
        }

        if ($active) {
            $classes[] = 'active-trail';
        }

        return implode(' ', $classes);
    }

    /**
     * Assemble list item link class name
     *
     * @param integer               $i        Item number
     * @param integer               $count    Items count
     * @param \XLite\Model\Category $category Current category
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function assembleLinkClassName($i, $count, \XLite\Model\Category $category)
    {
        return \XLite\Core\Request::getInstance()->category_id == $category->getCategoryId()
            ? 'active'
            : '';
    }

    /**
     * Assemble list item link class name
     *
     * @param integer           $i      Item number
     * @param integer           $count  Items count
     * @param \XLite\View\AView $widget Current category FIXME! this variable is not used 
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function assembleListItemClassName($i, $count, \XLite\View\AView $widget)
    {
        $classes = array('leaf');

        if (($count - 1) == $i) {
            $classes[] = 'last';
        }

        return implode(' ', $classes);
    }
}

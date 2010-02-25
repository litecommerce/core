<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Top categories widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Side bar with list of root categories (menu)
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_TopCategories extends XLite_View_SideBarBox
{
    /**
     * Display modes (template directories)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        'categories'      => 'List',
        'categories_tree' => 'Tree',
        'categories_path' => 'Path',
    );


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
        return $this->attributes['displayMode'];
    }

    /**
     * Return subcategories lis
     * 
     * @param integer $categoryId Category id
     *
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCategories($categoryId = null)
    {
        if (is_null($categoryId)) {
            $categoryId = $this->attributes['rootId'];
        }

        return $this->widgetParams['rootId']->getObject($categoryId)->getSubcategories();
    }

	/**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
		parent::defineWidgetParams();

        $this->widgetParams += array(
            'displayMode' => new XLite_Model_WidgetParam_List('displayMode', 'categories', 'Display mode', $this->displayModes),
            'rootId'      => new XLite_Model_WidgetParam_ObjectId_Category('rootId', 0, 'Root category Id'),
        );
    }


    /**
     * Check - category included into active trail or not
     * 
     * @param XLite_Model_Category $category Category
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isActiveTrail(XLite_Model_Category $category)
    {
        $currentCategory = $this->getCategory(XLite_Core_Request::getInstance()->category_id);

        return in_array(
            $category->get('category_id'),
            array($currentCategory->get('parent'), $currentCategory->get('category_id'))
        );
    }

    /**
     * Assemble item CSS class name 
     * 
     * @param int                  $index    item number
     * @param intr                 $count    items count
     * @param XLite_Model_Category $category current category
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleItemClassName($index, $count, XLite_Model_Category $category)
    {
        $classes = array();

        $active = $this->isActiveTrail($category);

        if (!$category->getSubcategories()) {
            $classes[] = 'leaf';

        } elseif ('list' != $this->attributes['displayMode']) {
            $classes[] = $active ? 'expanded' : 'collapsed';
        }

        if (0 == $index) {
            $classes[] = 'first';
        }

        if (($count - 1) == $index) {
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
     * @param intr                 $index    item number
     * @param int                  $count    items count
     * @param XLite_Model_Category $category current category
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function assembleLinkClassName($i, $count, $category)
    {
        return XLite_Core_Request::getInstance()->category_id == $category->get('category_id')
            ? 'active'
            : '';
    }

    /**
     * Return widget CSS files list
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        if ('tree' == $this->attributes['displayMode']) {
            $list[] = $this->getDir() . '/style.tree.css';
        }

        return $list;
    }

}


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

/**
 * Sidebar categories list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_TopCategories extends XLite_View_SideBarBox
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_ROOT_ID      = 'rootId';

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
    protected $pathIds = null;


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
     * @param integer $categoryId Category id
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCategories($categoryId = null)
    {
        $category = is_null($categoryId)
            ? $this->getWidgetParams(self::PARAM_ROOT_ID)->getObject()
            : new XLite_Model_Category($categoryId);

        return $category->getSubcategories();
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
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
                'Display mode', 'list', true, $this->displayModes
            ),
            self::PARAM_ROOT_ID => new XLite_Model_WidgetParam_ObjectId_Category(
                'Parent category ID (leave 0 for root categories list)', 0, true, true
            ),
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
        if (is_null($this->pathIds)) {
            $currentCategory = $this->getCategory(XLite_Core_Request::getInstance()->category_id);

            $this->pathIds = array();

            foreach ($currentCategory->getPath() as $c) {
                $this->pathIds[] = $c->get('category_id');
            }
        }

        return in_array($category->get('category_id'), $this->pathIds);
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

        } elseif (self::DISPLAY_MODE_LIST != $this->getParam(self::PARAM_DISPLAY_MODE)) {
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

}


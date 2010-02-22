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
     * Categoy path ids 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $pathIds = null;

    /**
     * Display modes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        'list' => array(
            'name' => 'List',
            'dir'  => 'categories',
        ),
        'tree' => array(
            'name' => 'Tree',
            'dir'  => 'categories_tree',
        ),
        'path' => array(
            'name' => 'Path',
            'dir'  => 'categories_path',
        ),
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
        return $this->displayModes[$this->attributes['displayMode']]['dir'];
    }

    /**
     * Return root category Id
     *
     * @param int $rootId passed Id
     *
     * @return int
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getRootId($rootId)
    {
        return isset($rootId) ? $rootId : $this->attributes['rootId'];
    }

    /**
     * Return current root category
     *
     * @param int $rootId root category Id
     *
     * @return XLite_Model_Category
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCategory($rootId = null)
    {
        return XLite_Model_CachingFactory::getObject('XLite_Model_Category', $this->getRootId($rootId));
    }

    /**
     * Return subcategories lis
     *
     * @param int $rootId root category Id
     *
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCategories($rootId = null)
    {
        return $this->getCategory($rootId)->getSubcategories();
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

        $displayMode = new XLite_Model_WidgetParam_List('displayMode', 'list', 'Display mode');
        foreach ($this->displayModes as $mode => $data) {
            $displayMode->options[$mode] = $data['name'];
        }

        $this->widgetParams += array(
			new XLite_Model_WidgetParam_String('rootId', 0, 'Root category Id'),
            $displayMode,
		);
    }


    /**
     * Define some attribute
     *
     * @param array $attributes attributes to set
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['rootId'] = 0;
        $this->attributes['displayMode'] = 'list';

        parent::__construct($attributes);
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attrs attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $conditions = array(
            array(
                self::ATTR_CONDITION => !isset($attrs['rootId']) || !is_numeric($attrs['rootId']),
                self::ATTR_MESSAGE   => 'Category Id is not numeric',
            ),
            array(
                self::ATTR_CONDITION => 0 > ($attrs['rootId'] = intval($attrs['rootId'])),
                self::ATTR_MESSAGE   => 'Category Id must be a non-negative integer',
            ),
            array(
                self::ATTR_CONDITION => 0 != $attrs['rootId'] && !$this->getCategory($attrs['rootId'])->isPersistent,
                self::ATTR_MESSAGE   => 'Category with Id #' . $attrs['rootId'] . ' is not found',
            ),
            array(
                self::ATTR_CONDITION => 0 != $attrs['rootId'] && !$this->getCategories($attrs['rootId']),
                self::ATTR_MESSAGE   => 'Category with Id #' . $attrs['rootId'] . ' has no subcategories',
            ),
            array(
                self::ATTR_CONDITION => !isset($attrs['displayMode']) || !isset($this->displayModes[$attrs['displayMode']]),
                self::ATTR_MESSAGE   => 'Display mode has a wrong value',
            ),
        );

        return parent::validateAttributes($attrs) + $this->checkConditions($conditions);
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
        $conditions = array(
            array(
                self::ATTR_CONDITION => !$category->getSubcategories(),
                self::ATTR_MESSAGE   => 'leaf',
            ),
            array(
                self::ATTR_CONDITION => $category->getSubcategories() && 'list' != $this->attributes['displayMode'],
                self::ATTR_MESSAGE   => $this->isActiveTrail($category) ? 'expanded' : 'collapsed',
            ),
            array(
                self::ATTR_CONDITION => 1 == $index,
                self::ATTR_MESSAGE   => 'first',
            ),
            array(
                self::ATTR_CONDITION => $count == $index,
                self::ATTR_MESSAGE   => 'last',
            ),
            array(
                self::ATTR_CONDITION => $this->isActiveTrail($category),
                self::ATTR_MESSAGE   => 'active-trail',
            ),
        );

        return implode(' ', $this->checkConditions($conditions));
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
        return (XLite_Core_Request::getInstance()->category_id == $category->get('category_id')) ? 'active' : '';
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


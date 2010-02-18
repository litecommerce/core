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
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public static $pathIds = null;

	/**
	 * Categories cache
	 * 
	 * @var    array
	 * @access protected
	 * @since  1.0.0
	 */
	protected $categories = null;

	/**
	 * Category root id 
	 * 
	 * @var    integer
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0 EE
	 */
	protected $rootid = 0;

    /**
     * Display mode 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $display_mode = 'list';

    /**
     * Display modes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $display_modes = array(
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
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
		parent::defineWidgetParams();

        $display_mode = new XLite_Model_WidgetParam_List('display_mode', 'list', 'Display mode');
        foreach ($this->display_modes as $k => $v) {
            $display_mode->options[$k] = $v['name'];
        }

        $this->widgetParams += array(
			new XLite_Model_WidgetParam_String('rootid', 0, 'Category root Id'),
            $display_mode,
		);
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

		if (!isset($attributes['rootid']) || !is_numeric($attributes['rootid'])) {
			$errors['rootid'] = 'Category Id is not numeric!';
		} else {
			$attributes['rootid'] = intval($attributes['rootid']);
		}

        if (!$errors && 0 > $attributes['rootid']) {
            $errors['rootid'] = 'Category Id must be positive integer!';
		}

		if (!$errors && 0 != $attributes['rootid']) {
			$category = new XLite_Model_Category($attributes['rootid']);

			if (!$category->isPersistent) {
				$errors['rootid'] = 'Category with category Id #' . $attributes['rootid'] . ' can not found!';

			} elseif (!$category->getSubcategories()) {
				$errors['rootid'] = 'Category with category Id #' . $attributes['rootid'] . ' has not subcategories!';
			}
		}

        // Display mode
        if (
            !$errors
            && (!isset($attributes['display_mode']) || !isset($this->display_modes[$attributes['display_mode']]))
        ) {
            $errors['display_mode'] = 'Display mode has wrong value!';
        }

		return $errors;
    }

    /**
     * Return root categories list 
     * 
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getCategories()
    {
        // get root categories
        if (is_null($this->categories)) {
	        $category = new XLite_Model_Category(0 < $this->rootid ? $this->rootid : null);
            $this->categories = $category->getSubcategories();
        }

        return $this->categories;
    }

    /**
     * Get path category id list 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPathIds()
    {
        if (is_null(self::$pathIds)) {
            self::$pathIds = array();

            if ($this->category_id) {
                $currentCategory = new XLite_Model_Category($this->category_id);

                foreach ($currentCategory->getPath() as $c) {
                    self::$pathIds[] = $c->get('category_id');
                }
            }
        }

        return self::$pathIds;
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
        return in_array($category->get('category_id'), $this->getPathIds());
    }

    /**
     * Assemble list item class name 
     * 
     * @param integer              $i        Item number
     * @param integer              $count    Items count
     * @param XLite_Model_Category $category Current category
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assembleItemClassName($i, $count, XLite_Model_Category $category)
    {
        $classes = array();

        if (!$category->getSubcategories()) {
            $classes[] = 'leaf';

        } elseif ($this->display_mode != 'list') {
            $classes[] = in_array($category->get('category_id'), $this->getPathIds())
                ? 'expanded'
                : 'collapsed';
        }

        if ($i == 1) {
            $classes[] = 'first';
        }

        if ($i == $count) {
            $classes[] = 'last';
        }

        if (in_array($category->get('category_id'), $this->getPathIds())) {
            $classes[] = 'active-trail';
        }

        return implode(' ', $classes);
    }

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
     * Get widget directory
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        $dir = 'categories';
    
        if (isset($this->display_modes[$this->display_mode])) {
            $dir = $this->display_modes[$this->display_mode]['dir'];
        }

        return $dir;
    }

    /**
     * Assemble list item link class name
     *
     * @param integer              $i        Item number
     * @param integer              $count    Items count
     * @param XLite_Model_Category $category Current category
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assembleLinkClassName($i, $count, $category)
    {
        $classes = array();

        if ($this->category_id && $this->category_id == $category->get('category_id')) {
            $classes[] = 'active';
        }

        return implode(' ', $classes);
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     */
    public function getCSSFiles()
    {
        $files = array();

        if ($this->display_mode == 'tree') {
            $files[] = $this->getDir() . '/style.' . $this->display_mode . '.css';
        }

        return $files;
    }

}


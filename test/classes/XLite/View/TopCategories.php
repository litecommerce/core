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
	 * Title
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $head = 'Categories';

	/**
	 * Directory contains sidebar content
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $dir = 'categories';

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
			new XLite_Model_WidgetParam_String('rootid', 0, 'Category root Id'),
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
}


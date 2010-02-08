<?php

/* $Id$ */

/**
 * Side bar with list of root categories (menu)
 *
 * @package    Lite Commerce
 * @subpackage View
 * @since      3.0
 */
class XLite_View_TopCategories extends XLite_View
{
	protected $head = 'Categories';

	protected $dir = 'categories';

	protected $categories = null;

	public function __construct()
	{
		$this->template = 'common' . LC_DS . 'sidebar_box.tpl';
	}

    public function getCategories()
    {
        // get root categories
        if (is_null($this->categories)) {
            $category = new XLite_Model_Category(); 
            $this->categories = $category->getComplex('topCategory.subcategories');
        }

        return $this->categories;
    }
}


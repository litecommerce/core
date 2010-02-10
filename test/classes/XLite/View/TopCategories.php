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
	 * Define the default template 
	 * 
	 * @return void
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct()
	{
		$this->template = $this->dir . LC_DS . 'body.tpl';
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

		// TODO - it's only an example. Must be removed
        $this->widgetParams += array(
			new XLite_Model_WidgetParam_String('head', null, 'Box title'),
			new XLite_Model_WidgetParam_String('test', null, 'Test'),
		);
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
            $category = new XLite_Model_Category(); 
            $this->categories = $category->getTopCategory()->getSubcategories();
        }

        return $this->categories;
    }
}


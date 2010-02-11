<?php

/**
 * XLite_Module_AdvancedSearch_View_AdvancedSearch 
 * 
 * @package    Litecommerce connector
 * @subpackage View
 * @since      3.0.0 EE
 */
class XLite_Module_AdvancedSearch_View_AdvancedSearch extends XLite_View_Dialog
{
	/**
     * Dialog title
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $head = 'Search for products';


	/**
	 * Define template 
	 * 
	 * @return void
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function __construct()
	{
		parent::__construct();

		$this->body = 'modules' . LC_DS . 'AdvancedSearch' . LC_DS . 'advanced_search.tpl';
	}
}


<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Advanced search widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Advanced search widget 
 * 
 * @package   View
 * @subpackage Widget
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


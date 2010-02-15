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
 * @package    View
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
     * Initilization
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
		parent::initView();

		$this->body = 'modules' . LC_DS . 'AdvancedSearch' . LC_DS . 'advanced_search.tpl';

        $request = XLite_Core_Request::getInstance();

        $this->visible = 'advanced_search' == $request->target;

        $this->mode = '';
	}
}


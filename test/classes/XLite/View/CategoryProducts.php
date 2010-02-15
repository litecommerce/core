<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category products list widget
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
 * Category products list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_CategoryProducts extends XLite_View_Dialog
{
	/**
	 * Title
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $head = 'Catalog';

    /**
     * Base href 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $href = 'cart.php';

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

        $request = XLite_Core_Request::getInstance();

        $this->category_id = intval($request->category_id);

        $this->body = 'category_products.tpl';
        $this->visible = 'category' == $request->target
            && 0 < $this->category_id
            && $this->getCategory()->getProducts();

        $this->showLocationPath = true;
    }

    /**
     * Get category 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategory()
    {
        return new XLite_Model_Category($this->category_id);
    }
}


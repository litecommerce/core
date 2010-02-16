<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product widget
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
 * Product widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Product extends XLite_View_Dialog
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

        $this->body = 'product_details.tpl';
        $this->visible = 'product' == $this->target
            && 0 < $this->product_id
            && $this->getProduct()->get('available');

        $this->showLocationPath = true;
    }

    /**
     * Get product 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct()
    {
        return new XLite_Model_Product($this->product_id);
    }
}


<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category widget
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
 * Category widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Category extends XLite_View_Abstract
{
    /**
     * Define template
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct()
    {
        $this->template = 'category_description.tpl';
    }

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

        $this->category_id = intval($this->category_id);

        $this->visible = 'category' == $this->target
            && 0 < $this->category_id
            && $this->getCategory()->get('description');
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


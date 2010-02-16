<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Featured Products widget
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
 * Featured products widget 
 * 
 * @package   View
 * @subpackage Widget
 * @since      3.0.0 EE
 */
class XLite_Module_FeaturedProducts_View_FeaturedProducts extends XLite_View_Dialog
{

    /**
     * Title 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
	protected $head = 'Featured products';

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
        $this->body = $this->config->FeaturedProducts->featured_products_look;
        $this->visible = in_array($this->target , array('main', 'category'))
            && $this->getCategory()->get('featuredProducts')
            && !$this->get('page');

        $this->mode = '';
    }
            
    /**
     * Get category id
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory()
    {
        return new XLite_Model_Category($this->category_id);
    }
}

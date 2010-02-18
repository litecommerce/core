<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Empty orders list widget
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
 * Empty orders list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_OrderListEmpty extends XLite_View_Abstract
{
    /**
     * Widget template
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $template = 'order/list_empty.tpl';

    /**
     * Allowed targets 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $allowed_targets = array('order_list');

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

        $this->visible = $this->visible
            && in_array($this->target, $this->allowed_targets)
            && $this->mode == 'search'
            && !$this->get('count');
    }
}


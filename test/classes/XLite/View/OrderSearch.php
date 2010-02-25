<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Orders search widget
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
 * Orders search widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_OrderSearch extends XLite_View_Dialog
{
    /**
     * Widget body template
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $body = 'search_form.tpl';

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('order_list');


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Search orders';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return 'order';
    }
}


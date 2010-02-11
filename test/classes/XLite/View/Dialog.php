<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
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
 * Dialogs for the center region
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
abstract class XLite_View_Dialog extends XLite_View
{
    /**
     * Dialog title 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $head = null;

    /**
     * Dialog content template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $body = null;


    /**
     * Define template
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct()
    {
        $this->template = 'common' . LC_DS . 'container.tpl';
    }
}


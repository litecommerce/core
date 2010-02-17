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
 * Abstract side bar box
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
abstract class XLite_View_SideBarBox extends XLite_View_Abstract
{
    /**
     * Directory contains sidebar content
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
	protected $dir = null;

    /**
     * Body file name
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $bodyFile = 'body.tpl';

    /**
     * Define the default template 
     * 
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function __construct()
    {
	    $this->template = 'common' . LC_DS . 'sidebar_box_container.tpl';
    }

}


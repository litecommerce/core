<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Abstract dialog for the center region
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Abstract dialog for the center region
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
abstract class XLite_View_Dialog extends XLite_View_Abstract
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


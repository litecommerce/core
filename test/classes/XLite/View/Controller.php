<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LC viewer
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
 * LC viewer
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Controller extends XLite_View_Abstract
{
    /**
     * Send headers 
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function startPage()
    {
        // send no-cache headers
        $error_reporting = error_reporting(0); // suppress warning messages
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: text/html");
        error_reporting($error_reporting);
    }

    /**
     * Send headers
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function initView()
    {
        $this->startPage();
    }

    /**
     * Set template and attributes 
     * 
     * @param string $template template to display
     * @param array  $attrs    widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array(), $template = null)
    {
        $this->attributes['silent'] = true;
        $this->attributes['dumpStarted'] = '';

        parent::__construct($attributes);

        $this->template = $template;
    }

    /**
     * Show current page and, optionally, footer  
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function display()
    {
        if (!$this->attributes['silent']) {
            parent::display();
        }

        if ($this->attributes['dumpStarted']) {
            func_refresh_end();
        }

        XLite::$controller->postprocess();
    }
}


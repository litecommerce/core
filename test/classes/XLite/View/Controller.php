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
    protected $silent = true;

    protected $dumpStarted = false;

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

    protected function initView()
    {
        $this->startPage();
    }

    public function __construct($template = null, $silent = false, $dumpStarted = false)
    {
        $this->template = $template;
        $this->silent = $silent;
        $this->dumpStarted = $dumpStarted;
    }

    public function display()
    {
        if (!$this->silent) {
            parent::display();
        }

        if ($this->dumpStarted) {
            func_refresh_end();
        }

        XLite::$controller->postprocess();
    }
}


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
            ob_start();
            parent::display();
            $content = ob_get_contents();
            ob_end_clean();

            echo $this->postprocessResources($content);
        }

        if ($this->attributes['dumpStarted']) {
            func_refresh_end();
        }

        XLite::$controller->postprocess();
    }

    /**
     * Postprocess widgets resources 
     * 
     * @param string $content Content
     *  
     * @return strong
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessResources($content)
    {
        if (preg_match('/<\/head>/Ssi', $content)) {
            $added = array();

            foreach (XLite_View_Abstract::$resources as $key => $list) {
                $list = array_unique($list);

                foreach ($list as $path) {
                    $url = XLite::getInstance()->shopURL(XLite_Model_Layout::getInstance()->getPath() . $path);

                    if ('js' == $key) {
                        $added[] = '<script type="text/javascript" src="' . $url . '"></script>';

                    } elseif ('css' == $key) {
                        $added[] = '<link rel="stylesheet" type="text/css" src="' . $url . '" />';
                    }
                }
            }

            if ($added) {
                $content = preg_replace(
                    '/<\/head>/iSs',
                    implode("\n", $added) . "\n" . '</head>',
                    $content
                );
            }
        }

        return $content;
    }
}


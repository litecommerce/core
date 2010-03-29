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
     * Widget parameter names
     */

    const PARAM_SILENT       = 'silent';
    const PARAM_DUMP_STARTED = 'dumpStarted';


    /**
     * Semaphore
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected static $isStarted = false;

    /**
     * Content of the currnt page
     * NOTE: this is a text, so it's not passed by reference; do not wrap it into a getter
     * 
     * @var    string
     * @access public
     * @since  3.0.0
     */
    public static $bodyContent = null;


    /**
     * Check for current display mode
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function useDefaultDisplayMode()
    {
        return self::$isStarted || $this->getParam(self::PARAM_IS_EXPORTED) || XLite::getInstance()->adminZone;
    }

    /**
     * Send headers 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function startPage()
    {
        // send no-cache headers
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: text/html');
    }

    /**
     * displayPage 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function displayPage()
    {
        // Set mutex
        self::$isStarted = true;

        if (!$this->getParam(self::PARAM_SILENT)) {

            self::$bodyContent = $this->getContent();
            
            $this->getWidgetParams(self::PARAM_TEMPLATE)->setValue('body.tpl');

            self::startPage();
            $this->display();
        }

        if ($this->getParam(self::PARAM_DUMP_STARTED)) {
            func_refresh_end();
        }

        XLite::getController()->postprocess();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SILENT       => new XLite_Model_WidgetParam_Bool('Silent', false),
            self::PARAM_DUMP_STARTED => new XLite_Model_WidgetParam_Bool('Dump started', false)
        );
    }


    /**
     * This viewer is only instantiated using the "new" operator (nor the "getWidget()" method)
     * 
     * @param string $template template to use
     * @param array  $params   widget params
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($template, array $params = array())
    {
        $this->init(array(self::PARAM_TEMPLATE => $template) + $params);
    }

    /**
     * TODO - check if it's really needed
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __destruct()
    {
        self::$bodyContent = null;
    }

    /**
     * Show current page and, optionally, footer  
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function display()
    {
        $this->useDefaultDisplayMode() ? parent::display() : $this->displayPage();
    }
}


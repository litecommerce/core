<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Controller main widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Controller extends \XLite\View\AView
{
    /**
     * Content of the currnt page
     * NOTE: this is a text, so it's not passed by reference; do not wrap it into a getter (or pass by reference)
     * NOTE: until it's not accessing via the function, do not change its access modifier
     * 
     * @var    string
     * @access public
     * @since  3.0.0
     */
    public static $bodyContent = null;

    /**
     * Send headers 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function startPage()
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
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'body.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SILENT       => new \XLite\Model\WidgetParam\Bool('Silent', false),
            self::PARAM_DUMP_STARTED => new \XLite\Model\WidgetParam\Bool('Dump started', false)
        );
    }

    /**
     * isSilent 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isSilent()
    {
        return $this->getParam(self::PARAM_SILENT);
    }

    /**
     * isDumpStarted 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isDumpStarted()
    {
        return $this->getParam(self::PARAM_DUMP_STARTED);
    }

    /**
     * getContentWidget 
     * 
     * @return \XLite\View\AView
     * @access protected
     * @since  3.0.0
     */
    protected function getContentWidget()
    {
        return $this->getWidget(array(\XLite\View\AView::PARAM_TEMPLATE => $this->template), '\XLite\View\Content');
    }

    /**
     * prepareContent 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function prepareContent()
    {
        self::$bodyContent = $this->getContentWidget()->getContent();
    }

    /**
     * useDefaultDisplayMode 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function useDefaultDisplayMode()
    {
        return $this->isExported();
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
        if ($this->useDefaultDisplayMode()) {
            $this->getContentWidget()->display();
        } else {
            $this->prepareContent();
            $this->startPage();
            parent::display();
        }
    }

    /**
     * refreshEnd 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function refreshEnd()
    {
        func_refresh_end();
    }


    /**
     * __construct 
     * 
     * @param array  $params          Widget params
     * @param string $contentTemplate Central area template
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array(), $contentTemplate = null)
    {
        parent::__construct($params);

        $this->template = $contentTemplate;
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
        if (!$this->isSilent()) {
            $this->displayPage();
        }

        if ($this->isDumpStarted()) {
            $this->refreshEnd();
        }

        $this->postprocess();
    }
}


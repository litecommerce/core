<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Template container
 *  
 * @category   Lite Commerce
 * @package    View
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */


/**
 * Template container 
 * 
 * @package    View
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_View_Container extends XLite_View_Abstract
{
    /**
     * Default body template
     */

    const PARAM_BODY_TEMPLATE = 'body.tpl';


    /**
     * Return title 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getHead();

    /**
     * Return templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDir();

    /**
     * Return default template
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultTemplate();


    /**
     * isWrapper 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isWrapper()
    {
        return $this->getParam(self::PARAM_TEMPLATE) == $this->getDefaultTemplate();
    }

    /**
     * Return current template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return $this->useBodyTemplate() ? $this->getBody() : parent::getTemplate();
    }

    /**
     * Return file name for the center part template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getBody()
    {
        return $this->getDir() . LC_DS . self::PARAM_BODY_TEMPLATE;
    }

	/**
	 * Determines if need to display only a widget body
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0
	 */
	protected function useBodyTemplate()
	{
		return XLite_Core_CMSConnector::isCMSStarted() && $this->isWrapper();
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

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue($this->getDefaultTemplate());
    }
}


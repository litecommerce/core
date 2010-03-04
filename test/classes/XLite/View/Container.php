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
 * @since      3.0.0 EE
 */


/**
 * Template container 
 * 
 * @package    View
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_View_Container extends XLite_View_Abstract
{
    /**
     * Widget body default template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $body = 'body.tpl';


    /**
     * Return title 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getHead();

    /**
     * Return templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getDir();


    /**
     * Return file name for the center part template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getBody()
    {
        return $this->getDir() . LC_DS . $this->body;
    }

	/**
	 * Determines if need to display only a widget body
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function isWrapped()
	{
		return $this->attributes['showWrapper'] && !XLite_Core_CMSConnector::isCMSStarted();
	}

     /**
     * Initialize widget
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init(array $attributes = array())
    {
        $this->attributes['showWrapper'] = true;

        parent::init($attributes);

        if (!$this->isWrapped() && !isset($attributes['template'])) {
            $this->template = $this->getBody();
        }
    }
}


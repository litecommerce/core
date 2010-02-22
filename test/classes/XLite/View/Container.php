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
	 */
	const BODY_TEMPLATE = 'body.tpl';


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
     * Check passed attributes
     * TODO - check if we need to move this function into the XLite_View_Abstract
     *
     * @param array $attrs attributes to check
     *
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $messages = array();

        foreach ($this->widgetParams as $name => $param) {

            if (isset($attrs[$name])) {
                
                list($result, $widgetErrors) = $param->validate($attrs[$name]);

                if (false === $result) {
                    $messages[] = $param->label . ': ' . implode('<br />' . $param->label . ': ', $widgetErrors);
                }
            } else {

                $messages[] = $param->label . ': is not set';
            }
        }

        return parent::validateAttributes($attrs) + $messages;
    }

    /**
     * Set attributes and template (is needed)
     * 
     * @param array $attributes widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['showWrapper'] = true;

        // FIXME - move this into the XLite_View_Abstract class
        foreach ($this->getWidgetParams() as $name => $param) {
            $this->attributes[$name] = $param->value;
        }

        parent::__construct($attributes);

        if (!$this->isWrapped()) {
            $this->template = $this->getDir() . LC_DS . self::BODY_TEMPLATE;
        }
    }
}


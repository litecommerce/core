<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_View_Button_Link 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_View_Button_Link extends XLite_View_Button_Abstract
{
    /**
     * Widget template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'button/regular.tpl';


    /**
     * JS code will be executed when this event occurs
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSEvent()
    {
        return $this->attributes['jsEvent'];
    }

    /**
     * JavaScript: this code will be used by default 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDefaultJSCode($action = null)
    {
        return 'self.location = \'' . $this->attributes['location'] . '\';';
    }

    /**
     * JavaScript: return specified (or default) JS code to execute
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSCode()
    {
        return empty($this->attributes['jsCode']) ? $this->getDefaultJSCode() : $this->attributes['jsCode'];
    }

    /**
     * Define some widget attributes
     * 
     * @param array $attributes widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init(array $attributes = array())
    {
        $this->attributes += array(
            'location' => '',
            'jsEvent'  => 'onclick',
            'jsCode'   => '',
        );

        parent::init($attributes);
    }
}


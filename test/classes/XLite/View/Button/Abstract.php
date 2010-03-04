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
 * XLite_View_Button_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_View_Button_Abstract extends XLite_View_Abstract
{
    /**
     * Return button text 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getButtonLabel()
    {
        return $this->attributes['label'];
    }

    /**
     * Define some common attributes
     * 
     * @param array $attributes widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */    
    public function init(array $attributes = array())
    {
        $this->attributes['label'] = '';

        parent::init($attributes);
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        return array('button/button.css');
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function getJSFiles()
    {
        return array('button/button.js');
    }
}


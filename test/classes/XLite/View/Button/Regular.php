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
 * XLite_View_Button_Regular 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_View_Button_Regular extends XLite_View_Button_Abstract
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
     * Return list of current form params to modify
     * 
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormParams()
    {
        $result = array();

        // It's possible to define separate attribute "action",
        // and it will be automatically used to modify current form params
        if (!empty($this->attributes['action'])) {
            $result += array('action' => $this->attributes['action']);
        }

        return $this->attributes['formParams'] + $result;
    }

    /**
     * JavaScript: compose the associative array definition by PHP array
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSFormParams()
    {
        $result = array();

        foreach ($this->getFormParams() as $name => $value) {
            $result[] = '\'' . $name . '\': \'' . $value . '\'';
        }

        return implode(',', $result);
    }

    /**
     * JavaScript: default JS code to execute 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDefaultJSCode()
    {
        return empty($this->attributes['formParams'])
            ? 'submitFormDefault(this.form, \'' . $this->attributes['action'] . '\');'
            : 'submitForm(this.form, {' . $this->getJSFormParams() . '})';
    }

    /**
     * JS code will be executed on this event 
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
     * Return specified (or default) JS code
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
            'action'     => '',
            'jsEvent'    => 'onclick',
            'jsCode'     => '',
            'formParams' => array(),
        );

        parent::init($attributes);
    }
}


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
 * XLite_Module_DrupalConnector_View_Form_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_Module_DrupalConnector_View_Form_Abstract extends XLite_View_Form_Abstract implements XLite_Base_IDecorator
{
    /**
     * Return widget template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTemplate()
    {
        return ($this->isDrupalGetForm() && !$this->attributes['end']) 
            ? 'modules/DrupalConnector/form.start.tpl' 
            : parent::getTemplate();
    }

    /**
     * Chech if widget is exported into Drupal and current form has its method = "GET"
     * 
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function isDrupalGetForm()
    {
        return XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS() 
               && 'GET' == $this->attributes['form_method'];
    }

    /**
     * This JavaScript code will be performed when form submits
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSOnSubmitCode()
    {
        return ($this->isDrupalGetForm() ? 'drupalOnSubmitGetForm(this); ' : '') . parent::getJSOnSubmitCode();
    }

    /**
     * JavaScript: compose the "{'a':<a>,'b':<b>,...}" string (JS array) by the params array
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormParamsAsJSArray()
    {
        return '[\'' . implode('\',\'', array_keys($this->getFormParams())) . '\']';
    }

    /**
     * Define new form parametr - drupal node URL 
     * 
     * @param array $attributes widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init(array $attributes = array())
    {
        parent::init($attributes);

        if ($this->isDrupalGetForm()) {
            $this->attributes['form_params']['q'] = '';
        }
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
        $result = parent::getJSFiles();

        if ($this->isDrupalGetForm()) {
            $result[] = 'modules/DrupalConnector/drupal.js';
        }

        return $result;
    }
}


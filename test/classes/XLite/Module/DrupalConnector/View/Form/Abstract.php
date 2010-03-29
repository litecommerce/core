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
 * @since      3.0.0
 */

/**
 * XLite_Module_DrupalConnector_View_Form_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_Module_DrupalConnector_View_Form_Abstract extends XLite_View_Form_Abstract implements XLite_Base_IDecorator
{
    /**
     * Return widget template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return ($this->isDrupalGetForm() && !$this->getParam(self::PARAM_END))
            ? 'modules/DrupalConnector/form.start.tpl' 
            : parent::getTemplate();
    }

    /**
     * Chech if widget is exported into Drupal and current form has its method = "GET"
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isDrupalGetForm()
    {
        return XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS() 
               && 'GET' == $this->getParam(self::PARAM_FORM_METHOD);
    }

    /**
     * This JavaScript code will be performed when form submits
     * 
     * @return string
     * @access protected
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getFormParamsAsJSArray()
    {
        return '[\'' . implode('\',\'', array_keys($this->getFormParams())) . '\']';
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

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(array('q' => ''));

    }
}


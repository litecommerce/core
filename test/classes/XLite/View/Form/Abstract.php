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
 * XLite_View_Form_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_View_Form_Abstract extends XLite_View_Abstract
{
    /**
     * Determines currently drawing form 
     * NOTE - currently not used
     * 
     * @var    XLite_View_Form_Abstract
     * @access protected
     * @since  3.0.0 EE
     */
    protected static $currentForm = null;

    /**
     * Saved value - parent form
     * NOTE - currently not used 
     * 
     * @var    XLite_View_Form_Abstract
     * @access protected
     * @since  3.0.0 EE
     */
    protected $parentForm = null;

    /**
     * Attributes common for all the forms 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $defaultFormAttributes = array(
        'form_target' => '',
        'form_action' => '',
        'form_name'   => '',
        'form_params' => array(),
        'form_method' => 'POST',
    );


    /**
     * Open and close form tags
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTemplate()
    {
        return 'form/' . ($this->attributes['end'] ? 'end' : 'start') . '.tpl';
    }

    /**
     * Required form parameters
     * 
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCommonFormParams()
    {
        return array(
            'target' => $this->attributes['form_target'],
            'action' => $this->attributes['form_action'],
        );
    }

    /**
     * Return value for the <form action="..." ...> attribute
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormAction()
    {
        return $this->buildURL($this->attributes['form_target']);
    }

    /**
     * Return list of additional params 
     * 
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormParams()
    {
        return $this->getCommonFormParams() + $this->attributes['form_params'];
    }

    /**
     * Ability to use Flexy attribute "name" as form name
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function assignFormName()
    {
        if (isset($this->attributes[self::WIDGET_NAME])) {
            $this->attributes['form_name'] = $this->attributes[self::WIDGET_NAME];
        }
    }

    /**
     * Save current and parent form handlers 
     * NOTE - currently not used
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function setCurrentForm()
    {
        if ($this->attributes['end']) {
            self::$currentForm = $this->parentForm;
        } else {
            $this->parentForm = self::$currentForm;
            self::$currentForm = $this;
        }
    }

    /**
     * JavaScript: this value will be returned on form submit
     * NOTE - this function designed for AJAX easy switch on/off  
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getOnSubmitResult()
    {
        return 'true';
    }

    /**
     * JavaScript: default action performed on form submit
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSOnSubmitCode()
    {
        return 'return ' . $this->getOnSubmitResult() . ';';
    }

    /**
     * Called before the display()
     * NOTE - currently not used
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function initView()
    {
        parent::initView();

        $this->assignFormName();
        $this->setCurrentForm();
    }


    /**
     * Each form must define its own name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getFormName();

    /**
     * Each form must define its parameters 
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function defineDefaultFormAttributes();


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
        $this->attributes['start'] = true;
        $this->attributes['end']   = false;

        $this->defineDefaultFormAttributes();
        $this->attributes += $this->defaultFormAttributes;

        parent::init($attributes);
    }
}


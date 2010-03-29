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

/**
 * Abstract form
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
abstract class XLite_View_Form_Abstract extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_FORM_TARGET = 'form_target';
    const PARAM_FORM_ACTION = 'form_action';
    const PARAM_FORM_NAME   = 'form_name';
    const PARAM_FORM_PARAMS = 'form_params';
    const PARAM_FORM_METHOD = 'form_method';

    const PARAM_START = 'start';
    const PARAM_END   = 'end';

    const PARAM_CLASS_NAME = 'className';

    protected $plainList = null;

    /**
     * Open and close form tags
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return 'form/' . ($this->getParam(self::PARAM_END) ? 'end' : 'start') . '.tpl';
    }

    /**
     * Required form parameters
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCommonFormParams()
    {
        return array(
            'target' => $this->getParam(self::PARAM_FORM_TARGET),
            'action' => $this->getParam(self::PARAM_FORM_ACTION),
        );
    }

    /**
     * Return value for the <form action="..." ...> attribute
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormAction()
    {
        return $this->buildURL($this->getParam(self::PARAM_FORM_TARGET));
    }

    /**
     * Return list of additional params 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParams()
    {
        return $this->getCommonFormParams() + $this->getParam(self::PARAM_FORM_PARAMS);
    }

    /**
     * Return list of additional params 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParamsAsPlainList()
    {
        if (is_null($this->plainList)) {
            foreach ($this->getFormParams() as $key => $value) {
                if (is_array($value)) {
                    $this->addChain2PlainList($key, $value);

                } else {
                    $this->plainList[$key] = $value;
                }
            }
        }

        return $this->plainList;
    }

    /**
     * Add array branch to plain parameters list 
     * 
     * @param string $prefix Branch prefix
     * @param array  $list   Branch
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addChain2PlainList($prefix, array $list)
    {
        foreach ($list as $key => $value) {
            $key = $prefix . '[' . $key . ']';
            if (is_array($value)) { 
                $this->addChain2PlainList($key, $value);

            } else {
                $this->plainList[$key] = $value;
            }
        }
    }

    /**
     * JavaScript: this value will be returned on form submit
     * NOTE - this function designed for AJAX easy switch on/off  
     * 
     * @return string
     * @access protected
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getJSOnSubmitCode()
    {
        return 'return ' . $this->getOnSubmitResult() . ';';
    }

    /**
     * getDefaultTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return '';
    }

    /**
     * getDefaultAction 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return '';
    }

    /**
     * getDefaultParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultParams()
    {
        return array();
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

        $this->widgetParams += array(
            self::PARAM_START => new XLite_Model_WidgetParam_Bool('Is start', true),
            self::PARAM_END   => new XLite_Model_WidgetParam_Bool('Is end', false),

            self::PARAM_FORM_TARGET => new XLite_Model_WidgetParam_String('Target', $this->getDefaultTarget()),
            self::PARAM_FORM_ACTION => new XLite_Model_WidgetParam_String('Action', $this->getDefaultAction()),
            self::PARAM_FORM_NAME   => new XLite_Model_WidgetParam_String('Name', ''),
            self::PARAM_FORM_PARAMS => new XLite_Model_WidgetParam_Array('Params', $this->getDefaultParams()),
            self::PARAM_FORM_METHOD => new XLite_Model_WidgetParam_LIst('Request method', 'post', array('post', 'get')),

            self::PARAM_CLASS_NAME  => new XLite_Model_WidgetParam_String('Class name', ''),
        );
    }


    /**
     * Each form must define its own name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getFormName();
}


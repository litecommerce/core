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
 * Regular button
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_View_Button_Regular extends XLite_View_Button_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_ACTION      = 'action';
    const PARAM_JS_EVENT    = 'jsEvent';
    const PARAM_JS_CODE     = 'jsCode';
    const PARAM_FORM_PARAMS = 'formParams';


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
            self::PARAM_ACTION      => new XLite_Model_WidgetParam_String('LC action', null, true),
            self::PARAM_JS_EVENT    => new XLite_Model_WidgetParam_List('JS event', 'onclick', true, $this->allowedJSEvents),
            self::PARAM_JS_CODE     => new XLite_Model_WidgetParam_String('JS code', '', true),
            self::PARAM_FORM_PARAMS => new XLite_Model_WidgetParam_Array('Form params to modify', array(), true),
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('button/regular.tpl');
    }

    /**
     * JavaScript: compose the associative array definition by PHP array
     *
     * @param array $params values to compose
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSFormParams(array $params)
    {
        $result = array();

        foreach ($params as $name => $value) {
            $result[] = $name . ': \'' . $value . '\'';
        }

        return implode(',', $result);
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultJSCode()
    {
        $formParams = $this->getParam(self::PARAM_FORM_PARAMS);

        if (!isset($formParams['action']) && $this->getParam(self::PARAM_ACTION)) {
            $formParams['action'] = $this->getParam(self::PARAM_ACTION);
        }

        return $formParams
            ? 'submitForm(this.form, {' . $this->getJSFormParams($formParams) . '})'
            : 'submitFormDefault(this.form, ' . (is_null($this->getParam(self::PARAM_ACTION)) ? 'null' : ('\'' . $this->getParam(self::PARAM_ACTION) . '\'')) . ');';
    }

    /**
     * JS code will be executed on this event
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSEvent()
    {
        return $this->getParam(self::PARAM_JS_EVENT);
    }

    /**
     * Return specified (or default) JS code
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSCode()
    {
        $jsCode = $this->getParam(self::PARAM_JS_CODE);

        return empty($jsCode) ? $this->getDefaultJSCode() : $jsCode;
    }
}


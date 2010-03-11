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
     * Widget parameter names
     */

    const PARAM_LOCATION = 'location';
    const PARAM_JS_EVENT = 'jsEvent';
    const PARAM_JS_CODE  = 'jsCode';


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
            self::PARAM_LOCATION => new XLite_Model_WidgetParam_String('Redirect to', null, true),
            self::PARAM_JS_EVENT => new XLite_Model_WidgetParam_List('JS event', 'onclick', true, $this->allowedJSEvents),
            self::PARAM_JS_CODE  => new XLite_Model_WidgetParam_String('JS code', null, true),
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('button/regular.tpl');
    }

    /**
     * JS code will be executed when this event occurs
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getJSEvent()
    {
        return $this->getParam(self::PARAM_JS_EVENT);
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
        return 'self.location = \'' . $this->getParam(self::PARAM_LOCATION) . '\';';
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
        return $this->getParam(self::PARAM_JS_CODE) ? $this->getParam(self::PARAM_JS_CODE) : $this->getDefaultJSCode();
    }
}


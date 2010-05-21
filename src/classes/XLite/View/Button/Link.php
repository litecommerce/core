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
 * Link as button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Button_Link extends XLite_View_Button_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_LOCATION = 'location';
    const PARAM_JS_CODE  = 'jsCode';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'button/regular.tpl';
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
            self::PARAM_LOCATION => new XLite_Model_WidgetParam_String('Redirect to', null, true),
            self::PARAM_JS_CODE  => new XLite_Model_WidgetParam_String('JS code', null, true),
        );
    }

    /**
     * JavaScript: this code will be used by default 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getJSCode()
    {
        return $this->getParam(self::PARAM_JS_CODE) ? $this->getParam(self::PARAM_JS_CODE) : $this->getDefaultJSCode();
    }
}


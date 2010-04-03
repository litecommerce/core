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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_FormField_Abstract 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_View_FormField_Abstract extends XLite_View_Abstract
{
    /**
     * Widget param names 
     */

    const PARAM_VALUE      = 'value';
    const PARAM_REQUIRED   = 'required';
    const PARAM_ATTRIBUTES = 'attributes';
    const PARAM_NAME       = 'name';

    /**
     * Available field types
     */

    const FIELD_TYPE_TEXT      = 'text';
    const FIELD_TYPE_PASSWORD  = 'password';
    const FIELD_TYPE_SELECT    = 'select';
    const FIELD_TYPE_CHECKBOX  = 'checkbox';
    const FIELD_TYPE_TEXTAREA  = 'textarea';
    const FIELD_TYPE_SEPARATOR = 'separator';


    /**
     * Return widget default template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultTemplate();

    /**
     * Return field type
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getFieldType();


    /**
     * Return field name
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getName()
    {
        return $this->getParam(self::PARAM_NAME);
    }

    /**
     * Return field value
     * 
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getValue()
    {
        return $this->getParam(self::PARAM_VALUE);
    }

    /**
     * Check if field is required
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isRequired()
    {
        return $this->getParam(self::PARAM_REQUIRED);
    }

    /**
     * Return HTML representation for widget attributes
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getAttributesCode()
    {
        $result = '';

        if (!empty($this->getParam(self::PARAM_ATTRIBUTES))) {
            foreach ($this->getParam(self::PARAM_ATTRIBUTES) as $name => $value) {
                $result .= ' ' . $name . '="' . $value . '"';
            }
        }

        return $result;
    }

    /**
     * Define widget params 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_VALUE      => new XLite_Model_WidgetParam_String('Value', null),
            self::PARAM_REQUIRED   => new XLite_Model_WidgetParam_Bool('Required', false),
            self::PARAM_ATTRIBUTES => new XLite_Model_WidgetParam_Array('Attributes', array()),
            self::PARAM_NAME       => new XLite_Model_WidgetParam_String('Name', null),
        );
    }
}


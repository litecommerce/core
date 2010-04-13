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
    const PARAM_LABEL      = 'label';
    const PARAM_COMMENT    = 'comment';

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
     * fieldParams 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $fieldParams = null;


    /**
     * Return field template
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getFieldTemplate();


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'form_field.tpl';
    }

    /**
     * Return name of the folder with templates
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'form_field';
    }

    /**
     * Return list of field-specific params
     * 
     * NOTE: params order is make sence!
     * You must pass them into constructor in the exact order as described here
     *
     * NOTE: keep this function synchronized 
     * with the XLite_View_Model_Abstract::getFieldSchemaArgs() one
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineFieldParams()
    {
        $this->fieldParams = array(
            self::PARAM_NAME       => new XLite_Model_WidgetParam_String('Name', null),
            self::PARAM_VALUE      => new XLite_Model_WidgetParam_String('Value', null),
            self::PARAM_LABEL      => new XLite_Model_WidgetParam_String('Label', null),
            self::PARAM_REQUIRED   => new XLite_Model_WidgetParam_Bool('Required', false),
            self::PARAM_COMMENT    => new XLite_Model_WidgetParam_String('Comment', null),
            self::PARAM_ATTRIBUTES => new XLite_Model_WidgetParam_Array('Attributes', array()),
        );
    }

    /**
     * Return list of field-specific params
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldParams()
    {
        if (!isset($this->fieldParams)) {
            $this->defineFieldParams();
        }

        return $this->fieldParams;
    }

    /**
     * getFieldParamsSchema 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldParamsSchema()
    {
        return array_keys($this->getFieldParams());
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

        foreach ($this->getParam(self::PARAM_ATTRIBUTES) as $name => $value) {
            $result .= ' ' . $name . '="' . $value . '"';
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

        $this->widgetParams += $this->getFieldParams();
    }

    /**
     * Compose params' array from the arguments passed to constructor
     * 
     * @param array $params arguments passed to constructor
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function prepareParams(array $params)
    {
        $result = array();

        $keys  = $this->getFieldParamsSchema();
        $count = min(count($keys), count($params));

        for ($i = 0; $i < $count; $i++) {
            $result[$keys[$i]] = $params[$i];
        }

        return $result;
    }


    /**
     * Return field type
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    abstract public function getFieldType();


    /**
     * Define and set handler attributes; initialize handler
     *
     * @param array $params handler params
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        $fieldParams = func_get_args();
        array_shift($fieldParams);

        parent::__construct($params + $this->prepareParams($fieldParams));
    }

    /**
     * Return field name
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getName()
    {
        return $this->getParam(self::PARAM_NAME);
    }

    /**
     * Wrapper; public function to retrieve widget params
     * 
     * @param string $name param name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function getFieldAttribute($name)
    {
        return $this->getParam($name);
    }

    /**
     * Validate field value
     *
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function validate()
    {
        return ('' == $this->getValue() && $this->isRequired()) ? 'is empty' : null;
    }
}


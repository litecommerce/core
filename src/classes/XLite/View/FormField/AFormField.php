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

namespace XLite\View\FormField;

/**
 * Abstract form field
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AFormField extends \XLite\View\AView
{
    /**
     * Widget param names 
     */

    const PARAM_VALUE      = 'value';
    const PARAM_REQUIRED   = 'required';
    const PARAM_ATTRIBUTES = 'attributes';
    const PARAM_NAME       = 'fieldName';
    const PARAM_LABEL      = 'label';
    const PARAM_COMMENT    = 'comment';

    const PARAM_IS_ALLOWED_FOR_CUSTOMER = 'isAllowedForCustomer';

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
     * name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $name = null;

    /**
     * validityFlag
     *
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $validityFlag = null;

    /**
     * Determines if this field is visible for customers or not 
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $isAllowedForCustomer = true;


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
     * checkSavedValue 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkSavedValue()
    {
        return !is_null($this->callFormMethod('getSavedData', array($this->getName())));
    }

    /**
     * getValidityFlag 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function getValidityFlag()
    {
        if (!isset($this->validityFlag)) {
            $this->validityFlag = $this->checkFieldValidity();
        }

        return $this->validityFlag;
    }

    /**
     * getCommonAttributes 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCommonAttributes()
    {
        return array(
            'id'   => $this->getFieldId(),
            'name' => $this->getName(),
        );
    }

    /**
     * setCommonAttributes 
     * 
     * @param array $attrs field attributes to prepare
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setCommonAttributes(array $attrs)
    {
        foreach ($this->getCommonAttributes() as $name => $value) {
            if (!isset($attrs[$name])) {
                $attrs[$name] = $value;
            }
        }

        return $attrs;
    }

    /**
     * prepareAttributes 
     * 
     * @param array $attrs field attributes to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function prepareAttributes(array $attrs)
    {
        if (!$this->getValidityFlag() && $this->checkSavedValue()) {
            $attrs['class'] = (empty($attrs['class']) ? '' : $attrs['class'] . ' ') . 'form_field_error';
        }

        return $this->setCommonAttributes($attrs);
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
     * getAttributes 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getAttributes()
    {
        return $this->prepareAttributes($this->getParam(self::PARAM_ATTRIBUTES));
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

        foreach ($this->getAttributes() as $name => $value) {
            $result .= ' ' . $name . '="' . $value . '"';
        }

        return $result;
    }

    /**
     * Some JavaScript code to insert 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInlineJSCode()
    {
        return null;
    }

    /**
     * getDefaultName 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultName()
    {
        return null;
    }

    /**
     * getDefaultValue 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultValue()
    {
        return isset($this->name) ? $this->callFormMethod('getDefaultFieldValue', array($this->name)) : null;
    }

    /**
     * getDefaultLabel 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultLabel()
    {
        return null;
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
            self::PARAM_NAME       => new \XLite\Model\WidgetParam\String('Name', $this->getDefaultName()),
            self::PARAM_VALUE      => new \XLite\Model\WidgetParam\String('Value', $this->getDefaultValue()),
            self::PARAM_LABEL      => new \XLite\Model\WidgetParam\String('Label', $this->getDefaultLabel()),
            self::PARAM_REQUIRED   => new \XLite\Model\WidgetParam\Bool('Required', false),
            self::PARAM_COMMENT    => new \XLite\Model\WidgetParam\String('Comment', null),
            self::PARAM_ATTRIBUTES => new \XLite\Model\WidgetParam\Collection('Attributes', array()),

            self::PARAM_IS_ALLOWED_FOR_CUSTOMER => new \XLite\Model\WidgetParam\Bool(
                'Is allowed for customer',
                $this->isAllowedForCustomer
            ),
        );
    }

    /**
     * Check field value validity
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkFieldValue()
    {
        return '' != $this->getValue();
    }

    /**
     * checkFieldValidity 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkFieldValidity()
    {
        return !$this->isRequired() || $this->checkFieldValue();
    }

    /**
     * getRequiredFieldErrorMessage 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getRequiredFieldErrorMessage()
    {
        return 'The "' . $this->getLabel() . '" field is empty';
    }

    /**
     * checkFieldAccessability 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkFieldAccessability()
    {
        return $this->getParam(self::PARAM_IS_ALLOWED_FOR_CUSTOMER) || \XLite::isAdminZone();
    }

    /**
     * callFormMethod 
     * 
     * @param string $method class method to call
     * @param array  $args   call arguments
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function callFormMethod($method, array $args = array())
    {
        return call_user_func_array(array(\XLite\View\Model\AModel::getCurrentForm(), $method), $args);
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
     * Return field value
     * 
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function getValue()
    {
        return $this->getParam(self::PARAM_VALUE);
    }

    /**
     * setValue 
     * 
     * @param mixed $value value to set
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setValue($value)
    {
        $this->getWidgetParams(self::PARAM_VALUE)->setValue($value);
    }

    /**
     * getLabel 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getLabel()
    {
        return $this->getParam(self::PARAM_LABEL);
    }

    /**
     * Return a value for the "id" attribute of the field input tag
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFieldId()
    {
        return strtolower(strtr($this->getName(), array('['=>'-', ']'=>'', '_'=>'-')));
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
        return array($this->getValidityFlag(), $this->getValidityFlag() ? null : $this->getRequiredFieldErrorMessage());
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/form_field.css';

        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->checkFieldAccessability();
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params widget params
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        if (isset($params[self::PARAM_NAME])) {
            $this->name = $params[self::PARAM_NAME];
        };

        parent::__construct($params);
    }
}


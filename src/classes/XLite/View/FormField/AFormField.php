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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\FormField;

/**
 * Abstract form field
 *
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
    const PARAM_ID         = 'fieldId';
    const PARAM_LABEL      = 'label';
    const PARAM_COMMENT    = 'comment';
    const PARAM_HELP       = 'help';
    const PARAM_FIELD_ONLY = 'fieldOnly';
    const PARAM_WRAPPER_CLASS = 'wrapperClass';

    const PARAM_IS_ALLOWED_FOR_CUSTOMER = 'isAllowedForCustomer';

    /**
     * Available field types
     */
    const FIELD_TYPE_LABEL      = 'label';
    const FIELD_TYPE_TEXT       = 'text';
    const FIELD_TYPE_PASSWORD   = 'password';
    const FIELD_TYPE_SELECT     = 'select';
    const FIELD_TYPE_CHECKBOX   = 'checkbox';
    const FIELD_TYPE_RADIO      = 'radio';
    const FIELD_TYPE_TEXTAREA   = 'textarea';
    const FIELD_TYPE_SEPARATOR  = 'separator';
    const FIELD_TYPE_ITEMS_LIST = 'itemsList';

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * validityFlag
     *
     * @var boolean
     */
    protected $validityFlag = null;

    /**
     * Determines if this field is visible for customers or not
     *
     * @var boolean
     */
    protected $isAllowedForCustomer = true;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Return field type
     *
     * @return string
     */
    abstract public function getFieldType();

    /**
     * Return field template
     *
     * @return string
     */
    abstract protected function getFieldTemplate();

    /**
     * Return field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParam(self::PARAM_NAME);
    }

    /**
     * Return field value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getParam(self::PARAM_VALUE);
    }

    /**
     * Set value
     *
     * @param mixed $value Value to set
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->getWidgetParams(self::PARAM_VALUE)->setValue($value);
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getParam(self::PARAM_LABEL);
    }

    /**
     * Return a value for the "id" attribute of the field input tag
     *
     * @return string
     */
    public function getFieldId()
    {
        return $this->getParam(self::PARAM_ID) ?: strtolower(strtr($this->getName(), array('['=>'-', ']'=>'', '_'=>'-')));
    }

    /**
     * Validate field value
     *
     * @return mixed
     */
    public function validate()
    {
        $this->setValue($this->sanitize());

        return array(
            $this->getValidityFlag(),
            $this->getValidityFlag() ? null : $this->errorMessage
        );
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/form_field.css';

        return $list;
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        if (isset($params[self::PARAM_NAME])) {
            $this->name = $params[self::PARAM_NAME];
        };

        parent::__construct($params);
    }

    /**
     * Register CSS class to use for wrapper block (SPAN) of input field.
     * It is usable to make unique changes of the field.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return $this->getParam(self::PARAM_WRAPPER_CLASS);
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        if (isset($params['value'])) {
            $this->setValue($params['value']);
        }
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'form_field.tpl';
    }

    /**
     * Return name of the folder with templates
     *
     * @return string
     */
    protected function getDir()
    {
        return 'form_field';
    }

    /**
     * checkSavedValue
     *
     * @return boolean
     */
    protected function checkSavedValue()
    {
        return !is_null($this->callFormMethod('getSavedData', array($this->getName())));
    }

    /**
     * Get validity flag (and run field validation procedire)
     *
     * @return boolean
     */
    protected function getValidityFlag()
    {
        if (!isset($this->validityFlag)) {
            $this->validityFlag = $this->checkFieldValidity();
        }

        return $this->validityFlag;
    }

    /**
     * Sanitize value
     *
     * @return mixed
     */
    protected function sanitize()
    {
       return $this->getValue();
    }

    /**
     * getCommonAttributes
     *
     * @return array
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
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     */
    protected function setCommonAttributes(array $attrs)
    {
        foreach ($this->getCommonAttributes() as $name => $value) {
            if (!isset($attrs[$name])) {
                $attrs[$name] = $value;
            }
        }

        if (!isset($attrs['class'])) {
            $attrs['class'] = '';
        }
        $classes = preg_grep('/.+/S', array_map('trim', explode(' ', $attrs['class'])));
        $classes = $this->assembleClasses($classes);
        $attrs['class'] = implode(' ', $classes);

        return $attrs;
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     */
    protected function assembleClasses(array $classes)
    {
        $validationRules = $this->assembleValidationRules();
        if ($validationRules) {
            $classes[] = 'validate[' . implode(',', $validationRules) . ']';
        }

        return $classes;
    }

    /**
     * Assemble validation rules
     *
     * @return array
     */
    protected function assembleValidationRules()
    {
        return $this->isRequired() ? array('required') : array();
    }

    /**
     * prepareAttributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
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
     * @return boolean
     */
    protected function isRequired()
    {
        return $this->getParam(self::PARAM_REQUIRED);
    }

    /**
     * getAttributes
     *
     * @return array
     */
    protected function getAttributes()
    {
        return $this->prepareAttributes($this->getParam(self::PARAM_ATTRIBUTES));
    }

    /**
     * Return HTML representation for widget attributes
     *
     * @return string
     */
    protected function getAttributesCode()
    {
        $result = '';

        foreach ($this->getAttributes() as $name => $value) {
            $result .= ' ' . $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return $result;
    }

    /**
     * Some JavaScript code to insert
     *
     * @todo   Remove it. Use getFormFieldJSData method instead.
     * @return string
     */
    protected function getInlineJSCode()
    {
        return null;
    }

    /**
     * getDefaultName
     *
     * @return string
     */
    protected function getDefaultName()
    {
        return null;
    }

    /**
     * getDefaultValue
     *
     * @return string
     */
    protected function getDefaultValue()
    {
        return isset($this->name) ? $this->callFormMethod('getDefaultFieldValue', array($this->name)) : null;
    }

    /**
     * getDefaultLabel
     *
     * @return string
     */
    protected function getDefaultLabel()
    {
        return null;
    }

    /**
     * Get default attributes
     *
     * @return array
     */
    protected function getDefaultAttributes()
    {
        return array();
    }

    /**
     * Getter for Field-only flag
     *
     * @return boolean
     */
    protected function getDefaultParamFieldOnly()
    {
        return false;
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ID         => new \XLite\Model\WidgetParam\String('Id', ''),
            self::PARAM_NAME       => new \XLite\Model\WidgetParam\String('Name', $this->getDefaultName()),
            self::PARAM_VALUE      => new \XLite\Model\WidgetParam\String('Value', $this->getDefaultValue()),
            self::PARAM_LABEL      => new \XLite\Model\WidgetParam\String('Label', $this->getDefaultLabel()),
            self::PARAM_REQUIRED   => new \XLite\Model\WidgetParam\Bool('Required', false),
            self::PARAM_COMMENT    => new \XLite\Model\WidgetParam\String('Comment', null),
            self::PARAM_HELP       => new \XLite\Model\WidgetParam\String('Help', null),
            self::PARAM_ATTRIBUTES => new \XLite\Model\WidgetParam\Collection('Attributes', $this->getDefaultAttributes()),
            self::PARAM_WRAPPER_CLASS => new \XLite\Model\WidgetParam\String('Wrapper class', $this->getDefaultWrapperClass()),

            self::PARAM_IS_ALLOWED_FOR_CUSTOMER => new \XLite\Model\WidgetParam\Bool(
                'Is allowed for customer',
                $this->isAllowedForCustomer
            ),
            self::PARAM_FIELD_ONLY    => new \XLite\Model\WidgetParam\Bool(
                'Skip wrapping with label and required flag, display just a field itself',
                $this->getDefaultParamFieldOnly()
            ),
        );
    }

    /**
     * Check field value validity
     *
     * @return boolean
     */
    protected function checkFieldValue()
    {
        return '' != $this->getValue();
    }

    /**
     * Check field validity
     *
     * @return boolean
     */
    protected function checkFieldValidity()
    {
        $result = true;
        $this->errorMessage = null;

        if ($this->isRequired() && !$this->checkFieldValue()) {
            $this->errorMessage = $this->getRequiredFieldErrorMessage();
            $result = false;
        }

        return $result;
    }

    /**
     * Get required field error message
     *
     * @return string
     */
    protected function getRequiredFieldErrorMessage()
    {
        return \XLite\Core\Translation::lbl('The X field is empty', array('name' => $this->getLabel()));
    }

    /**
     * checkFieldAccessability
     *
     * @return boolean
     */
    protected function checkFieldAccessability()
    {
        return $this->getParam(self::PARAM_IS_ALLOWED_FOR_CUSTOMER) || \XLite::isAdminZone();
    }

    /**
     * callFormMethod
     *
     * @param string $method Class method to call
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     */
    protected function callFormMethod($method, array $args = array())
    {
        $form = \XLite\View\Model\AModel::getCurrentForm();

        return $form
            ? call_user_func_array(array($form, $method), $args)
            : null;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->checkFieldAccessability();
    }

    /**
     * Get default wrapper class
     *
     * @return string
     */
    protected function getDefaultWrapperClass()
    {
        $suffix = preg_replace('/^.+\\\(?:Module\\\([a-zA-Z0-9]+\\\[a-zA-Z0-9]+\\\))?View\\\FormField\\\(.+)$/Ss', '$1$2', get_called_class());
        $suffix = str_replace('\\', '-', strtolower($suffix));

        return 'input ' . $suffix;
    }

    /**
     * Get label container class
     *
     * @return string
     */
    protected function getLabelContainerClass()
    {
        return 'table-label ' . $this->getFieldId() . '-label';
    }

    /**
     * Get value container class
     *
     * @return string
     */
    protected function getValueContainerClass()
    {
        return 'table-value ' . $this->getFieldId() . '-value';
    }

    /**
     * Return some data for JS external scripts if it is needed.
     *
     * @return null|array
     */
    protected function getFormFieldJSData()
    {
        return null;
    }
}

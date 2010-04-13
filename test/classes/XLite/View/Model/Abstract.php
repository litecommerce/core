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
 * XLite_View_Model 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_View_Model_Abstract extends XLite_View_Dialog
{
    /**
     * Widget param names
     */

    const PARAM_MODEL_OBJECT  = 'modelObject';
    const PARAM_FIELDSET_NAME = 'fieldsetName';

    /**
     * Indexes in field schemas 
     */

    const SCHEMA_CLASS      = 'class';
    const SCHEMA_VALUE      = 'value';
    const SCHEMA_REQUIRED   = 'required';
    const SCHEMA_ATTRIBUTES = 'attributes';
    const SCHEMA_NAME       = 'name';
    const SCHEMA_LABEL      = 'label';
    const SCHEMA_COMMENT    = 'comment';
    

    /**
     * Unique name of current web form
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $formName = null;

    /**
     * List of form fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $formFields = null;


    /**
     * getDefaultModelObjectClass 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultModelObjectClass();

    /**
     * getDefaultModelObjectKeys 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultModelObjectKeys();

    /**
     * Return name of web form widget class
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getFormClass();

   
    /**
     * getFormContentTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormContentTemplate()
    {
        return 'form_content.tpl';
    }
 
    /**
     * Return file name for body template
     *
     * @return id
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return $this->isExported() ? $this->getFormContentTemplate() : parent::getBodyTemplate();
    }

    /**
     * composeFieldName 
     * 
     * @param string $name name to prepare
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function composeFieldName($name)
    {
        $fieldsetName = $this->getFieldsetName();

        if (!empty($fieldsetName)) {
            $name = $fieldsetName . '[' . $name . ']';
        }

        return $name;
    }

    /**
     * getDefaultFieldsetName 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultFieldsetName()
    {
        return 'postedData';
    }

    /**
     * getFieldsetName 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldsetName()
    {
        return $this->getParam(self::PARAM_FIELDSET_NAME);
    }

    /**
     * getDefaultModelObjectSignature 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObjectSignature()
    {
        return __METHOD__ . $this->getDefaultModelObjectClass();
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObject()
    {
        return XLite_Model_CachingFactory::getObject(
            $this->getDefaultModelObjectSignature(),
            $this->getDefaultModelObjectClass(),
            $this->getDefaultModelObjectKeys()
        );
    }

    /**
     * Return model object to use
     * 
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getParam(self::PARAM_MODEL_OBJECT); 
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'model';
    }

    /**
     * Return form templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormDir()
    {
        return 'form';
    }

    /**
     * Define form field classes and values 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineFormFields()
    {
        $this->formFields = array();
    }

    /**
     * Generate unique name for the current form widget
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function generateFormName()
    {
        return uniqid();
    }

    /**
     * Return unique name for the current form widget (this name is used by Flexy compiler)
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        if (!isset($this->formName)) {
            $this->formName = $this->generateFormName();
        }

        return $this->formName;
    }

    /**
     * Ret urn list of web form widget params
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParams()
    {
        return array();
    }

    /**
     * Retrieve property from th model object
     * 
     * @param string $field field/property name
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldValue($field)
    {
        return $this->getModelObject()->get($field);
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
            self::PARAM_MODEL_OBJECT => new XLite_Model_WidgetParam_Object(
                'Object', $this->getDefaultModelObject(), false, $this->getDefaultModelObjectClass()
            ),
            self::PARAM_FIELDSET_NAME => new XLite_Model_WidgetParam_String(
                'Fieldset name', $this->getDefaultFieldsetName() 
            ),
        );
    }

    /**
     * getFieldSchemaArgs 
     * 
     * NOTE: keep this function synchronized 
     * with the XLite_View_FormField_Abstract::defineFieldParams() one
     * 
     * @param string $name node name
     * @param array  $data field description
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldSchemaArgs($name, array $data)
    {
        return array(
            array(),
            isset($data[self::SCHEMA_NAME]) ? $data[self::SCHEMA_NAME] : $this->composeFieldName($name),
            isset($data[self::SCHEMA_VALUE]) ? $data[self::SCHEMA_VALUE] : $this->getFieldValue($name),
            empty($data[self::SCHEMA_LABEL]) ? '' : $data[self::SCHEMA_LABEL],
            !empty($data[self::SCHEMA_REQUIRED]),
            empty($data[self::SCHEMA_COMMENT]) ? '' : $data[self::SCHEMA_COMMENT],
            empty($data[self::SCHEMA_ATTRIBUTES]) ? array() : $data[self::SCHEMA_ATTRIBUTES],
        );
    }


    /**
     * getFieldsBySchema 
     * 
     * @param array $schema field descriptions
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFieldsBySchema(array $schema)
    {
        $result = array();

        foreach ($schema as $name => $data) {

            $result[$name] = XLite_Model_Factory::createObjectInstance(
                $data[self::SCHEMA_CLASS], $this->getFieldSchemaArgs($name, $data)
            );
        }

        return $result;
    }

    /**
     * Return list of form fields
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFields()
    {
        if (!isset($this->formFields)) {
            $this->defineFormFields();
        }

        return $this->formFields;
    }

    /**
     * getErrorMessages 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getErrorMessages()
    {
        $messages = array();

        foreach ($this->getFormFields() as $key => $field) {
            if ($result = $field->validate()) {
                $messages[$key] = $result;
            }
        }

        return $messages;
    }

    /**
     * isValid 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isValid()
    {
        return !((bool) $this->getErrorMessages());
    }
}


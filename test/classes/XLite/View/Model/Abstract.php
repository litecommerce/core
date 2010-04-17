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
     *
     * FIXME: keep this list synchronized with the classes,
     * derived from the XLite_View_FormField_Abstract
     */

    const SCHEMA_CLASS      = 'class';
    const SCHEMA_VALUE      = XLite_View_FormField_Abstract::PARAM_VALUE;
    const SCHEMA_REQUIRED   = XLite_View_FormField_Abstract::PARAM_REQUIRED;
    const SCHEMA_ATTRIBUTES = XLite_View_FormField_Abstract::PARAM_ATTRIBUTES;
    const SCHEMA_NAME       = XLite_View_FormField_Abstract::PARAM_NAME;
    const SCHEMA_LABEL      = XLite_View_FormField_Abstract::PARAM_LABEL;
    const SCHEMA_COMMENT    = XLite_View_FormField_Abstract::PARAM_COMMENT;

    const SCHEMA__OPTIONS   = XLite_View_FormField_Select_Abstract::PARAM_OPTIONS;

    /**
     * Session cell to store form data 
     */

    const SAVED_FORM        = 'savedForm';
    const SAVED_FORM_PARAMS = 'savedFormParams';
    const SAVED_FORM_DATA   = 'savedFormData';


    /**
     * currentForm 
     * 
     * @var    XLite_View_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected static $currentForm = null;


    /**
     * List of form fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $formFields = null;

    /**
     * errorMessages 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $errorMessages = null;

    /**
     * savedData 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $savedData;

    /**
     * keyParams 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $keyParams = array(
        self::PARAM_FIELDSET_NAME,
    );


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
     * Return file name for body template
     *
     * @return id
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return 'form_content.tpl';
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
     * getFormName
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return get_class($this);
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
    protected function getDefaultFieldValue($field)
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
     * FIXME. You understand :)
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
        if (!isset($data[self::SCHEMA_NAME])) {
            $data[self::SCHEMA_NAME] = $this->composeFieldName($name);
        }

        if (!isset($data[self::SCHEMA_VALUE])) {
            $data[self::SCHEMA_VALUE] = $this->getDefaultFieldValue($name);
        }

        // ...

        return $data;
    }

    /**
     * setModelProperties 
     * 
     * @param array $data data to set
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setModelProperties(array $data)
    {
        $this->getModelObject()->setProperties($data);
    }

    /**
     * prepareFormDataToSave 
     * 
     * @param array $data data to save
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function prepareFormDataToSave(array $data)
    {
        return XLite_Core_Converter::getInstance()->flatArray($data);
    }

    /**
     * getSavedForm 
     * 
     * @param string $field data field to return
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getSavedForm($field = null)
    {
        $data = XLite_Model_Session::getInstance()->get(self::SAVED_FORM);
        $name = $this->getFormName();

        return isset($data[$name][$field]) ? $data[$name][$field] : array();
    }

    /**
     * getFormSavedData 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormSavedData()
    {
        return $this->getSavedForm(self::SAVED_FORM_DATA);
    }

    /**
     * getFormSavedParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormSavedParams()
    {
        return $this->getSavedForm(self::SAVED_FORM_PARAMS);
    }

    /**
     * saveFormData 
     * 
     * @param mixed $data data to save
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function saveFormData($data)
    {
        $savedData = $this->getFormSavedData();

        if (isset($data)) {
            $savedData[$this->getFormName()] = array(
                self::SAVED_FORM_PARAMS => $this->getParamsHash($this->keyParams),
                self::SAVED_FORM_DATA   => $this->prepareFormDataToSave($data),
            );
        } else {
            unset($savedData[$this->getFormName()]);
        }

        XLite_Model_Session::getInstance()->set(self::SAVED_FORM, empty($savedData) ? null : $savedData);
    }

    /**
     * clearFormData 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function clearFormData()
    {
        $this->saveFormData(null);
    }

    /**
     * Save form state in session 
     * 
     * @param array $data form fields
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function saveFormErrors(array $data)
    {
        $this->saveFormData($data);
    }

    /**
     * Perform some action on success 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function success()
    {
    }

    /**
     * startCurrentForm 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function startCurrentForm()
    {
        self::$currentForm = $this;
    }

    /**
     * Called after the includeCompiledFile()
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function closeView()
    {
        parent::closeView();

        $this->clearFormData();
    }


    /**
     * getCurrentForm 
     * 
     * @return XLite_View_Model_Abstract
     * @access public
     * @since  3.0.0
     */
    public static function getCurrentForm()
    {
        return self::$currentForm;
    }


    /**
     * __construct 
     * 
     * @param array $params widget params
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params + $this->getFormSavedParams());

        $this->startCurrentForm();
        $this->savedData = $this->getFormSavedData();
    }


    /**
     * getSavedFieldValue 
     * 
     * NOTE: do not use the getFormSavedData() function:
     * it will decrease the perfomance
     * 
     * @param string $name field name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function getSavedFieldValue($name)
    {
        return isset($this->savedData[$name]) ? $this->savedData[$name] : null;
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
            $class = $data[self::SCHEMA_CLASS];
            $result[$name] = new $class($this->getFieldSchemaArgs($name, $data));
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
        if (!isset($this->errorMessages)) {
            $this->errorMessages = array();

            foreach ($this->getFormFields() as $field) {
                list($flag, $message) = $field->validate();

                if (!$flag) {
                    $this->errorMessages[$field->getName()] = $message;
                }
            }
        }

        return $this->errorMessages;
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

    /**
     * performAction 
     * 
     * @param string $action action to perform
     * @param array  $data   form data
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function performAction($action, array $data = array())
    {
        if (empty($data)) {
            $data = XLite_Core_Request::getInstance()->getData();
        }

        $fieldset = $this->getFieldsetName();
        if (isset($data[$fieldset]) && is_array($data[$fieldset])) {
            $properties = $data[$fieldset];
        }

        $this->setModelProperties($properties);

        if ($result = $this->isValid()) {
            call_user_func_array(array($this, 'performAction' . ucfirst($action)), array($properties));
            $this->success();
        } else {
            $this->saveFormErrors($data);
        }

        return $result;
    }

    /**
     * performActionCreate 
     * 
     * @param array $data model properties
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function performActionCreate(array $data = array())
    {
        $this->getModelObject()->create();
    }

    /**
     * performActionUpdate 
     * 
     * @param array $data model properties
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function performActionUpdate(array $data = array())
    {
        $this->getModelObject()->update();
    }

    /**
     * performActionModify 
     * 
     * @param array $data model properties
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function performActionModify(array $data = array())
    {
        $this->getModelObject()->modify();
    }

    /**
     * performActionDelete 
     * 
     * @param array $data model properties
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function performActionDelete(array $data = array())
    {
        $this->getModelObject()->delete();
    }
}


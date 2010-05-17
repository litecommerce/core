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
 * Abstract model widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_View_Model_Abstract extends XLite_View_Dialog
{
    /**
     * Widget param names
     */

    const PARAM_MODEL_OBJECT  = 'modelObject';
    const PARAM_FIELDSET_NAME = 'fieldsetName';

    /**
     * Fieldset default name 
     */

    const FIELDSET_DEFAULT_NAME = 'postedData';

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

    const SCHEMA_OPTIONS = XLite_View_FormField_Select_Abstract::PARAM_OPTIONS;

    /**
     * Session cell to store form data 
     */

    const SAVED_FORMS     = 'savedForms';
    const SAVED_FORM_DATA = 'savedFormData';


    /**
     * Current form object
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
     * Form error messages cache 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $errorMessages = null;

    /**
     * Form saved data cache
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $savedData;


    /**
     * Model class associated with the form 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultModelObjectClass();

    /**
     * List of model primary keys 
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
     * Add (if required) an additional part to the form name
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
     * Base part of the field name (default value)
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultFieldsetName()
    {
        return self::FIELDSET_DEFAULT_NAME;
    }

    /**
     * Base part of the field name
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
     * Index in global cache for the model object
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
     * Return name of the current form
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
     * Return list of web form widget params
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
     * Perform some operations when creating fiels list by schema
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
     * Check data and return only ones for the current fieldset 
     * 
     * @param array $data data to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldsetData(array $data)
    {
        $fieldset = $this->getFieldsetName();

        if (isset($data[$fieldset]) && is_array($data[$fieldset])) {
            $data = $data[$fieldset];
        }

        return $data;
    }

    /**
     * Populate model object properties by the passed data 
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
     * Transform multi-dimensional data array into the "flat" one
     * 
     * @param array $data data to save
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function prepareFormDataToSave(array $data)
    {
        return XLite_Core_Converter::flatArray($data);
    }

    /**
     * Fetch saved forms data from session
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getSavedForms()
    {
        return XLite_Model_Session::getInstance()->get(self::SAVED_FORMS);
    }

    /**
     * Return saved data for current form (all or certain field(s))
     * 
     * @param string $field data field to return
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getSavedForm($field = null)
    {
        $data = $this->getSavedForms();
        $name = $this->getFormName();

        $data = isset($data[$name]) ? $data[$name] : array();

        if (isset($field) && isset($data[$field])) {
            $data = $data[$field];
        }

        return $data;
    }

    /**
     * Return fields' saved values for current form (saved data itself)
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
     * Save form fields in session 
     * 
     * @param mixed $data data to save
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function saveFormData($data)
    {
        $savedData = $this->getSavedForms();

        if (isset($data)) {
            $savedData[$this->getFormName()] = array(
                self::SAVED_FORM_DATA => $this->prepareFormDataToSave($data),
            );
        } else {
            unset($savedData[$this->getFormName()]);
        }

        XLite_Model_Session::getInstance()->set(self::SAVED_FORMS, empty($savedData) ? null : $savedData);
    }

    /**
     * Clear form fields in session
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
     * Perform some actions on success 
     * 
     * @param bool $setTopMessages set or not top messages
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function postprocessSuccessAction($setTopMessages = true)
    {
        if ($setTopMessages) {
            XLite_Core_TopMessage::getInstance()->add(
                'Data have been saved successfully',
                XLite_Core_TopMessage::INFO
            );
        }

        $this->setActionSuccess();
    }

    /**
     * Perform some action on error
     * 
     * @param bool $setTopMessages set or not top messages
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function postprocessErrorAction($setTopMessages = true)
    {
        if ($setTopMessages) {
            XLite_Core_TopMessage::getInstance()->addBatch(
                $this->getErrorMessages(),
                XLite_Core_TopMessage::ERROR
            );
        }

        $this->setActionError();
    }

    /**
     * Save reference to the current form 
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
     * Return list of form fields objects by schema
     *
     * @param array $schema field descriptions
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldsBySchema(array $schema)
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
     * @access protected
     * @since  3.0.0
     */
    protected function getFormFields()
    {
        if (!isset($this->formFields)) {
            $this->defineFormFields();
        }

        return $this->formFields;
    }

    /**
     * Return list of form error messages
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getErrorMessages()
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
     * Call the corresponded method for current action
     * 
     * @param string $action action name
     * @param array  $data   data passed
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function callActionHandler($action, array $data = array())
    {
        $action = 'performAction' . ucfirst($action);

        // Variable name = 'performAction' + ucfirst($action)
        return $this->$action($data);
    }

    /**
     * Perform certain action for the model object
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionCreate(array $data = array())
    {
        return $this->getModelObject()->create();
    }

    /**
     * Perform certain action for the model object
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate(array $data = array())
    {
        return $this->getModelObject()->update();
    }

    /**
     * Perform certain action for the model object 
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionModify(array $data = array())
    {
        return $this->getModelObject()->modify();
    }

    /**
     * Perform certain action for the model object
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionDelete(array $data = array())
    {
        return $this->getModelObject()->delete();
    }


    /**
     * Return reference to the current form object
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
     * Check for the form errors
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
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() || $this->isExported();
    }

    /**
     * Return saved value fior the certain form field
     * NOTE: do not use the getFormSavedData() function: it will decrease the perfomance
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
     * Perform some action for the model object
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

        $properties = $this->getFieldsetData($data);
        $this->setModelProperties($properties);

        $result = $this->isValid();
        if ($result) {

            $result = $this->callActionHandler($action, $properties);
            if ($result) {
                $this->postprocessSuccessAction();
            }

        } else {

            $this->saveFormData($data);
            $this->postprocessErrorAction();
        }

        return $result;
    }

    /**
     * Save current form reference and initialize the cache
     *
     * @param array $params widget params
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->startCurrentForm();
        $this->savedData = $this->getFormSavedData();
    }
}


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

namespace XLite\View\Model;

/**
 * Abstract model widget
 *
 */
abstract class AModel extends \XLite\View\Dialog
{
    /**
     * Widget param names
     */
    const PARAM_MODEL_OBJECT      = 'modelObject';
    const PARAM_USE_BODY_TEMPLATE = 'useBodyTemplate';

    /**
     * Indexes in field schemas
     *
     * FIXME: keep this list synchronized with the classes,
     * derived from the \XLite\View\FormField\AFormField
     */
    const SCHEMA_CLASS      = 'class';
    const SCHEMA_VALUE      = \XLite\View\FormField\AFormField::PARAM_VALUE;
    const SCHEMA_REQUIRED   = \XLite\View\FormField\AFormField::PARAM_REQUIRED;
    const SCHEMA_ATTRIBUTES = \XLite\View\FormField\AFormField::PARAM_ATTRIBUTES;
    const SCHEMA_NAME       = \XLite\View\FormField\AFormField::PARAM_NAME;
    const SCHEMA_LABEL      = \XLite\View\FormField\AFormField::PARAM_LABEL;
    const SCHEMA_COMMENT    = \XLite\View\FormField\AFormField::PARAM_COMMENT;
    const SCHEMA_HELP       = \XLite\View\FormField\AFormField::PARAM_HELP;

    const SCHEMA_OPTIONS = \XLite\View\FormField\Select\ASelect::PARAM_OPTIONS;
    const SCHEMA_IS_CHECKED = \XLite\View\FormField\Input\Checkbox::PARAM_IS_CHECKED;

    const SCHEMA_MODEL_ATTRIBUTES = 'model_attributes';

    /**
     * Session cell to store form data
     */
    const SAVED_FORMS     = 'savedForms';
    const SAVED_FORM_DATA = 'savedFormData';

    /**
     * Form sections
     */
    // Title for this section will not be dispalyed
    const SECTION_DEFAULT = 'default';
    // This section will not be displayed
    const SECTION_HIDDEN  = 'hidden';

    /**
     * Indexes in the "formFields" array
     */
    const SECTION_PARAM_WIDGET = 'sectionParamWidget';
    const SECTION_PARAM_FIELDS = 'sectionParamFields';

    /**
     * Name prefix of the methods to handle actions
     */
    const ACTION_HANDLER_PREFIX = 'performAction';

    /**
     * Current form object
     *
     * @var \XLite\View\Model\AModel
     */
    protected static $currentForm = null;

    /**
     * List of form fields
     *
     * @var array
     */
    protected $formFields = null;

    /**
     * Names of the form fields (hash)
     *
     * @var array
     */
    protected $formFieldNames = array();

    /**
     * Form error messages cache
     *
     * @var array
     */
    protected $errorMessages = null;

    /**
     * Form saved data cache
     *
     * @var array
     */
    protected $savedData = null;

    /**
     * Available form sections
     *
     * @var array
     */
    protected $sections = array(
        self::SECTION_DEFAULT => null,
        self::SECTION_HIDDEN  => null,
    );

    /**
     * Current action
     *
     * @var string
     */
    protected $currentAction = null;

    /**
     * Data from request
     *
     * @var array
     */
    protected $requestData = null;

    /**
     * shemaDefault
     *
     * @var array
     */
    protected $shemaDefault = array();

    /**
     * schemaHidden
     *
     * @var array
     */
    protected $schemaHidden = array();

    /**
     * The list of fields (fiel names) that must be excluded from the array(data) for mapping to the object
     *
     * @var array
     */
    protected $excludedFields = array();

    /**
     * This object will be used if another one is not passed
     *
     * @return \XLite\Model\AEntity
     */
    abstract protected function getDefaultModelObject();

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    abstract protected function getFormClass();

    /**
     * Get instance to the current form object
     *
     * @return void
     */
    public static function getCurrentForm()
    {
        return self::$currentForm;
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        if (!empty($sections)) {
            $this->sections = \Includes\Utils\ArrayManager::filterByKeys($this->sections, $sections);
        }

        parent::__construct($params);

        $this->startCurrentForm();
    }

    /**
     * Retrieve property from the request or from  model object
     *
     * @param string $name Field/property name
     *
     * @return mixed
     */
    public function getDefaultFieldValue($name)
    {
        $value = $this->getSavedData($name);

        if (!isset($value)) {
            $value = $this->getRequestData($name);

            if (!isset($value)) {
                $value = $this->getModelObjectValue($name);
            }
        }

        return $value;
    }

    /**
     * Check for the form errors
     *
     * @return boolean
     */
    public function isValid()
    {
        return !((bool) $this->getErrorMessages());
    }

    /**
     * Perform some action for the model object
     *
     * @param string $action Action to perform
     * @param array  $data   Form data OPTIONAL
     *
     * @return boolean
     */
    public function performAction($action, array $data = array())
    {
        // Save some data
        $this->currentAction = $action;
        $this->defineRequestData($data);

        $requestData = $this->prepareDataForMapping();

        // Map model object with the request data
        $this->setModelProperties($requestData);

        // Do not call "callActionHandler()" method if model object is not valid
        $result = $this->isValid() && $this->callActionHandler();

        if ($result) {
            $this->postprocessSuccessAction();

        } else {
            $this->rollbackModel();
            $this->saveFormData($requestData);
            $this->postprocessErrorAction();
        }

        return $result;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/model.css';

        return $list;
    }

    /**
     * Return fields' saved values for current form (saved data itself)
     *
     * @param string $name Parameter name OPTIONAL
     *
     * @return array
     */
    public function getSavedData($name = null)
    {
        if (!isset($this->savedData)) {
            $this->savedData = $this->getSavedForm(self::SAVED_FORM_DATA);
        }

        return isset($name)
            ? (isset($this->savedData[$name]) ? $this->savedData[$name] : null)
            : $this->savedData;
    }

    /**
     * getRequestData
     *
     * @param string $name Index in the request data OPTIONAL
     *
     * @return mixed
     */
    public function getRequestData($name = null)
    {
        if (!isset($this->requestData)) {
            $this->defineRequestData(array(), $name);
        }

        return isset($name)
            ? (isset($this->requestData[$name]) ? $this->requestData[$name] : null)
            : $this->requestData;
    }

    /**
     * setRequestData
     *
     * @param string $name  Index in the request data
     * @param mixed  $value Value to set
     *
     * @return void
     */
    public function setRequestData($name, $value)
    {
        $this->requestData[$name] = $value;
    }

    /**
     * Return model object to use
     *
     * @return \XLite\Model\AEntity
     */
    public function getModelObject()
    {
        return $this->getParam(self::PARAM_MODEL_OBJECT);
    }


    /**
     * Check if current form is accessible
     *
     * @return boolean
     */
    protected function checkAccess()
    {
        return true;
    }

    /**
     * Return file name for body template
     *
     * @return string
     */
    protected function getBodyTemplate()
    {
        return $this->checkAccess() ? parent::getBodyTemplate() : 'access_denied.tpl';
    }

    /**
     * getAccessDeniedMessage
     *
     * @return string
     */
    protected function getAccessDeniedMessage()
    {
        return 'Access denied';
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'model';
    }

    /**
     * getFormDir
     *
     * @param string $template Template file basename OPTIONAL
     *
     * @return string
     */
    protected function getFormDir($template = null)
    {
        return 'form';
    }

    /**
     * Return form templates directory name
     *
     * @param string $template Template file base name
     *
     * @return void
     */
    protected function getFormTemplate($template)
    {
        return $this->getFormDir($template) . '/' . $template . '.tpl';
    }

    /**
     * Return list of form fields for certain section
     *
     * @param string $section Section name
     *
     * @return array
     */
    protected function getFormFieldsForSection($section)
    {
        $method = __FUNCTION__ . ucfirst($section);

        // Return the method getFormFieldsForSection<SectionName>
        return method_exists($this, $method) ? $this->$method() : $this->translateSchema($section);
    }

    /**
     * Define form field classes and values
     *
     * @return void
     */
    protected function defineFormFields()
    {
        $this->formFields = array();

        foreach ($this->sections as $section => $label) {

            $widget = new \XLite\View\FormField\Separator\Regular(
                array(self::SCHEMA_LABEL => $label)
            );

            $this->formFields[$section] = array(
                self::SECTION_PARAM_WIDGET => $widget,
                self::SECTION_PARAM_FIELDS => $this->getFormFieldsForSection($section),
            );
        }
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $object = $this->getDefaultModelObject();

        $this->widgetParams += array(
            self::PARAM_MODEL_OBJECT => new \XLite\Model\WidgetParam\Object(
                'Object', $object, false, $object ? get_class($object) : ''
            ),
            self::PARAM_USE_BODY_TEMPLATE => new \XLite\Model\WidgetParam\Bool(
                'Use default body template', false
            ),
        );
    }

    /**
     * useBodyTemplate
     *
     * @return void
     */
    protected function useBodyTemplate()
    {
        return $this->getParam(self::PARAM_USE_BODY_TEMPLATE) ? true : parent::useBodyTemplate();
    }

    /**
     * Flag if the panel widget for buttons is used
     *
     * @return boolean
     */
    protected function useButtonPanel()
    {
        return !is_null($this->getButtonPanelClass());
    }

    /**
     * Return class of button panel widget
     *
     * @return string
     */
    protected function getButtonPanelClass()
    {
        return null;
    }

    /**
     * Add (if required) an additional part to the form name
     *
     * @param string $name Name to prepare
     *
     * @return string
     */
    protected function composeFieldName($name)
    {
        return $name;
    }

    /**
     * Return model field name for a provided form field name
     *
     * @param string $name Name of form field
     *
     * @return string
     */
    protected function getModelFieldName($name)
    {
        return $name;
    }

    /**
     * Return field mappings structure for the model
     *
     * @return array
     */
    protected function getFieldMappings()
    {
        if (!isset($this->fieldMappings)) {

            // Collect metadata for fields of class and its translation class if there is one.
            $metaData = \XLite\Core\Database::getEM()->getClassMetadata(get_class($this->getModelObject()));
            $this->fieldMappings = $metaData->fieldMappings;

            $metaDataTranslationClass = isset($metaData->associationMappings['translations'])
                ? $metaData->associationMappings['translations']['targetEntity']
                : false;

            if ($metaDataTranslationClass) {

                $metaDataTranslation = \XLite\Core\Database::getEM()->getClassMetadata($metaDataTranslationClass);
                $this->fieldMappings += $metaDataTranslation->fieldMappings;
            }
        }

        return $this->fieldMappings;
    }

    /**
     * Return field mapping info for a given $name key
     *
     * @param string $name
     *
     * @return array|null
     */
    protected function getFieldMapping($name)
    {
        $fieldMappings = $this->getFieldMappings();

        $fieldName = $this->getModelFieldName($name);

        return $fieldMappings[$fieldName] ?: null;
    }

    /**
     * Return widget attributes that are collected from the model properties
     *
     * @param string $name
     * @param array  $data
     *
     * @return array
     */
    protected function getModelAttributes($name, array $data)
    {
        $fieldMapping = $this->getFieldMapping($name);

        $result = array();

        if ($fieldMapping) {

            foreach ($data[static::SCHEMA_MODEL_ATTRIBUTES] as $widgetAttribute => $modelAttribute) {

                if (isset($fieldMapping[$modelAttribute])) {

                    $result[$widgetAttribute] = $fieldMapping[$modelAttribute];
                }
            }
        }

        return $result;
    }

    /**
     * Perform some operations when creating fields list by schema
     *
     * @param string $name Node name
     * @param array  $data Field description
     *
     * @return array
     */
    protected function getFieldSchemaArgs($name, array $data)
    {
        if (!isset($data[static::SCHEMA_NAME])) {
            $data[static::SCHEMA_NAME] = $this->composeFieldName($name);
        }

        $data[static::SCHEMA_VALUE] = $this->getDefaultFieldValue($name);

        $data[static::SCHEMA_ATTRIBUTES] = !empty($data[static::SCHEMA_ATTRIBUTES]) ? $data[static::SCHEMA_ATTRIBUTES] : array();
        $data[static::SCHEMA_ATTRIBUTES] += isset($data[static::SCHEMA_MODEL_ATTRIBUTES]) ? $this->getModelAttributes($name, $data) : array();

        return $data;
    }

    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     */
    protected function setModelProperties(array $data)
    {
        $this->prepareObjectForMapping()->map($data);
    }

    /**
     * Transform multi-dimensional data array into the "flat" one
     *
     * @param array $data Data to save
     *
     * @return array
     */
    protected function prepareFormDataToSave(array $data)
    {
        return \XLite\Core\Converter::convertTreeToFlatArray($data);
    }

    /**
     * Fetch saved forms data from session
     *
     * @return array
     */
    protected function getSavedForms()
    {
        return \XLite\Core\Session::getInstance()->get(self::SAVED_FORMS);
    }

    /**
     * Return saved data for current form (all or certain field(s))
     *
     * @param string $field Data field to return OPTIONAL
     *
     * @return array
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
     * Save form fields in session
     *
     * @param mixed $data Data to save
     *
     * @return void
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

        \XLite\Core\Session::getInstance()->set(self::SAVED_FORMS, empty($savedData) ? null : $savedData);
    }

    /**
     * Clear form fields in session
     *
     * @return void
     */
    protected function clearFormData()
    {
        $this->saveFormData(null);
    }

    /**
     * Add top message
     *
     * @return void
     */
    protected function addDataSavedTopMessage()
    {
        \XLite\Core\TopMessage::addInfo('Data have been saved successfully');
    }

    /**
     * Add top message
     *
     * @return void
     */
    protected function addDataDeletedTopMessage()
    {
        \XLite\Core\TopMessage::addInfo('Data have been deleted successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     */
    protected function postprocessSuccessActionCreate()
    {
        $this->addDataSavedTopMessage();
    }

    /**
     * Perform some actions on success
     *
     * @return void
     */
    protected function postprocessSuccessActionUpdate()
    {
        $this->addDataSavedTopMessage();
    }

    /**
     * Perform some actions on success
     *
     * @return void
     */
    protected function postprocessSuccessActionModify()
    {
        $this->addDataSavedTopMessage();
    }

    /**
     * Perform some actions on success
     *
     * @return void
     */
    protected function postprocessSuccessActionDelete()
    {
        $this->addDataDeletedTopMessage();
    }

    /**
     * Perform some actions on success
     *
     * @return void
     */
    protected function postprocessSuccessAction()
    {
        $method = __FUNCTION__ . ucfirst($this->currentAction);

        if (method_exists($this, $method)) {
            // Run the corresponded function
            $this->$method();
        }

        $this->setActionSuccess();
    }

    /**
     * Perform some actions on error
     *
     * @return void
     */
    protected function postprocessErrorAction()
    {
        \XLite\Core\TopMessage::getInstance()->addBatch($this->getErrorMessages(), \XLite\Core\TopMessage::ERROR);

        $method = __FUNCTION__ . ucfirst($this->currentAction);

        if (method_exists($this, $method)) {
            // Run corresponded function
            $this->$method();
        }

        $this->setActionError();
    }

    /**
     * Rollback model if data validation failed
     *
     * @return void
     */
    protected function rollbackModel()
    {
        if (\XLite\Core\Database::getEM()->contains($this->getModelObject())) {
            \XLite\Core\Database::getEM()->refresh($this->getModelObject());
        }
    }

    /**
     * Save reference to the current form
     *
     * @return void
     */
    protected function startCurrentForm()
    {
        self::$currentForm = $this;
    }

    /**
     * Called after the includeCompiledFile()
     *
     * @return void
     */
    protected function closeView()
    {
        parent::closeView();

        $this->clearFormData();
    }

    /**
     * getFieldBySchema
     * TODO - should use the Factory class
     *
     * @param string $name Field name
     * @param array  $data Field description
     *
     * @return \XLite\View\FormField\AFormField
     */
    protected function getFieldBySchema($name, array $data)
    {
        $class = $data[self::SCHEMA_CLASS];

        $method = 'prepareFieldParams' . \XLite\Core\Converter::convertToCamelCase($name);

        if (method_exists($this, $method)) {
            // Call the corresponded method
            $this->$method($data);
        }

        return new $class($this->getFieldSchemaArgs($name, $data));
    }

    /**
     * Return list of form fields objects by schema
     *
     * @param array $schema Field descriptions
     *
     * @return array
     */
    protected function getFieldsBySchema(array $schema)
    {
        $result = array();

        foreach ($schema as $name => $data) {
            $result[$name] = $this->getFieldBySchema($name, $data);
        }

        return $result;
    }

    /**
     * Remove empty sections
     *
     * @return void
     */
    protected function filterFormFields()
    {
        // First dimension - sections list
        foreach ($this->formFields as $section => &$data) {

            // Second dimension - fields
            foreach ($data[self::SECTION_PARAM_FIELDS] as $index => $field) {

                if (!$field->checkVisibility()) {
                    // Exclude field from list if it's not visible
                    unset($data[self::SECTION_PARAM_FIELDS][$index]);
                } else {
                    // Else include this field into the list of available fields
                    $this->formFieldNames[] = $field->getName();
                }
            }

            // Remove whole section if it's empty
            if (empty($data[self::SECTION_PARAM_FIELDS])) {
                unset($this->formFields[$section]);
            }
        }
    }

    /**
     * Wrapper for the "getFieldsBySchema()" method
     *
     * @param string $name Schema short name
     *
     * @return array
     */
    protected function translateSchema($name)
    {
        $schema = 'schema' . ucfirst($name);

        return property_exists($this, $schema) ? $this->getFieldsBySchema($this->$schema) : array();
    }

    /**
     * Return list of form fields
     *
     * @param boolean $onlyNames Flag; return objects or only the indexes OPTIONAL
     *
     * @return array
     */
    protected function getFormFields($onlyNames = false)
    {
        if (!isset($this->formFields)) {
            $this->defineFormFields();
            $this->filterFormFields();
        }

        return $onlyNames ? $this->formFieldNames : $this->formFields;
    }

    /**
     * Return certain form field
     *
     * @param string  $section        Section where the field located
     * @param string  $name           Field name
     * @param boolean $preprocessName Flag; prepare field name or not OPTIONAL
     *
     * @return \XLite\View\FormField\AFormField
     */
    protected function getFormField($section, $name, $preprocessName = true)
    {
        $result = null;
        $fields = $this->getFormFields();

        if ($preprocessName) {
            $name = $this->composeFieldName($name);
        }

        if (isset($fields[$section][self::SECTION_PARAM_FIELDS][$name])) {
            $result = $fields[$section][self::SECTION_PARAM_FIELDS][$name];
        }

        return $result;
    }

    /**
     * Return list of form fields to display
     *
     * @return array
     */
    protected function getFormFieldsForDisplay()
    {
        $result = $this->getFormFields();
        unset($result[self::SECTION_HIDDEN]);

        return $result;
    }

    /**
     * Display section header or not
     *
     * @param string $section Name of section to check
     *
     * @return boolean
     */
    protected function isShowSectionHeader($section)
    {
        return !in_array($section, array(self::SECTION_DEFAULT, self::SECTION_HIDDEN));
    }

    /**
     * prepareRequestData
     *
     * @param array $data Request data
     *
     * @return array
     */
    protected function prepareRequestData(array $data)
    {
        return $data;
    }

    /**
     * Prepare and save passed data
     *
     * @param array       $data Passed data OPTIONAL
     * @param string|null $name Index in request data array (optional) OPTIONAL
     *
     * @return void
     */
    protected function defineRequestData(array $data = array(), $name = null)
    {
        if (empty($data)) {
            $data = \XLite\Core\Request::getInstance()->getData();
        }
        // FIXME: check if there is the way to avoid this
        $this->formFields = null;

        // TODO: check if there is more convenient way to do this
        $this->requestData = $this->prepareRequestData($data);
        $this->requestData = \Includes\Utils\ArrayManager::filterByKeys(
            $this->requestData,
            $this->getFormFields(true)
        );
    }

    /**
     * Return an assotiative array(the) section field values
     *
     * @param string $section Section name
     *
     * @return array
     */
    protected function getSectionFieldValues($section)
    {
        $result = array();
        $fields = $this->getFormFields();

        foreach ($fields[$section][self::SECTION_PARAM_FIELDS] as $index => $field) {
            $result[$field->getName()] = $this->prepareFieldValue(null, $field->getValue(), $section);
        }

        return $fields;
    }

    /**
     * Return list of the "Button" widgets
     * Do not use this method if you want sticky buttons panel.
     * The sticky buttons panel class has the buttons definition already.
     *
     * TODO: Maybe we should move it to the StickyPanel classes family?
     *
     * @return array
     */
    protected function getFormButtons()
    {
        return array();
    }

    /**
     * Prepare error message before display
     *
     * @param string $message Message itself
     * @param array  $data    Current section data
     *
     * @return string
     */
    protected function prepareErrorMessage($message, array $data)
    {
        if (isset($data[self::SECTION_PARAM_WIDGET])) {
            $sectionTitle = $data[self::SECTION_PARAM_WIDGET]->getLabel();
        }

        if (!empty($sectionTitle)) {
            $message = $sectionTitle . ': ' . $message;
        }

        return $message;
    }

    /**
     * Check if field is valid and (if needed) set an error message
     *
     * @param array  $data    Current section data
     * @param string $section Current section name
     *
     * @return void
     */
    protected function validateFields(array $data, $section)
    {
        foreach ($data[self::SECTION_PARAM_FIELDS] as $field) {
            list($flag, $message) = $field->validate();
            if (!$flag) {
                $this->addErrorMessage($field->getName(), $message, $data);
            }
        }
    }

    /**
     * Return list of form error messages
     *
     * @return array
     */
    protected function getErrorMessages()
    {
        if (!isset($this->errorMessages)) {
            $this->errorMessages = array();

            foreach ($this->getFormFields() as $section => $data) {
                $this->validateFields($data, $section);
            }
        }

        return $this->errorMessages;
    }

    /**
     * addErrorMessage
     *
     * @param string $name    Error name
     * @param string $message Error message
     * @param array  $data    Section data OPTIONAL
     *
     * @return void
     */
    protected function addErrorMessage($name, $message, array $data = array())
    {
        $this->errorMessages[$name] = $this->prepareErrorMessage($message, $data);
    }

    /**
     * Some JavaScript code to insert at the begin of form page
     *
     * @return string
     */
    protected function getTopInlineJSCode()
    {
        return null;
    }

    /**
     * Some JavaScript code to insert at the end of form page
     *
     * @return string
     */
    protected function getBottomInlineJSCode()
    {
        return null;
    }

    /**
     * Call the corresponded method for current action
     *
     * @param string $action Action name OPTIONAL
     *
     * @return boolean
     */
    protected function callActionHandler($action = null)
    {
        $action = self::ACTION_HANDLER_PREFIX . ucfirst($action ?: $this->currentAction);

        // Run the corresponded method
        return $this->$action();
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     */
    protected function performActionCreate()
    {
        return $this->getModelObject()->create();
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     */
    protected function performActionUpdate()
    {
        return $this->getModelObject()->update();
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     */
    protected function performActionModify()
    {
        if ($this->getModelObject()->isPersistent()) {
            $this->currentAction = 'update';
            $result = $this->callActionHandler('update');

        } else {
            $this->currentAction = 'create';
            $result = $this->callActionHandler('create');
        }

        return $result;
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     */
    protected function performActionDelete()
    {
        return $this->getModelObject()->delete();
    }

    /**
     * Retrieve property from the model object
     *
     * @param mixed $name Field/property name
     *
     * @return mixed
     */
    protected function getModelObjectValue($name)
    {
        $model = $this->getModelObject();
        $method = 'get' . \XLite\Core\Converter::convertToCamelCase($name);

        return method_exists($model, $method) ? $model->$method() : $model->getterProperty($name);
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() || $this->isExported();
    }

    /**
     * Add field into the list of excluded fields
     *
     * @param string $fieldName Field name
     *
     * @return void
     */
    protected function excludeField($fieldName)
    {
        $this->excludedFields[] = $fieldName;
    }

    /**
     * Prepare request data for mapping into model object.
     * Model object is provided with methods:
     * prepareObjectForMapping <- getModelObject <- getDefaultModelObject (or getParam(self::PARAM_MODEL_OBJECT))
     *
     * Use $this->excludeField($fieldName) method to remove unnecessary data from request.
     *
     * Call $this->excludeField() method in "performAction*" methods before parent::performAction* call.
     *
     * @return array
     */
    protected function prepareDataForMapping()
    {
        $data = $this->getRequestData();

        // Remove fields in the $excludedFields list from the data for mapping
        if (!empty($this->excludedFields)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->excludedFields)) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * Prepare object for mapping
     *
     * @return \XLite\Model\AEntity
     */
    protected function prepareObjectForMapping()
    {
        return $this->getModelObject();
    }

    /**
     * Return name of the current form
     *
     * @return string
     */
    protected function getFormName()
    {
        return get_class($this);
    }

    /**
     * Display view sublist
     *
     * @param string $suffix    List usffix
     * @param array  $arguments List arguments
     *
     * @return void
     */
    protected function displayViewSubList($suffix, array $arguments = array())
    {
        $class = preg_replace('/^.+\\\View\\\Model\\\/Ss', '', get_called_class());
        $class = str_replace('\\', '.', $class);
        if (preg_match('/\\\Module\\\(a-z0-9+)\\\(a-z0-9+)\\\View\\\Model\\\/Sis', get_called_class(), $match)) {
            $class = $match[1] . '.' . $match[2] . '.' . $class;
        }
        $class = strtolower($class);

        $list = 'crud.' . $class . '.' . $suffix;

        $arguments = $this->assembleViewSubListArguments($suffix, $arguments);

        $this->displayViewListContent($list, $arguments);
    }

    /**
     * Assemble biew sublist arguments
     *
     * @param string $suffix    List suffix
     * @param array  $arguments Arguments
     *
     * @return array
     */
    protected function assembleViewSubListArguments($suffix, array $arguments)
    {
        $arguments['model'] = $this;
        $arguments['useBodyTemplate'] = false;

        return $arguments;
    }

    /**
     * Get container class
     *
     * @return string
     */
    protected function getContainerClass()
    {
        return 'model-properties';
    }

    /**
     * Get item class
     *
     * @param integer                          $index  Item index
     * @param integer                          $length items list length
     * @param \XLite\View\FormField\AFormField $field  Current item
     *
     * @return string
     */
    protected function getItemClass($index, $length, \XLite\View\FormField\AFormField $field)
    {
        $classes = preg_grep('/.+/Ss', array_map('trim', explode(' ', $field->getWrapperClass())));

        if (0 === $index % 2) {
            $classes[] = 'even';
        }

        if (1 === $index) {
            $classes[] = 'first';
        }

        if ($length == $index) {
            $classes[] = 'last';
        }

        return implode(' ', $classes);
    }
}

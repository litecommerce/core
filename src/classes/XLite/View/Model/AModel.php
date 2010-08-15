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

namespace XLite\View\Model;

/**
 * Abstract model widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AModel extends \XLite\View\Dialog
{
    /**
     * Widget param names
     */

    const PARAM_MODEL_OBJECT  = 'modelObject';


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

    const SCHEMA_OPTIONS = \XLite\View\FormField\Select\ASelect::PARAM_OPTIONS;
    const SCHEMA_IS_CHECKED = \XLite\View\FormField\Input\Checkbox::PARAM_IS_CHECKED;

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
     * @var    \XLite\View\Model\AModel
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
     * Names of the form fields (hash)
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $formFieldNames = array();

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
    protected $savedData = null;

    /**
     * Available form sections
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sections = array(
        self::SECTION_DEFAULT => null,
        self::SECTION_HIDDEN  => null,
    );

    /**
     * Current action
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentAction = null;

    /**
     * Data from request
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $requestData = null;


    /**
     * shemaDefault
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shemaDefault = array();

    /**
     * schemaHidden
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $schemaHidden = array();


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
     * Check if current form is accessible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return true;
    }

    /**
     * Return file name for body template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return $this->checkAccess() ? parent::getBodyTemplate() : 'access_denied.tpl';
    }

    /**
     * getAccessDeniedMessage
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAccessDeniedMessage()
    {
        return 'Access denied';
    }
 
    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObject()
    {
        return \XLite\Model\Factory::create($this->getDefaultModelObjectClass(), $this->getDefaultModelObjectKeys());
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
     * getFormDir
     *
     * @param string $template template file basename
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormDir($template = null)
    {
        return 'form';
    }

    /**
     * Return form templates directory name
     *
     * @param string $template template file base name
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormTemplate($template)
    {
        return $this->getFormDir($template) . '/' . $template . '.tpl';
    }

    /**
     * Return list of form fields for certain section
     *
     * @param string $section section name
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormFieldsForSection($section)
    {
        $method = __FUNCTION__ . ucfirst($section);

        return method_exists($this, $method) ? $this->$method() : $this->translateSchema($section);
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
            self::PARAM_MODEL_OBJECT => new \XLite\Model\WidgetParam\Object(
                'Object', $this->getDefaultModelObject(), false, $this->getDefaultModelObjectClass()
            ),
        );
    }

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
        return $name;
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
        return \XLite\Core\Converter::convertTreeToFlatArray($data);
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
        return \XLite\Model\Session::getInstance()->get(self::SAVED_FORMS);
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

        \XLite\Model\Session::getInstance()->set(self::SAVED_FORMS, empty($savedData) ? null : $savedData);
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
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSuccessActionCreate()
    {
        \XLite\Core\TopMessage::getInstance()->addInfo('Data have been saved successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSuccessActionUpdate()
    {
        \XLite\Core\TopMessage::getInstance()->addInfo('Data have been saved successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSuccessActionModify()
    {
        \XLite\Core\TopMessage::getInstance()->addInfo('Data have been saved successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSuccessActionDelete()
    {
        \XLite\Core\TopMessage::getInstance()->addInfo('Data have been deleted successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSuccessAction()
    {
        if (method_exists($this, $method = __FUNCTION__ . ucfirst($this->currentAction))) {
            // Run the corresponded function
            $this->$method();
        }

        $this->setActionSuccess();
    }

    /**
     * Perform some actions on error
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessErrorAction()
    {
        \XLite\Core\TopMessage::getInstance()->addBatch($this->getErrorMessages(), \XLite\Core\TopMessage::ERROR);

        if (method_exists($this, $method = __FUNCTION__ . ucfirst($this->currentAction))) {
            // Run corresponded function
            $this->$method();
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
     * getFieldBySchema
     * TODO - should use the Factory class
     *
     * @param string $name field name
     * @param array  $data field description
     *
     * @return \XLite\View\FormField\AFormField
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFieldBySchema($name, array $data)
    {
        $class = $data[self::SCHEMA_CLASS];

        return new $class($this->getFieldSchemaArgs($name, $data));
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
            $result[$name] = $this->getFieldBySchema($name, $data);
        }

        return $result;
    }

    /**
     * Remove empty sections
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $name schema short name
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function translateSchema($name)
    {
        $schema = 'schema' . ucfirst($name);

        return property_exists($this, $schema) ? $this->getFieldsBySchema($this->$schema) : array();
    }
 
    /**
     * Return list of form fields
     *
     * @param bool $onlyNames flag; return objects or only the indexes
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $section        section where the field located
     * @param string $name           field name
     * @param bool   $preprocessName flag; prepare field name or not
     *
     * @return \XLite\View\FormField\AFormField
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $section name of section to check
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isShowSectionHeader($section)
    {
        return !in_array($section, array(self::SECTION_DEFAULT, self::SECTION_HIDDEN));
    }

    /**
     * prepareRequestData
     *
     * @param array $data request data
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareRequestData(array $data)
    {
        return $data;
    }

    /**
     * Prepare and save passed data
     *
     * @param array       $data passed data
     * @param string|null $name index in request data array (optional)
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
        $this->requestData = \Includes\Utils\ArrayManager::filterArrayByKeys(
            $this->requestData,
            $this->getFormFields(true)
        );
    }

    /**
     * Return an assotiative array of the section field values
     *
     * @param string $section section name
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSectionFieldValues($section)
    {
        $result = array();
        $fields = $this->getFormFields();

        foreach ($fields[$section][self::SECTION_PARAM_FIELDS] as $index => $field) {
            $result[$field->getName()] = $field->getValue();
        }

        return $fields;
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormButtons()
    {
        return array();
    }

    /**
     * Prepare error message before display
     *
     * @param string $message message itself
     * @param array  $data    current section data
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareErrorMessage($message, array $data)
    {
        $sectionTitle = $data[self::SECTION_PARAM_WIDGET]->getLabel();

        if (!empty($sectionTitle)) {
            $message = $sectionTitle . ': ' . $message;
        }

        return $message;
    }

    /**
     * Check if field is valid and (if needed) set an error message
     *
     * @param array $data current section data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function validateFields(array $data)
    {
        foreach ($data[self::SECTION_PARAM_FIELDS] as $field) {
            list($flag, $message) = $field->validate();
            $flag ?: $this->addErrorMessage($field->getName(), $message, $data);
        }
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

            foreach ($this->getFormFields() as $section => $data) {
                $this->validateFields($data);
            }
        }

        return $this->errorMessages;
    }

    /**
     * addErrorMessage
     *
     * @param string $name    error name
     * @param string $message error message
     * @param array  $data    section data (optional)
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addErrorMessage($name, $message, array $data = array())
    {
        $this->errorMessages[$name] = $this->prepareErrorMessage($message, $data);
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
     * Call the corresponded method for current action
     *
     * @param string $action action name
     *
     * @return bool
     * @access protected
     * @since  3.0.0
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
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionCreate()
    {
        return $this->getModelObject()->create();
    }

    /**
     * Perform certain action for the model object
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate()
    {
        return $this->getModelObject()->update();
    }

    /**
     * Perform certain action for the model object
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionModify()
    {
        return $this->callActionHandler($this->getModelObject()->isPersistent ? 'update' : 'create');
    }

    /**
     * Perform certain action for the model object
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionDelete()
    {
        return $this->getModelObject()->delete();
    }


    /**
     * Get instance to the current form object
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCurrentForm()
    {
        return self::$currentForm;
    }

    /**
     * Retrieve property from the request or from  model object
     *
     * @param string $name field/property name
     *
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function getDefaultFieldValue($name)
    {
        $value = $this->getSavedData($name);

        if (!isset($value)) {
            $value = $this->getRequestData($name);

            if (!isset($value)) {
                $value = $this->getModelObject()->get($name);
            }
        }

        return $value;
    }

    /**
     * Check for the form errors
     *
     * @return bool
     * @access public
     * @see    ____func_see____
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
    protected function isVisible()
    {
        return parent::isVisible() || $this->isExported();
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
        // Save some data
        $this->currentAction = $action;
        $this->defineRequestData($data);

        // Map model object with the request data
        $this->setModelProperties($this->getRequestData());

        // Do not call "callActionHandler()" method if model object is not valid
        $result = $this->isValid() && $this->callActionHandler();

        if ($result) {
            $this->postprocessSuccessAction();
        } else {
            $this->saveFormData($this->getRequestData());
            $this->postprocessErrorAction();
        }

        return $result;
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
        $list[] = $this->getDir() . '/model.css';

        return $list;
    }

    /**
     * Return fields' saved values for current form (saved data itself)
     *
     * @return array
     * @access public
     * @since  3.0.0
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
     * @param string $name index in the request data
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $name  index in the request data
     * @param mixed  $value value to set
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setRequestData($name, $value)
    {
        $this->requestData[$name] = $value;
    }

    /**
     * Return model object to use
     *
     * @return \XLite\Model\AModel
     * @access public
     * @since  3.0.0
     */
    public function getModelObject()
    {
        return $this->getParam(self::PARAM_MODEL_OBJECT);
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   widget params
     * @param array $sections sections list
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        if (!empty($sections)) {
            $this->sections = \Includes\Utils\ArrayManager::filterArrayByKeys($this->sections, $sections);
        }

        parent::__construct($params);

        $this->startCurrentForm();
    }
}


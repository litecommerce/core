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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Model;

/**
 * Settings dialog model widget
 *
 * @see   ____class_see____
 * @since 1.0.11
 */
class Settings extends \XLite\View\Model\AModel
{
    /**
     * Get schema fields
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSchemaFields()
    {
        $list = array();

        foreach ($this->getOptions() as $option) {
            $cell = $this->getFormFieldByOption($option);
            if ($cell) {
                $list[$option->getName()] = $cell;
            }
        }

        return $list;
    }

    /**
     * Get form field by option
     *
     * @param \XLite\Model\Config $option Option
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getFormFieldByOption(\XLite\Model\Config $option)
    {
        $cell = null;

        $class = $this->detectFormFieldClassByOption($option);

        if ($class) {
            $cell = array(
                self::SCHEMA_CLASS    => $this->detectFormFieldClassByOption($option),
                self::SCHEMA_LABEL    => $option->getOptionName(),
                self::SCHEMA_HELP     => $option->getOptionComment(),
                self::SCHEMA_REQUIRED => false,
            );

            if ($this->isOptionRequired($option)) {
                $cell[self::SCHEMA_REQUIRED] = true;
            }

            $parameters = $option->getWidgetParameters();
            if ($parameters && is_array($parameters)) {
                $cell += $parameters;
            }
        }

        return $cell;
    }

    /**
     * Detect form field class by option
     *
     * @param \XLite\Model\Config $option Option
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function detectFormFieldClassByOption(\XLite\Model\Config $option)
    {
        $class = null;
        $type = $option->getType() ?: 'text';

        switch ($type) {
            case 'textarea':
                $class = '\XLite\View\FormField\Textarea\Simple';
                break;

            case 'checkbox':
                $class = '\XLite\View\FormField\Input\Checkbox\Setting';
                break;

            case 'country':
                $class = '\XLite\View\FormField\Select\Country';
                break;

            case 'state':
                $class = '\XLite\View\FormField\Select\State';
                break;

            case 'currency':
                $class = '\XLite\View\FormField\Select\Currency';
                break;

            case 'separator':
                $class = '\XLite\View\FormField\Separator\Regular';
                break;

            case 'text':
                $class = '\XLite\View\FormField\Input\Text';
                break;

            default:
                if (preg_match('/^\\\?XLite\\\/Ss', $option->getType())) {
                    $class = $option->getType();
                }
        }

        return $class;
    }

    /**
     * Check - option is required or not
     *
     * @param \XLite\Model\Config $option Option
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function isOptionRequired(\XLite\Model\Config $option)
    {
        return false;
    }

    /**
     * Get form fields for default section
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getFormFieldsForSectionDefault()
    {
        return $this->getFieldsBySchema($this->getSchemaFields());
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => 'Submit',
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionUpdate()
    {
        return true;
    }

    /**
     * Retrieve property from the model object
     *
     * @param mixed $name Field/property name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelObjectValue($name)
    {
        $value = null;

        foreach ($this->getOptions() as $option) {
            if ($option->getName() == $name) {
                $value = $option->getValue();
                break;
            }
        }

        return $value;
    }

    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setModelProperties(array $data)
    {
        $optionsToUpdate = array();
        $options = $this->getOptions();

        // Find changed options and store them in $optionsToUpdate
        foreach ($options as $key => $option) {

            $name  = $option->name;
            $type  = $option->type;
            $value = $option->value;

            if ('checkbox' == $type) {
                $newValue = empty($data[$name]) ? 'N' : 'Y';

            } elseif ('serialized' == $type && isset($data[$name]) && is_array($data[$name])) {
                $newValue = serialize($data[$name]);

            } elseif ('text' == $type) {
                $newValue = isset($data[$name]) ? trim($data[$name]) : '';

            } else {
                $newValue = isset($data[$name]) ? $data[$name] : '';
            }

            if ($value != $newValue) {
                $option->value = $newValue;
                $optionsToUpdate[] = $option;
            }
        }

        // Save changed options to the database
        if (!empty($optionsToUpdate)) {

            foreach ($optionsToUpdate as $option) {
                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => $option->category,
                        'name'     => $option->name,
                        'value'    => $option->value
                    )
                );
            }
        }
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        return null;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Settings';
    }
}

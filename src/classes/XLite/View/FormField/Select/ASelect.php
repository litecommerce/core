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

namespace XLite\View\FormField\Select;

/**
 * Form abstract selector
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ASelect extends \XLite\View\FormField\AFormField
{
    /**
     * Widget param names
     */

    const PARAM_OPTIONS = 'options';


    /**
     * Return default options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getDefaultOptions();


    /**
     * Return field type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFieldType()
    {
        return self::FIELD_TYPE_SELECT;
    }

    /**
     * Set value
     *
     * @param mixed $value Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setValue($value)
    {
        if (is_object($value) && $value instanceOf \XLite\Model\AEntity) {
            $value = $value->getUniqueIdentifier();
        }

        parent::setValue($value);
    }


    /**
     * Return field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFieldTemplate()
    {
        return 'select.tpl';
    }

    /**
     * getOptions
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOptions()
    {
        return $this->getParam(self::PARAM_OPTIONS);
    }

    /**
     * Checks if the list is empty
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isListEmpty()
    {
        return 0 >= count($this->getOptions());
    }

    /**
     * Check - options list Has option groups or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function hasOptionGroups()
    {
        $cnt = 0;

        foreach ($this->getOptions() as $option) {
            if (is_array($option)) {
                $cnt++;
            }
        }

        return count($this->getOptions()) == $cnt;
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_OPTIONS => new \XLite\Model\WidgetParam\Collection(
                'Options', $this->getDefaultOptions(), false
            ),
        );
    }

    /**
     * Check - current value is selected or not
     * 
     * @param mixed $value Value
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function isOptionSelected($value)
    {
        return $value == $this->getValue();
    }

    /**
     * Check - specified option group is disabled or not
     * 
     * @param mixed $optionGroupIndex Option group index
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isOptionGroupDisabled($optionGroupIndex)
    {
        return false;
    }

    /**
     * Check - specidifed option is disabled or not
     * 
     * @param mixed $value Option value
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isOptionDisabled($value)
    {
        return false;
    }

    /**
     * Get option group attributes as HTML code 
     * 
     * @param mixed $optionGroupIdx Option group index
     * @param array $optionGroup    Option group
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getOptionGroupAttributesCode($optionGroupIdx, array $optionGroup)
    {
        $list = array();

        foreach ($this->getOptionGroupAttributes($optionGroupIdx, $optionGroup) as $name => $value) {
            $list[] = $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return implode(' ', $list);
    }

    /**
     * Get option group attributes 
     * 
     * @param mixed $optionGroupIdx Option group index
     * @param array $optionGroup    Option group
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getOptionGroupAttributes($optionGroupIdx, array $optionGroup)
    {
        $attributes = array(
            'label' => static::t($optionGroup['label']),
        );

        if ($this->isOptionGroupDisabled($optionGroupIdx)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

    /**
     * Get option attributes as HTML code
     *
     * @param mixed $value Value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getOptionAttributesCode($value)
    {
        $list = array();

        foreach ($this->getOptionAttributes($value) as $name => $value) {
            $list[] = $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return implode(' ', $list);
    }

    /**
     * Get option attributes 
     * 
     * @param mixed $value Value
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getOptionAttributes($value)
    {
        $attributes = array(
            'value' => func_htmlspecialchars($value),
        );

        if ($this->isOptionSelected($value)) {
            $attributes['selected'] = 'selected';
        }

        if ($this->isOptionDisabled($value)) {
            $attributes['disabled'] = 'disabled';
        }

        return $attributes;
    }

}

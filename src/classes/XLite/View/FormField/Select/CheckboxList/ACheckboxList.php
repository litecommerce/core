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
 * @since     1.0.17
 */

namespace XLite\View\FormField\Select\CheckboxList;

/**
 * Multiple select based on checkboxes list
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
abstract class ACheckboxList extends \XLite\View\FormField\Select\Multiple
{
    /**
     * Return field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFieldTemplate()
    {
        return 'checkbox_list.tpl';
    }

    /**
     * Get item attributes as string
     * 
     * @param mixed $value Item value
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getItemAttributesCode($value)
    {
        $result = '';

        foreach ($this->getItemAttributes($value) as $name => $value) {
            $result .= ' ' . $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return $result;
    }

    /**
     * Get item attributes
     *
     * @param mixed $value Item value
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getItemAttributes($value)
    {
        $attributes = $this->getAttributes();


        if ($this->isOptionSelected($value)) {
            $attributes['checked'] = 'checked';
        }

        $attributes['name'] .= '[' . $value . ']';
        $attributes['id'] = $this->getItemId($value);
        $attributes['type'] = self::FIELD_TYPE_CHECKBOX;

        return $attributes;
    }

    /**
     * Get item attributes for dump input
     *
     * @param mixed $value Item value
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getItemDumpAttributes($value)
    {
        $attributes = $this->getItemAttributes($value);

        $attributes = array(
            'type'  => 'hidden',
            'name'  => $attributes['name'],
            'value' => '',
        );

        return $attributes;
    }

    /**
     * Get item attributes as string
     *
     * @param mixed $value Item value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getItemDUmpAttributesCode($value)
    {
        $result = '';

        foreach ($this->getItemDumpAttributes($value) as $name => $value) {
            $result .= ' ' . $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return $result;
    }

    /**
     * Prepare attributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareAttributes(array $attrs)
    {
        $attrs = parent::prepareAttributes($attrs);

        if (isset($attrs['multiple'])) {
            unset($attrs['multiple']);
        }

        $attrs['name'] = substr($attrs['name'], 0, -2);

        return $attrs;
    }

    /**
     * Get item ID
     * 
     * @param mixed $value Item value
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getItemId($value)
    {
        return str_replace(array('[', ']'), array('-', ''), $this->getName()) . '-' . $value;
    }

}


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

namespace XLite\View\FormField\Input;

/**
 * Common checkbox
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Checkbox extends \XLite\View\FormField\Input\AInput
{
    /**
     * Widget param names
     */

    const PARAM_IS_CHECKED = 'isChecked';


    /**
     * Return field type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFieldType()
    {
        return self::FIELD_TYPE_CHECKBOX;
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
            self::PARAM_IS_CHECKED => new \XLite\Model\WidgetParam\Bool('Is checked', false),
        );
    }

    /**
     * Determines if checkbox is checked
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isChecked()
    {
        return $this->getParam(self::PARAM_IS_CHECKED) || $this->checkSavedValue();
    }

    /**
     * prepareAttributes
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

        if ($this->isChecked()) {
            $attrs['checked'] = 'checked';
        }

        return $attrs;
    }

    /**
     * Get default value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultValue()
    {
        return parent::getDefaultValue() ?: '1';
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
        return 'checkbox.tpl';
    }

}

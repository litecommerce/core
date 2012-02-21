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

namespace XLite\View\FormField\Input\Text\Base;

/**
 * Numeric
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Numeric extends \XLite\View\FormField\Input\Text
{
    /**
     * Widget param names
     */
    const PARAM_MIN              = 'min';
    const PARAM_MAX              = 'max';
    const PARAM_MOUSE_WHEEL_CTRL = 'mouseWheelCtrl';
    const PARAM_MOUSE_WHEEL_ICON = 'mouseWheelIcon';

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
            static::PARAM_MIN              => new \XLite\Model\WidgetParam\Int('Minimum', null),
            static::PARAM_MAX              => new \XLite\Model\WidgetParam\Int('Maximum', null),
            static::PARAM_MOUSE_WHEEL_CTRL => new \XLite\Model\WidgetParam\Bool('Mouse wheel control', true),
            static::PARAM_MOUSE_WHEEL_ICON => new \XLite\Model\WidgetParam\Bool('User mouse wheel icon', true),
        );
    }

    /**
     * Check field validity
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkFieldValidity()
    {
        $result = parent::checkFieldValidity();

        if ($result) {
            $result = $this->checkRange();
        }

        return $result;
    }

    /**
     * Check range 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function checkRange()
    {
        $result = true;

        if (!is_null($this->getParam(self::PARAM_MIN)) && $this->getValue() < $this->getParam(self::PARAM_MIN)) {

            $result = false;
            $this->errorMessage = \XLite\Core\Translation::lbl(
                'The value of the X field must be greater than Y',
                array(
                    'name' => $this->getLabel(),
                    'min' => $this->getParam(self::PARAM_MIN),
                )
            );

        } elseif (!is_null($this->getParam(self::PARAM_MAX)) && $this->getValue() > $this->getParam(self::PARAM_MAX)) {

            $result = false;
            $this->errorMessage = \XLite\Core\Translation::lbl(
                'The value of the X field must be less than Y',
                array(
                    'name' => $this->getLabel(),
                    'max' => $this->getParam(self::PARAM_MAX),
                )
            );

        }

        return $result;
    }

    /**
     * Assemble validation rules
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleValidationRules()
    {
        $rules = parent::assembleValidationRules();

        if (!is_null($this->getParam(self::PARAM_MIN))) {
            $rules[] = 'min[' . $this->getParam(self::PARAM_MIN) . ']';
        }

        if (!is_null($this->getParam(self::PARAM_MAX))) {
            $rules[] = 'max[' . $this->getParam(self::PARAM_MAX) . ']';
        }

        return $rules;
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleClasses(array $classes)
    {
        $classes = parent::assembleClasses($classes);

        if ($this->getParam(static::PARAM_MOUSE_WHEEL_CTRL)) {
            $classes[] = 'wheel-ctrl';
            if (!$this->getParam(static::PARAM_MOUSE_WHEEL_ICON)) {
                $classes[] = 'no-wheel-mark';
            }
        }

        return $classes;
    }

}

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

namespace XLite\View\FormField\Input\Base;

/**
 * String-based
 *
 */
abstract class String extends \XLite\View\FormField\Input\AInput
{
    /**
     * Widget param names
     */
    const PARAM_DEFAULT_VALUE = 'defaultValue';
    const PARAM_MAX_LENGTH    = 'maxlength';

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DEFAULT_VALUE => new \XLite\Model\WidgetParam\String('Default value', ''),
            self::PARAM_MAX_LENGTH    => new \XLite\Model\WidgetParam\Int('Maximum length', $this->getDefaultMaxSize()),
        );
    }

    /**
     * getCommonAttributes
     *
     * @return array
     */
    protected function getCommonAttributes()
    {
        $list = parent::getCommonAttributes();

        if ($this->getParam(static::PARAM_MAX_LENGTH)) {
            $list['maxlength'] = $this->getParam(static::PARAM_MAX_LENGTH);
        }

        return $list;
    }

    /**
     * Get default maximum size 
     * 
     * @return integer
     */
    protected function getDefaultMaxSize()
    {
        return 255;
    }

    /**
     * Check field validity
     *
     * @return boolean
     */
    protected function checkFieldValidity()
    {
        $result = parent::checkFieldValidity();

        if ($result && strlen($result) > $this->getParam(self::PARAM_MAX_LENGTH)) {
            $result = false;
            $this->errorMessage = \XLite\Core\Translation::lbl(
                'The value of the X field should not be longer than Y',
                array(
                    'name' => $this->getLabel(),
                    'max'  => $this->getParam(self::PARAM_MAX_LENGTH),
                )
            );
        }

        return $result;
    }

    /**
     * Assemble validation rules
     *
     * @return array
     */
    protected function assembleValidationRules()
    {
        $rules = parent::assembleValidationRules();

        $rules[] = 'maxSize[' . $this->getParam(self::PARAM_MAX_LENGTH) . ']';

        return $rules;
    }

    /**
     * Register some data that will be sent to template as special HTML comment
     *
     * @return array
     */
    protected function getCommentedData()
    {
        return array(
            'defaultValue' => $this->getParam(self::PARAM_DEFAULT_VALUE),
        );
    }

    /**
     * Sanitize value
     *
     * @return mixed
     */
    protected function sanitize()
    {
       return trim(parent::sanitize());
    }
}

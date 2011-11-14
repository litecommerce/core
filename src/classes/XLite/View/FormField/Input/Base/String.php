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

namespace XLite\View\FormField\Input\Base;

/**
 * String-based
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class String extends \XLite\View\FormField\Input\AInput
{
    const PARAM_DEFAULT_VALUE = 'defaultValue';
    const PARAM_MAX_SIZE      = 'maxSize';

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
            self::PARAM_DEFAULT_VALUE => new \XLite\Model\WidgetParam\String('Default value', ''),
            self::PARAM_MAX_SIZE      => new \XLite\Model\WidgetParam\Int('Maximum size', $this->getDefaultMaxSize()),
        );
    }

    /**
     * Get default maximum size 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getDefaultMaxSize()
    {
        return 255;
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

        if ($result && strlen($result) > $this->getParam(self::PARAM_MAX_SIZE)) {
            $result = false;
            $this->errorMessage = \XLite\Core\Translation::lbl(
                'The value of X should not be longer than Y',
                array(
                    'name' => $this->getLabel(),
                    'max'  => $this->getParam(self::PARAM_MAX_SIZE),
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

        $rules[] = 'maxSize[' . $this->getParam(self::PARAM_MAX_SIZE) . ']';

        return $rules;
    }

    /**
     * Register some data that will be sent to template as special HTML comment
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function sanitize()
    {
       return trim(parent::sanitize());
    }
}

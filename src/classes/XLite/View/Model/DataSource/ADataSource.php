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

namespace XLite\View\Model\DataSource;

/**
 * Abstract data source model widget
 *
 * @see   ____class_see____
 * @since 1.0.17
 */
abstract class ADataSource extends \XLite\View\Model\AModel
{
    /**
     * Retrieve property from the model object
     *
     * @param mixed $name Field/property name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getModelObjectValue($name)
    {
        // If field name starts with 'parameter_', this field
        // is represented by a bound \XLite\Model\DataSource\Parameter object
        if (strpos($name, 'parameter_') === 0) {

            $paramName = substr($name, 10);

            return $this->getModelObject()->getParameterValue($paramName);

        } else {
            // Otherwise it's a field of a current model object
            return parent::getModelObjectValue($name);
        }
    }

    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function setModelProperties(array $data)
    {
        foreach ($data as $name => $value) {
            if (strpos($name, 'parameter_') === 0) {
                $paramName = substr($name, 10);

                $this->getModelObject()->setParameterValue($paramName, $value);

                // Remove already set properties
                unset($data[$name]);
            }
        }

        parent::setModelProperties($data);
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.17
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
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\DataSource';
    }
}

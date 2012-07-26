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

namespace XLite\View\FormField\Inline\Base;

/**
 * Single-field
 * 
 */
abstract class Single extends \XLite\View\FormField\Inline\AInline
{
    /**
     * Define form field
     *
     * @return string
     */
    abstract protected function defineFieldClass();

    /**
     * Define fields
     *
     * @return array
     */
    protected function defineFields()
    {
        return array(
            $this->getParam(static::PARAM_FIELD_NAME) => array(
                static::FIELD_NAME  => $this->getParam(static::PARAM_FIELD_NAME),
                static::FIELD_CLASS => $this->defineFieldClass(),
            ),
        );
    }

    /**
     * Get entity value
     *
     * @return mixed
     */
    protected function getEntityValue()
    {
        $method = 'get' . ucfirst($this->getParam(static::PARAM_FIELD_NAME));

        // $method assembled from 'get' + field short name
        return $this->getEntity()->$method();
    }

    /**
     * Get field value from entity
     *
     * @param array $field Field
     *
     * @return mixed
     */
    protected function getFieldEntityValue(array $field)
    {
        return $this->getEntityValue();
    }

    /**
     * Get single field 
     * 
     * @return array
     */
    protected function getSingleField()
    {
        $list = $this->getFields();

        return array_shift($list);
    }

    /**
     * Get single field as widget
     *
     * @return \XLite\View\FormField\AFormField
     */
    protected function getSingleFieldAsWidget()
    {
        $field = $this->getSingleField();

        return $field['widget'];
    }

}


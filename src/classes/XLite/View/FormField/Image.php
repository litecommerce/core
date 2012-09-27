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

namespace XLite\View\FormField;

/**
 * Image loader
 * 
 */
class Image extends \XLite\View\FormField\AFormField
{
    const PARAM_BUTTON_LABEL   = 'buttonLabel';
    const PARAM_REMOVE_BUTTON  = 'removeButton';
    const PARAM_OBJECT         = \XLite\View\Button\FileSelector::PARAM_OBJECT;
    const PARAM_OBJECT_ID      = \XLite\View\Button\FileSelector::PARAM_OBJECT_ID;
    const PARAM_FILE_OBJECT    = \XLite\View\Button\FileSelector::PARAM_FILE_OBJECT;
    const PARAM_FILE_OBJECT_ID = \XLite\View\Button\FileSelector::PARAM_FILE_OBJECT_ID;

    /**
     * Return field type
     *
     * @return string
     */
    public function getFieldType()
    {
        return 'image';
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'image.tpl';
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[self::PARAM_VALUE] = new \XLite\Model\WidgetParam\Object('Image', null, false, 'XLite\Model\Base\Image');

        $this->widgetParams += array(
            self::PARAM_BUTTON_LABEL    => new \XLite\Model\WidgetParam\String('Button label', 'Add image'),
            self::PARAM_REMOVE_BUTTON   => new \XLite\Model\WidgetParam\Bool('Remove button', false),
            self::PARAM_OBJECT          => new \XLite\Model\WidgetParam\String('Object', ''),
            self::PARAM_OBJECT_ID       => new \XLite\Model\WidgetParam\Int('Object ID', 0),
            self::PARAM_FILE_OBJECT     => new \XLite\Model\WidgetParam\String('File object', 'image'),
            self::PARAM_FILE_OBJECT_ID  => new \XLite\Model\WidgetParam\Int('File object ID', 0),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getObjectId();
    }

    /**
     * Check - remove button is visible or not
     * 
     * @return boolean
     */
    protected function isRemoveButtonVisible()
    {
        return $this->getParam(static::PARAM_REMOVE_BUTTON);
    }

    /**
     * Get remove button label 
     * 
     * @return string
     */
    protected function getRemoveButtonLabel()
    {
        return 'Remove image';
    }

    /**
     * Get default wrapper class
     *
     * @return string
     */
    protected function getDefaultWrapperClass()
    {
        return trim(parent::getDefaultWrapperClass() . ' image-selector');
    }

    /**
     * Get button label 
     * 
     * @return string
     */
    protected function getButtonLabel()
    {
        return $this->getParam(self::PARAM_BUTTON_LABEL);
    }

    /**
     * Get object
     *
     * @return string
     */
    protected function getObject()
    {
        return $this->getParam(self::PARAM_OBJECT);
    }

    /**
     * Get object id
     *
     * @return integer
     */
    protected function getObjectId()
    {
        return $this->getParam(self::PARAM_OBJECT_ID);
    }

    /**
     * Get file object
     *
     * @return string
     */
    protected function getFileObject()
    {
        return $this->getParam(self::PARAM_FILE_OBJECT);
    }

    /**
     * Get file object id
     *
     * @return integer
     */
    protected function getFileObjectId()
    {
        return $this->getParam(self::PARAM_FILE_OBJECT_ID);
    }

}


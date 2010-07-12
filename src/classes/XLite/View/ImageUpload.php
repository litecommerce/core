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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Image upload component.
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ImageUpload extends \XLite\View\AView
{
    /*
     * Widget parameters names
     */
    const PARAM_FIELD = 'field';
    const PARAM_ACTION_NAME = 'actionName';
    const PARAM_FORM_NAME = 'formName';
    const PARAM_OBJECT = 'object';

    public $showDelete = true;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/image_upload.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FIELD       => new \XLite\Model\WidgetParam\String('Field', ''),
            self::PARAM_ACTION_NAME => new \XLite\Model\WidgetParam\String('Action name', ''),
            self::PARAM_FORM_NAME   => new \XLite\Model\WidgetParam\String('Form name', ''),
            self::PARAM_OBJECT      => new \XLite\Model\WidgetParam\Object('Object', null),
        );
    }

    /**
     * Check if object has image
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasImage()
    {
        $field = $this->getParam(self::PARAM_FIELD);
        $method = "has$field";
        $object = $this->getParam(self::PARAM_OBJECT);

        $result = false;

        if (is_object($object) && method_exists($object, $method)) {
            $result = $object->$method();
        }

        return $result;
    }

    /**
     * Check if image is on file system 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isFS()
    {
        return ('F' == $this->getParam(self::PARAM_OBJECT)->get($this->getParam(self::PARAM_FIELD))->getDefaultSource());
    }
}


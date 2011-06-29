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

namespace XLite\View;

/**
 * Tooltip widget
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class Tooltip extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ID              = 'id';
    const PARAM_TEXT            = 'text';
    const PARAM_CLASS           = 'className';
    const PARAM_CAPTION         = 'caption';
    const PARAM_IS_IMAGE_TAG    = 'isImageTag';

    const ATTR_CLASS    = 'class';
    const ATTR_ID       = 'id';

    const CAPTION_CSS_CLASS = 'tooltip-caption';


    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/tooltip.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getDefaultTemplate()
    {
        return 'tooltip.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TEXT            => new \XLite\Model\WidgetParam\String('Text to show in tooltip', ''),
            self::PARAM_ID              => new \XLite\Model\WidgetParam\String('ID of element', ''),
            self::PARAM_CLASS           => new \XLite\Model\WidgetParam\String('CSS class for caption', ''),
            self::PARAM_CAPTION         => new \XLite\Model\WidgetParam\String('Caption', ''),
            self::PARAM_IS_IMAGE_TAG    => new \XLite\Model\WidgetParam\Bool('Is it shown as image?', true),
        );
    }

    /**
     * Checks if image must be shown
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function isImageTag()
    {
        return $this->getParam(self::PARAM_IS_IMAGE_TAG);
    }

    /**
     * Define array of attributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getAttributes()
    {
        $attrs = array(
            self::ATTR_CLASS => $this->getClass(),
        );

        $attrs += $this->getParam(self::PARAM_ID)
                ? array(self::ATTR_ID => $this->getParam(self::PARAM_ID))
                : array();

        return $attrs;
    }

    /**
     * Return HTML representation for widget attributes
     * TODO - REWORK with AFormField class - same method using
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getAttributesCode()
    {
        $result = '';

        foreach ($this->getAttributes() as $name => $value) {
            $result .= ' ' . $name . '="' . $value . '"';
        }

        return $result;
    }


    /**
     * Define CSS class of caption text
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getClass()
    {
        return self::CAPTION_CSS_CLASS . ' ' . $this->getParam(self::PARAM_CLASS);
    }
}

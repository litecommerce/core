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

namespace XLite\View\Button\Popup;

/**
 * Element to use with popup
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class APopup extends \XLite\View\Button\AButton
{
    /**
     * Widget param names
     */
    const PARAM_POPUP_TARGET = 'popupTarget';
    const PARAM_POPUP_WIDGET = 'popupWidget';

    /**
     * Return CSS files list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'button/css/popup.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'button/js/popup_button.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list[static::RESOURCE_JS][] = 'js/core.popup.js';
        $list[static::RESOURCE_JS][] = 'js/core.popup_button.js';

        return $list;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_POPUP_TARGET => new \XLite\Model\WidgetParam\String('Target', $this->getDefaultTarget()),
            static::PARAM_POPUP_WIDGET => new \XLite\Model\WidgetParam\String('Widget', $this->getDefaultWidget()),
        );
    }

    /**
     * Return default value for widget param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultTarget()
    {
        return '';
    }

    /**
     * Return default value for widget param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultWidget()
    {
        return '';
    }

    /**
     * Return array of additional URL params for JS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAdditionalURLParams()
    {
        return array();
    }

    /**
     * Return array of URL params for JS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getURLParams()
    {
        return array(
            'url_params' => array(
                'target' => $this->getParam(static::PARAM_POPUP_TARGET),
                'widget' => $this->getParam(static::PARAM_POPUP_WIDGET),
            ) + $this->getAdditionalURLParams(),
        );
    }

    /**
     * Return content for popup button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getButtonContent()
    {
        return $this->getParam(self::PARAM_LABEL) ?: $this->getDefaultLabel();
    }

    /**
     * Defines CSS class for widget to use in templates
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClass()
    {
        return parent::getClass() . ' popup-opener';
    }
}

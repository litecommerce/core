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

namespace XLite\View\Button;

/**
 * Button to use with popup
 *
 */
abstract class APopupButton extends \XLite\View\Button\AButton
{
    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     */
    abstract protected function prepareURLParams();

    /**
     * Return array of URL params for JS
     *
     * @return array
     */
    public function getURLParams()
    {
        return array(
            'url_params' => $this->prepareURLParams(),
        );
    }

    /**
     * Return CSS files list
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'button/css/popup.css';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list[static::RESOURCE_JS][] = 'js/core.popup.js';
        $list[static::RESOURCE_JS][] = 'js/core.popup_button.js';

        return $list;
    }

    /**
     * Return content for popup button
     *
     * @return string
     */
    protected function getButtonContent()
    {
        return $this->getParam(static::PARAM_LABEL) ?: $this->getDefaultLabel();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'button/popup_button.tpl';
    }

    /**
     * Defines CSS class for widget to use in templates
     *
     * @return string
     */
    protected function getClass()
    {
        return parent::getClass() . ' popup-button';
    }
}

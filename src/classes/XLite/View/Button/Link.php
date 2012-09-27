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
 * Link as button
 *
 */
class Link extends \XLite\View\Button\AButton
{
    /**
     * Widget parameter names
     */

    const PARAM_LOCATION = 'location';
    const PARAM_JS_CODE  = 'jsCode';
    const PARAM_BLANK    = 'blank';


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'button/regular.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_LOCATION => new \XLite\Model\WidgetParam\String('Redirect to', null, true),
            self::PARAM_JS_CODE  => new \XLite\Model\WidgetParam\String('JS code', null, true),
            self::PARAM_BLANK    => new \XLite\Model\WidgetParam\Bool('Open in new window', false),
        );
    }

    /**
     * JavaScript: this code will be used by default
     *
     * @return string
     */
    protected function getDefaultJSCode($action = null)
    {
        return $this->getParam(self::PARAM_BLANK)
                ? 'window.open(\'' . $this->getParam(self::PARAM_LOCATION) . '\');'
                : 'self.location = \'' . $this->getParam(self::PARAM_LOCATION) . '\';';
    }

    /**
     * JavaScript: return specified (or default) JS code to execute
     *
     * @return string
     */
    protected function getJSCode()
    {
        return $this->getParam(self::PARAM_JS_CODE) ? $this->getParam(self::PARAM_JS_CODE) : $this->getDefaultJSCode();
    }
}

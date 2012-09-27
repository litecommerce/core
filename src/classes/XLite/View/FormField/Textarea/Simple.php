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

namespace XLite\View\FormField\Textarea;

/**
 * Textarea
 *
 */
class Simple extends \XLite\View\FormField\Textarea\ATextarea
{
    /**
     * Widget param names
     */
    const PARAM_MIN_HEIGHT = 'maxWidth';
    const PARAM_MAX_HEIGHT = 'maxHeight';

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_MIN_HEIGHT  => new \XLite\Model\WidgetParam\Int('Min. height', 0),
            static::PARAM_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int('Max. height', 0),
        );
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    protected function getCommonFiles()
    {
        $list = parent::getCommonFiles();

        if ($this->getParam(static::PARAM_MAX_HEIGHT)) {
            $list[static::RESOURCE_JS][] = 'js/jquery.textarea-expander.js';
        }

        return $list;
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'textarea.tpl';
    }

    /**
     * setCommonAttributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     */
    protected function setCommonAttributes(array $attrs)
    {
        $attrs = parent::setCommonAttributes($attrs);

        if ($this->getParam(static::PARAM_MAX_HEIGHT)) {

            if ($this->getParam(static::PARAM_MIN_HEIGHT)) {
                $attrs['data-min-size-height'] = $this->getParam(static::PARAM_MIN_HEIGHT);
            }

            if ($this->getParam(static::PARAM_MAX_HEIGHT)) {
                $attrs['data-max-size-height'] = $this->getParam(static::PARAM_MAX_HEIGHT);
            }

            if (empty($attrs['class'])) {
                $attrs['class'] = '';
            }

            $attrs['class'] = trim($attrs['class'] . ' resizeble-txt');
        }

        return $attrs;
    }
}

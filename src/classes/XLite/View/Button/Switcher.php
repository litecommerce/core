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

namespace XLite\View\Button;

/**
 * Switcher button
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Switcher extends \XLite\View\Button\AButton
{
    /**
     * Widget parameter names
     */
    const PARAM_ENABLED = 'enabled';

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

        $list[] = 'button/js/switcher.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'button/switcher.tpl';
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
            self::PARAM_ENABLED => new \XLite\Model\WidgetParam\Bool('Enabled', true),
        );
    }

    /**
     * Get formatted enabled status
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEnabled()
    {
        return $this->getParam(self::PARAM_ENABLED) ? '1' : '';
    }

    /**
     * Get style 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function  getStyle()
    {
        return 'switcher '
            . ($this->getParam(self::PARAM_ENABLED) ? 'on' : 'off')
            . ($this->getParam(self::PARAM_STYLE) ? ' ' . $this->getParam(self::PARAM_STYLE) : '');
    }

    /**
     * Get title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getTitle()
    {
        $label = $this->getParam(self::PARAM_ENABLED) ? 'Disable' : 'Enable';

        return \XLite\Core\Translation::lbl($label);
    }
}

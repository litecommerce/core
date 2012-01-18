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

namespace XLite\View\FormField\Input\Checkbox;

/**
 * Switch
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Switcher extends \XLite\View\FormField\Input\Checkbox
{
    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/input/checkbox/switcher.css';

        return $list;
    }

    /**
     * Get a list of JS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/input/checkbox/switcher.js';

        return $list;
    }

    /**
     * Return field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFieldTemplate()
    {
        return 'input/checkbox/switcher.tpl';
    }

    /**
     * Get default wrapper class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getDefaultWrapperClass()
    {
        return trim(parent::getDefaultWrapperClass() . ' switcher');
    }

    /**
     * Get widget title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getWidgetTitle()
    {
        return $this->isChecked() ? $this->getDisableLabel() : $this->getEnableLabel();
    }

    /**
     * Get 'Disable' label 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDisableLabel()
    {
        return \XLite\Core\Translation::lbl('Disable');
    }

    /**
     * Get 'Enable' label 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEnableLabel()
    {
        return \XLite\Core\Translation::lbl('Enable');
    }

    /**
     * prepareAttributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareAttributes(array $attrs)
    {
        $attrs = parent::prepareAttributes($attrs);

        $attrs['value'] = '1';

        return $attrs;
    }

}

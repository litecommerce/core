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

namespace XLite\View\FormField\Input\Checkbox;

/**
 * Switch
 *
 */
class Switcher extends \XLite\View\FormField\Input\Checkbox
{
    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
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
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/input/checkbox/switcher.js';

        return $list;
    }

   /**
     * Register CSS class to use for wrapper block (SPAN) of input field.
     * It is usable to make unique changes of the field.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return trim(parent::getWrapperClass() . ' ' . ($this->isChecked() ? 'enabled' : 'disabled'));
    }

    /**
     * Determines if checkbox is checked
     *
     * @return boolean
     */
    protected function isChecked()
    {
        return $this->getValue() || $this->checkSavedValue();
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'input/checkbox/switcher.tpl';
    }

    /**
     * Get default wrapper class
     *
     * @return string
     */
    protected function getDefaultWrapperClass()
    {
        return trim(parent::getDefaultWrapperClass() . ' switcher');
    }

    /**
     * Get widget title 
     * 
     * @return string
     */
    protected function getWidgetTitle()
    {
        return $this->isChecked() ? $this->getDisableLabel() : $this->getEnableLabel();
    }

    /**
     * Get 'Disable' label 
     * 
     * @return string
     */
    protected function getDisableLabel()
    {
        return 'Disable';
    }

    /**
     * Get 'Enable' label 
     * 
     * @return string
     */
    protected function getEnableLabel()
    {
        return 'Enable';
    }

    /**
     * getCommonAttributes
     *
     * @return array
     */
    protected function getCommonAttributes()
    {
        $list = parent::getCommonAttributes();
        $list['value'] = '1';

        return $list;
    }
}

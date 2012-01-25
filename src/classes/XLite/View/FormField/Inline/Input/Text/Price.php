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
 * @since     1.0.15
 */

namespace XLite\View\FormField\Inline\Input\Text;

/**
 * Price 
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class Price extends \XLite\View\FormField\Inline\AInline
{
    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'form_field/inline/input/text/price.js';

        return $list;
    }

    /**
     * Define form field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineFieldClass()
    {
        return 'XLite\View\FormField\Input\Text\Price';
    }

    /**
     * Get view value
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getViewValue()
    {
        $value = parent::getViewValue();
        $sign = 0 <= $value ? '' : '&minus;&#8197';

        return $sign . $this->getField()->getCurrency()->formatValue(abs($value));
    }

    /**
     * Get container class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return parent::getContainerClass() . ' inline-price';
    }

    /**
     * Get view template
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getViewTemplate()
    {
        return 'form_field/inline/input/text/price.tpl';
    }

    /**
     * Get initial field parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFieldParams()
    {
        return parent::getFieldParams()
            + array(\XLite\View\FormField\Input\Text\Base\Numeric::PARAM_MOUSE_WHEEL_ICON => false);
    }

}


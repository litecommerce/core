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
class Price extends \XLite\View\FormField\Inline\Base\Single
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
     * @param array $field Field
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getViewValue(array $field)
    {
        $value = parent::getViewValue($field);
        $sign = 0 <= $value ? '' : '&minus;&#8197';

        return $sign . $field['widget']->getCurrency()->formatValue(abs($value));
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
     * Get currency 
     * 
     * @return \XLite\Model\Currency
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getCurrency()
    {
        return $this->getSingleFieldAsWidget()->getCurrency();
    }

    /**
     * Get initial field parameters
     *
     * @param array $field Field data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFieldParams(array $field)
    {
        return parent::getFieldParams($field)
            + array(\XLite\View\FormField\Input\Text\Base\Numeric::PARAM_MOUSE_WHEEL_ICON => false);
    }
    /**
     * Get field value from entity
     *
     * @param array $field Field
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getFieldEntityValue(array $field)
    {
        return doubleval(parent::getFieldEntityValue($field));
    }

}


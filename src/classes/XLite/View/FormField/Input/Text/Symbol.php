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

namespace XLite\View\FormField\Input\Text;

/**
 * Input with symbol
 *
 * @see   ____class_see____
 * @since 1.0.15
 */
class Symbol extends \XLite\View\FormField\Input\Text\Float
{
    /**
     * Widget param names
     */
    const PARAM_SYMBOL = 'symbol';

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
        $list[] = $this->getDir() . '/input/symbol.css';

        return $list;
    }

    /**
     * Register CSS class to use for wrapper block (SPAN) of input field.
     * It is usable to make unique changes of the field.
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getWrapperClass()
    {
        return trim(parent::getWrapperClass() . ' input-text-symbol');
    }

    /**
     * Return symbol
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSymbol()
    {
        return $this->getParam(static::PARAM_SYMBOL);
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
        return 'input/symbol.tpl';
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_SYMBOL => new \XLite\Model\WidgetParam\String('Symbol', ''),
        );
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleClasses(array $classes)
    {
        $classes = parent::assembleClasses($classes);
        $classes[] = 'symbol';

        return $classes;
    }
}

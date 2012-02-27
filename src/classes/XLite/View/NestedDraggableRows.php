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
 * @since     1.0.14
 */

namespace XLite\View;

/**
 * NestedDraggableRows
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
class NestedDraggableRows extends \XLite\View\AView
{
    /**
     * Widget param names
     */
    const PARAM_ENTRIES         = 'entries';
    const PARAM_STYLE_CLASSES   = 'style_classes';

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

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
        $list[] = $this->getDir() . '/controller.js';

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
        return $this->getDir() . '/body.tpl';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getDir()
    {
        return 'nested_draggable_rows';
    }

    /**
     * Return CSS classes for main container
     *
     * @return string
     * @see    ____func_see____
     * since   1.0.14
     */
    protected function getStyleClasses()
    {
        return 'draggable-rows ' . ($this->getParam(static::PARAM_STYLE_CLASSES)?: '');
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ENTRIES       => new \XLite\Model\WidgetParam\Collection('Entries list', array()),
            static::PARAM_STYLE_CLASSES => new \XLite\Model\WidgetParam\String('CSS style classes', ''),
        );
    }

    /**
     * Return widget class name for the rows
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getRowWidgetClass()
    {
        return '\XLite\View\NestedDraggableRows\Row';
    }

    /**
     * Alias
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getEntries()
    {
        return $this->getParam(static::PARAM_ENTRIES);
    }
}

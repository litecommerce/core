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

namespace XLite\View\DraggableRows;

/**
 * Row 
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
class Row extends \XLite\View\AView
{
    /**
     * Widget param names
     */
    const PARAM_INTERNAL_WIDGET = 'internalWidget';

    /**
     * Return widget template path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getDefaultTemplate()
    {
        return 'draggable_rows/row/body.tpl';
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_INTERNAL_WIDGET=> new \XLite\Model\WidgetParam\Object(
                'Entry', null, false, '\XLite\View\DraggableRows\Row\ARow'
            ),
        );
    }

    /**
     * Alias
     *
     * @return \XLite\View\AView
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getInternalWidget()
    {
        return $this->getParam(static::PARAM_INTERNAL_WIDGET);
    }

    /**
     * Alias
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getInternalWidgetId()
    {
        return $this->getInternalWidget()->getRowUniqueId();
    }

    /**
     * Return name of the "position" field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getInternalWidgetPosFieldName()
    {
        return $this->getInternalWidget()->getPosInputName();
    }

    /**
     * Return value of the "position" field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getInternalWidgetPosFieldValue()
    {
        return $this->getInternalWidget()->getPosInputValue();
    }

    /**
     * Method to display row content
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function displayInternalWidget()
    {
        $this->getInternalWidget()->display();
    }
}

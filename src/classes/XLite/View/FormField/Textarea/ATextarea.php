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
 * Abstract class for textarea widget
 *
 */
abstract class ATextarea extends \XLite\View\FormField\AFormField
{
    /**
     *  Number of rows in textarea widget (HTML attribute)
     */
    const PARAM_ROWS = 'rows';

    /**
     *  Number of columns in textarea widget (HTML attribute)
     */
    const PARAM_COLS = 'cols';


    /**
     * Return field type
     *
     * @return string
     */
    public function getFieldType()
    {
        return self::FIELD_TYPE_TEXTAREA;
    }

    /**
     * Rows getter
     *
     * @return integer
     */
    public function getRows()
    {
        return $this->getParam(static::PARAM_ROWS);
    }

    /**
     * Columns getter
     *
     * @return integer
     */
    public function getCols()
    {
        return $this->getParam(static::PARAM_COLS);
    }

    /**
     * Return default value of 'rows' HTML attribute.
     *
     * @return integer
     */
    protected function getDefaultRows()
    {
        return 10;
    }

    /**
     * Return default value of 'cols' HTML attribute.
     *
     * @return integer
     */
    protected function getDefaultCols()
    {
        return 50;
    }

    /**
     * getCommonAttributes
     *
     * @return array
     */
    protected function getCommonAttributes()
    {
        return parent::getCommonAttributes() + array(
            static::PARAM_ROWS => $this->getRows(),
            static::PARAM_COLS => $this->getCols(),
        );
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ROWS => new \XLite\Model\WidgetParam\Int('Rows', $this->getDefaultRows()),
            static::PARAM_COLS => new \XLite\Model\WidgetParam\Int('Cols', $this->getDefaultCols()),
        );
    }
}

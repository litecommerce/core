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
 * @since     1.0.2
 */

namespace XLite\View;

/**
 * \XLite\View\CurrencySelect
 *
 * @see   ____class_see____
 * @since 1.0.2
 */
class CurrencySelect extends \XLite\View\FormField
{
    /**
     * Widget param names
     */

    const PARAM_ALL        = 'all';
    const PARAM_FIELD_NAME = 'field';
    const PARAM_CURRENCY   = 'currency';
    const PARAM_FIELD_ID   = 'fieldId';
    const PARAM_CLASS_NAME = 'className';


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_currency.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ALL        => new \XLite\Model\WidgetParam\Bool('All', true),
            self::PARAM_FIELD_NAME => new \XLite\Model\WidgetParam\String('Field name', ''),
            self::PARAM_FIELD_ID   => new \XLite\Model\WidgetParam\String('Field ID', ''),
            self::PARAM_CLASS_NAME => new \XLite\Model\WidgetParam\String('Class name', ''),
            self::PARAM_CURRENCY   => new \XLite\Model\WidgetParam\Int('Value', 840)
        );
    }

    /**
     * Check - display used only currency or all
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function usedOnly()
    {
        return !$this->getParam(self::PARAM_ALL);
    }

    /**
     * Return currencies list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function getCurrencies()
    {
        return $this->usedOnly()
            ? \XLite\Core\Database::getRepo('XLite\Model\Currency')->findUsed()
            : \XLite\Core\Database::getRepo('XLite\Model\Currency')->findAllSortedByName();
    }
}

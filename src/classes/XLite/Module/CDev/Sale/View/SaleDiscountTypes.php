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

namespace XLite\Module\CDev\Sale\View;

/**
 * Product options list
 *
 */
class SaleDiscountTypes extends \XLite\View\AView
{
    /**
     * Sale price value name
     */
    const PARAM_SALE_PRICE_VALUE = 'salePriceValue';

    /**
     * Discount type name
     */
    const PARAM_DISCOUNT_TYPE    = 'discountType';


    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/CDev/Sale/sale_discount_types/js/script.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/Sale/sale_discount_types/css/style.css';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/Sale/sale_discount_types/body.tpl';
    }

    /**
     * Return percent off value.
     *
     * @return integer
     */
    protected function getPercentOffValue()
    {
        return intval($this->getParam('salePriceValue'));
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SALE_PRICE_VALUE => new \XLite\Model\WidgetParam\Float('Sale price value', 0),
            self::PARAM_DISCOUNT_TYPE    => new \XLite\Model\WidgetParam\String('Discount type', \XLite\Model\Product::SALE_DISCOUNT_TYPE_PERCENT),
        );
    }

}

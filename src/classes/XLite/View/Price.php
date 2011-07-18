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

namespace XLite\View;

/**
 * Product price
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Price extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT            = 'product';
    const PARAM_DISPLAY_ONLY_PRICE = 'displayOnlyPrice';


    /**
     * Check - sale price is enabled or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isSalePriceEnabled()
    {
        return \XLite\Core\Config::getInstance()->General->enable_sale_price
            && $this->getProduct()->getSalePrice() > $this->getProduct()->getListPrice();
    }

    /**
     * Check - is save block is enabeld or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isSaveEnabled()
    {
        return ('N' !== \XLite\Core\Config::getInstance()->General->you_save) && (0 < $this->getSaveValuePercent());
    }

    /**
     * Get save value (absolute)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSaveValueAbsolute()
    {
        $product = $this->getProduct();

        return $this->formatPrice($product->getSalePrice() - $product->getListPrice());
    }

    /**
     * Get save value (absolute)
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSaveValuePercent()
    {
        $product = $this->getProduct();

        return round(($product->getSalePrice() - $product->getListPrice()) / $product->getSalePrice() * 100, 0);
    }

    /**
     * Check - display only price or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDisplayOnlyPrice()
    {
        return $this->getParam(self::PARAM_DISPLAY_ONLY_PRICE);
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
        return 'common/price_plain.tpl';
    }


    /**
     * Return list price of product
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function getListPrice()
    {
        return $this->getProduct()->getListPrice();
    }

    /**
     * Return sale price of product
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.2
     */
    protected function getSalePrice()
    {
        return $this->getProduct()->getSalePrice();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object(
                'Product', null, false, '\XLite\Model\Product'
            ),
            self::PARAM_DISPLAY_ONLY_PRICE => new \XLite\Model\WidgetParam\Bool(
                'Only price', false
            ),
        );
    }

    /**
     * getProduct
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProduct()
    {
        return $this->getParam(self::PARAM_PRODUCT);
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getProduct();
    }
}

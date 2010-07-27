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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Product price
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Price extends AView
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT            = 'product';
    const PARAM_DISPLAY_ONLY_PRICE = 'displayOnlyPrice';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/price_plain.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PRODUCT            => new \XLite\Model\WidgetParam\Object(
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct()
    {
        return $this->getParam(self::PARAM_PRODUCT);
    }


    /**
     * Check widget visibility
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getProduct();
    }

    /**
     * Check - sale price is enabled or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSalePriceEnabled()
    {
        return $this->config->General->enable_sale_price
            && $this->getProduct()->getSalePrice() > $this->getProduct()->getListPrice();
    }

    /**
     * Check - is save block is enabeld or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSaveEnabled()
    {
        return ('N' !== $this->config->General->you_save) && (0 < $this->getSaveValuePercent());
    }

    /**
     * Get save value (absolute)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSaveValueAbsolute()
    {
        $product = $this->getProduct();

        return $this->price_format($product->getSalePrice() - $product->getListPrice());
    }

    /**
     * Get save value (absolute)
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayOnlyPrice()
    {
        return $this->getParam(self::PARAM_DISPLAY_ONLY_PRICE);
    }
}

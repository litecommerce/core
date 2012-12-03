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

namespace XLite\Module\CDev\MarketPrice;

/**
 * Main
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'X-Cart team';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Market price';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Adds support for the product market price.';
    }

    /**
     * Determine if we need to display product market price
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return boolean
     */
    public static function isShowMarketPrice(\XLite\Model\Product $product)
    {
        return 0 < static::getProductPrice($product) 
            && static::getProductMarketPrice($product) > static::getProductPrice($product);
    }

    /**
     * Get the "You save" value
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return float
     */
    public static function getSaveDifferenceAbsolute(\XLite\Model\Product $product)
    {
        return static::getProductMarketPrice($product) - static::getProductPrice($product);
    }

    /**
     * Get the "You save" value in percents
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return float
     */
    public static function getSaveDifferenceInPercents(\XLite\Model\Product $product)
    {
        return min(99, round((static::getSaveDifferenceAbsolute($product) / $product->getMarketPrice()) * 100));
    }

    /**
     * Get the "X% less" label
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return array
     */
    public static function getLabels(\XLite\Model\Product $product)
    {
        $result  = array();
        $percent = static::getSaveDifferenceInPercents($product);

        if (0 < $percent) {
            $result['orange market-price'] = $percent . '% ' 
                . \XLite\Core\Translation::getInstance()->translate('less');
        }

        return $result;
    }

    /**
     * Wrapper to get product price
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return void
     */
    protected static function getProductPrice(\XLite\Model\Product $product)
    {
        return $product->getDisplayPrice();
    }

    /**
     * Wrapper to get product list price
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return void
     */
    protected static function getProductMarketPrice(\XLite\Model\Product $product)
    {
        return $product->getMarketPrice();
    }
}

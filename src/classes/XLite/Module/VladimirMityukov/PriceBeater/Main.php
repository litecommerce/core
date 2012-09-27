<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
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
 * @category  LiteCommerce
 * @author    Vladimir Mityukov <mityukov@gmail.com>
 * @copyright Copyright (c) 2012 Vladimir Mityukov <mityukov@gmail.com>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   0.0.1
 */

namespace XLite\Module\VladimirMityukov\PriceBeater;

/**
 * The module offers an ability to purchase at smaller
 * price if URL to competitor's website provided.
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Return the developer name (company name).
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Vladimir Mityukov';
    }

    /**
     * Return the module display name.
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Price beater';
    }

     /**
      * Return the minor module version (revision number).
      *
      * @return string
      */
     public static function getMinorVersion()
     {
         return '1';
     }

     /**
      * Return an URL to the module icon.
      * If an empty string is returned "icon.png" from the module directory will be used.
      *
      * @return string
      */
    public static function getIconURL()
    {
        return '';
    }

    /**
     * Return a brief module description.
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Provides Price beater functionality.';
    }

    /**
     * Return a list of modules the module depends on.
     * Each item should be a full module identifier: "<Developer>\<Module>".
     *
     * @return array
     */
    public static function getDependencies()
    {
        return array();
    }    

    /**
     * Determine if we need to display price beater icon/widget
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isShowPriceBeater(\XLite\Model\Product $product)
    {
    	return 0 < static::getProductPrice($product)
    	&& static::getProductPriceBeaterThreshold($product) < static::getProductPrice($product);
    }
    
    
    /**
     * Wrapper to get product price
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getProductPrice(\XLite\Model\Product $product)
    {
    	return $product->getDisplayPrice();
    }
    
    /**
     * Wrapper to get price beater threshold
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getProductPriceBeaterThreshold(\XLite\Model\Product $product)
    {
    	return $product->getPriceBeaterThreshold();
    }
}
?>
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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\Controller\Customer;

/**
 * \XLite\Module\ProductOptions\Controller\Customer\Wishlist 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Wishlist extends \XLite\Module\WishList\Controller\Customer\Wishlist implements \XLite\Base\IDecorator
{
    /**
     * Widget param names 
     */

    const PARAM_PRODUCT_OPTIONS = 'product_options';


    /**
     * setProductOptions 
     * 
     * @param \XLite\Module\WishList\Model\WishListProduct $product current item
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setProductOptions(\XLite\Module\WishList\Model\WishListProduct $product)
    {
        $options = $this->getParam(self::PARAM_PRODUCT_OPTIONS);

        if (!empty($options)) {
            $product->setProductOptions($options);
        }
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
            self::PARAM_PRODUCT_OPTIONS => new \XLite\Model\WidgetParam\Collection('Product options', array()),
        );
    }

    /**
     * getWishListProduct
     *
     * @return \XLite\Module\WishList\Model\WishListProduct
     * @access protected
     * @since  3.0.0
     */
    protected function getWishListProduct()
    {
        $product = parent::getWishListProduct();

        // Set options here for correct calculation of wishlist item ID
        $this->setProductOptions($product);

        return $product;
    }

    /**
     * prepareWishListItem
     *
     * @param \XLite\Module\WishList\Model\WishListProduct $product item to prepare
     * @param bool                                         $status  if item exists or not
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function prepareWishListItem(\XLite\Module\WishList\Model\WishListProduct $product, $status)
    {
        parent::prepareWishListItem($product, $status);

        // We need to call the "setProductOptions()" function again
        // if item was not found, and properties were clared in the "find()" method
        if (!$status) {
            $this->setProductOptions($product);
        }
    }
}

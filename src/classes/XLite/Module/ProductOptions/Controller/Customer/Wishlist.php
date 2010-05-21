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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_ProductOptions_Controller_Customer_Wishlist 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_ProductOptions_Controller_Customer_Wishlist extends XLite_Module_WishList_Controller_Customer_Wishlist implements XLite_Base_IDecorator
{
    /**
     * Widget param names 
     */

    const PARAM_PRODUCT_OPTIONS = 'product_options';


    /**
     * setProductOptions 
     * 
     * @param XLite_Module_WishList_Model_WishListProduct $product current item
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setProductOptions(XLite_Module_WishList_Model_WishListProduct $product)
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
            self::PARAM_PRODUCT_OPTIONS => new XLite_Model_WidgetParam_Array('Product options', array()),
        );
    }

    /**
     * getWishListProduct
     *
     * @return XLite_Module_WishList_Model_WishListProduct
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
     * @param XLite_Module_WishList_Model_WishListProduct $product item to prepare
     * @param bool                                        $status  if item exists or not
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function prepareWishListItem(XLite_Module_WishList_Model_WishListProduct $product, $status)
    {
        parent::prepareWishListItem($product, $status);

        // We need to call the "setProductOptions()" function again
        // if item was not found, and properties were clared in the "find()" method
        if (!$status) {
            $this->setProductOptions($product);
        }
    }
}

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

namespace XLite\Module\CDev\ProductOptions\Controller\Customer;

/**
 * Cart controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Cart extends \XLite\Controller\Customer\Cart implements \XLite\Base\IDecorator
{
    /**
     * Options invalid flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $optionInvalid = false;


    /**
     * Return product options that are requested to add to cart with a provided product.
     *
     * @param \XLite\Model\Product $product Product class that is requested to add to cart
     *
     * @return null|array
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getCurrentProductOptions(\XLite\Model\Product $product)
    {
        if (isset(\XLite\Core\Request::getInstance()->product_options)) {

            $options = $product->prepareOptions(\XLite\Core\Request::getInstance()->product_options);

            if (!$product->checkOptionsException($options)) {

                $options = null;
            }

        } else {

            $options = $product->getDefaultProductOptions();
        }

        return $options;
    }


    /**
     * Get (and create) current cart item.
     * Change order item options from customer request.
     *
     * @return \XLite\Model\OrderItem
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentItem()
    {
        $item = parent::getCurrentItem();

        if ($item->getProduct() && $item->getProduct()->hasOptions()) {

            // We take a product options array from customer request
            $options = $this->getCurrentProductOptions($item->getProduct());

            if (is_array($options)) {

                $item->setProductOptions($options);

            } else {

                $this->optionInvalid = true;
            }
        }

        return $item;
    }

    /**
     * 'add' action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionAdd()
    {
        parent::doActionAdd();

        // check for valid ProductOptions
        if ($this->optionInvalid) {

            // Save wrong options set
            $request = \XLite\Core\Request::getInstance();

            \XLite\Core\Session::getInstance()->saved_invalid_options = array(
                $request->product_id => $request->product_options,
            );

            \XLite\Core\TopMessage::getInstance()->clear();

            \XLite\Core\TopMessage::addError(
                'The product options you have selected are not valid or fall into an exception.'
                . ' Please select other product options and add the product to cart once again.'
            );

            $this->setReturnURL(
                $this->buildURL(
                    'product',
                    '',
                    array('product_id' => \XLite\Core\Request::getInstance()->product_id)
                )
            );

        } else {

            \XLite\Core\Session::getInstance()->saved_invalid_options = null;
        }
    }
}

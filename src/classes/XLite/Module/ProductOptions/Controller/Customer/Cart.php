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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Controller\Customer\Cart implements \XLite\Base\IDecorator
{
    /**
     * Options invalid flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $optionInvalid = false;

    /**
     * Get (and create) current cart item
     *
     * @param \XLite\Model\Product $product product to add
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentItem(\XLite\Model\Product $product)
    {
        if (!isset($this->currentItem)) {
            parent::getCurrentItem($product);

            // set item options if present
            if (
                is_object($this->currentItem->getProduct())
                && $this->currentItem->getProduct()->hasOptions()
            ) {

                if (isset(\XLite\Core\Request::getInstance()->product_options)) {
                    $options = $this->currentItem->getProduct()
                        ->prepareOptions(\XLite\Core\Request::getInstance()->product_options);
                    if (!$this->currentItem->getProduct()->checkOptionsException($options)) {
                        $options = null;
                    }

                } else {
                    $options = $this->currentItem->getProduct()
                        ->getDefaultProductOptions();
                }

                if (is_array($options)) {
                    $this->currentItem->setProductOptions($options);

                } else {
                    $this->optionInvalid = true;
                }
            }
        }

        return $this->currentItem;
    }

    /**
     * 'add' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        parent::doActionAdd();

        // check for valid ProductOptions
        if ($this->optionInvalid) {

            // Save wrong options set
            $this->session->set(
                'saved_invalid_options',
                array(
                    \XLite\Core\Request::getInstance()->product_id => \XLite\Core\Request::getInstance()->product_options,
                )
            );

            // Delete item from cart and switch back to product details
            $key = $this->getCurrentItem()->getKey();
            foreach ($this->getCart()->getItems() as $i) {
                if ($i->getKey() == $key) {
                    $this->cart->deleteItem($i);
                    break;
                }
            }
            $this->updateCart();

            \XLite\Core\TopMessage::getInstance()->add(
                'Product has not been added to cart',
                \XLite\Core\TopMessage::ERROR
            );

            $this->setReturnUrl(
                $this->buildUrl(
                    'product',
                    '',
                    array('product_id' => \XLite\Core\Request::getInstance()->product_id)
                )
            );

        } else {
            $this->session->set('saved_invalid_options', null);
        }
    }
}


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
 * @subpackage Controller_
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\InventoryTracking\Controller\Customer;

/**
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Controller\Customer\Cart
implements \XLite\Base\IDecorator
{
    /**
     * Additional return URL
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $addReturnUrl = null;

    /**
     * Recalculates the shopping cart
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function updateCart()
    {
        parent::updateCart();

        if (
            \XLite\Core\Request::getInstance()->action == 'add'
            && !is_null($this->getCart()->get('outOfStock'))
        ) {
            $productId = $this->getCart()->get('outOfStock');
            $categoryId = intval(\XLite\Core\Request::getInstance()->category_id);
            if (0 == $categoryId) {
                $product = new \XLite\Model\Product($productId);
                $categoryId = $product->getComplex('category.category_id');
            }

            $this->addReturnUrl = $this->buildUrl(
                'product',
                '',
                array('product_id' => $productId, 'category_id' => $categoryId, 'mode' => 'out_of_stock')
            );
        }

        if (
            \XLite\Core\Request::getInstance()->action == 'add'
            && $this->getCart()->get('exceeding')
        ) {
            $this->addReturnUrl = $this->buildUrl('cart', '', array('mode' => 'exceeding'));
        }
    }

    /**
     * Add to cart
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        parent::action_add();

        if ($this->addReturnUrl) {
            $this->set('returnUrl', $this->addReturnUrl);
        }
    }
}


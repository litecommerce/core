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

namespace XLite\Module\CDev\WishList\Controller\Customer;

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
     * Add item to cart (from wishlist)
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        $wishlistId = \XLite\Core\Request::getInstance()->wishlist_id;
        $itemId = \XLite\Core\Request::getInstance()->item_id;
        $amount = intval(\XLite\Core\Request::getInstance()->amount);

        if (!is_null($wishlistId) && !is_null($itemId)) {

            // process this wishlist
            parent::getCurrentItem();

            $wishlistProduct = new \XLite\Module\CDev\WishList\Model\WishListProduct($itemId, $wishlistId);
            
            if (!$wishlistProduct->isOptionsExist()) {

                \XLite\Core\TopMessage::getInstance()->add(
                    'Sorry, but some options of "'
                    . $wishlistProduct->getProduct()->get('name')
                    . '" do not exist anymore and you can not add this product to the cart.',
                    \XLite\Core\TopMessage::ERROR
                );
                $this->set(
                    'returnUrl',
                    $this->buildUrl('cart')
                );

                return;

            } elseif ($wishlistProduct->isOptionsInvalid()) {

                \XLite\Core\TopMessage::getInstance()->add(
                    'Sorry, but options of "'
                    . $wishlistProduct->getProduct()->get('name')
                    . '" are invalid. You coudn\'t add product to cart.',
                    \XLite\Core\TopMessage::ERROR
                );
                $this->set(
                    'returnUrl',
                    $this->buildUrl('cart')
                );

                return;

            }

            if (0 > $amount) {
                $amount = $wishlistProduct->get('amount');
            }

            $this->currentItem->set('options', $wishlistProduct->get('options'));
            $this->currentItem->set('amount', \XLite\Core\Request::getInstance()->amount);
        }

        parent::action_add();

        if (isset($wishlistProduct)) {

            // TODO - add adding operation status - product must be remove only after SUCCESSFULL adding operation

            if ($wishlistProduct->get('amount') <= $amount) {
                $wishlistProduct->delete();

            } else {
                $wishlistProduct->set('amount', $wishlistProduct->get('amount') - $amount);
                $wishlistProduct->update();
            }
        }
    }

}

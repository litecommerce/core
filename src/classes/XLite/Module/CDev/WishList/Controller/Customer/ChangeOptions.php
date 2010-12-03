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
 * Change options from wishlist item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ChangeOptions extends \XLite\Module\CDev\ProductOptions\Controller\Customer\ChangeOptions implements \XLite\Base\IDecorator
{
    /**
     * Assemble return url 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleReturnUrl()
    {
        parent::assembleReturnUrl();

        if (\XLite\Core\Request::getInstance()->source == 'wishlist') {
            $this->set('returnUrl', $this->buildUrl('wishlist'));
        }
    }

    /**
     * Change product options
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionChange()
    {
        parent::doActionChange();

        if (\XLite\Core\Request::getInstance()->source == 'wishlist') {
            // TODO - add exception checking

            $this->getItem()->setProductOptions(\XLite\Core\Request::getInstance()->product_options);
            $this->getItem()->update();

            \XLite\Core\TopMessage::getInstance()->add('Options has been successfully changed');
        }
    }

    /**
     * Get cart / wishlist item 
     * 
     * @return \XLite\Model\OrderItem
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItem()
    {
        if (is_null($this->item)) {
            if (
                \XLite\Core\Request::getInstance()->source == 'wishlist'
                && \XLite\Core\Request::getInstance()->item_id
                && is_numeric(\XLite\Core\Request::getInstance()->storage_id)
                && 0 < \XLite\Core\Request::getInstance()->storage_id
            ) {
                $this->item = new \XLite\Module\CDev\WishList\Model\WishListProduct(
                    \XLite\Core\Request::getInstance()->item_id,
                    \XLite\Core\Request::getInstance()->storage_id
                );

                if (!$this->item->isExists()) {
                    $this->item = false;
                }

            } else {
                parent::getItem();
            }
        }

        return $this->item;
    }
}

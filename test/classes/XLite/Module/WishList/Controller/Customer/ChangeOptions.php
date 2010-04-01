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

/**
 * Change options from wishlist item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Customer_ChangeOptions extends XLite_Module_ProductOptions_Controller_Customer_ChangeOptions implements XLite_Base_IDecorator
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

        if (XLite_Core_Request::getInstance()->source == 'wishlist') {
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

        if (XLite_Core_Request::getInstance()->source == 'wishlist') {
            // TODO - add exception checking

            $this->getItem()->setProductOptions(XLite_Core_Request::getInstance()->product_options);
            $this->getItem()->update();

            XLite_Core_TopMessage::getInstance()->add('Options has been successfully changed');
        }
	}

    /**
     * Get cart / wishlist item 
     * 
     * @return XLite_Model_OrderItem
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItem()
    {
        if (is_null($this->item)) {
            if (
                XLite_Core_Request::getInstance()->source == 'wishlist'
                && XLite_Core_Request::getInstance()->item_id
                && is_numeric(XLite_Core_Request::getInstance()->storage_id)
                && 0 < XLite_Core_Request::getInstance()->storage_id
            ) {
                $this->item = new XLite_Module_WishList_Model_WishListProduct(
                    XLite_Core_Request::getInstance()->item_id,
                    XLite_Core_Request::getInstance()->storage_id
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

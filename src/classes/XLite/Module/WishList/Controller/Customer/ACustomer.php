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
 * Abstract customer interface controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Customer_ACustomer extends XLite_Controller_Customer_ACustomer implements XLite_Base_IDecorator
{
    /**
     * Name of the session cell kept the product ID to add to wishlist
     */
    const SESSION_CELL_WL_PRODUCT_TO_ADD = 'WLProductToAdd';


    /**
     * wishlist 
     * 
     * @var    XLite_Module_WishList_Model_WishList
     * @access protected
     * @since  3.0.0
     */
    protected $wishlist = null;

    /**
     * Get wishlist 
     * 
     * @return XLite_Module_WishList_Model_WishList
     * @access public
     * @since  3.0.0
     */
    public function getWishList()
    {
        $profile = XLite_Model_Auth::getInstance()->getProfile(XLite_Core_Request::getInstance()->profile_id);

        if (!isset($this->wishlist) && isset($profile)) {
            $this->wishlist = new XLite_Module_WishList_Model_WishList();
            $profileId = $profile->get('profile_id');

            if (!$this->wishlist->find('profile_id = \'' . $profileId . '\'')) {
                $this->wishlist->set('profile_id', $profileId);
                $this->wishlist->set('date', time());
                $this->wishlist->create();
            }
        }

        return $this->wishlist;
    }
}

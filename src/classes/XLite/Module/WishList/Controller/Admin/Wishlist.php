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
 * Wishlist admin controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Admin_Wishlist extends XLite_Controller_Admin_Abstract
{
    /**
     * wishlist 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $wishlist = null;

    /**
     * getRegularTemplate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        if ('print' == XLite_Core_Request::getInstance()->mode) {
            $return = 'modules/WishList/wishlist.tpl';

        } else {
            $return = parent::getRegularTemplate();
        }

        return $return;
    }

    /**
     * getWishlist 
     * 
     * @return XLite_Module_WishList_Model_WishList
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWishlist()
    {
        if (is_null($this->wishlist)) {
            $this->wishlist = new XLite_Module_WishList_Model_WishList(XLite_Core_Request::getInstance()->wishlist_id);
        }

        return $this->wishlist;
    }
    
    /**
     * Do action 'delete'
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionDelete()
    {
        $wishlist = new XLite_Module_WishList_Model_WishList(XLite_Core_Request::getInstance()->wishlist_id);

        $wishlistProducts = $wishlist->get('products');

        foreach ($wishlistProducts as $product) {
            $product->delete();
        }

        $wishlist->delete();

        $this->set('returnUrl', $this->buildUrl('wishlists', '', array('mode' => 'search')));
    }

}


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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Wishlist
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_View_Wishlist extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('wishlist');

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Wishlist';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/WishList/wishlist';
    }

    /**
     * Return file name for body template
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return $this->getItems() ? parent::getBodyTemplate() : 'empty.tpl';
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/wishlist.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/wishlist.css';

        return $list;
    }

    /**
     * Get wishlist items 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItems()
    {
        $result = array();

        $wishlist = $this->getWishlist();
        if ($wishlist) {
            $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();

            $result = $wishlist_product->findAll('wishlist_id = \'' . $wishlist->get('wishlist_id') . '\'');
        }

        return $result;
    }

    /**
     * Get wishlist 
     * 
     * @return XLite_Module_WishList_Model_WishList
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWishlist()
    {
        return XLite::getController()->getWishList();
    }

}


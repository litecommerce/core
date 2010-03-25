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
 * Wishlist base class
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_WishList_Model_WishList extends XLite_Model_Abstract
{

    /**
     * fields 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
	public $fields = array (
		'wishlist_id' => 0,
		'profile_id'  => 0,
		'order_by'	  => 0,
        'date'		  => ''
    );
		
    /**
     * alias 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $alias = 'wishlist';

    /**
     * defaultOrder 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $defaultOrder = 'wishlist_id';

    /**
     * primaryKey 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $primaryKey = array('wishlist_id', 'profile_id');

    /**
     * autoIncrement 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $autoIncrement = 'wishlist_id';

    /**
     * profile   
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
	public $profile	= null;

    /**
     * getProducts 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function getProducts()
	{
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();

        return $wishlist_product->findAll('wishlist_id = ' . $this->get('wishlist_id'));
    }
	
    /**
     * getProfile 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function getProfile()
	{
		if (is_null($this->profile)) { 
			$this->profile = new XLite_Model_Profile($this->get('profile_id'));	
        }

		return $this->profile;
	}

    /**
     * collectGarbage 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function collectGarbage()
	{
		$wishlist = new XLite_Module_WishList_Model_WishList();
        $wishlists = $wishlist->findAll();

		if (is_array($wishlists)) {
			foreach($wishlists as $wishlist_) {
                if (!$wishlist_->get('products')) {
                    $wishlist_->delete();
                }
            }
		}
	}
	
    /**
     * Search wishlists
     * 
     * @param mixed $start_id
     * @param mixed $end_id
     * @param mixed $profile
     * @param mixed $sku
     * @param mixed $name
     * @param mixed $startDate
     * @param mixed $endDate
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function search($start_id, $end_id, $profile, $sku, $name, $startDate, $endDate)
	{
		$where = array();

		if (!empty($start_id)) {
            $where[] = 'wishlist_id >=' . intval($start_id);
        }
        if (!empty($end_id)) {
            $where[] = 'wishlist_id <=' . intval($end_id);
        }
	    if ($profile) {
            $where[] = 'profile_id = \'' . $profile->get('profile_id') . '\'';
        }
        if ($startDate) {
            $where[] = 'date >= ' . $startDate;
        }
        if ($endDate) {
            $where[] = 'date <= ' .$endDate;
        }

		$wishlists = $this->findAll(implode(' AND ', $where), 'date DESC');

        if (!empty($sku) || !empty($name)) {

            $product = new XLite_Model_Product();

			$found = array();
            $found_product = $product->findImportedProduct($sku, '', '', false);

			if ($found_product) {
                $found[] = 'product_id = ' . $found_product->get('product_id');  
            }

            $found_product = $product->findImportedProduct('', '', $name, false);

            if ($found_product) {
                $found[] = 'product_id = ' . $found_product->get('product_id');      
            }

            if (empty($found)) {
                return array();
            }

            $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();

            $wishlist_products = $wishlist_product->findAll(implode(' OR ', $found));

            $wishlist_ids = array();

            foreach ($wishlist_products as $wishlist_product) {
				if (!in_array($wishlist_product->get('wishlist_id'), $wishlist_ids)) {
                    $wishlist_ids[] = $wishlist_product->get('wishlist_id'); 
                }
            }

			foreach($wishlists as $key => $wishlist) {
                if (!in_array($wishlist->get('wishlist_id'), $wishlist_ids)) {
                    unset($wishlists[$key]);
                }
            }
        } 

		return $wishlists;  
	}

    /**
     * Get default search conditions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getDefaultSearchConditions()
    {
        return array(
            'startId'       => '',
			'endId'         => '',
			'email'			=> '',
			'sku'			=> '',
			'productTitle'	=> '',
            'startDate'     => '',
            'endDate'       => '',
            'sortCriterion' => 'date',
            'sortOrder'     => 'desc'
        );
    }

}


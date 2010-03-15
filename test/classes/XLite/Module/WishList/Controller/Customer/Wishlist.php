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
 * XLite_Module_WishList_Controller_Customer_Wishlist
 * 
 * @package    XLite
 * @subpackage Controller
 * @since      3.0.0
 */
class XLite_Module_WishList_Controller_Customer_Wishlist extends XLite_Controller_Customer_Abstract
{	
	public $params = array("target", "mode");


	/**
     * Common method to determine current location 
     * 
     * @return array 
     * @access protected 
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {
        return 'Wish list';
    }


	
	function action_add() // {{{
	{
		if (!$this->auth->is("logged"))	{
			 $this->set("returnUrl", $this->buildURL('login', '', array('mode' => 'wishlist')));
			 $this->session->set("wishlist_url", $this->buildURL('wishlist', 'add', array('product_id' => $this->product_id)));

	  		 return;
		}
        
        $product = new XLite_Model_Product($this->product_id);
        
		// alternative way to set product options
		if ($this->xlite->get("ProductOptionsEnabled") && isset($this->OptionSetIndex[$product->get("product_id")])) {
			$options_set = $product->get("expandedItems");
			foreach ($options_set[$this->OptionSetIndex[$product->get("product_id")]] as $_opt) {
				$this->product_options[$_opt->class] = $_opt->option_id;	
			}
		}
        
        if (
			$this->xlite->get("ProductOptionsEnabled")
			&& $product->hasOptions()
			&& !isset($this->product_options)
		) {
            $this->set("returnUrl", $this->buildURL('product', '', array('product_id' => $this->product_id)));
            return;
        }

        $wishlist = $this->get("wishList");
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
        
        $wishlist_product->set("product_id", $this->get("product_id"));

        $wishlist_product->set("wishlist_id", $wishlist->get("wishlist_id"));
        $orderItem  = $wishlist_product->get("orderItem");
        if (isset($this->product_options)) {
            $wishlist_product->setProductOptions($this->product_options);
            if (version_compare(PHP_VERSION, '5.0.0')===-1) {
				$orderItem->setProductOptions($this->product_options);
			}
        }

		$wishlist_product->set("item_id", $orderItem->get("key"));
        $found = $wishlist_product->find("item_id = '" . addslashes($wishlist_product->get("item_id")) . "' AND wishlist_id = '" . $wishlist->get("wishlist_id"). "'");

        $amount = $wishlist_product->get("amount");
        $amount += isset($this->amount) ? $this->amount : 1;

        $wishlist_product->set("amount",$amount);

        if ($found) {
        	$wishlist_product->update();
        } else {
        	$wishlist_product->create();
        }
	} // }}} 

	function getItems() // {{{
	{
		$wishlist = $this->get("wishList");
		if (!$wishlist) return false; 
		$wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
		return $wishlist_product->findAll("wishlist_id = '" . $wishlist->get("wishlist_id") ."'");
		
	} // }}}  
	
	function action_delete() // {{{
	{
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct($this->get("item_id"),$this->get("wishlist_id"));
        $wishlist_product->delete();

	} // }}} 

	function action_update() // {{{ 
	{
		$wishlist_product = new XLite_Module_WishList_Model_WishListProduct($this->get("item_id"), $this->get("wishlist_id"));

		$properties = $this->get("properties");
        if ($properties['wishlist_amount'] <= 0) {
			$this->action_delete();
		}

		$wishlist_product->set("amount", $properties['wishlist_amount']);

		$wishlist_product->update();
	} // }}}	

	function action_send() // {{{
	{
		$Mailer = new XLite_Model_Mailer();
		$Mailer->wishlist_recipient = $this->wishlist_recipient;
		$Mailer->items = $this->get("items");
		$Mailer->customer = $this->auth->getComplex('profile.billing_firstname') . ' ' . $this->auth->getComplex('profile.billing_lastname');
		$Mailer->compose($this->config->Company->site_administrator, $this->wishlist_recipient, 'modules/WishList/send');
		$Mailer->send();	
		$this->set("mode","MessageSent");
		
	} // }}}

	function action_clear() // {{{
	{
		foreach ($this->get("items") as $item) {
			$item->delete();
		}
	} // }}}

    function _needConvertToIntStr($name) // {{{
    {
        if ($name == "item_id") {
        	return false;
		}

        return parent::_needConvertToIntStr($name);
    } // }}}

    /**
     * Get page instance data (name and URL)
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
        $this->target = 'wishlist';

        return parent::getPageInstanceData();
    }

    /**
     * Get page title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTitle()
    {
        return 'Wishlist';
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        return array_merge(parent::getCSSFiles(), array('modules/WishList/wishlist.css'));
    }

} // }}}

<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4 foldmethod=marker: */

/**
* WishList module dialog class.
*
* @package Dialog_WishList
* @access public
* @version $Id$
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
     * Get page type name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeName()
    {
        return 'Wishlist';
    }

} // }}}

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
	var $params = array("target", "mode");
	
	function action_add() // {{{
	{
        include_once "modules/WishList/encoded.php";
		Module_WishList_action_add($this);
	} // }}} 

	function getItems() // {{{
	{
		$wishlist = $this->get("wishList");
		if (!$wishlist) return false; 
		$wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
		return $wishlist_product->findAll("wishlist_id ='" . $wishlist->get("wishlist_id") ."'");
		
	} // }}}  
	
	function action_delete() // {{{
	{
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct($this->get("item_id"),$this->get("wishlist_id"));
        $wishlist_product->delete();

	} // }}} 

	function action_update() // {{{ 
	{
		$wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
		$properties = $this->get("properties");
        if ($properties['wishlist_amount']<=0) $this->action_delete();
		$wishlist_product->set("amount",$properties['wishlist_amount']);
		$wishlist_product->set("item_id", $properties['item_id']);
		$wishlist_product->set("wishlist_id",$properties['wishlist_id']);
		$wishlist_product->update();
	} // }}}	

	function action_send() // {{{
	{
		$Mailer = new XLite_Model_Mailer();
		$Mailer->wishlist_recipient = $this->wishlist_recipient;
		$Mailer->items = $this->get("items");
		$Mailer->customer = $this->auth->get("profile.billing_firstname")." ".$this->auth->get("profile.billing_lastname");
		$Mailer->compose($this->get("config.Company.site_administrator"),$this->wishlist_recipient,"modules/WishList/send");
		$Mailer->send();	
		$this->set("mode","MessageSent");
		
	} // }}}

	function action_clear() // {{{
	{
		foreach($this->get("items") as $item) 
			$item->delete();	
			
	} // }}}

    function _needConvertToIntStr($name) // {{{
    {
        if ($name == "item_id") 
        	return false;

        return parent::_needConvertToIntStr($name);
    } // }}}

} // }}}


// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

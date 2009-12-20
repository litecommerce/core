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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* WishList module cart dialog class.
*
* @package Module_WishList
* @access public
* @version $Id$
*/

class WishList_Dialog_cart extends Dialog_cart // {{{ 
{
	var $currentItem = null;
	
	function action_add() // {{{
	{
		if (isset($this->wishlist_id)&&isset($this->item_id)) {
			// process this wishlist
			$this->currentItem = parent::get("currentItem");
			$wishlist_product = & func_new("WishListProduct",$this->item_id,$this->wishlist_id);
			$product = $wishlist_product->getProduct();
			
			if (!$wishlist_product->isOptionsExist()) {
				$this->set("returnUrl", "cart.php?target=wishlist&absentOptions=1&invalidProductName=" . $product->get("name"));
				return;				
			} elseif ($wishlist_product->isOptionsInvalid()) {				
				$this->set("returnUrl", "cart.php?target=wishlist&invalidOptions=1&invalidProductName=" . $product->get("name"));
				return;
			} else {
				$this->currentItem->set("options",$wishlist_product->get("options"));
				$this->currentItem->set("amount",$this->get("wishlist_amount"));
				$this->session->set("wishlist_products",$wishlist_products);
				parent::action_add();
			}
		} else {
			// no wishlists
			parent::action_add();
		}
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

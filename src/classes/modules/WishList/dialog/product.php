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
* Wishlist module product dialog class.
*
* @package Module_Wishlist
* @access public
* @version $Id: product.php,v 1.8 2008/10/23 12:04:59 sheriff Exp $
*/
class WishList_Dialog_product extends Dialog_product
{
	function getSenderName() // {{{
	{
		return isset($this->sender_name) ? $this->sender_name : $this->auth->get("profile.billing_firstname")." ".$this->auth->get("profile.billing_lastname");
	} // }}} 

  	function getSenderEmail() 
    {
        return isset($this->sender_email) ? $this->sender_email : $this->auth->get("profile.login");
    }


	function action_send_friend() // {{{
    {
        $Mailer = & func_new("Mailer");
	    $Mailer->sender_name  = $this->sender_name;
	    $Mailer->sender_email = $this->sender_email;
	    $Mailer->recipient_email = $this->recipient_email;
        $product = & func_new("Product",$this->product_id);
 		$Mailer->product = $product;
        $Mailer->url = $this->shopUrl("cart.php?target=product&product_id=".$this->product_id);
        $Mailer->compose($this->get("sender_email"),$this->get("recipient_email"),"modules/WishList/send_friend");
        $Mailer->send();

        $this->params[] = "mode";
		$this->set("mode","MessageSent");
	  } // }}}
}
// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

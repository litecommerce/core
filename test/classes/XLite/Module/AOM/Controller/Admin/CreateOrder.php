<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
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
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Module_AOM_Controller_Admin_CreateOrder extends XLite_Controller_Admin_Order
{	
	public $page = "order_edit";	
	public $pages = array("order_edit" => "Create order #%s");	
    public $pageTemplates = array 
    (
    	"order_edit" => "modules/AOM/order_edit.tpl",
		"order_preview" => "modules/AOM/order_edit/preview.tpl"
	);

	function init() // {{{
	{
	    $ordersUpdated = $this->session->get("ordersUpdated") ? $this->session->get("ordersUpdated") : array();
		parent::init();
		if(isset($ordersUpdated[$this->get("order_id")])) {
			$order = $this->get("cloneOrder");
			if (!$order->isEmpty()) {
				$this->pages["order_preview"] = "Review and Save Order";
			} else {
				if ($this->page == "order_preview") {
					$this->redirect("admin.php?target=create_order&order_id=".$this->get("order_id")."&page=order_edit");
				}
			}
	    }
	} // }}}

	function getCloneOrder() // {{{
	{
		return parent::getOrder();
	} // }}}

	function getCloneProfile() // {{{
	{
		if (!$this->get("order_id")) {
			return null;
		}
		return parent::getProfile();
	} // }}}

    function action_create_order() // {{{
    {
        $order = new XLite_Model_Order();
        $order->set("date",time());
		$order->set("status","T");
		$order->set("shipping_id","-1");
        $order->create();
        $orderHistory = new XLite_Module_AOM_Model_OrderHistory();
	    $orderHistory->log($order, null, null,"create_order");
						
        $this->set("returnUrl","admin.php?target=create_order&page=order_edit&mode=products&order_id=".$order->get("order_id"));
    } // }}}

	function action_save_changes() // {{{
	{
        $order = new XLite_Model_Order($this->get("order_id"));
        $order->set("orderStatus",$_POST['substatus']);
		if ($order->get("payment_method") == "CreditCard") {
			$this->addDetails($order);
		}
		$order->update();
        $this->cloneUpdated(true);
        $this->set("returnUrl","admin.php?target=order&order_id=".$this->get("order_id")."&page=order_info");
	} // }}}	
	

	function preUpdateProducts()
	{
 	    $order = $this->get("cloneOrder");
        $this->originalShippingId = $order->get("shipping_id");
	}

	function postUpdateProducts()
	{
		if ($this->originalShippingId == -1) {
 	    	$order = $this->get("cloneOrder");
			$order->set("shipping_id", -1);
			$order->set("shipping_cost", 0);
			$order->set("tax", 0);
			$order->set("taxes", "");
			$order->set("subtotal", 0);
			$order->set("total", 0);
			$order->update();
		}
	}

	function action_update_products()
	{
 	    $this->preUpdateProducts();
        parent::action_update_products();
 	    $this->postUpdateProducts();
 	}
	
 	function action_delete_products()
	{
 	    $this->preUpdateProducts();
        parent::action_delete_products();
 	    $this->postUpdateProducts();
	}	
	
	function action_add_products()
	{
 	    $this->preUpdateProducts();
        parent::action_add_products();
 	    $this->postUpdateProducts();
	}

	function action_update_profile()
	{
 	    $this->preUpdateProducts();
        parent::action_update_profile();
 	    $this->postUpdateProducts();
	}

	function action_fill_user()
	{
 	    $this->preUpdateProducts();
        parent::action_fill_user();
 	    $this->postUpdateProducts();
	}

	function action_add_gc()
	{
 	    $this->preUpdateProducts();
        parent::action_add_gc();
 	    $this->postUpdateProducts();
	}

    function action_delete_gc()
	{
 	    $this->preUpdateProducts();
        parent::action_delete_gc();
 	    $this->postUpdateProducts();
	}

	function action_pay_gc()
	{
 	    $this->preUpdateProducts();
        parent::action_pay_gc();
 	    $this->postUpdateProducts();
	}

	function action_clean_gc()
	{
 	    $this->preUpdateProducts();
        parent::action_clean_gc();
 	    $this->postUpdateProducts();
	}

 	function action_add_dc()
	{
 	    $this->preUpdateProducts();
        parent::action_add_dc();
 	    $this->postUpdateProducts();
	}

	function action_del_dc()
	{
 	    $this->preUpdateProducts();
        parent::action_del_dc();
 	    $this->postUpdateProducts();
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

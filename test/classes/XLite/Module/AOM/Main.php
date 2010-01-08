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
* 
*
* @package AOM
* @access public
* @version $Id$
*/

class XLite_Module_AOM_Main extends Module // {{{
{
	var $minVer 			= "2.1.2";
	var	$showSettingsForm 	= true;

	function init() // {{{
	{
		parent::init();

		$this->addDecorator("Order","Module_AOM_Order");
		$this->addDecorator("OrderItem","Module_AOM_OrderItem");
		$this->addDecorator("Profile","Module_AOM_Profile");
		$this->addDecorator("CStatusSelect","Module_AOM_CStatusSelect");
        $this->addDecorator("Admin_Dialog_orders_stats","Module_AOM_Admin_Dialog_orders_stats");
		$this->addDecorator("Widget","Module_AOM_Widget");
        $this->addLayout("common/order_status.tpl","modules/AOM/common/order_status.tpl");
	    $this->addLayout("common/invoice.tpl","modules/AOM/order_info.tpl");
		$this->addLayout("common/print_invoice.tpl","modules/AOM/order_info.tpl");
 	 
		if($this->xlite->is("adminZone")) {
			$this->addDecorator("Admin_Dialog_Order","Module_AOM_Admin_Dialog_order");
			$this->addDecorator("Admin_Dialog_order_list","Module_AOM_Admin_Dialog_order_list");
            $this->addDecorator("Admin_Dialog_module", "Module_AOM_Admin_Dialog_module");
			$this->addDecorator("WysiwygMediator", "Module_AOM_WysiwygMediator");
			$this->addLayout("common/select_status.tpl","modules/AOM/common/select_status.tpl");
            $this->addLayout("order/search_form.tpl","modules/AOM/search_form.tpl");
            $this->addLayout("order/order.tpl","modules/AOM/order.tpl");
				  
		}	
        if ($this->xlite->get("mm.activeModules.GiftCertificates")) {
			$this->xlite->set("GiftCertificatesEnabled",true);
		}	
       	if ($this->xlite->get("mm.activeModules.Promotion")) {
            $this->xlite->set("PromotionEnabled",true);
		}	
        if ($this->xlite->get("mm.activeModules.Egoods")) {
            $this->xlite->set("EgoodsEnabled",true);
        }
        if ($this->xlite->get("mm.activeModules.Affiliate")) {
            $this->xlite->set("AffiliateEnabled",true);
        }
										
		$this->xlite->set("AOMEnabled",true);
	} // }}}

    function uninstall() // {{{ 
    {
        func_cleanup_cache("classes");
        func_cleanup_cache("skins");
        parent::uninstall();
    } // }}}
	
} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

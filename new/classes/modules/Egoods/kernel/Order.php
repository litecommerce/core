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
* @package Module_Egoods
* @access public
* @version $Id$
*/
class Module_Egoods_Order extends Order
{
    function processed() // {{{
    {
		$this->Egoods_processed();
		parent::processed();
    } // }}} 

	function Egoods_processed()
	{
		require_once "modules/Egoods/encoded.php";
		func_moduleEgoods_send_files($this);
		func_moduleEgoods_send_pins($this);
	}

    // assign pin codes to order items
	
	function checkedOut()
	{
		$this->Egoods_checkedOut();
		parent::checkedOut();	
	}
	
	function Egoods_checkedOut()
	{
		$items =& $this->get('items');
		for ($i = 0; $i < count($items); $i++) {
			if ($items[$i]->is('pin') && $items[$i]->get('product.pin_type') == "D") {
				for ($j = 0; $j < $items[$i]->get('amount'); $j++) {
					$pin =& func_new('PinCode');
					if ($pin->find('enabled=1 and product_id=' . $items[$i]->get('product.product_id') . " and item_id='' and order_id=0")) {
						$pin->set('item_id', $items[$i]->get('item_id'));
						$pin->set('order_id', $this->get('order_id'));
						$pin->update();
					}
				}
				
				$pin_settings = &func_new("PinSettings",$items[$i]->get('product.product_id'));
				$pin = &func_new("PinCode");
				if ($pin->getFreePinCount($items[$i]->get('product.product_id'))<= $pin_settings->get("low_available_limit") && $pin_settings->get("low_available_limit")) {
					$mail =& func_new("Module_Egoods_Mailer");
					$mail->item =& $items[$i];
					$product = & func_new("Product");
					$product->find("product_id = " . $items[$i]->get('product.product_id'));
					$mail->product = $product;
					$mail->free_pins = $pin->getFreePinCount($items[$i]->get('product.product_id'));
					$mail->compose($this->config->get("Company.site_administrator"), $this->config->get("Company.site_administrator"), "modules/Egoods/low_available_limit");
					$mail->send();
				}
			}
		}
	}
    
    // free assigned pin codes in case of failure
	function uncheckedOut()
	{
		$this->Egoods_uncheckedOut();
		parent::uncheckedOut();	
	}	
    
	function Egoods_uncheckedOut()
    {
		$items =& $this->get('items');
		for ($i = 0; $i < count($items); $i++) {
			if ($items[$i]->is('pin') && $items[$i]->get('product.pin_type') == "D") {
                $pins =& func_new('PinCode');
                foreach ($pins->findAll("order_id='" . $this->get("order_id") . "' AND item_id='" . $items[$i]->get("item_id") . "'") as $pin) {
                    $pin->set('item_id', '');
                    $pin->set('order_id', 0);
                    $pin->update();
                }
			}
		}
    }

    function isShippingAvailable()
    {
		$items = $this->getItems();
		$egoodsOnly = true;
		if (is_array($items)) {
			for ($i = 0; $i < count($items); $i++) {
				if (!$items[$i]->isEgood()) {
					$egoodsOnly = false;
					break;
				}
			}
		}
		if ($egoodsOnly) {
			return false;
		}

		return parent::isShippingAvailable();
    }

    function declined()
    {
        $this->Egoods_declined(); 
        parent::declined();
    }
	
    function Egoods_declined()
    {
        $items =& $this->get("items");
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]->unStoreLinks();
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

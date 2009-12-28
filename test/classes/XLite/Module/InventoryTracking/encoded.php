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

function func_update_inventory(&$order, &$inventory, &$items)
{
    $amount = $inventory->get("amount");
    // check inventory
    if ($amount <= 0) { // shouldn't be < 0 ..
        // product out of stock, delete these items from cart/order
        for($i = 0; $i < count($items); $i++) {
            $items[$i]->order->deleteItem($items[$i]);
        }
        // set item id
        if (count($items) > 0) {
            $order->set("outOfStock", $items[0]->get("product_id"));
        }
        return;
    }    

    $quantity = 0;
    foreach ($items as $item) {
        $quantity += $item->get("amount");
    }
    // trim items amount to available amount
    if ($amount - $quantity < 0) {
        $index = 0;
        while ($amount >= $items[$index]->get("amount")) {
            $amount -= $items[$index]->get("amount");
            $index++;
        }
        
        $items[$index]->updateAmount($amount);
        $items[$index]->set("outOfStock", true);
        $order->set("exceeding",$items[$index]->get("product_id"));    
    }
}

function func_change_inventory(&$order, $status, &$inventory, &$item)
{
    $amount = $status ? $inventory->get("amount") - $item->get("amount") : $inventory->get("amount") + $item->get("amount");
    $inventory->set("amount", $amount);
    $inventory->update();
    // check low_avail_limit
    if ($order->get("config.InventoryTracking.send_notification")) {
        $inventory->checkLowLimit($item);
    }    

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

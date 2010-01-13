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
* @package SnsIntegration
* @access public
* @version $Id$
*/

class XLite_Module_SnsIntegration_Controller_Admin_SnsIntegration extends XLite_Controller_Admin_Abstract
{
    function action_resendorders() 
    {
        $this->startPage();
        $this->set("silent", 1);
        echo "<html><body>\n";
        require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';

        $orders = new XLite_Model_Order();
        foreach ($orders->findAll("status in ('C', 'P')") as $order) {
            $actions = array();

            foreach ($order->get("items") as $item) {
                if ((!$item->get("product_id")) && (!$item->get("gcid"))) { 
                    continue;
				}
				$itemInfo = $order->getOrderItemInfo($item);
                $action = "";
                $action .= "name=Order";
                $action .= "&orderId=".urlencode($order->get("order_id"));
                $action .= "&productId=".urlencode($itemInfo["id"]);
                $action .= "&productName=".urlencode($itemInfo["name"]);
                $action .= "&total=".urlencode($item->get("total"));
                $action .= "&quantity=".urlencode($item->get("amount"));
                $action .= "&categoryName=".urlencode($itemInfo["category"]);
                $action .= func_sns_profile_params($order->get("profile"));
                $actions[]= $action;
            }
            echo "sending order no ".$order->get("order_id")."<br>";
            flush();
            func_sns_request($order->config, $order->get("snsClientId"), $actions, $order->get("date")); 
        }
        echo "<span style=\"color: #4444ff\">OK</span>";
    }
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

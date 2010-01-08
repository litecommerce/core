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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Module_SnsIntegration_Order description.
*
* @package $Package$
* @version $Id$
*/

class XLite_Module_SnsIntegration_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    var $sendSnsCartChanged = false;

    public function __construct($param = null) // {{{
    {
        $this->fields["snsClientId"] = "";
        parent::__construct($param);
    } // }}}

        // item can be OrderItem of product or OrderItem of GiftCert.
    function getOrderItemInfo($item) {
		$result = array(
		    "id" => "",
	    	"name" => "",
		    "category" => "");
		$product = $item->getProduct();
		if (!is_null($product)) {
		    $result["id"] = $product->get("product_id");
		    $result["name"] = $product->get("name");
		    $result["category"] = $product->get("categories.0.stringPath");
		} else {
		    if ($this->xlite->get("GiftCertificatesEnabled")) {
                $gc = $item->getGC();
                if (!is_null($gc)) {
                    $result["id"] = $gc->get("gcid");
                    $result["name"] = "Gift Certificate";
                    $result["category"] = "Gift Certificates";
                }
		    }
		}
		return $result;
    }

    function SnsIntegration_processed()
    {
		require_once("modules/SnsIntegration/include/misc.php");
		$clientId = $this->get("snsClientId");
        $this->logger->log("clientId = ".$clientId);
		$actions = array();

        foreach ($this->get("items") as $item) {
            if ((!$item->get("product_id")) && (!$item->get("gcid"))) {
                continue;
            }
            $itemInfo = $this->getOrderItemInfo($item);
            $action = "";
            $action .= "name=Order";
            $action .= "&orderId=".urlencode($this->get("order_id"));
            $action .= "&productId=".urlencode($itemInfo["id"]);
            $action .= "&productName=".urlencode($itemInfo["name"]);
            $action .= "&total=".urlencode($item->get("total"));
            $action .= "&quantity=".urlencode($item->get("amount"));
            $action .= "&categoryName=".urlencode($itemInfo["category"]);
            $action .= func_sns_profile_params($this->get("profile"));
			$actions[]= $action;
        }
		func_sns_request($this->config, $this->get("snsClientId"), $actions, $this->get("date"));
    } // }}}

    function processed() // {{{
    {
        parent::processed();
        $this->SnsIntegration_processed();
    } // }}}

    function update() // {{{
    {
        parent::update();
        if ($this->sendSnsCartChanged) {
            $this->sendSnsCartChanged = false;
            $action = "name=CartChanged&itemsCount=" . count($this->get("items")) . "&total=" . $this->get("total");
            require_once("modules/SnsIntegration/include/misc.php");
            $snsClientId = $_COOKIE[PERSONALIZE_CLIENT_ID];
            func_sns_request($this->config, $snsClientId, array($action));
        }
    } // }}}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

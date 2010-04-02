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
* Module_SnsIntegration_Cart description.
*
* @package $Package$
* @version $Id$
*/

class XLite_Module_SnsIntegration_Model_Cart extends XLite_Model_Cart implements XLite_Base_IDecorator
{
    function checkout() 
    {
		require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
        // save client id & session id to the order
		if (!$this->get("snsClientId")) {
        	$this->set("snsClientId", func_get_sns_client_id());
        }
        parent::checkout();
    }

	function clear()
	{
        $action = "name=CartChanged&itemsCount=0&total=0";
        require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
        $snsClientId = func_get_sns_client_id();
        func_sns_request($this->config, $snsClientId, array($action));

		parent::clear();
	}

	function delete()
	{
		parent::delete();
        $action = "name=CartChanged&itemsCount=0&total=0";
        require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
        $snsClientId = func_get_sns_client_id();
        func_sns_request($this->config, $snsClientId, array($action));
	}

	function addItem(XLite_Model_OrderItem $item)
	{
		parent::addItem($item);

		require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
        // save client id & session id to the order
		$snsClientId = func_get_sns_client_id();
		if (!$this->get("snsClientId")) {
        	$this->set("snsClientId", $snsClientId);
        }

		if (!$item->is("valid")) {
			return;
		}
        $itemInfo = $this->getOrderItemInfo($item);
		$actions = array();
		$action = "name=AddToCart";
		$action .= "&productId=".urlencode($itemInfo["id"]);
		$action .= "&productName=".urlencode($itemInfo["name"]);
		$action .= "&categoryName=".urlencode($itemInfo["category"]);

        $this->sendSnsCartChanged = true;
		$actions []= $action;

		$result = func_sns_request($this->config, $snsClientId, $actions);
	}

	function deleteItem($item)
	{
        $itemInfo = $this->getOrderItemInfo($item);
		
		parent::deleteItem($item);
		
		$actions = array();
		$action = "name=DeleteFromCart";
		$action .= "&productId=".urlencode($itemInfo["id"]);
		$action .= "&productName=".urlencode($itemInfo["name"]);
		$action .= "&categoryName=".urlencode($itemInfo["category"]);
		$actions []= $action;

		require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
		$snsClientId = func_get_sns_client_id();
		$result = func_sns_request($this->config, $snsClientId, $actions);

        $this->sendSnsCartChanged = true;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
* @package Module_WholesaleTrading
* @access public
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["access_list"] = "Product access";
        $this->pageTemplates["access_list"] = "modules/WholesaleTrading/product_access/access_list.tpl";
        $this->pages["wholesale_pricing"] = "Wholesale pricing";
        $this->pageTemplates["wholesale_pricing"] = "modules/WholesaleTrading/wholesale_pricing.tpl";
        $this->pages["purchase_limit"] = "Purchase limit";
        $this->pageTemplates["purchase_limit"] = "modules/WholesaleTrading/purchase.tpl";
    }

	function action_update_access()
	{
		$pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
		$found = false;
		if ($pa->find("product_id='" . intval($this->product_id) . "'")) {
			$found = true;
		}

		$pa->set("product_id", $this->product_id);
		$pa->set("show_group", $this->parseAccess($_REQUEST['access_show']));
		$pa->set("show_price_group", $this->parseAccess($_REQUEST['access_show_price']));
		$pa->set("sell_group", $this->parseAccess($_REQUEST['access_sell']));
		
		if (true === $found) {
			$pa->update();

		} else {
			$pa->create();
		}	
	}

	function getProductAccess()
	{
		if (is_null($this->product_access)) {
			$pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
			if (!$pa->find("product_id='" . intval($this->product_id) . "'")) {
				$pa->set("porduct_id", $this->product_id);
			}
			$this->product_access = $pa;
            $pa->collectGarbage();
		}
		return $this->product_access;
	}

	function getWholesalePricing()
	{
		if (is_null($this->wholesale_pricing)) {
			$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
			$this->wholesale_pricing = $wp->findAll("product_id='" . intval($this->product_id) . "'");
            $wp->collectGarbage();
		}
		return $this->wholesale_pricing;
	}

	function action_add_wholesale_pricing()
	{
		$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
		$wp->set("product_id", $this->product_id);
		$wp->set("price", $_REQUEST["wp_price"]);
		$wp->set("amount", $_REQUEST["wp_amount"]);
		$wp->set("membership", $_REQUEST["wp_membership"]);
		$wp->create();
	}

	function action_delete_wholesale_price()
	{
		$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing($_REQUEST["wprice_id"]);
		$wp->delete();
	}

	function action_update_wholesale_pricing()
	{
		$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing($_REQUEST["wprice_id"]);
		$wp->set("product_id", $this->product_id);
		$wp->set("price", $_REQUEST["w_price"]);
		$wp->set("amount", $_REQUEST["w_amount"]);
		$wp->set("membership", $_REQUEST["w_membership"]);
		$wp->update();
	}

	function action_add_purchase_limit()
	{
		$pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
		$action = "create";
		if ($pl->find("product_id='" . intval($this->product_id) . "'")) {
			$action = "update";
		}	
		$pl->set("product_id", $this->product_id);
		$pl->set("min", $_REQUEST["min_purchase"]);
		$pl->set("max", $_REQUEST["max_purchase"]);
		$pl->$action();
	}
	
	function getPurchaseLimit()
	{
		if (is_null($this->purchase_limit)) {
			$pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
			if (!$pl->find("product_id='" . intval($this->product_id) . "'")) {
				$pl->set("product_id", $this->product_id);
			}
			$this->purchase_limit = $pl;
            $pl->collectGarbage();
		}
		return $this->purchase_limit;
	}

    function action_info()
    {
		$_POST["validaty_period"] = $_POST["vp_modifier"] . $_POST["vperiod"];
		parent::action_info();
    }

	function getValidatyModifier()
	{
		return substr($this->getProduct()->get('validaty_period'), 0, 1);
	}

	function getValidatyPeriod()
	{
		return substr($this->getProduct()->get('validaty_period'), 1);
	}

	protected function parseAccess($groups)
	{
		$result = null;

	    if (empty($groups)) {
    	    $result = '';

	    } elseif (in_array('all', $groups)) {
    	    $result = 'all';

	    } elseif (in_array('registered', $groups)) {
        	$result = 'registered';

	    } else {
			$result = implode(',', $groups);
		}

		return $result;
	}
}


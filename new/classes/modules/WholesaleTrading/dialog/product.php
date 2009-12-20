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
* @package WholesaleTrading
* @access public
* @version $Id$
*/
class Module_WholesaleTrading_Dialog_product extends Dialog_product
{
    function init()
    {
		$this->get("product");
		if (is_object($this->product)) {
			if ($this->product->get("product_id") <= 0) {
				// recover product_id if unset by read() method
				$this->product->set("product_id", $_REQUEST['product_id']);
			}
			if ($_REQUEST['action'] != "buynow") {
				// don't show the product if it is available for direct sale only
				$this->product->assignDirectSaleAvailable(false);
				$this->product = null;
			} else {
				// perform direct sale check if the product does not exist
				$this->product->_checkExistanceRequired = true;
				if (!$this->product->is("directSaleAvailable")) {
					$this->redirect("cart.php?mode=add_error");
					exit;
				}
			}
		}

    	parent::init();
    }

    function _conditionActionBuynow()
    {
        $product = $this->get("product");
		if (!is_object($product)) return false;

        $product->set("product_id", $this->product_id);
		if (!$product->is("directSaleAvailable")) {
        	$this->set("returnUrl", "cart.php?mode=add_error");
			return false;
		}	

		// min/max purchase amount check
		$pl =& func_new("PurchaseLimit");
		if ($pl->find("product_id=" . $product->get("product_id"))) {
			$category_id = $this->get("category_id");
			if (!isset($category_id)) {
				$category_id = $product->get("Category.category_id");
				$this->set("category_id", $category_id);
			}
			return false;
		}	
		
		return true;
    }

    function action_buynow()
    {
    	if ($this->_conditionActionBuynow()) {
			parent::action_buynow();
		}
    }

    function isAvailableForSale()
    {
		if (!$this->is("product.saleAvailable")) {
			return false;
		}	
        return parent::isAvailableForSale();
    }

	function getWholesalePricing()
	{
		if (is_null($this->wholesale_pricing)) {
			$product =& func_new("Product", $this->get("product.product_id"));
			$this->wholesale_pricing = $product->getWholesalePricing();
		}	
		return $this->wholesale_pricing;
	}

	function option_selected($p_id, $key)
	{
		return $key == 0;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

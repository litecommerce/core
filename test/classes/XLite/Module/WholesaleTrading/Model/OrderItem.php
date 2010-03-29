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
| The Initial Developer of the Original Code is Creative Development LCC       |
| Portions created by Creative Development LCC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_WholesaleTrading
* @access public
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{	
	public $_itemChanged = false;

	public function __construct()
	{
		$this->fields["wholesale_price"] = 0;
		parent::__construct();
	}

	function _getStoredWholesalePrice()
	{
		return $this->xlite->get("useStoredWholesale");
	}

	function _needStoredWholesalePrice()
	{
		return !$this->xlite->get("dontStoreWholesale");
	}

	function _needSetWholesalePrice()
	{
		return $this->isPersistent && !$this->_itemChanged;
	}

	function _setWholesalePrice($price)
	{
		$this->set("wholesale_price", $price);
		if ($this->_needSetWholesalePrice()) {
			$this->update();
		}
	}

	function _getWholesalePrice($parentPrice = false)
	{
		if ($parentPrice) {
            return parent::get("price");
		}

        if ($this->_getStoredWholesalePrice()) {
        	$price = $this->get("wholesale_price");
        	if ($price >= 0) {
            	return $this->get("wholesale_price");
            }
        }
        
        // if not a product, return parent value
        if (!$this->getComplex('product.product_id')) {
            return parent::get("price");
        }
        $product = $this->get("product");
		if (!isset($this->wholesale_prices)) {
			$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
			$this->wholesale_prices = $wp->getProductPrices($product->get("product_id"), $this->get("amount"), "OR membership='" . $this->getComplex('order.profile.membership') . "'");
		}	
		if (count($this->wholesale_prices) == 0) {
			$price = parent::get("price");
    		if (strval($this->formatCurrency($this->get("wholesale_price"))) != strval($this->formatCurrency($price)) && $this->_needStoredWholesalePrice()) {
    			$this->_setWholesalePrice($price);
    		}
			return $price;
		}

		$price = $this->wholesale_prices[count($this->wholesale_prices) - 1]->get("price");
		if ($this->config->getComplex('Taxes.prices_include_tax')) {
			$product->set("price", $price);
			if (!$this->_skipTaxingWholesalePrice) {
				$price = $product->get("listPrice");
			}
		}
		if (strval($this->formatCurrency($this->get("wholesale_price"))) != strval($this->formatCurrency($price)) && $this->_needStoredWholesalePrice()) {
			$this->_setWholesalePrice($price);
		}
		
		return $price;
	}

	function get($name)
	{
        if ($name == "price") {
            return $this->_getWholesalePrice();
        } else if ($name == "parentPrice") {
            return $this->_getWholesalePrice(true);
        } else {
            return parent::get($name);
        }
	}

	function set($name, $value)
	{
		if (($name != "wholesale_price") && (in_array($name, $this->fields))) {
			$this->_itemChanged = true;
		}
		parent::set($name, $value);
	}

	function update()
	{
		parent::update();
		$this->_itemChanged = false;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
class XLite_Module_WholesaleTrading_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
	public function __construct($oid = null)
	{
		$this->fields['global_discount'] = '';
		parent::__construct($oid);
	}
	
    function calcSubTotal($shippedOnly=false) // {{{
    {
        $subtotal = parent::calcSubTotal($shippedOnly);
		$global_discount = $this->calcGlobalDiscount($subtotal);
		$this->set("global_discount", $global_discount);
		$subtotal = $this->reduceSubTotal($subtotal);
		return $subtotal;
    } // }}}

	function calcGlobalDiscount($subtotal)
	{
		require_once LC_MODULES_DIR . 'WholesaleTrading' . LC_DS . 'encoded.php';
		$global_discount = func_wholesaleTrading_calc_global_discount($this, $subtotal);
		$global_discount = min(abs($subtotal), abs($global_discount));
		return $global_discount;
	}

	function reduceSubTotal($subtotal)
	{
		$global_discount = $this->get("global_discount");
		if ($global_discount > 0) {
			if ($this->config->get("Taxes.prices_include_tax") && !$this->get("config.Taxes.discounts_after_taxes")) {
				$taxed_global_discount = $this->getTaxedGlobalDiscount();
				$subtotal -= $taxed_global_discount;
			} else {
				$subtotal -= $global_discount;
			}
			if ($subtotal < 0) $subtotal = 0;
    	    $this->set("subtotal", $this->formatCurrency($subtotal));
		}
		return $subtotal;
	}

	function getTaxedGlobalDiscount()
	{
		$discount = $this->get("global_discount");
		if ($discount == 0) return $discount;

		$is_percent_discount = ($this->get("appliedGlobalDiscount.discount_type") == "p");
		if ($is_percent_discount) return $discount;

        if ($this->config->get("Taxes.prices_include_tax") && $this->config->get("Taxes.discounts_after_taxes")) {
            $taxed_discount = $discount;
        } else {
            $taxes = (array) $this->getTaxedGlobalDiscountRates();
            $tax = (isset($taxes['Tax']))?$taxes['Tax']:0;
            $taxed_discount = $discount + abs($tax);
            $taxed_discount = min($this->get("subtotal"), $taxed_discount);
        }
		return $taxed_discount;
	}

	function getItemsByTaxValue()
	{
		if (is_null($this->_items_by_tax_value)) {
			$taxRates = new XLite_Model_TaxRates();
			$taxRates->set("order", $this);

			$items = (array) $this->get("items");
			$tax_items = array();
			$tax_values = array();
			foreach ($items as $k=>$i) {
				if ($this->config->get("Taxes.prices_include_tax")) {
					$i->set("price", $i->get("product.price"));
				} 
				$skip_flag = $i->_skipTaxingWholesalePrice;
				$i->_skipTaxingWholesalePrice = true;
				$taxRates->setOrderItem($i);
				$i->_skipTaxingWholesalePrice = $skip_flag;
  				$taxRates->calculateTaxes();
				$_taxes = $taxRates->get("allTaxes");
				$_tax = (isset($_taxes["Tax"]))?$_taxes["Tax"]:0;
				$tax_items[$k] = $i;
				$tax_values[$k] = sprintf("%010.2f_%010d", $_tax, rand());
			}
			array_multisort($tax_values, SORT_DESC, SORT_STRING, $tax_items);
			$this->_items_by_tax_value = $tax_items;
		}
		return $this->_items_by_tax_value;
	}

	function getTaxedGlobalDiscountRates()
	{
		$discount = $this->get("global_discount");
		$is_percent_discount = ($this->get("appliedGlobalDiscount.discount_type") == "p");

		$result = array();
		if ($discount <= 0) return $result;

		$taxRates = new XLite_Model_TaxRates();
		$taxRates->set("order", $this);

		// apply discount to items maximally taxed 
		$tax_items = (array) $this->getItemsByTaxValue();

		foreach ($tax_items as $k=>$i) {
			if ($discount <= 0) break;
			$taxRates->setOrderItem($i);
			if (!$this->config->get("Taxes.prices_include_tax")) {
				$item_cost = $i->get("taxableTotal");
			} else {
				$skip_flag = $i->_skipTaxingWholesalePrice;
				$i->_skipTaxingWholesalePrice = true;
				$item_cost = $i->get("price") * $i->get("amount");
				$i->_skipTaxingWholesalePrice = $skip_flag;
			}

			$taxable_discount = ($is_percent_discount)?$this->formatCurrency($item_cost * $this->get("appliedGlobalDiscount.discount") / 100):$discount;
			$cost = min(abs($item_cost), abs($taxable_discount));
			$taxRates->_conditionValues["cost"] = abs($cost) * -1;
			$taxRates->_conditionValues["amount"] = 1;

			// omit extra rounding
			$taxRates->_conditionValues["cost"] *= 100;
			$taxRates->calculateTaxes();

			$discount_taxes = $taxRates->get("allTaxes");
			foreach ($discount_taxes as $tax_name => $tax_value) {
				$discount_taxes[$tax_name] = ($tax_value) / 100;
			}

			$result = $this->_addTaxes($result, $discount_taxes);
			if (!$is_percent_discount) $discount -= $cost;
		}
		return $result;
	}

	function getAppliedGlobalDiscount()
	{
		if (is_null($this->_applied_global_discount)) {
			$subtotal = parent::calcSubTotal();
			require_once LC_MODULES_DIR . 'WholesaleTrading' . LC_DS . 'encoded.php';
			func_wholesaleTrading_calc_global_discount($this, $subtotal);
			if (is_null($this->_applied_global_discount)) $this->_applied_global_discount = new XLite_Module_WholesaleTrading_Model_GlobalDiscount();
		}
		return $this->_applied_global_discount;
	}

    function calcAllTaxes()
    {
    	$result = parent::calcAllTaxes();

		if (floatval($this->get("global_discount")) > 0) {
			if (!($this->get("config.Taxes.prices_include_tax") && $this->get("config.Taxes.discounts_after_taxes"))) {
				$rates = $this->getTaxedGlobalDiscountRates();
				$result = $this->_addTaxes($result, $rates);
				// after discount correction taxes should be adjusted to default format to avoid rounding problems
				foreach ($result as $tax_name=>$tax_value) {
					// if the value ends with 5, floor it instead of rounding
					if (floor($tax_value * 1000) % 10 == 5) {
						$result[$tax_name] = floor(max(0, $tax_value)*100) / 100;
					}
					$result[$tax_name] = $this->formatCurrency(max(0, $result[$tax_name]));
				}
    	    	$this->set("allTaxes", $result);
			}
        }
        return $result;
    }
	
	function processed()
	{
		$this->WholesaleTrading_processed();
		parent::processed();	
	}
	
	function WholesaleTrading_processed()
	{
		$items = $this->get('items');
		foreach ($items as $item) {
			if ($item->is('product.sellingMembership')) {
				$profile = $this->get('origProfile');
				require_once LC_MODULES_DIR . 'WholesaleTrading' . LC_DS . 'encoded.php';
				func_wholesaleTrading_set_membership($this, $profile, $item->get('product'));
				$profile->update();
				break;
			}
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

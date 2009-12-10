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
* Module_Promotion_Order description.
*
* @package Module_Promotion
* @access public
* @version $Id: Order.php,v 1.57 2008/10/23 11:59:29 sheriff Exp $
*/
class Module_Promotion_Order extends Order
{
	var $_bonusPrices = true; // getPrice & getSubTotal return bonus prices
	var $DC = null;
	var $_getDiscountedShippingCost = false;
    var $_appliedBonuses = null;
	var $_count_all_products = false;

	function constructor($id = null)
	{
		$this->fields["payedByPoints"] = 0;
		$this->fields["discountCoupon"] = "";
		$this->fields["discount"] = 0;
		parent::constructor($id);
	}

	/**
	* A default or current 'pay by bonus points:' field.
	*/
	function getPayByPoints()
	{
		if ($this->config->get("Promotion.bonusPointsCost") <= 0) {
			return 0;
		}

		$payedByPoints = $this->get("payedByPoints");
		if ($payedByPoints != 0.00) {
			return ceil($payedByPoints / $this->config->get("Promotion.bonusPointsCost"));
		}
		$payByPoints = min($this->get("origProfile.bonusPoints"), ceil($this->getMaxPayByPoints() / $this->config->get("Promotion.bonusPointsCost")));
		return (int)$payByPoints;
	}

	function getMaxPayByPoints()
	{
		$allTaxes = $this->get("allTaxes");
		$tax = $allTaxes['Tax'];
		if ($this->config->get("Taxes.prices_include_tax")) {
			$shippingTaxes = $this->get("shippingTaxes");
			$tax = $shippingTaxes['Tax'];
		}
		return max(0, $this->get("shipping_cost") + $this->get("subtotal") - $this->get("discount") + $tax);
	}

	/**
	* Get order total in bonus points.
	*/
	function getTotalBonusPoints()
	{
		if ($this->config->get("Promotion.bonusPointsCost") <= 0) {
			return 0;
		}
		
		return ceil($this->getMaxPayByPoints() / $this->config->get("Promotion.bonusPointsCost"));
	}
	
	function &getOrderAppliedBonuses()
	{
		if (!$this->xlite->is("adminZone")) 
			$this->_appliedBonuses = null;
		$bonuses = $this->getAppliedBonuses();
		return $bonuses;
	}

	function &getRealAppliedBonuses()
	{
        if (!$this->xlite->is("adminZone"))
            $this->_appliedBonuses = null;
		$bonuses = $this->getAppliedBonuses();
		$realBonuses = array();
		foreach($bonuses as $bonus) {
			if ($bonus->checkBonus($this)) $realBonuses[] = $bonus;
		}
		return $realBonuses;
	}

	function &get($name)
	{
		if ($name == "totalWithBonusPoints") {
			return $this->getMaxPayByPoints();
		} else {
			return parent::get($name);
		}
	}

	function isShipped()
	{
		if ($this->isFreeShipping()) {
			return false;
		}
		return parent::isShipped();
	}

    function isShippingAvailable() // {{{
    {   
        return count($this->get("shippingRates")) > 0;

    } // }}}

	function calcShippingCost()
	{
		if ($this->isFreeShipping()) {
            $this->set("shippingCost", 0);
			return 0;
		}
		parent::calcShippingCost();
        $sc = $this->get("shippingCost");
        $this->originalShippingCost = $sc;
		$this->set("shippingCost", $sc);
		return $sc;
    }
	/**
    * Add bonus points (or substract if negative) to the 
    * oroginal customer's profile.
    */
	function addBonusPoints($points)
	{
        $op = & $this->get("origProfile");
		if (!is_null($op)) {
			$op->set("bonusPoints", $op->get("bonusPoints") + $points);
			$op->update();
		}
	}

	/**
	* Order status change event {T,I,F,D} -> {Q,P,C}
	*/
	function checkedOut()
    {
        $this->promotionStatusChanged(-1);
		parent::checkedOut();
		if ($this->isFreeShipping()) {
			// set shipping_id to 0 for free shipping
			$this->set("shipping_id", 0);
		}
    }

	/**
	* Order status change event  {Q,P,C} -> {T,I,F,D}
	*/
	function uncheckedOut()
	{
		$this->promotionStatusChanged(1);
		parent::uncheckedOut();
	}

	function promotionStatusChanged($sign=-1)
    {
		if ($this->config->get("Promotion.bonusPointsCost") <= 0) {
			return;
		}

        // decrease bonus points
        $this->addBonusPoints($sign * ceil($this->get("payedByPoints") / $this->config->get("Promotion.bonusPointsCost")));
        $dc =& $this->get("DC.peer");
        if (!is_null($dc)) {
            if ($dc->get("status") != "D") {
            	// increase/decrease times the discount coupon used times
                $dc->set("timesUsed", $dc->get("timesUsed")-$sign);
                if ($dc->get("timesUsed") >= $dc->get("times")) {
                    $dc->set("status", "U");
                } else {
                    $dc->set("status", "A");
                }
                $dc->update();
            }
        }
    }

	function processed()
	{
        $this->logger->log("->Order::processed(), ".$this->_parentList());
		if ($this->config->get("Promotion.earnBonusPointsRate")) {
			$this->addBonusPoints((int)($this->get("subtotal") * $this->config->get("Promotion.earnBonusPointsRate")));
		}
		$this->addBonusPointsSpecialOffer(1);
        parent::processed();
        $this->logger->log("<-Order::processed()");
	}

	function declined()
	{
		if ($this->config->get("Promotion.earnBonusPointsRate")) {
			$this->addBonusPoints(-(int)($this->get("subtotal") * $this->config->get("Promotion.earnBonusPointsRate")));
		}
		$this->addBonusPointsSpecialOffer(-1);
		parent::declined();
	}

    function dumpBonuses($bonuses)
    {
        if (count($bonuses) == 0) {
            $this->logger->log("No bonuses");
        }
        foreach ($bonuses as $bonus) {
            $this->logger->log($bonus->get("offer_id").":".$bonus->get("conditionType").":".$bonus->get("bonusType"));
        }
    }

	function addBonusPointsSpecialOffer($sign)
	{
        $this->logger->log("->addBonusPointsSpecialOffer($sign)");
		$total = 0;
		foreach ($this->getAppliedBonuses() as $bonus) {
			if ($bonus->get("bonusType") == "bonusPoints") {
				$total += $bonus->get("bonusAmount");
			}
		}
		if ($total) {
			$this->addBonusPoints($sign*$total);
		}
        $this->logger->log("<-addBonusPointsSpecialOffer($sign)");
	}

	function buildWhereSpecialOffers($where)
	{
		return "enabled=1 AND start_date<= " . time() . " AND end_date >= " . time();
	}

	function &getSpecialOffers($where="")
	{
		$so =& func_new("SpecialOffer");
		$result = array();
		$where = $this->buildWhereSpecialOffers($where);
        $found = $so->findAll($where);
        for ($i=0; $i<count($found); $i++) {
            $specialOffer = $found[$i];
			if ($specialOffer->checkCondition($this)) {
				$newSpOff = true;
				$replaceSpOff = null;
				foreach($result as $spOffIdx => $spOff) {
					$compResult = $so->compareOffers($spOff, $specialOffer, $this);
					if ($compResult > 0) {
						$newSpOff = false;
						$replaceSpOff = $spOffIdx;
						break;
					}
					if ($compResult < 0) {
						$newSpOff = false;
						break;
					}
				}
				if (isset($replaceSpOff)) {
					$result[$replaceSpOff] = $specialOffer;
				}
				if ($newSpOff) {
					$result[] = $specialOffer;
				}
			}
		}

		return $result;
	}

	function _getProductAmount($product, $category, $countBonusItems = false)
	{
		$product_ids = array();
		if (is_array($product)) {
			foreach ($product as $pp) {
				$product_ids[] = $pp->get("product_id");
			}
		} else if (isset($product)) {
			$product_ids[] = $product->get("product_id");
		}
		$amount = 0;
		$bonusItems = 0;
		foreach ($this->get("items") as $item) {
			$found = array_search($item->get("product_id"), $product_ids);
			if (!($found === false || $found === null) || !is_null($category) && $this->_inCategoriesRecursive($item->get("product"), $category) || $this->get("_count_all_products")) {
				$amount += $item->get("amount");
				if ($countBonusItems && $item->get("bonusItem") || !$countBonusItems) {
					$bonusItems += $item->get("amount");
				}
			}
		}

		$this->set("_count_all_products", false);
		if ($countBonusItems) {
			return array($amount, $bonusItems);
		} else {
			return $amount;
		}
	}

	function _inCategoriesRecursive($product, $category)
	{
		if (is_array($category)) {
			for ($i=0; $i<count($category); $i++) {
				if ($this->_inCategoryRecursive($product, $category[$i])) {
					return true;
				}
			}
			return false;
		} else {
			return $this->_inCategoryRecursive($product, $category);
		}
	}

	function _inCategoryRecursive($product, $category)
	{
        require_once "modules/Promotion/encoded.php";
		return func_in_category_recursive($product, $category);
	}

	function _inCategoryRecursiveCategory($subcategory, $category)
	{
		if ($subcategory->get("category_id") == $category->get("category_id")) {
			return true;
		}
		foreach ($category->get("subcategories") as $c) {
			if ($this->_inCategoryRecursiveCategory($subcategory, $c)) return true;
		}
		return false;
	}

	/**
	* Find special offers that were applied to this order.
	* @return array of SpecialOffer with bonus information
	*/
	function &getAppliedBonuses($where = null)
	{
		if (is_null($this->_appliedBonuses)) {
        	// cache results
			if (!$this->get("order_id")) {
				$this->_appliedBonuses = array();
			} else {
				$bonus =& func_new("SpecialOffer");
				$bonus->_range = "order_id=" . $this->get("order_id");
				$this->_appliedBonuses =& $bonus->findAll($where);
			}
		}
		return $this->_appliedBonuses;
	}

	function delete()
	{
		// remove attached bonuses
		$this->_appliedBonuses = null;
		foreach ($this->getAppliedBonuses() as $so) {
			$so->delete();
		}

        $dc =& $this->get("DC");
        if (!is_null($dc)) {
			$dc->delete();
    	}
		parent::delete();
	}

	function isFreeShipping()
	{
		$defaultCountry = $this->config->get("General.default_country");
		if (!$this->auth->is("logged") && isset($defaultCountry)) {
			$destCountry = $defaultCountry;
		} else {
			$destCountry = $this->get("profile.shipping_country");
		}

		foreach ($this->getAppliedBonuses() as $so) {
			if ($so->get("bonusType") == "freeShipping" && ($so->get("bonusAllCountries") || $so->checkCountry($destCountry))) {
				return true;
			}
		}
		if ($this->get("DC.type") == "freeship") {
            $discount = $this->get("DC");
            return $discount->checkCondition($this);
		}
		return false;
	}

	function calcDiscount()
	{
        // calculate discount coupon discount
        require_once "modules/Promotion/encoded.php";
        func_calc_discount($this);
	}

    function getDiscountableTotal()
    {
        $subtotal = 0;
        foreach ($this->get("items") as $item) {
            $subtotal += $item->get("discountablePrice")*$item->get("amount");
        }
        return $subtotal;
    }

	function calcTotal()
    {
		parent::calcTotal();

        $this->logger->log("->Order::calcTotal");
		$this->refresh("items");

        $this->calcSubtotal();
        $this->calcShippingCost();
        $this->calcDiscount();

        $this->calcTax();

        $total = $this->get("subtotal");
        if ( !$this->config->get("Taxes.prices_include_tax") ) {
   	        $total += $this->get("tax");
       	}
        if ( $this->config->get("Taxes.prices_include_tax") ) {
   	        $total += $this->get("shippingTax");
       	}

        $this->originalTotal = $this->get("subtotal") + $this->get("shippingCost");
   	    if ( !$this->config->get("Taxes.prices_include_tax") ) {
       	    $this->originalTotal += $this->get("tax");
        }
		if ( $this->config->get("Taxes.prices_include_tax") ) {
			$this->originalTotal += $this->get("shippingTax");
		}

		if ($this->config->get("Taxes.prices_include_tax") && !$this->get("config.Taxes.discounts_after_taxes")) {
			$discount = $this->get("taxedDiscount");
		} else {
			$discount = $this->get("discount");
		}
        $total = max(0, $this->originalTotal - $discount);
        if ($this->_bonusPrices) {
            $total = max(0,$total - $this->get("payedByPoints"));
        }
        $this->set("total", $this->formatCurrency($total));

        if ($this->get("payedByPoints") > $this->getMaxPayByPoints()) {
            $this->set("payedByPoints", $this->getMaxPayByPoints());
            $this->calcTotal();
        }
        $this->logger->log("<-Order::calcTotal");
    }
	
	function &getDC()
    {
        if (is_null($this->DC) && $this->get("order_id")) { 
            $dc =& func_new("DiscountCoupon");
            $dc->_range = "";
            if ($dc->find("order_id=".$this->get("order_id"))) {
                $this->DC = $dc;
            }
        }
        return $this->DC;
    }

	function calcAllTaxes()
	{
		global $calcAllTaxesInside;

		$orig_calcAllTaxesInside = $calcAllTaxesInside;
		$calcAllTaxesInside = true;
    	$result = parent::calcAllTaxes();
		$calcAllTaxesInside = $orig_calcAllTaxesInside;

		if ( function_exists(func_is_php5) && func_is_php5() ) {
			$this->_items = null;
		}

		if (floatval($this->get("discount")) > 0) {
			if (!($this->get("config.Taxes.prices_include_tax") && $this->get("config.Taxes.discounts_after_taxes"))) {

				$rates = $this->getTaxedDiscountRates();
				$result = $this->_addTaxes($result, $rates);
				// after discount correction taxes should be adjusted to default format to avoid rounding problems
				foreach ($result as $tax_name=>$tax_value) {
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

    function getBonucedItemsNumber(&$offer)
    {
    	$items = $this->getItems();
    	$number = 0;
    	foreach($items as $item) {
    		if ($item->get("bonusItem")) {
    			if ($offer->_isConditionalProduct($item->get("product")) || $offer->_isConditionalCategory($item->get("product.category")) || $offer->_isConditionalProductPrice($item->get("product"))) {
    				$number ++;
    			}
    		}
    	}
    	return $number;
    }

	function isDiscountCouponApplied()
	{
    	$items = $this->getItems();
    	foreach($items as $item) {
    		if ($item->isDiscountCouponApplies()) {
				return true;
    		}
    	}
		return false;
	}

	function getTaxedDiscount()
	{
		$discount = $this->get("discount");
		if ($discount == 0) return $discount;

		$is_percent_discount = ($this->get("DC.type") == "percent");
		if ($is_percent_discount) return $discount;

		$taxes = (array) $this->getTaxedDiscountRates();
		$tax = (isset($taxes['Tax']))?$taxes['Tax']:0;
		$taxed_discount = $discount + abs($tax);
		$taxed_discount = min($this->get("subtotal"), $taxed_discount);
		return $taxed_discount;
	}

	function getItemsByTaxValue()
	{
		if (is_null($this->_items_by_tax_value)) {
			$taxRates =& func_new("TaxRates");
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

	function getTaxedDiscountRates()
	{
		$discount = $this->get("discount");

		$result = array();
		if ($discount <= 0) return $result;

		$is_percent_discount = ($this->get("DC.type") == "percent");

		$ignore_discount = 0;
		$ignore_percent = 0;
		if ($this->xlite->get("WholesaleTradingEnabled")) {
			// check whether WholesaleTrading has already applied an absolute global discount:
			$global_discount = $this->get("global_discount");
			$is_absolute_global_discount = ($this->get("appliedGlobalDiscount.discount_type") == "a");
			$is_percent_global_discount = ($this->get("appliedGlobalDiscount.discount_type") == "p");
			if ($is_absolute_global_discount) {
				$ignore_discount = $global_discount;
			}
			if ($is_percent_global_discount) {
				$ignore_percent = $this->get("appliedGlobalDiscount.discount");
			}
		}

		$taxRates =& func_new("TaxRates");
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
            
			if (!$is_percent_discount) {
				// take global discount into account:
				if ($ignore_percent > 0) {
					// percent global discount + absolute discount
					$reduce_cost = min($item_cost, $item_cost * $ignore_percent / 100);
					$item_cost -= $reduce_cost;
					if ($item_cost <= 0) continue;
				}
				if ($ignore_discount > 0) {
					// absolute global discount + absolute discount
					$reduce_cost = min($item_cost, $ignore_discount);
					$item_cost -= $reduce_cost;
					$ignore_discount -= $reduce_cost;
					if ($item_cost <= 0) continue;
				}
			}

			$taxable_discount = ($is_percent_discount)?$this->formatCurrency($item_cost * $this->get("DC.discount") / 100):$discount;
			$cost = min(abs($item_cost), abs($taxable_discount));
			$taxRates->_conditionValues["cost"] = abs($cost) * -1;
			$taxRates->_conditionValues["amount"] = 1;

			// omit extra rounding
			$taxRates->_conditionValues["cost"] *= 100;
			$taxRates->calculateTaxes();

			$discount_taxes = $taxRates->get("allTaxes");
            foreach ($discount_taxes as $tax_name => $tax_value) {
                if (!$this->config->get("Taxes.discounts_after_taxes")) {
                    $discount_taxes[$tax_name] = $tax_value / 100;
                } else {
                    // KOI8-R: значит не надо изменять значение скидки на величину налога, т.к. 
                    // скидка применяется после расчета налога
                    $discount_taxes[$tax_name] = 0 / 100; 
                }
            }
            

			$result = $this->_addTaxes($result, $discount_taxes);
			if (!$is_percent_discount) $discount -= $cost;
		}
        
		return $result;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

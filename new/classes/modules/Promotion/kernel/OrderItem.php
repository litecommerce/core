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
* Module_Promotion_OrderItem description.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class Module_Promotion_OrderItem extends OrderItem
{
	var $_isBonusItem = false;
	var $_createPeerItem = false;

    function constructor($param = null)
    {
		$this->fields["bonusItem"] = 0; // amount of bonus items
		$this->primaryKey[] = "bonusItem"; // it's a primary key also
        parent::constructor();
    }

	function _getPromotionPrice($parentPrice = false)
	{
		if ($parentPrice) {
            return $this->formatCurrency(parent::get("price"));
		}

        $price = parent::get("price");

		global $calcAllTaxesInside;

        if (!is_null($this->get("product")) && $this->is("order._bonusPrices")) {
			if ($this->get("config.Taxes.prices_include_tax") && !$this->get("config.Taxes.discounts_after_taxes") && !$calcAllTaxesInside) {
				// calculate original item price without taxes...
				$p = func_new("Product", $this->get("product_id"));
				$p->set("price", 100.00); // use a 100 dollar product
				$taxed100 = $p->getTaxedPrice(); // tax increment
				$orig_price = $price * 100 / $taxed100;
				$price = $orig_price;
			}
			if (!($this->get("config.Taxes.prices_include_tax") && $this->get("config.Taxes.discounts_after_taxes") && $calcAllTaxesInside)) {
				// take bonuses into account
				foreach ($this->get("order.appliedBonuses") as $bonus) {
					if ($bonus->get("bonusType") == "discounts" || $this->get("bonusItem")) {
						$price = $bonus->getBonusPrice($this, $price);
					}
				}
			}
			if ($this->get("config.Taxes.prices_include_tax") && !$this->get("config.Taxes.discounts_after_taxes") && !$calcAllTaxesInside) {
				$p = func_new("Product", $this->get("product_id"));
				$p->set("price", $price);
				$price = $p->getTaxedPrice();
			}
        }

        // discount coupon price for this item
        if ($this->isDiscountCouponApplies()) {
            if ($this->get("order.DC.type") == "absolute") {
                $price = max(0, $price - $this->get("order.DC.discount"));
            }
            if ($this->get("order.DC.type") == "percent") {
                $price *= (100 - $this->get("order.DC.discount")) / 100;
            }
        }

		if ($this->config->get("Promotion.only_positive_price")) {
            if ($price < 0) {
            	$price = 0;
            }
		}

        return $this->formatCurrency($price);
	}

	function &get($name)
	{
        if ($name == "price") {
            return $this->_getPromotionPrice();
        } else if ($name == "parentPrice") {
            return $this->_getPromotionPrice(true);
        } else {
            return parent::get($name);
        }
	}

    function set($property, $value)
    {
        if ($property == "price") {
        	$price = $this->get("price");
        }
        parent::set($property, $value);
    }

	function isBonusApplies()
	{
		if ($this->get("bonusItem")) {
			return true;
		}
		if ($this->is("order._bonusPrices") && !is_null($this->get("product"))) {
			// take bonuses into account
			foreach ($this->get("order.appliedBonuses") as $bonus) {
				if ($bonus->get("conditionType") != "eachNth" || $this->get("bonusItem")) {
					return true;
				}
			}
		}
		return false;
	}

    function isPromotionItem()
    {
        return $this->get("price") != $this->get("parentPrice");
    }

	function isDiscountCouponApplies()
	{
		if (!is_null($this->get("order.DC"))) {
			// discount coupon is set
			if ($this->get("order.DC.applyTo") == "product") {
				if ($this->get("product_id") == $this->get("order.DC.product_id")) {
					return true;
				}
			}
			if ($this->get("order.DC.applyTo") == "category" && !is_null($this->get("product"))) {
				if ($this->order->_inCategoryRecursive($this->get("product"), $this->get("order.DC.category"))) {
					return true;
				}
			}
		}
		return false;
	}

    function getOrderby()
    {
		if ($this->_createPeerItem) {
			return $this->get("orderby");
		} else {
			return parent::getOrderby();
		}
    }

    function formatCurrency($price)
    {   
    	$isNewFC = $this->xlite->get("PromotionNewFC");
    	if (!isset($isNewFC)) {
			$classMethods = array_map("strtolower", get_class_methods(get_parent_class(get_class($this))));
			$isNewFC = in_array("formatcurrency", $classMethods);
			$this->xlite->set("PromotionNewFC", $isNewFC);
		}

		if ($isNewFC) {
			return parent::formatCurrency($price);
		} else {
        	return round($price, 2);
        }
    }               

    function isOfferCondition(&$offer)
    {
		if ($offer->get("allProducts")) {
			return true;
		}

		$product = $this->get("product");
		if (is_object($product)) {
			if ($offer->get("product_id") != 0 && $product->get("product_id") == $offer->get("product_id")) {
				return true;
			}
			if ($offer->get("category_id") != 0) {
				$cat =& func_new("Category", $offer->get("category_id"));
				if ($product->inCategory($cat)) {
					return true;
				}
			}
			if ($offer->get("bonusType") == "discounts") {
				$offer->getProducts();
				foreach($this->bonusProducts as $bp) {
					if ($bp->get("product_id") == $product->get("product_id")) {
						return true;
					}
				}
			}
		}

		return false;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

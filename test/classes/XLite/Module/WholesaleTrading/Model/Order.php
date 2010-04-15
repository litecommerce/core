<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
		return min(
			abs($subtotal),
			abs($this->calculateGlobalDiscount($subtotal))
		);
	}

	function reduceSubTotal($subtotal)
	{
		$global_discount = $this->get("global_discount");
		if ($global_discount > 0) {
			if ($this->config->getComplex('Taxes.prices_include_tax') && !$this->getComplex('config.Taxes.discounts_after_taxes')) {
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

		$is_percent_discount = ($this->getComplex('appliedGlobalDiscount.discount_type') == "p");
		if ($is_percent_discount) return $discount;

        if ($this->config->getComplex('Taxes.prices_include_tax') && $this->config->getComplex('Taxes.discounts_after_taxes')) {
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
				if ($this->config->getComplex('Taxes.prices_include_tax')) {
					$i->set("price", $i->getComplex('product.price'));
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
		$is_percent_discount = ($this->getComplex('appliedGlobalDiscount.discount_type') == "p");

		$result = array();
		if ($discount <= 0) return $result;

		$taxRates = new XLite_Model_TaxRates();
		$taxRates->set("order", $this);

		// apply discount to items maximally taxed 
		$tax_items = (array) $this->getItemsByTaxValue();

		foreach ($tax_items as $k=>$i) {
			if ($discount <= 0) break;
			$taxRates->setOrderItem($i);
			if (!$this->config->getComplex('Taxes.prices_include_tax')) {
				$item_cost = $i->get("taxableTotal");
			} else {
				$skip_flag = $i->_skipTaxingWholesalePrice;
				$i->_skipTaxingWholesalePrice = true;
				$item_cost = $i->get("price") * $i->get("amount");
				$i->_skipTaxingWholesalePrice = $skip_flag;
			}

			$taxable_discount = ($is_percent_discount)?$this->formatCurrency($item_cost * $this->getComplex('appliedGlobalDiscount.discount') / 100):$discount;
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
			$this->calculateGlobalDiscount($subtotal);

			if (is_null($this->_applied_global_discount)) {
				$this->_applied_global_discount = new XLite_Module_WholesaleTrading_Model_GlobalDiscount();
			}
		}

		return $this->_applied_global_discount;
	}

    function calcAllTaxes()
    {
    	$result = parent::calcAllTaxes();

		if (floatval($this->get("global_discount")) > 0) {
			if (!($this->getComplex('config.Taxes.prices_include_tax') && $this->getComplex('config.Taxes.discounts_after_taxes'))) {
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
			if ($item->isComplex('product.sellingMembership')) {
				$profile = $this->get('origProfile');
				$product = $item->get('product');

				$membership = $profile->get("membership");
				$membership_exp_date = $profile->get("membership_exp_date");

				if (
					!empty($membership)
					&& $membership != $product->get('selling_membership')
					&& !$this->config->WholesaleTrading->override_membership
				) {
					break; 
				}

				$period = array(
					"d" => 0,
					"m" => 0,
					"y" => 0
				);
				$val_period = $product->get('validaty_period');
				$p_stamp = substr($val_period, 0);
				$p_time = substr($val_period, 1);

				// Store membership in history
				$history = $profile->get("membership_history");
				foreach ($history as $hn_idx => $hn) {
					if (isset($hn['current']) && $hn['current']) {
						unset($history[$hn_idx]);
						break;
					}
				}

				$history_node = array(
					'membership'          => $membership,
					'membership_exp_date' => empty($membership) ? 0 : $membership_exp_date,
					'date'                => time(),
					'current'             => false,
				);

				$history[] = $history_node;
				$profile->set("membership_history", $history);

				if ($membership != $product->get("selling_membership")) {
					$profile->set("membership", $product->get("selling_membership"));
					$c_time = time();
					$period['d'] = date('d', $c_time);
					$period['m'] = date('m', $c_time);
					$period['y'] = date('Y', $c_time);

				} else {
					$temp_exp_date = $membership_exp_date > 0 ? $membership_exp_date : time();
					$period['d'] = date('d', $temp_exp_date);
					$period['m'] = date('m', $temp_exp_date);
					$period['y'] = date('Y', $temp_exp_date);
				}

				switch ($p_stamp) {
					case "D":
						$period['d'] = (int)$period['d'] + (int)$p_time;			
						break;

					case "W":
						$period['d'] = (int)$period['d'] + (int)$p_time * 7;
						break;

					case "M":
						$period['m'] = (int)$period['m'] + (int)$p_time;
						break;

					case "Y":
						$period['y'] = (int)$period['y'] + (int)$p_time;
						break;
				}

				$exp_date = mktime(0, 0, 0, $period['m'], $period['d'], $period['y']);

				// unset expiration date, if not defined for the product
				if (empty($p_time)) {
					$exp_date = 0;
				}

				$profile->set("membership_exp_date", $exp_date);

				$history_node = array(
					'membership'          => $profile->get("membership"),
					'membership_exp_date' => $exp_date,
					'date'                => time(),
					'current'             => true,
				);

				$history[] = $history_node;
				$profile->set('membership_history', $history);

				$profile->update();
				break;
			}
		}
	}

	protected function calculateGlobalDiscount($subtotal)
	{
        $global_discount = 0;

        $gd = new XLite_Module_WholesaleTrading_Model_GlobalDiscount();
        $gd->set('defaultOrder', 'subtotal');
        $profile = $this->get("profile");
        $membership = (is_object($profile)) ? $profile->get("membership") : "";
        $discounts = $gd->findAll('subtotal < ' . $subtotal . ' AND (membership = \'all\' OR membership = \'' . $membership . '\')');

        if (count($discounts) != 0) {
            $applied_gd = $discounts[count($discounts) - 1];

            if ($applied_gd->get('discount_type') == 'a') {
                $global_discount = $applied_gd->get('discount');

            } elseif ($applied_gd->get('discount_type') == 'p') {
                $global_discount = $this->formatCurrency(($subtotal * $applied_gd->get('discount')) / 100);
            }

            $this->_applied_global_discount = $applied_gd;

        } else {
            $this->_applied_global_discount = new XLite_Module_WholesaleTrading_Model_GlobalDiscount();
        }

		return $global_discount;
	}

}

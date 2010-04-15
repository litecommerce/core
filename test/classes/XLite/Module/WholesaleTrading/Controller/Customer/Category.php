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
 * @subpackage Controller
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
class XLite_Module_WholesaleTrading_Controller_Customer_Category extends XLite_Controller_Customer_Category implements XLite_Base_IDecorator
{	
	public $totals = array();	
	public $wholesale_prices = array();	
	public $subtotal = 0;	
	public $errors = array();	
	public $calculate = false;
	
	// FIXME - correct the "bulk categories" functionality and this code too
    /*function init() // {{{
    {
		if (in_array($_REQUEST["category_id"], explode(";", $this->getComplex('config.WholesaleTrading.bulk_categories')))) {
			$layout = XLite_Model_Layout::getInstance();
			$layout->addLayout("category_products.tpl", "modules/WholesaleTrading/bulk_category_products.tpl");
		}
	    if ($this->config->getComplex('WholesaleTrading.direct_addition')) {
			$this->session->set("DirectSaleAvailable", null);
		}
        parent::init();
    }*/ // }}}

	function action_bulk() // {{{
	{
		$products = $_REQUEST["product_qty"];
		$opt_products = $_REQUEST["opt_product_qty"];

		if (empty($products) && empty($opt_products)) {
			$this->valid = false;
			return;
		}

		if (!empty($products)) {
			foreach($products as $key=>$value) {
				if (($err = $this->_check_product($key, $value, null)) != "") {
					$this->errors[] = $err;
				}
			}
		}
		
		if (!empty($opt_products)) {
			foreach($opt_products as $key=>$value) {
				$p = new XLite_Model_Product($key);
				if (!$p->get("tracking")) {
					foreach ($value as $idx=>$option_qty) {
						$qty += $option_qty;	
					}
					if (($err = $this->_check_product($key, $qty, null)) != "") {
						$this->errors[] = $err;
					}
				} else {
					foreach ($value as $idx=>$option_qty) {
						if (($err = $this->_check_product($key, $option_qty, $idx)) != "") {
							$this->errors[] = $err;
						}
					}
				}
			}
		}
		if (empty($this->errors)) {
			$this->add_products($products);
			$this->add_products($opt_products);

            if ($this->config->getComplex('General.redirect_to_cart')) {
                 $this->set("returnUrl", "cart.php?target=cart");
             }
		} else {
			$this->valid = false;
			$this->action_calculate_price();
			return;
		}
	} // }}}

	function add_products($products) // {{{
	{
		if (empty($products)) {
			return;
		}	
		foreach ($products as $key=>$value) {
			$add = false;
			if (is_array($value)) { // product with options
				foreach ($value as $idx=>$qty) {
					if ($qty > 0) {
						$_REQUEST["OptionSetIndex"][$key] = $idx;
						$_REQUEST["amount"] = $qty;
						$_REQUEST["product_id"] = $key;
						$cart = new XLite_Controller_Customer_Cart();
						$cart->init();
						$this->xlite->set("dont_update_cart", true); 
						$cart->action_add();
						$this->xlite->set("dont_update_cart", false); 
						$add = false;
					}
				}
			} else if ($value > 0) {
				$_REQUEST["amount"] = $value;
				$add = true;
			}
			if ($add == true) {
				$_REQUEST["product_id"] = $key;
				$cart = new XLite_Controller_Customer_Cart();
				$cart->init();
				$this->xlite->set("dont_update_cart", true); 
				$cart->action_add();
				$this->xlite->set("dont_update_cart", false); 
			}	
		}

		if (!$this->cart->isEmpty()) {
    		$this->cart->_items = null;
        	$this->updateCart(); // recalculate shopping cart
    	}
	} // }}}

	function action_calculate_price() // {{{
	{
		$products = $_REQUEST["product_qty"];
		$opt_products = $_REQUEST["opt_product_qty"];

		if (empty($products) && empty($opt_products)) {
			$this->valid = false;
			return;
		}	
		
		$this->subtotal = 0;
		if (!empty($products)) {
			foreach($products as $key=>$value) {
				if ($value != "" && $value > 0) {
					$p = new XLite_Model_Product($key);
					$price = $p->getFullPrice($value);
					$this->wholesale_prices[$key] = $price;
					$this->totals[$key] = $price * $value;
					$this->subtotal += $this->totals[$key];
				}
			}
		}

		if (!empty($opt_products)) {
			foreach($opt_products as $key=>$value) {
				foreach ($value as $idx=>$qty) {
					if ($qty != "" && $qty > 0) {
						$p = new XLite_Model_Product($key);
						if ($this->xlite->get("ProductOptionsEnabled") && $p->hasOptions()) {
							$price = $p->getFullPrice($qty,$idx);
							$this->wholesale_prices[$key][$idx] = $price;
							$this->totals[$key][$idx] =  $price * $qty;
						}
						$this->subtotal += $this->totals[$key][$idx];
					}
				}
			}
		}
		$this->valid = false;
		$this->calculate = true;
	} // }}}

	function total_price($product_id, $idx = null) // {{{
	{
		if ($idx !== null) {
			if (isset($this->totals[$product_id][$idx])) {
				return $this->totals[$product_id][$idx];
			} 
			return 0;
		}
		return $this->totals[$product_id];
	} // }}}

	function wholesale_prices($product_id, $idx = null) // {{{
	{
		if ($idx !== null) {
			if (isset($this->wholesale_prices[$product_id][$idx])) {
				return $this->wholesale_prices[$product_id][$idx];
			} 
			return 0;
		}
		return $this->wholesale_prices[$product_id];
	} // }}}

	function quantity($product_id, $key=null) // {{{
	{
		if ($key !== null) {
			if (isset($_POST["opt_product_qty"][$product_id][$key])) {
				return $_POST["opt_product_qty"][$product_id][$key];
			}
		} else {
			if (isset($_POST["product_qty"][$product_id])) {
				return $_POST["product_qty"][$product_id];
			}
		}	
		return 1;
	} // }}}

	function option_selected($p_id, $key) // {{{
	{
		return ($key == $_POST["OptionSetIndex"][$p_id]);
	} // }}}

	function _check_product($product_id, $qty, $option_idx) // {{{
	{
		// check for quantity
		$items = $this->cart->get('items');
		$exists_amount = 0;
		for ($i=0; $i < count($items); $i++) {
			if ($items[$i]->getComplex('product.product_id') == $product_id) {
				$exists_amount += $items[$i]->get('amount');
			}
		}
		$amount = $qty + $exists_amount;
		// check for min/max range
		$pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
		if ($pl->find("product_id=" . $product_id)) {
			$hasError = false;
			$error = array();
			$p = new XLite_Model_Product($product_id);
			if (($amount < $pl->get('min') && $qty > 0) ) {
				$hasError = true;
				$error["type"] = 'min';
				$error["amount"] = $pl->get('min');
			} else if (($pl->get('max') > 0 && $pl->get('max') < $amount) ) {
				$hasError = true;
				$error["type"] = 'max';
				$error["amount"] = $pl->get('max');
			}
			if ($hasError) {
				$error["pr_name"] = $p->get("name");
				$error["product_id"] = $product_id;
/*				if ($option_idx !== null) {
					$error["option_idx"] = $option_idx;
				}
				if ($this->xlite->get("ProductOptionsEnabled") && $p->hasOptions()) {
					$options_set = $p->get("expandedItems");
					foreach($options_set as $key => $_opt) {
						$error["options"][$_opt[0]->class] = $_opt[]->option;
					}
				}*/
				return (object)$error;
			} 
		}

		// check for inventory
		if ($this->xlite->get("InventoryTrackingEnabled")) {
			$inventory = new XLite_Module_InventoryTracking_Model_Inventory();
			$p = new XLite_Model_Product($product_id);
			
			if ($this->xlite->get("ProductOptionsEnabled") && $p->hasOptions() && isset($option_idx)) {
				if ($amount > $p->getAmountByOptions($option_idx) && $p->getAmountByOptions($option_idx) > -1) {
					$p = new XLite_Model_Product($product_id);
					$error["pr_name"] = $p->get("name");
					$error["type"] = 'max';
					$error["amount"] = $p->getAmountByOptions($option_idx);
					$options_set = $p->get("expandedItems");
					foreach($options_set[$option_idx] as $_opt) {
						$error["options"][$_opt->class] = $_opt->option;
					}
					return (object)$error;
				}
			} else {
				if ($inventory->find("inventory_id='$product_id' AND enabled=1")) {
					if ($amount > $inventory->get('amount')) {
						$error["pr_name"] = $p->get("name");
						$error["type"] = 'max';
						$error["amount"] = $inventory->get('amount');
						return (object)$error;
					}
				}
			}	
		}
		return "";
	} // }}} 

	function getErrors() // {{{
	{
		return $this->errors;
	} // }}}

	function isProductError($product_id, $o_idx = null) // {{{
	{
		if ($o_idx !== null) {
			foreach($this->errors as $err) {
				if ($err->product_id == $product_id && $err->option_idx == $o_idx) {
					return true;
				}	
			}
			return false;
		}	
		foreach($this->errors as $err) {
			if ($err->product_id == $product_id) {
				return true;
			}	
		}
		return false;
	} // }}}

	function productSelected($product_id, $key=null) // {{{
	{
		if ($key !== null) {
			return isset($_POST["opt_product_qty"][$product_id][$key]);
		}	
		return isset($_POST["product_selected"][$product_id]);
	} // }}}

	function isProductOutOfStock($product_id, $option_idx = null)
	{
		if (!$this->xlite->get("InventoryTrackingEnabled")) return false;
		$product = new XLite_Model_Product($product_id);
		if ($this->xlite->get("ProductOptionsEnabled") && $product->hasOptions() && $product->get("tracking") && (!is_null($option_idx))) {
			$avail = $product->getAmountByOptions($option_idx);
			if ($avail == -1) return false; // unlimited
			elseif ($avail > 0) return false; // in stock
			else return true; // out of stock
		} else {
			$inventory = new XLite_Module_InventoryTracking_Model_Inventory();
			$product_id = $product->get("product_id");
			if ($inventory->find("inventory_id='$product_id' AND enabled=1")) {
				return $inventory->get('amount') <= 0;
			}
			return false; // unlimited
		}
	}

	function getProductExpandedItems($product)
	{
		$items = (array) $product->get("expandedItems");
		if ($this->xlite->get("InventoryTrackingEnabled") && $this->getComplex('config.InventoryTracking.exclude_product') && 
			$this->xlite->get("ProductOptionsEnabled") && $product->hasOptions() && $product->get("tracking")) {
			// remove out-of-stock options combinations from the expanded items list
			$product_id = $product->get("product_id");
			foreach ($items as $key=>$value) {
				if ($this->isProductOutOfStock($product_id, $key)) {
					unset($items[$key]);
				}
			}
		}
		return $items;
	}
}

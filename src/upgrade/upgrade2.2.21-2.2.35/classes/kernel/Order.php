<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

define('ORDER_EXPIRATION_TIME', 3600 * 24); // one day

/**
* Class represens an order.
*
* @package kernel
* @access public
* @version $Id: Order.php,v 1.3 2007/05/21 11:53:28 osipov Exp $
* @see Cart
*/
class Order extends Base
{
    // Order properties {{{
    var $fields = array(
            'order_id' => '',       // primary key
            'profile_id' => '',     // profile data at the moment of the purchase
            'orig_profile_id' => '',// original profile id
            'total' => '',          // order costs: Total, Subtotal, Shipping
            'subtotal' => '',       
            'shipping_cost' => '',  
            'tax' => '',            // taxes applied to this order (serialized)
            'shipping_id' => null,  // order shipping method primary key
            'tracking' => '',       // delivery tracking number
            'date' => '',           // date/time when the order purchased, timestamp
            'status' => 'I',        // order status: Queued, Processed, Failed etc.
            'payment_method' => '', // selected Payment method
            'details' => '',        // secure order data
            'detail_labels' => '',  // order data field names
            'notes' => '',          // notes entered by the customer
            'taxes' => ''           // serialized tax array
        );
    
    var $autoIncrement = "order_id";
    var $alias = "orders";
        
    /**
    * cache properties
    */
    var $_origProfile = null;
    var $_profile = null;
    var $_paymentMethod = null;
    var $_shippingMethod = null;
    var $_items = null;
    var $_details = null;
    var $_detailLabels = null;
    var $_shippingRates = null;
    var $_taxes = null;

    var $_statusChanged = false;
    var $_oldStatus = 'I';
    var $_range = "status!='T'";

    // }}}
   
    ////////////// Order calculation functions ////////////////

    /**
    * Calculates the Order taxes. 
    */
    function calcAllTaxes() // {{{
    {
        $taxRates =& func_new("TaxRates");
        $taxRates->set("order", $this);
        $result = array();
        $items = $this->getItems();
        foreach ($items as $item) {
        	$product = $item->get("product");
        	if ($this->config->get("Taxes.prices_include_tax") && isset($product)) {
        		$item->set("price", $item->get("product.price"));
        	} 
            $taxRates->set("orderItem", $item);
            $taxRates->calculateTaxes();
            $result = $this->_addTaxes($result, $taxRates->get("allTaxes"));
        }

        // tax on shipping
        if (($this->get("shippingDefined") && !$this->config->get("Taxes.prices_include_tax")) || ($this->get("shippingDefined") && $this->config->get("Taxes.prices_include_tax") && $taxRates->get("shippingDefined"))) {
            $taxRates->_conditionValues["product class"] = "shipping service";
            $taxRates->_conditionValues["cost"] = $this->get("shippingCost");
            $taxRates->calculateTaxes();
            $result = $this->_addTaxes($result, $taxRates->get("allTaxes"));

            $shippingTaxes = array();
            $shippingTaxes = $this->_addTaxes($shippingTaxes, $taxRates->get("shippingTaxes"));
            foreach ($shippingTaxes as $name => $value) {
                $shippingTaxes[$name] = $this->formatCurrency($shippingTaxes[$name]);
            }
            $this->set("shippingTaxes", $shippingTaxes);
        }
        // round all tax values
        foreach ($result as $name => $value) {
            $result[$name] = $this->formatCurrency($result[$name]);
        }

        $this->set("allTaxes", $result);
        return $result;
    } // }}}

    function _addTaxes($acc, $taxes) // {{{
    {
        foreach ($taxes as $tax => $value) {
            if (!isset($acc[$tax])) {
                $acc[$tax] = 0;
            }
            $acc[$tax] += $value;
        }
        return $acc;
    } // }}}

    /**
    * Returns the total tax value (in currency) or null if no taxes applicable.
    */
    function calcTax() // {{{
    {
        $this->calcAllTaxes();
        $taxes = $this->get("allTaxes");
        if (isset($taxes["Tax"])) {
            $tax = $taxes["Tax"];	// total tax for all tax systems
        } else {
            $tax = 0;
        }
        $this->set("tax", $tax);

        if ($this->get("shippingDefined")) {
			$shippingTax = 0;
           	$shippingTaxes = $this->get("shippingTaxes");
           	if (is_array($shippingTaxes)) {
				if ($this->config->get("Taxes.prices_include_tax") && isset($shippingTaxes["Tax"])) {
					$shippingTax = $shippingTaxes["Tax"];
				} else {
            		foreach ($shippingTaxes as $name => $value) {
                    	if (isset($taxes[$name])) {
                        	if (!$this->config->get("Taxes.prices_include_tax")) {
                            	// ignoring "included" taxes
                            	if ($taxes[$name] == $value) {
                            		$shippingTax += $value;
                        		}
                        	} else {
                        		$shippingTax += $value;
                   			}
            			}
            		}
           		}
           	}
			$this->set("shippingTax", $shippingTax);
        } else {
        	$this->set("shippingTax", 0);
        }

        return $tax;
    } // }}}

    /**
    * Returns the Order SubTotal (as the order items Total sum).
    */
    function calcSubTotal() // {{{
    {
        $subtotal = 0;
        foreach ($this->get("items") as $item) {
            $subtotal += $item->get("total");
        }
        $this->set("subtotal", $this->formatCurrency($subtotal));
        return $subtotal;
    } // }}}

    /**
    * Returns the Order total (as the order SubTotal + Taxes)
    */
    function calcTotal() // {{{
    {
		if ($this->getItemsCount() <= 0) {
			return;
		}

        $this->calcSubtotal();
        $this->calcShippingCost();
        $this->calcTax();
		$total = $this->get("subtotal");
        if (!$this->config->get("Taxes.prices_include_tax")) {
			$total += $this->get("tax");
        }
		$total += $this->get("shippingCost");
		if ($this->config->get("Taxes.prices_include_tax")) {
			$total += $this->get("shippingTax");
		}
		$this->set("total", $this->formatCurrency($total));
    } // }}}

    function getShippingCost() // {{{
    {
        return $this->get("shipping_cost");
    } // }}}

    function setShippingCost($cost)  // {{{
    {
        $this->set("shipping_cost", $cost);
    } // }}}

    /** 
    * Returns True if any of order items are shipped.
    */

    function isShipped() // {{{
    {
        foreach ($this->get("items") as $item) {
            if ($item->get("shipped"))
                return true;
        }
        return false;
    } // }}}

    /**
    * Returns an array of order items to be shipped.
    */
    function &getShippedItems() // {{{
    {
        $result = array();
        foreach ($this->get("items") as $item) {
            if ($item->get("shipped"))
                $result[] = $item;
        }
        return $result;
    } // }}}

    /**
    * Returns the count of shipped order items.
    */
    function getShippedItemsCount() // {{{
    {
        $items =& $this->get("shippedItems");
        $result = 0;
        foreach ($items as $item) {
            $result += $item->get("amount");
        }
        return $result;
    } // }}}

    /**
    * Returns the Total shipping weight, ounces.
    */
    function getWeight() // {{{
    {
        $weight = 0;
        foreach ($this->get("items") as $item) {
            if ($item->get("shipped")) {
                $weight += $item->get("weight");
            }    
        }
        return $weight;
    } // }}}

    /**
    * Calculates the Shiping cost for the selected shipping method.
    * If no method selected, calculates it on the Shipping rates basis.
    */
    function calcShippingCost() // {{{
    {
    	$cost = 0;
        if (!$this->get("shipped")) {
            $this->set("shipping_cost", $cost);
            return $cost;
        }

        $shippingMethod = $this->get("shippingMethod");
        if (!is_null($shippingMethod)) {
            $cost = $shippingMethod->calculate($this);
        } else {
            $cost = false; 
        }
        if ($cost === false) {
            $rates = $this->calcShippingRates();
            // find the first available shipping method
            if (!is_null($rates) && count($rates)>0) {
                foreach ($rates as $key => $val) {
                    $shippingID = $key;
                    break;
                }
                $shippingMethod = func_new("Shipping", $shippingID);
                $this->set("shippingMethod", $shippingMethod);
                $cost = $shippingMethod->calculate($this);
            }
        }
        $this->set("shipping_cost", $this->formatCurrency($cost));
        return $cost;
    } // }}}

    /**
    * Returns the Shipping rates available. 
    * Note: rates are cached after the first calculation.
    */
    function calcShippingRates() // {{{
    {
        // cache rates
        $shipping = func_new("Shipping");
        $rates = array();
        foreach ($shipping->get("modules") as $module) {
            $r = $module->getRates($this);
            foreach ($r as $k=>$v) {
                $rates[$k] = $v; 
            }
        }
        $this->_sortRates($rates);
        $this->_shippingRates = $rates;
        return $this->_shippingRates;
    } // }}}

    /**
    * Sorts the Shipping rates array by the Position specified in admin zone
    */
    function _sortRates(&$rates) { // {{{
        $sorted = array();
        $i = count($rates);
        while ($i--) {
            // find the minimum orderby
            $minOrderby = 1000000000;
            $minRate = "";
            foreach ($rates as $k => $v) {
            	if (is_object($v)) {
                    $orderby = $v->shipping->get("order_by");
                    if ($orderby < $minOrderby) {
                        $minRate = $k;
                        $minOrderby = $orderby;
                    }
                } else {
        			$this->xlite->logger->log("->Order::_sortRates");
        			$this->xlite->logger->log("$k index points to an invalid object");
        			$this->xlite->logger->log("<-Order::_sortRates");
                }
            }
            if ($minRate !== "") {
                // minimum is found
                $sorted[$minRate] = $rates[$minRate];
                if (isset($rates[$minRate])) {
                	unset($rates[$minRate]);
                }
            }
        }
        $rates = $sorted;
    } // }}}
   
    /////////////// Order validation functions /////////////////

    function isTaxDefined() // {{{
    {
        return true;
    } // }}}
    
    function isShippingDefined() // {{{
    {
        return $this->get("shipping_id");
    } // }}}

    function isShippingAvailable() // {{{
    {
       	return count($this->get("shippingRates")) > 0;

    } // }}}

    function isMinOrderAmountError() // {{{
    {
        
        if ($this->get("subtotal") < (float)$this->config->get("General.minimal_order_amount")) {
            return true;
        }    
        return false;
    } // }}}

    function isMaxOrderAmountError() // {{{
    {
        
        if ($this->get("subtotal") > (float)$this->config->get("General.maximal_order_amount")) {
            return true;
        }    
        return false;
    } // }}}
 
    /////////////// Order data access functions ////////////////

    function refresh($name) // {{{
    {
        $name = "_" . $name;
        if (isset($this->$name)) {
        	unset($this->$name);
        }
        $this->$name = null;
    } // }}}

    function set($name, $value) // {{{
    {
        if ($name == "details") {
            $this->setDetails($value);
        } else {
            $oldStatus = $this->get("status");
            parent::set($name, $value);
            if ($name == "status") {
                if (!$this->_statusChanged && $value != $oldStatus) {
                    $this->_statusChanged = true; // call statusChanged later
                    $this->_oldStatus = $oldStatus;
                }    
            }
            // re-calculate shipping rates on next call to get("shippingRates")
            $this->refresh("shippingRates"); 
        }
    } // }}}

    function &get($name) // {{{
    {
        switch ($name) {
        	case "details":
            return $this->getDetails();

        	case "detail_labels":
            return $this->getDetailLabels();

        	default:
            return parent::get($name);
        }
    } // }}}

    function &getShippingMethod() // {{{
    {
        if (is_null($this->_shippingMethod) && $this->get("shipping_id")) {
            $this->_shippingMethod = func_new("Shipping", $this->get("shipping_id"));
        }
        return $this->_shippingMethod;
    } // }}}

    function setShippingMethod($shippingMethod) // {{{
    {
        if (!is_null($shippingMethod)) {
            $this->_shippingMethod = $shippingMethod;
            $this->set("shipping_id", $shippingMethod->get("shipping_id"));
        } else {
            $this->_shippingMethod = false;
            $this->set("shipping_id", 0);
        }
    } // }}}

    function &getPaymentMethod() // {{{
    {
        if (is_null($this->_paymentMethod) && $this->get("payment_method")) {
        	$pm =& func_new("PaymentMethod");
        	if ($pm->isRegisteredMethod($this->get("payment_method"))) {
            	$this->_paymentMethod =& func_new("PaymentMethod", $this->get("payment_method"));
            }
        }
        return $this->_paymentMethod;
    } // }}}
    
    function setPaymentMethod($paymentMethod) // {{{
    {
        $this->_paymentMethod = $paymentMethod;
        if (is_null($paymentMethod)) {
            $this->set("payment_method", 0);
        } else {
            $this->set("payment_method", $paymentMethod->get("payment_method"));
        }
    } // }}}

    function &getShippingRates() // {{{
    {
        if (is_null($this->_shippingRates)) {
            $this->calcShippingRates();
        }
        return $this->_shippingRates;
    } // }}}

    function &getProfile() // {{{
    {
        if (is_null($this->_profile)) {
            if($pid = $this->get("profile_id")) {
                $this->_profile =& func_new("Profile", $pid);
            }
        }
        return $this->_profile;
    } // }}}

    function setProfile($profile) // {{{
    {
        if (is_null($profile)) {
            $this->_profile = null;
            $this->set("profile_id", 0);
        } else {
            $this->_profile = $profile;
            $this->set("profile_id", $profile->get("profile_id"));
        }
    } // }}}
    
    function &getOrigProfile() // {{{
    {
        if (is_null($this->_origProfile)) {
            if($pid = $this->get("orig_profile_id")) {
                $this->_origProfile =& func_new("Profile", $pid);
            } else {
                return $this->getProfile();
            }
        }
        return $this->_origProfile;
    } // }}}

    function setOrigProfile($profile) // {{{
    {
        if (is_null($profile)) {
            $this->_origProfile = null;
            $this->set("orig_profile_id", 0);
        } else {
            $this->_origProfile = $profile;
            $this->set("orig_profile_id", $profile->get("profile_id"));
        }
    } // }}}

    function setProfileCopy($prof) // {{{
    {
        $this->set("origProfile", $prof);
        $p = $prof->cloneObject();
        $p->set("order_id", $this->get("order_id"));
        $p->update();
        $this->set("profile", $p);
    } // }}}

    /**
    * Returns all tax values as an associative Array.
    */
    function &getAllTaxes() // {{{
    {
        if (is_null($this->_taxes)) {
            if ($this->get("taxes") == "") {
                $this->_taxes = array();
            } else {
                $this->_taxes = unserialize($this->get("taxes"));
            }
        }
        return $this->_taxes;
    } // }}}

    function setAllTaxes($taxes) // {{{
    {
        $this->_taxes = $taxes;
        $this->set("taxes", serialize($taxes));
    } // }}}

    /**
    * Returns the named tax label.
    */
    function getTaxLabel($name) // {{{
    {
        $tax = func_new("TaxRates");
        return $tax->getTaxLabel($name);
    } // }}}

	function getRegistration($name) // {{{
	{
		$tax = func_new("TaxRates");
		return $tax->getRegistration($name);
	}	

	function isTaxRegistered()
	{
		foreach($this->get("allTaxes") as $name => $value) 
			if ($this->getRegistration($name) !='') return true;
		return false;	
	}
    /**
    * Selects taxes to be shown in cart totals.
    */
    function &getDisplayTaxes() // {{{
    {
        if (is_null($this->get("profile")) && !$this->config->get("General.def_calc_shippings_taxes")) {
        	return null;
        }

        $taxRates =& func_new("TaxRates");
		$values = $names = $orderby = array();
        foreach ($this->get("allTaxes") as $name => $value) {
            if ($taxRates->getTaxLabel($name)) {
                $values[] = $value;
				$names[] = $name;
				$orderby[] = $taxRates->getTaxPosition($name);
            }
        }
		// sort taxes according to $orderby
		array_multisort($orderby, $values, $names);
		// compile an associative array $name=>$value
		$taxes = array();
		for ($i=0; $i<count($names); $i++) {
			$taxes[$names[$i]] = $values[$i];
		}
        return $taxes;
    } // }}}

    function &getItems() // {{{
    {
        if (is_null($this->_items)) { // cache result in _items
			if ($this->isPersistent) {
				$oi =& func_new("OrderItem");
				$this->_items =& $oi->findAll("order_id='".$this->get("order_id")."'", "orderby");
                for ($i=0; $i<count($this->_items); $i++) {
                    $this->_items[$i]->order =& $this;
                }
			} else {
				$this->_items = array();
			}
        }
        return $this->_items;
    } // }}}

    function getItemsCount() // {{{
    {
        return count($this->get("items"));
    } // }}}

    function getItemsFingerprint() // {{{
    {
    	if ($this->isEmpty()) {
    		return false;
    	}

        $result = array();
        $items =& $this->get("items");
        foreach ($items as $item_idx => $item) {
        	$result[] = array
        	(
        		$item_idx,
        		$item->get("key"),
        		$item->get("amount")
        	); 
        }

        return serialize($result);
    } // }}}

    /**
    * Checks whether the shopping cart/order is empty.
    *
    * @access public
    * @return bool True if cart is empty/False otherwise.
    */
    function isEmpty() // {{{
    {
        return count($this->get("items")) == 0;
    } // }}}

    function addItem($item) // {{{
    {
		if (!$item->is("valid")) {
			return;
		}
        $key = $item->get("key");
        $items =& $this->get("items");
        // if the item already exists
        for ($i=0; $i<count($items); $i++) {
            if ($items[$i]->get("key") == $key) {
                // add quantity
				$items[$i]->updateAmount($items[$i]->get("amount") + $item->get("amount"));
                return;
            }
        }
        // otherwise create an item
        $this->_createItem($item);
    } // }}}

    function deleteItem(&$item) // {{{
    {
        $item->delete();
        $this->refresh("items");
    } // }}}

    /**
    * Updates the specified item.
    *
    * @param OrderItem $item The order item to update
    * @access public
    */
    function updateItem(&$item) // {{{
    {
        if (!is_null($this->_items)) {
			for($i = 0; $i < count($this->_items); $i++) {
				if ($this->_items[$i]->_uniqueKey == $item->_uniqueKey) {
					$this->_items[$i] = $item;
				}
			}
		}
    } // }}}

    /**
    * Calculates order totals and store them in the order properties:
    * total, subtotal, tax, shipping, etc
    */
    function calcTotals() // {{{
    {
        $this->calcTotal();
    } // }}}

    /**
    * Generate a string representation of the order
    * to send to a payment service.
    */
    function getDescription() // {{{
    {
        $result = array();
        foreach ($this->get("items") as $item) {
			if (method_exists($item, "getDescription")) {
            	$result[] = $item->getDescription();
			} else {
            	$result[] = $item->get("description");
            }
        }
		$result[] = "";
        $result = implode("\n", $result);
        return $result;
    } // }}}

    function &getDetails() // {{{
    {
        if (is_null($this->_details)) {
            $d = parent::get("details");
            if ($d == '') {
                $this->_details = array();
            } else {
                $this->_details = unserialize($d);
            }
        }
        return $this->_details;
    } // }}}

    function setDetails($value) // {{{
    {
    	if (!is_array($value)) {
    		$value = unserialize($value);
    	}
    	if (!is_array($value)) {
    		$value = array();
    	}
        parent::set("details", serialize($value));
        $this->_details = $value;
    } // }}}

    function &getDetailLabels() // {{{
    {
        if (is_null($this->_detailLabels)) {
            $d = parent::get("detail_labels");
            if ($d == '') {
                $this->_detailLabels = array();
            } else {
                $this->_detailLabels = unserialize($d);
            }
        }
        return $this->_detailLabels;
    } // }}}

    function getDetailLabel($name) // {{{
    {
        $d = $this->getDetailLabels();
        return $d[$name];
    } // }}}

    function setDetailLabels($value) // {{{
    {
        parent::set("detail_labels", serialize($value));
        $this->_detailLabels = $value;
    } // }}}

    function _prepareSearchWhere($where)
    {
    	return $where;
    }

    function search($profile, $id1, $id2, $status, $startDate, $endDate, $orig_profile=true) // {{{
    {
        $where = array();
        if (isset($profile)) {
            $where[] = (($orig_profile)?"orig_profile_id='":"profile_id='") .$profile->get("profile_id")."'";
        }
        if (!empty($id1)) {
            $where[] = "order_id>=".(int)$id1;
        }
        if (!empty($id2)) {
            $where[] = "order_id<=".(int)$id2;
        }
        if (!empty($status)) {
            $where[] = "status='".substr($status,0,1)."'";
        }
        if ($startDate) {
            $where[] = "date>='".intval($startDate)."'";
        }
        if ($endDate) {
            $where[] = "date<='".intval($endDate)."'";
        }

        $where = $this->_prepareSearchWhere($where);
        return $this->findAll(implode(" AND ", $where), "date DESC");
    } // }}}

    ////////////////// Order status functions //////////////////

	/**
	* Calls one of the following function: declined, processed, failed, queued
	* when order status is changed. See share/doc/developmentdocs/status.gif
	*/
    function statusChanged($oldStatus, $newStatus) // {{{
    {
        if ($oldStatus != 'P' && $oldStatus != 'C' && $oldStatus != 'Q' &&
                ($newStatus =='P' || $newStatus == 'C' || $newStatus == 'Q')) {
            $this->checkedOut();
        }
        if ($oldStatus == 'I' && $newStatus == 'Q') {
            $this->queued();
        }
        if ($oldStatus != 'P' && $oldStatus != 'C' &&
                ($newStatus =='P' || $newStatus == 'C')) {
            $this->processed();
        }
        if (($oldStatus == 'P' || $oldStatus == 'C') &&
            $newStatus !='P' && $newStatus != 'C') {
            $this->declined();
        }
        if (($oldStatus == 'P' || $oldStatus == 'C' || $oldStatus == 'Q') &&
            $newStatus !='P' && $newStatus != 'C' && $newStatus != 'Q') {
            $this->uncheckedOut();
        }

        if ($oldStatus != 'F' && $oldStatus != 'D' && 
            ($newStatus == 'F' || $newStatus == 'D')) {
            $this->failed();
        }
    } // }}}

	function checkedOut() {}
	function uncheckedOut() {}
    function queued() {}

    /**
    * Called when an order successfully placed by a client.
    */
    function succeed()
    {
        // save order ID#
        $this->session->set("last_order_id", $this->get("order_id"));

        // send email notification about initially placed order
        $status = $this->get("status");
        if (!($status == "P" || $status == "C") && ($this->config->get("Email.enable_init_order_notif") || $this->config->get("Email.enable_init_order_notif_customer"))) {    
            $mail =& func_new("Mailer");
            // for compatibility with dialog.order syntax in mail templates
            $mail->order =& $this;
            // notify customer
            if ($this->config->get("Email.enable_init_order_notif_customer")) {
                $mail->adminMail = false;
                $mail->selectCustomerLayout();
                $mail->set("charset", $this->get("profile.billingCountry.charset"));
                $mail->compose(
                        $this->config->get("Company.orders_department"),
                        $this->get("profile.login"),
                        "order_created");
                $mail->send();
            }

            // notify admin about initially placed order
            if ($this->config->get("Email.enable_init_order_notif")) {
                // whether or not to show CC info in mail notification
                $mail->adminMail = true;
                $mail->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
                $mail->compose(
                        $this->config->get("Company.site_administrator"),
                        $this->config->get("Company.orders_department"),
                        "order_created_admin");
                $mail->send();
            }
        }
    }
    
    /**
    * called when an order becomes processed, before saving it
    * to the databsse
    */
    function processed() // {{{
    {
        $mail =& func_new("Mailer");
        $mail->order =& $this; 
		$mail->adminMail = true;
		$mail->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mail->compose(
                $this->config->get("Company.site_administrator"),
                $this->config->get("Company.orders_department"),
                "order_processed");
        $mail->send();

		$mail->adminMail = false;
		$mail->selectCustomerLayout();
		$mail->set("charset", $this->get("profile.billingCountry.charset"));
        $mail->compose(
                $this->config->get("Company.site_administrator"),
                $this->get("profile.login"),
                "order_processed");
        $mail->send();
    } // }}}

    /**
    * Called when an order status changed from processed to not processed
    */
    function declined()
    {
    }

    /**
    * Called when the order status changed to failed
    */
    function failed()
    {
        $mail =& func_new("Mailer");
        $mail->order =& $this; 
		$mail->adminMail = true;
		$mail->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mail->compose(
                $this->config->get("Company.site_administrator"),
                $this->config->get("Company.orders_department"),
                "order_failed");
        $mail->send();

		$mail->adminMail = false;
		$mail->selectCustomerLayout();
		$mail->set("charset", $this->get("profile.billingCountry.charset"));
        $mail->compose(
                $this->config->get("Company.orders_department"),
                $this->get("profile.login"),
                "order_failed");
        $mail->send();
    }

    //////////////// Private data storage functions ////////////////

    /**
    * Changes were made to the order
    */
    function _beforeSave() // {{{
    {
        if ($this->_statusChanged) {
            $this->statusChanged($this->_oldStatus, $this->get("status"));
            $this->_statusChanged = false;
        }
        parent::_beforeSave();
    } // }}}

    function create() // {{{
    {
        parent::create();
        $orderStartID = (int)$this->config->get("General.order_starting_number"); 
        if ($this->get("order_id") < $orderStartID) {
            $order_id = $this->get("order_id");
            $this->set("order_id", $orderStartID);
            $table = $this->db->getTableByAlias($this->get("alias"));
            $this->db->query("UPDATE $table SET order_id=$orderStartID WHERE order_id=$order_id");
        }
    } // }}}

    function _createItem($item) // {{{
    {
        if (!$this->isPersistent) {
            $this->create();
        }

        $item->set("order_id", $this->get("order_id"));
        // select maximum orderby from order items
        $orderBy = $this->db->getOne("select max(orderby) from " . $this->db->getTableByAlias("order_items") . " where order_id=" . $this->get("order_id"));
        if ($orderBy===null) {
            $orderBy = 0;
        } else {
            $orderBy ++;
        }
        $item->set("orderby", $orderBy);
        $item->create();
        $item->order = &$this;
        if (isset($this->_items)) {
            // are items cached ?
            $this->_items[] =& $item;
        }
    } // }}}

    function remove()
    {
        $status = $this->get("status");
		if ($status == "Q" || $status == "I") {
	        $this->set("status", "D"); // decline an order before deleting it
            $this->statusChanged($status, "D");
		}
        $this->delete();
    }

    function delete() // {{{
    {
        foreach ($this->get("items") as $item) {
            $this->deleteItem($item);
        }
        if (!is_null($this->get("profile")) && $this->get("status") != 'T') {
            $p = $this->get("profile");
            // don't remove profile if this is a cart object
            $this->call("profile.delete");
        }
        parent::delete();
    } // }}}

    /**
    * Removes expired 'T' orders (carts)
    */
    function collectGarbage() // {{{
    {
        $order = func_new("Order");
        $order->_range = "status='T'";
        $orders = $order->findAll("date<".(time()-ORDER_EXPIRATION_TIME));
        foreach($orders as $o) {
            $o->delete();
        }
    } // }}}

    function isShowCCInfo()
    {
        return $this->get("payment_method") == "credit_card" && $this->config->get("Email.show_cc_info");
    }

    function isProcessed()
    {
        return $this->get("status") == "P" || $this->get("status") == "C";
    }

    function isQueued()
    {
        return $this->get("status") == "Q";
    }

    function recalcItems()
    {
        $items = $this->getItems();
        if (is_array($items)) {
            foreach ($items as $item_key => $item) {
            	$product = $item->get("product");
            	if ($this->config->get("Taxes.prices_include_tax") && isset($product)) {
            		$oldPrice = $item->get("price");
            		$items[$item_key]->setProduct($item->get("product"));
                    $items[$item_key]->updateAmount($item->get("amount"));
                    if ($items[$item_key]->get("price") != $oldPrice) {
						$this->_items = null;
					}
                }
            }
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

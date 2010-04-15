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
class XLite_Module_Promotion_Model_SpecialOffer extends XLite_Model_Abstract
{	
    public $fields = array(
		'offer_id' => 0,
		'date' => 0,
		'start_date' => 0,
		'end_date'	=> 0,
		'status'	=> 'Trash',
		'title' => '',
		'product_id' => 0,
		'category_id' => 0,
		'allProducts' => 0,
		'conditionType' => '',
		'bonusType' => '',
		'amount' => 0,
		'bonusAmount' => 0,
		'bonusAmountType' => '%',
		'bonusAllProducts' => 0,
		'bonusCategory_id' => 0,
		'enabled' => 1,
		'order_id' => 0,
		'bonusAllCountries' => 1,
		'bonusCountries' => ''
		);	
	public $alias = "special_offers";	
	public $autoIncrement = "offer_id";	
	public $_range = "order_id=0";	

	public $product = null;	
	public $category = null;	
	public $products = null;	
	public $bonusProducts = null;	
	public $bonusCategory = null;	
	public $bonusPrices = null;	
	public $defaultOrder = "date";	
	public $condition = null;	
	public $bonus = null;
	
	function getProduct()
    {
        if (is_null($this->product)) {
            if ($this->get("product_id")) {
                $this->product = new XLite_Model_Product($this->get("product_id"));
            } else {
                $this->product = null;
            }
        }
        return $this->product;
    }
   	
	function getMembership()
	{	
		return $this->auth->getComplex('profile.membership');
	}
 
	function getCategory()
    {
        if (is_null($this->category)) {
            if ($this->get("category_id")) {
                $this->category = new XLite_Model_Category($this->get("category_id"));
            } else {
                $this->category = null;
            }
        }
        return $this->category;
    }

    function getBonusCategory()
    {
        if (is_null($this->bonusCategory)) {
            if ($this->get("bonusCategory_id")) {
        		$this->bonusCategory = new XLite_Model_Category($this->get("bonusCategory_id"));
            } else {
                $this->bonusCategory = null;
            }
        }
        return $this->bonusCategory;
    }

    function initProducts()
    {
		$this->products = array();
		$this->bonusProducts = array();
		$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
		$so_products = $so_product->findAll("offer_id = ". $this->get("offer_id"));
		foreach ($so_products as $_product) {
			$product = new XLite_Model_Product($_product->get("product_id"));
			if ($_product->get("type") == "C") {
				$this->products[] = $product;
			} else {
				if ($product->filter()) {
					$this->bonusProducts[] = $product;
				}
			}
		}
    }
    
    function getProducts()
    {
        if (is_null($this->products)) {
            $this->initProducts();
        }
        return $this->products;
    }

    function getBonusProducts()
    {
        if (is_null($this->bonusProducts)) {
            $this->initProducts();
        }
        return $this->bonusProducts;
    }

    function getBonusPrices()
    {
        if (is_null($this->bonusPrices)) {
            $pricing = new XLite_Module_Promotion_Model_BonusPrice();
            $this->bonusPrices = $pricing->findAll("offer_id=" . $this->get("offer_id"));
        }
        return $this->bonusPrices;
	}

	/**
	* Add bonus/condition product ('B' / 'C' $type)
	*/
	function addProduct($product, $type = 'C')
	{
		$product_id = $product->get("product_id");
		$offer_id = $this->get("offer_id");
		$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
        if(!$so_product->find("offer_id = $offer_id AND product_id = $product_id AND type = '$type'"))
		{
			$so_product->set("offer_id",$offer_id);
			$so_product->set("product_id",$product_id);
			$so_product->set("type",$type);
			$so_product->create();	
			$this->products = $this->bonusProducts = null;
		}
	}

	function deleteProduct($product, $type = 'C')
	{
		$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
		if($so_product->find("offer_id = ". $this->get("offer_id") ." AND product_id =". $product->get("product_id") ." AND type = '$type'"))
			$so_product->delete();
	}

	function addBonusPrice($product, $category, $price = null, $type = '$')
	{
		if (is_null($product)) {
			$product_id = 0;
		} else {
			$product_id = $product->get("product_id");
		}
		if (is_null($category)) {
			$category_id = 0;
		} else {
			$category_id = $category->get("category_id");
		}
		if (is_null($price)) {
			$price = $product->get("price");
		}
		$pricing = new XLite_Module_Promotion_Model_BonusPrice();
		$pricing->set("offer_id", $this->get("offer_id"));
		$pricing->set("product_id", $product_id);
		$pricing->set("category_id", $category_id);
		$pricing->set("bonusType", $type);
		$pricing->set("price" , $price);
		if ($pricing->is("exists")) {
			$pricing->update();
		} else {
			$pricing->create();
		}
        $this->bonusPrices = null;
	}

	function changeBonusPrice($product, $category, $price)
	{
		if (is_null($product)) {
			$product_id = 0;
		} else {
			$product_id = $product->get("product_id");
		}
		if (is_null($category)) {
			$category_id = 0;
		} else {
			$category_id = $category->get("category_id");
		}
		$pricing = new XLite_Module_Promotion_Model_BonusPrice();
		$pricing->set("offer_id", $this->get("offer_id"));
		$pricing->set("product_id", $product_id);
		$pricing->set("category_id", $category_id);
		$pricing->set("price" , $price);
		$pricing->update();
        $this->bonusPrices = null;
	}

	function deleteBonusPrice($product, $category)
	{
		if (is_null($product)) {
			$product_id = 0;
		} else {
			$product_id = $product->get("product_id");
		}
		if (is_null($category)) {
			$category_id = 0;
		} else {
			$category_id = $category->get("category_id");
		}

		$pricing = new XLite_Module_Promotion_Model_BonusPrice();
		$pricing->set("offer_id", $this->get("offer_id"));
		$pricing->set("product_id", $product_id);
		$pricing->set("category_id", $category_id);
		$pricing->delete();
	}

	function delete()
	{
		$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
		$so_products = $so_product->findAll("offer_id = ". $this->get("offer_id"));
		foreach($so_products as $_product)
			$_product->delete();

		$so_bonusPrice = new XLite_Module_Promotion_Model_BonusPrice();
		$so_bonusPrices = $so_bonusPrice->findAll("offer_id = ". $this->get("offer_id"));
 		foreach($so_bonusPrices as $_bonusPrice)
	       	$_bonusPrice->delete();

 		$membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
        $memberships = $membership->findAll('offer_id = ' . $this->get('offer_id'));
        if (is_array($memberships))
            foreach($memberships as $membership_) {
                $membership_->delete();
            }
		
		parent::delete();
	}

	function create()
	{
		// setup creation time
		if (!$this->get("date")) {
			$this->set("date", time());
		}
		if (!$this->get("start_date")) {
			$this->set("start_date",time());
		}
		if (!$this->get("end_date")) {
			$this->set("end_date",time());
		}
		if (!$this->get("status")) {
			$this->set("status",'Trash');
		}
		parent::create();
	}

	function getCondition()
	{
		if (!is_null($this->condition)) {
			return $this->condition;
		}
		
		$this->condition = array();
		switch($this->get("conditionType")) {
			case "productAmount":
		    case "eachNth":
				if ($this->get('product_id')) {
					$product = new XLite_Model_Product($this->get("product_id"));
					$this->condition["Product"] = $product->get("name");
				}
				if ($this->get('category_id')) {
					$category = new XLite_Model_Category($this->get("category_id"));
					$this->condition["Category"] = $category->get('name');
				}
				$this->condition["Amount"] = $this->get("amount");
				break;
			case "orderTotal":
				$this->condition["Total"] =  sprintf($this->config->getComplex('General.price_format'), sprintf("%.02f",$this->get("amount")));
				break;
			case "productSet":
					$this->products = null;
					$this->initProducts();
					foreach($this->products as $key => $product) {
						$index = $key + 1;
						$this->condition["Product ".$index] = $product->get('name');	
					}
				break;
			case "hasMembership":
				$membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
				$memberships = $membership->findAll('offer_id =' .  $this->get("offer_id"));
				foreach ($memberships as $membership_)
					$applied .= "," . $membership_->get("membership");
				$this->condition["Memberships"] = substr($applied,1);
				 break;
			case "bonusPoints":
				$this->condition["Bonus points"] = $this->get("amount");			
			break;
		}

		return $this->condition;
	}	

	function getBonus()
	{
		if (!is_null($this->bonus)) {
			return $this->bonus;
		}

		$this->condition = array();
		switch($this->get("bonusType")) {
			case "discounts":
				if ($this->get("bonusAllProducts")) {
					$this->condition["Discount on"] = "All products"; 	
				} else {
					if($this->get("bonusCategory_id")) {
						$category = new XLite_Model_Category($this->get("bonusCategory_id"));
						$this->condition["Discount on"] = $category->get("name");
					} else {
		                $this->bonusProducts = null;
		                $this->initProducts();
        		        foreach($this->bonusProducts as $key => $product) {
                	       $index = $key + 1;
                           $this->condition["Product ".$index] = $product->get("name");
                        }
					}
				}
				$value = '';
				if ($this->get("bonusAmountType") == '$') {
					$wg = new XLite_View_Abstract();
					$value = $wg->price_format($this->get("bonusAmount"));
				} else {
					$value = $this->get("bonusAmount").$this->get("bonusAmountType");
				}
				$this->condition["Discount"] = $value;
			break;
			case "specialPrices":
				$pricing = new XLite_Module_Promotion_Model_BonusPrice();
				$prices = $pricing->findAll("offer_id = " . $this->get("offer_id"));
				$condition = "";
				foreach($prices as $key => $price) {
					if ($price->get('product_id')) {
						$product = new XLite_Model_Product($price->get('product_id'));
						$condition = "Product: " . $product->get('name') ." ";
					}
					if ($price->get('category_id')) {
                        $category = new XLite_Model_Category($price->get('category_id'));
						$condition .= "Category: " . $category->get('name');
					}
					$index = $key + 1;
					$this->condition["Bonus ".$index] = $condition;
				}
			break;	
			case "freeShipping":
				$this->condition["Countries"] = ($this->get("bonusAllCountries")) ? "All countries" : $this->get("bonusCountries");
			break;
			case "bonusPoints":
				$this->condition["Bonus points"] = $this->get("bonusAmount");
			break;
		}
		$this->bonus = $this->condition;
		return $this->bonus;	
	}

	function checkBonus($order)
	{
		switch($this->get("bonusType"))	{
        	case "discounts":
			case "specialPrices":
				$bonusPrices = $this->get("bonusPrices");
				foreach($bonusPrices as $price) {
					$order->set("_count_all_products", (($this->get("allProducts") == 1) ? true : false));
					if ($order->_getProductAmount($price->get("product"), $price->get("category")) > 0) return true;
				}
				$bonusProducts = ($this->get("allBonusProducts") ? $this->get("allBonusProducts") : $this->get("bonusProducts"));
				foreach($bonusProducts as $product) {
					foreach($order->get("items") as $item) {
						if ($item->get("product_id") == $product->get("product_id"))
							return true;
					}
				}
				return false;
			case "freeShipping":			 
				return $this->checkCountry($order->getComplex('profile.shipping_country'));
	        case "bonusPoints": 
				return true;	
		}			   
	}
	
	function checkCondition($order)
	{
		// find $product_id and calculate its total
		$order->set("_count_all_products", (($this->get("allProducts") == 1) ? true : false));
		$amount = $order->_getProductAmount($this->get("product"), $this->get("category"));
		switch ($this->get("conditionType")) {
		case "productAmount":
			return $amount >= $this->get("amount");
		case "eachNth":
			return $amount >= $this->get("amount");
		case "orderTotal":
			$order->_bonusPrices = true;
            $order->calcSubTotal();
			$subtotal = $order->get("subtotal");
			return $subtotal >= $this->get("amount");
		case "productSet":
			foreach ($this->get("products") as $product) {
				$order->set("_count_all_products", (($this->get("allProducts") == 1) ? true : false));
				if (!$order->_getProductAmount($product, null)) {
					return false;
				}
			}
			return true;
		case "hasMembership":
			if (!is_null($order->get("profile"))) {	
				$membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
				$memberships = $membership->findAll("offer_id = ". $this->get("offer_id"));
				foreach($memberships as $membership_) {
					if ($order->getComplex('profile.membership') == $membership_->get("membership")) {
						return true;
					}
				}
			}	
			return false;
		case "bonusPoints":
			if (!is_null($order->get("profile"))) {
				return $order->getComplex('profile.bonusPoints') >= $this->get("amount");
			}
			return false;
		}
		return false;
	}

	function _discountAppliesTo($product)
	{
		$order = new XLite_Model_Order();
		if (!is_null($this->get("bonusCategory")) && $order->_inCategoryRecursive($product, $this->get("bonusCategory"))) {
			return true;
		}
		if (!is_null($this->get("bonusProducts"))) {
			foreach ($this->get("bonusProducts") as $bonusProduct) {
				if ($bonusProduct->get("product_id") == $product->get("product_id")) {
					return true;
				}
			}
		}
		return $this->get("bonusAllProducts");
	}

	function getBonusTaxedPrice($item, $price = null)
	{
		$this->_calcTaxedPrice = true;
		$price = $this->getBonusPrice($item, $price);
		$this->_calcTaxedPrice = false;

		return $price;
	}

	function getBonusPrice($item, $price = null)
	{
		$product = $item->get("product");	
		if (is_null($product)) {
			$product = $item;
		}
		if (is_null($price)) {
			// get original product's price
			$price = $product->get("price");
		}
		if ($this->get("bonusType") == "discounts") {
			if ($this->_discountAppliesTo($product)) {
				if ($this->get("bonusAmountType") == "%") {
					$price *= (100 - $this->get("bonusAmount")) / 100;
				} else {
					$price -= $this->get("bonusAmount");
				}   
			}
		} elseif($this->get("bonusType") == "specialPrices") {
            $order = new XLite_Model_Order();
			foreach ($this->get("bonusPrices") as $bonusPrice) {
				if ($bonusPrice->get("product_id") == $product->get("product_id") || !is_null($bonusPrice->get("category")) && $order->_inCategoryRecursive($product, $bonusPrice->get("category"))) {
					if ($bonusPrice->get("bonusType") == '$') {
						$price = $bonusPrice->get("price");
        				if ($this->config->getComplex('Taxes.prices_include_tax')) {
							$item->set("originalBonusPrice", $price);
							$product->set("price", $price);
							$price = $product->get("listPrice");
						}
						return $price;
					} else {
						$price = $bonusPrice->get("price") * $price / 100;
        				if ($this->config->getComplex('Taxes.prices_include_tax') && $this->_calcTaxedPrice) {
							$item->set("originalBonusPrice", $price);
							$product->set("price", $price);
							$price = $product->get("listPrice");
						}
						return $price;
					}
				}
			}
		}
		return $price;
	}

	function compareOffers(&$offer1, &$offer2, $order)
	{
	    $offerScheme = $this->xlite->config->getComplex('Promotion.offerScheme');
	    if (!$offerScheme) {
			return 0;
	    }

		$conditionType1 = $offer1->get("conditionType");
		$conditionType2 = $offer2->get("conditionType");
		$bonusType1 = $offer1->get("bonusType");
		$bonusType2 = $offer2->get("bonusType");

		if ($conditionType1 == $conditionType2) {
		    // TODO: should be configurable !!!!
			if ($conditionType1 == "eachNth") {
				if (($offer1->get("product_id") == $offer2->get("product_id")) || ($offer1->get("category_id") == $offer2->get("category_id"))) {
					$items = $order->getItems();
					foreach ($items as $item) {
						if ($item->isOfferCondition($offer1) && $item->isOfferCondition($offer2)) {
            				if ($offer2->get("amount") > $offer1->get("amount")) {
            					return 1;	// should replace another
            				}
            				if ($offer2->get("amount") < $offer1->get("amount")) {
            					return -1;	// should skip it
            				}
						}
					}
				}
			}

		    // TODO: should be configurable !!!!
			if ($bonusType1 == $bonusType2 && $bonusType1 == "bonusPoints") {
				$items = $order->getItems();
				foreach ($items as $item) {
					if ($item->isOfferCondition($offer1) && $item->isOfferCondition($offer2)) {
						if ($offer2->get("bonusAmount") < $offer1->get("bonusAmount")) {
							return -1;	// should skip it
						}
						if ($offer2->get("bonusAmount") > $offer1->get("bonusAmount")) {
        					return 1;	// should replace another
						}
					}
				}
			}

		    // TODO: should be configurable !!!!
			if ($bonusType1 == $bonusType2 && $bonusType1 == "discounts") {
				$items = $order->getItems();
				foreach ($items as $item) {
					if ($item->isOfferCondition($offer1) && $item->isOfferCondition($offer2)) {
						if ($offer2->get("bonusAmount") < $offer1->get("bonusAmount") && ($offer1->get("bonusAmountType") == $offer2->get("bonusAmountType"))) {
							return -1;	// should skip it
						}
						if ($offer2->get("bonusAmount") > $offer1->get("bonusAmount") && ($offer1->get("bonusAmountType") == $offer2->get("bonusAmountType"))) {
        					return 1;	// should replace another
						}
					}
				}
			}
		}

		return 0;
	}

	function equalsTo(&$offer)
	{
		$a1 = $this->get("properties");
		$a2 = $offer->get("properties");
		if (isset($a1["order_id"])) {
			unset($a1["order_id"]);
		}
		if (isset($a1["offer_id"])) {
			unset($a1["offer_id"]);
		}
		if (isset($a2["order_id"])) {
			unset($a2["order_id"]);
		}
		if (isset($a2["offer_id"])) {
			unset($a2["offer_id"]);
		}
		if (count($a1) == count($a2)) {
			foreach ($a1 as $key => $value) {
				if (!isset($a2[$key]) || $a2[$key] != $value) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	function __clone()
	{
		$clone = parent::__clone();

		$this->bonusPrices = null;
		foreach ($this->get("bonusPrices") as $bonusPrice) {
			$clone->addBonusPrice($bonusPrice->get("product"), $bonusPrice->get("category"), $bonusPrice->get("price"), $bonusPrice->get("bonusType"));
		}
		foreach ($this->get("bonusProducts") as $bonusProduct) {
			$clone->addProduct($bonusProduct, 'B');
		}
		foreach ($this->get("products") as $product) {
			$clone->addProduct($product, 'C');
		}
		$membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
		$memberships = $membership->findAll('offer_id = ' . $this->get('offer_id'));
		if (is_array($memberships))	
			foreach($memberships as $membership_) {
				$membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
				$membership->set('offer_id', $clone->get('offer_id'));
				$membership->set('membership',$membership_->get("membership"));
				$membership->create();
			}
				
		$clone->read(); // init fields
		return $clone;
	}

	function getAllBonusProducts()
	{
		$parentProduct = $this->get("product");
		if ($this->get("bonusProducts")) {
			$products = $this->get("bonusProducts");
			$bonusProducts = array();
			if (is_array($products)) {
				foreach ($products as $product) {
                	if (!is_null($parentProduct) && $parentProduct->get("product_id") == $product->get("product_id")) {
                		continue;
                	}
    				$bonusProducts[] = $product;
				}
			}
			if (count($bonusProducts) == 0) {
				$bonusProducts = null;
			}
			return $bonusProducts;
		}
		$products = array();
		$cart = XLite_Model_Cart::getInstance();
		foreach ($this->get("bonusPrices") as $bonusPrice) {
			if (!is_null($bonusPrice->get("product"))) {
				$product = $bonusPrice->get("product");
            	if (!is_null($parentProduct) && $parentProduct->get("product_id") == $product->get("product_id")) {
            		continue;
            	}
            	if (!$cart->isEmpty()) {
            		$same_cart_item = false;
					foreach ($cart->getItems() as $cart_item) {
            	 		if ($cart_item->get("product_id") == $product->get("product_id")) {
            	 			$same_cart_item = true;
            	 			break;
            	 		}
            	 	}
            	 	if ($same_cart_item) {
            			continue;
            		}
            	}
				if ($product->filter()) {
					$products[] = $product;
				}
			}
		}

		return $products;
	}

	function getAllBonusCategories()
	{
		$categories = array();
		$this->bonusPrices = null;
		foreach ($this->get("bonusPrices") as $bonusPrice) {
			if (!is_null($bonusPrice->get("category"))) {
				$áategory = $bonusPrice->get("category");
				if ($áategory->filter()) {
					$categories[] = $áategory;
				}
			}
		}
		if (!is_null($this->get("bonusCategory"))) {
			$áategory = $this->get("bonusCategory");
			if ($áategory->filter()) {
				$categories[] = $áategory;
			}
		}

		$cart = XLite_Model_Cart::getInstance();
		if (!$cart->isEmpty()) {
        	$excluded_categories = array();
			foreach ($cart->getItems() as $cart_item) {
				if ($cart_item->get("bonusItem")) {
					$product = $cart_item->get("product");
					if (!is_null($product)) {
						foreach($categories as $cat_idx => $cat) {
							if ($product->inCategory($cat)) {
								$excluded_categories[$cat_idx] = true;
							}
						}
					}
				}
			}
			foreach($excluded_categories as $cat_idx => $cat) {
				unset($categories[$cat_idx]);
			}
		}

		return $categories;
	}

	/**
	* To use from templates
	*/
	function isCategoryDiscountType($category, $type)
	{
		$cid = $category->get("category_id");
		if ($cid == $this->get("bonusCategory_id")) {
			return $this->get("bonusAmountType") == $type;
		}
		for ($i=0; $i<count($this->get("bonusPrices")); $i++) {
			if ($this->bonusPrices[$i]->get("category_id") == $cid) {
				return $this->bonusPrices[$i]->get("bonusType") == $type;
			}
		}
		return false;
	}

	function isCategoryDiscount($category)
	{
		return $category->get("category_id") == $this->get("bonusCategory_id");
	}

	function getCategoryDiscount($category)
	{
		$cid = $category->get("category_id");
		if ($cid == $this->get("bonusCategory_id")) {
			return $this->get("bonusAmount");
		}
		for ($i=0; $i<count($this->get("bonusPrices")); $i++) {
			if ($this->bonusPrices[$i]->get("category_id") == $cid) {
				return $this->bonusPrices[$i]->get("price");
			}
		}
		return false;
	}

	function _isConditionalProductPrice($product)
	{
		if (is_null($product)) {
			return false;
		}
		if ($this->get("conditionType") != "eachNth") {
			return false;
		}

		$this->bonusPrices = null;
		foreach ($this->get("bonusPrices") as $bonusPrice) {
			if (!is_null($bonusPrice->get("category"))) {
				$áategory = $bonusPrice->get("category");
				if ($product->inCategory($áategory)) {
					return true;
				}
			}
			if ($bonusPrice->get("product_id") != 0) {
				if ($product->get("product_id") == $bonusPrice->get("product_id")) {
					return true;
				}
			}
		}

		return false;
	}

	function _isConditionalProduct($product)
	{
		if (is_null($product)) {
			return false;
		}
		if (!is_null($this->get("product")) && $this->getComplex('product.product_id') == $product->get("product_id")) {
			return true;
		}
		foreach ($this->get("products") as $p) {
			if ($p->get("product_id") == $product->get("product_id")) {
				return true;
			}
		}
		if (!is_null($this->get("category"))) {
			$order = new XLite_Model_Order();
			return $order->_inCategoryRecursive($product, $this->get("category"));
		}
		return false;
	}

	function _isConditionalCategory($category)
	{
		if (is_null($category)) {
			return false;
		}
		if (!is_null($this->get("category"))) {
			$order = new XLite_Model_Order();
			return $order->_inCategoryRecursiveCategory($category, $this->get("category"));
		}
		return false;
	}

	/**
	* Delete bonus products that are not conditional products
	* Example: 'buy each 5th DVD and get one DVD or video CD for free'
	* removes 'video CD' from $this->products array (not from database!)
	* Return true if there are products or categories that remain.
	*/
	function excludeNonConditionalProducts()
	{
		if ($this->get("bonusAllProducts")) {
			// copy condition products
			$this->bonusProducts = array($this->get("product"));
			$this->bonusPrices = array();
			$this->bonusCategory = $this->get("category");
		} else {
			$prices = array();
			foreach ($this->get("bonusPrices") as $price) {
				if ($this->_isConditionalProduct($price->get("product")) || $this->_isConditionalCategory($price->get("category"))) {
					$prices[] = $price;
				}
			}
			$this->bonusPrices = $prices;

			$products = array();
			foreach ($this->get("bonusProducts") as $product) {
				if ($this->_isConditionalProduct($product)) {
					$products[] = $product;
				}
			}
			$this->bonusProducts = $products;

			// bonuscategory should be a subcategory of category or category
			// itself
			if (!is_null($this->get("category")) && !is_null($this->get("bonusCategory"))) {
				$order = new XLite_Model_Order();
				if ($order->_inCategoryRecursiveCategory($this->get("category"), $this->get("bonusCategory"))) {
					$this->bonusCategory = $this->get("category");

				} else if (!$order->_inCategoryRecursiveCategory($this->get("bonusCategory"), $this->get("category"))) {
					$this->bonusCategory = null;
				}
			} else {
				$this->bonusCategory = null;
			}
		}
		return $this->get("bonusProducts") || $this->get("bonusPrices") || !is_null($this->get("bonusCategory"));
	}

	function excludeInCartProducts($cart)
	{
		$product_ids = array();
		foreach ($cart->get("items") as $item) {
			$product_id = $item->get("product_id");
			if ($product_id) {
				$product_ids[] = $product_id;
			}
		}
		$bonusProducts = array();
		foreach ($this->get("bonusProducts") as $p) {
			$found = array_search($p->get("product_id"), $product_ids);
			if ($found == null || $found == false) {
				$bonusProducts[] = $p;
			}
		}
		$this->bonusProducts = $bonusProducts;
		$bonusPrices = array();
		foreach ($this->get("bonusPrices") as $p) {
			$found = array_search($p->get("product_id"), $product_ids);
			if ($found == null || $found == false) {
				$bonusPrices[] = $p;
			}
		}
		$this->bonusPrices = $bonusPrices;

		return $this->get("bonusProducts") || $this->get("bonusPrices") || !is_null($this->get("bonusCategory"));
	}

	function set($name, $value)
	{
		if ($name == "conditionType") {
			switch ($value) {
			case 'productSet':
				$this->set("product_id", 0);
				$this->set("category_id", 0);
				break;
			case 'productAmount':
			case 'eachNth':
			case 'orderTotal':
			case 'bonusPoints':
				if ($this->isPersistent) {
					$offer_id = $this->get("offer_id");
					$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
			        $so_products = $so_product->findAll("offer_id = $offer_id AND type='C'");
			        foreach($so_products as $_product)
            			$_product->delete();
				}
				if ($value == 'orderTotal' || $value == 'bonusPoints') {
					$this->set("product_id", 0);
					$this->set("category_id", 0);
				}
				break;
			}
		}
		if ($name == "bonusType") {
			$offer_id = $this->get("offer_id");
			if ($this->isPersistent) {
				if ($value == 'discounts' || $value == 'freeShipping' || $value == 'bonusPoints') {
					$so_bonusPrice = new XLite_Module_Promotion_Model_BonusPrice();
        			$so_bonusPrices = $so_bonusPrice->findAll("offer_id = $offer_id");
        			foreach($so_bonusPrices as $_bonusPrice)
           				$_bonusPrice->delete();
				}
				if ($value == 'specialPrices' || $value == 'freeShipping' || $value == 'bonusPoints') {
					$so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
                    $so_products = $so_product->findAll("offer_id = $offer_id AND type='B'");
                    foreach($so_products as $_product)
                        $_product->delete();
				}
			}
			if ($value == 'specialPrices' || $value == 'freeShipping' || $value == 'bonusPoints') {
				$this->set("bonusCategory_id", 0);
			}
		}

		parent::set($name, $value);
	}

	function checkCountry($code)
	{
		$c = new XLite_Model_Country($code);
		$name = $c->get("country");
		$countries = explode(',', $this->get("bonusCountries"));
		foreach ($countries as $c) {
			if (!strcasecmp($name, trim($c))) {
				return true;
			}
		}
		return false;
	}
	
	function collectGarbage() // {{{ 
	{
		$specialOffer = new XLite_Module_Promotion_Model_SpecialOffer();
		$specialOffers = $specialOffer->findAll("status = 'Trash'");
		foreach($specialOffers as $specialOffer_) {
			$specialOffer_->delete();
		}
	} // }}}

	function markInvalid()
	{
		$this->set("enabled", false);
		$this->set("status", "Invalid");
		$this->update();
	}
}

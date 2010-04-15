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
class XLite_Module_Promotion_Model_Cart extends XLite_Model_Cart implements XLite_Base_IDecorator
{	
	public $_originalItems = false;	
    public $doNotCallCartChanged = false;	
    public $doNotCallConstructorCalcTotal = false;

    function _constructorNeedCalcTotal()
    {
		if (isset($_REQUEST["target"]) && $_REQUEST["target"] == "checkout" && isset($_REQUEST["action"]) && $_REQUEST["action"] == "return") {
			return false;
		}

		return ($this->doNotCallConstructorCalcTotal)?false:true;
    }

    public function __construct($id = null)
    {
        $obj = new XLite_Base();
        $obj->logger->log("->Cart::constructor()");
        $this->doNotCallCartChanged = true;
        parent::__construct($id);
        $this->doNotCallCartChanged = false;
        if ($this->_constructorNeedCalcTotal()) {
        	$this->logger->log("->Cart::calcTotals()");
        	$this->calcTotals();
        	$this->logger->log("<-Cart::calcTotals()");
        }
        $this->logger->log("<-Cart::constructor()");
    }

    function checkout()
    {
        $this->logger->log("->Cart::checkout()");
        $this->doNotCallCartChanged = true;
        parent::checkout();
        $this->doNotCallCartChanged = false;
        $this->logger->log("<-Cart::checkout()");
    }

	function update()
	{
        $items = $this->getItems();
		if (!$this->doNotCallCartChanged) {
            $this->logger->log("->Cart::update() cartChanged is called");
            $this->cartChanged();
			$this->doNotCallCartChanged = true;
		} else {
            $this->logger->log("->Cart::update() cartChanged is not called");
            $this->doNotCallCartChanged = false;
        }
		// check bonus points
        $op = $this->get("origProfile");
        $status = $this->get("status");
		if ($status == "P" || $status == "Q" || $status == "C") {
			if ($this->get("payedByPoints") > $op->get("bonusPoints")*$this->config->getComplex('Promotion.bonusPointsCost')) {
				$this->set("payedByPoints", $op->get("bonusPoints")*$this->config->getComplex('Promotion.bonusPointsCost'));
			}
		}
        $this->calcTotals();
		parent::update();
        $this->logger->log("<-Cart::update()");
	}
	
	function cartChanged()
	{
        $this->logger->log("->cartChanged()");
		// each time cart is changed by customer, 
		// compare applicable special order list with
		// already applied bonuses
		$offers = $this->getSpecialOffers();
		$this->_appliedBonuses = null; // reset applied bonuses before proceeding
		$bonuses = $this->getAppliedBonuses();
        $this->logger->log("========== calculated offers ==========");
        $this->dumpBonuses($offers);
        $this->logger->log("========== stored offers ==============");
        $this->dumpBonuses($bonuses);
		if ($this->get("order_id")) {
            $this->logger->log("  change bonuses");
			// change bonuses attached to the order
			for ($i=0; $i<count($bonuses); $i++) {
				$bonuses[$i]->delete();
			}
            for ($i=0; $i<count($offers); $i++) {
                $offer = new XLite_Module_Promotion_Model_SpecialOffer($offers[$i]->get("offer_id"));
				$offer->bonusPrices = null;
				if ( function_exists("func_is_clone_deprecated") && func_is_clone_deprecated() ) {
					$newOffer = $offer->cloneObject();
				} else {
					$newOffer = $offer->clone();
				}
				$newOffer->set("order_id", $this->get("order_id"));
				$newOffer->update();
			}
			$this->_appliedBonuses = null;
		}

        // recalculate order items
		$this->_originalItems = true;
        $this->_items = null;
		parent::getItems();
		$items = $this->_items;
		$this->_items = array();
        foreach($items as $item) {
			if (!$item->get("bonusItem")) {
				$this->_items[] = $item;
			}        	
        }
        foreach($items as $item) {
			if ($item->get("bonusItem")) {
				$foundPair = false;
        		foreach($this->_items as $itemIdx => $tItem) {
        			if ($item->get("key") == $tItem->get("key")) {
        				$this->_items[$itemIdx]->set("amount", $tItem->get("amount") + $item->get("amount"));
        				$this->_items[$itemIdx]->update();
        				$item->delete();
        				$foundPair = true;
        				break;
        			}
        		}
        		if (!$foundPair) {
					$this->_items[] = $item;
        		}
        	}
        }
		$items = array_reverse($this->_items);

        $this->_appliedBonuses = null;
        $applied = $this->getAppliedBonuses();
        if (isset($bonuses)) {
        	unset($bonuses);
        }
		$bonuses = array();

        $this->logger->log("recalculate base on ");
        $this->dumpBonuses($applied);

        for ($i = 0; $i < count($applied); $i++) {
            $bonus = $applied[$i];
			if ($bonus->get("bonusType") != "discounts") {
				$bonuses[] = $bonus;
			}
		}

		$add = array();
        for ($j = 0; $j < count($bonuses); $j++) {
            $bonus = $bonuses[$j];
			$total = $this->_getProductAmount($bonus->get("product"), $bonus->get("category"));
			if ($bonus->get("conditionType") == "eachNth") {
		        // check eachNth -  conditions, redistribute amount if neccessary
				$N = $bonus->get("amount");
				$bonusItemsCount = (int)($total/$N);
			} else {
				$bonusItemsCount = $this->getDefaultBonusItemsCount();
			}
			for ($i=0; $i<count($items); $i++) {
				$item = $items[$i];
				if (!is_null($item->get("product"))) {
					$product_id = $item->get("product_id");
					if (!isset($add[$product_id])) {
						$add[$product_id] = 0;
					}
				}
			}
			for ($i=0; $i<count($items); $i++) {
                if (isset($item)) {
                	unset($item);
                }
				$item = $items[$i];
				if (!is_null($item->get("product"))) {
					$product_id = $item->get("product_id");
					$_bonusPrices = $item->getComplex('order._bonusPrices');
					$item->order->_bonusPrices = false;
					$price = $item->get("price");
					$item->order->_bonusPrices = $_bonusPrices;
					$bonusPrice = $bonus->getBonusPrice($item, $price);
					if ($price != $bonusPrice) {
						$toAdd = min($bonusItemsCount, $item->get("amount"));
						if ($toAdd) {
							$bonusItemsCount -= $toAdd;
							$add[$product_id] += $toAdd;
						}
					}
				}
				if ($bonusItemsCount == 0) 
					break; // end up with this bonus
			}
		}
		// update/create bonus items according to $add
		foreach ($add as $product_id => $bonusAmount) {
			// collect item pairs (regular item, bonus item)
            if (isset($regular)) {
            	unset($regular);
            }
			$regular = array();
            if (isset($bonus)) {
            	unset($bonus);
            }
			$bonus = array();
            if (isset($keys)) {
				unset($keys);
            }
			$keys = array();
			for ($i=0; $i<count($items); $i++) {
				if ($items[$i]->get("product_id") == $product_id) {
					$key = $items[$i]->get("key");
					if ($items[$i]->get("bonusItem")) {
						$bonus[$key] = $i;
					} else {
						$regular[$key] = $i;
					}
					$keys[$key] = 1;
				}
			}
			// for each item pair, modify its amount until bonus items count
			// is equal to $add
			foreach ($keys as $key => $tempo) {
				if (isset($regular[$key])) {
					$regularItem = $items[$regular[$key]];
					$regularItemAmount = $regularItem->get("amount");
				} else {
					$regularItem = null;
					$regularItemAmount = 0;
				}
				if (isset($bonus[$key])) {
					$bonusItem = $items[$bonus[$key]];
					$bonusItemAmount = $bonusItem->get("amount");
				} else {
					$bonusItem = null;
					$bonusItemAmount = 0;
				}

				$newBonusAmount = min($regularItemAmount+$bonusItemAmount, $bonusAmount);
				$newRegularAmount = $regularItemAmount+$bonusItemAmount - $newBonusAmount; // $regularItemAmount+$bonusItemAmount is an invariant
				if (is_object($regularItem)) {
					$reg_props = $regularItem->get("properties");
					$regularItem1 = new XLite_Model_OrderItem();
					$regularItem1->set("properties", $reg_props);
					$regularItem2 = new XLite_Model_OrderItem();
					$regularItem2->set("properties", $reg_props);
				} else {
					$regularItem1 = $regularItem2 = null;
				}
				if (is_object($bonusItem)) {
					$reg_props = $bonusItem->get("properties");
					$bonusItem1 = new XLite_Model_OrderItem();
					$bonusItem1->set("properties", $reg_props);
					$bonusItem2 = new XLite_Model_OrderItem();
					$bonusItem2->set("properties", $reg_props);
				} else {
					$bonusItem1 = $bonusItem2 = null;
				}
				$this->_updateItemAmount($regularItem1, $bonusItem1, $newRegularAmount, 0);
				$this->_updateItemAmount($bonusItem2, $regularItem2, $newBonusAmount, 1);
				$bonusAmount -= $newBonusAmount;
			}
		}
		$this->_originalItems = false;
		// removing bonuced items when bonus is lost
		do {
			$countDowning = false;
            if (isset($items)) {
            	unset($items);
            }
    		$items = parent::getItems(); // re-read items if changed
    		reset($items);
            $this->_items = $items;
            foreach($items as $item_idx => $item) {
            	if ($item->isBonusApplies() && !$item->isPromotionItem()) {
            		$item_key = $item->getKey();
            		foreach($items as $item_idx_2 => $item_2) {
            			if ($item_idx != $item_idx_2 && $item_2->getKey() == $item_key && (!$item_2->isBonusApplies() && !$item_2->isPromotionItem())) {
            			    $amount = $item->get("amount");
            			    $this->deleteItem($item);
            				$item_2->updateAmount($item_2->get("amount")+$amount);
            				$countDowning = true;
            				break;
            			}
            		}
            		if ($countDowning) {
            			break;
            		}
            	}
            }
		} while ($countDowning);

        // sort items by orderby:
        $sorted = array();
        foreach ($this->_items as $_key=>$_item) {
			// sometimes orderby values are equal
			$sort_index = sprintf("%3d-%3d", $_item->get("orderby"), $_key);
			$sorted[$sort_index] = $_item;
        }
        ksort($sorted);
        $this->_items = array_values($sorted);

        $this->logger->log("<-cartChanged()");
	}

	function _updateItemAmount($item, $peer, $amount, $bonusItem)
	{
		if ($amount) {
			if (!is_null($item)) {
				if ($item->get("amount") != $amount) {
					$item->set("amount", $amount);
					$item->update();
					$this->refresh("items");
				}
			} else {
				$item = $peer;
				$item->set("bonusItem", $bonusItem);
				$item->set("amount", $amount);
				$item->_createPeerItem = true; // change behaviour of item ordering
				$item->create();
				$item->read();
				$this->refresh("items");
			}
		} else {
			if (!is_null($item)) {
				$item->delete();
				$this->refresh("items");
			}
		}
	}

	/**
	* Return a list of available products for bonus price. It is displayed at
	* the first checkout step.
	*/
	function getBonusList()
	{
		$bonusList = array(); // array of BonusPrice, which is product-price pair
        // take bonuses into account
        $applied = $this->getAppliedBonuses();
        for ($i=0; $i<count($applied); $i++) {
            $bonus = $applied[$i];
            if ($bonus->get("conditionType") == "eachNth") {
				$total = $this->_getProductAmount($bonus->get("product"), $bonus->get("category"));
	            $N = $bonus->get("amount");
                if ($total % $N == 0) {
					$bonused = $this->_getProductAmount($bonus->getAllBonusProducts(), $bonus->getAllBonusCategories());
					if ($total / $N > $bonused) {
						// unless all products are already bonused
						$bonusList[] = $bonus;
					}
				} else {
					// find bonus products that are condition products
					// at the same time
					if ($bonus->excludeNonConditionalProducts() || (!$bonus->excludeNonConditionalProducts() && !$this->getBonucedItemsNumber($bonus))) {
						$bonusList[] = $bonus;
					}
				}
			} else {
				$bonusType = $bonus->get("bonusType");
				if ($bonusType != "freeShipping") {
					$bonusList[] = $bonus;
				}
            }
        }
		// exclude already-in-cart products
		$bonusListFiltered = array();
		for ($i=0; $i<count($bonusList); $i++) {
			if ($bonusList[$i]->get("bonusType") != "bonusPoints") {
            	if ($bonusList[$i]->get("conditionType") == "eachNth") {
    				$total = $this->_getProductAmount($bonusList[$i]->get("product"), $bonusList[$i]->get("category"));
    	            $N = $bonusList[$i]->get("amount");
                    if ($total % $N == 0) {
        				if ($bonusList[$i]->excludeInCartProducts($this) && !$this->getBonucedItemsNumber($bonusList[$i])) {
        					$bonusListFiltered[] = $bonusList[$i];
        				}
    				} else {
        				if (!$bonusList[$i]->excludeInCartProducts($this) || ($bonusList[$i]->excludeInCartProducts($this) && !$this->getBonucedItemsNumber($bonusList[$i]))) {
        					$bonusListFiltered[] = $bonusList[$i];
        				}
    				}
            	} else {
					$all_bonus_items_count = count((array) $bonusList[$i]->get("bonusProducts")) + count((array) $bonusList[$i]->get("bonusPrices"));
    				if ($bonusList[$i]->excludeInCartProducts($this)) {
						if (($bonusList[$i]->get("bonusType") != "specialPrices")) {
	    					$bonusListFiltered[] = $bonusList[$i];
						} else { 
							$unused_bonus_items_count = count((array) $bonusList[$i]->bonusProducts) + count((array) $bonusList[$i]->bonusPrices);
							$used_bonus_items_count = $all_bonus_items_count - $unused_bonus_items_count;
							if (($unused_bonus_items_count > 0) && ($used_bonus_items_count < $this->getDefaultBonusItemsCount())) {
	    						$bonusListFiltered[] = $bonusList[$i];
							}
						}
    				}
    			}
				// Clear bonusProducts and bonusPrices because they don't contain the 
				// products/prices, which are already in the shopping cart.
				$bonusList[$i]->bonusProducts = null;
				$bonusList[$i]->bonusPrices = null;
			} else {
				$bonusListFiltered[] = $bonusList[$i];
			}
		}

		return $bonusListFiltered;
	}

	/**
	* Sets a discount coupon code for this order; unset the previous 
	* coupon, if any. The original coupon is cloned and saved with the 
	* specific order_id.
	*/
	function setDC($dc)
	{
		// unset existing discount coupon
		if (!is_null($this->get("DC"))) {
			$this->DC->delete();
			$this->DC = null;
		}

        if (!is_null($dc)) {
			if ( function_exists("func_is_clone_deprecated") && func_is_clone_deprecated() ) {
	            $clone = $dc->cloneObject();
			} else {
				$clone = $dc->clone();
			}
            $clone->set("order_id", $this->get("order_id"));
            $clone->set("parent_id", $dc->get("coupon_id"));
            $clone->update();
            $this->set("discountCoupon", $dc->get("coupon_id"));
            $this->DC = $clone;
        } else {
			$this->set("discountCoupon", "");
		}
		return "";
	}

	/**
	* Checks if the discount coupon is valid for checkout.
	* Returns one of the following strings:
	* 'not_found', 'used', 'disabled', 'expired', and '' - ok
	*/
	function validateDiscountCoupon($couponCode = null)
	{
		if (is_null($couponCode)) {
			$couponCode = $this->getComplex('DC.coupon');
		}
		$coupon = new XLite_Module_Promotion_Model_DiscountCoupon();
		if (!$coupon->find("coupon='$couponCode' AND order_id=0")) {
			return 'not_found';
		}
		if ($coupon->get("status") == "U") {
			return 'used';
		}
		if ($coupon->get("status") == "D") {
			return 'disabled';
		}
		if ($coupon->get("expire") < time()) {
			return 'expired';
		}
		return '';
	}

	function getDefaultBonusItemsCount()
	{
		return 1;
	}
}

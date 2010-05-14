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
class XLite_Module_Promotion_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
    public $_isBonusItem = false;
    public $_createPeerItem = false;

    public function __construct($param = null)
    {
        $this->fields['bonusItem'] = 0; // amount of bonus items
        $this->primaryKey[] = "bonusItem"; // it's a primary key also
        parent::__construct();
    }

    function _getPromotionPrice($parentPrice = false)
    {
        if ($parentPrice) {
            return $this->formatCurrency(parent::get('price'));
        }

        $price = parent::get('price');

        global $calcAllTaxesInside;

        if (!is_null($this->get('product')) && $this->isComplex('order._bonusPrices')) {
            if ($this->getComplex('config.Taxes.prices_include_tax') && !$this->getComplex('config.Taxes.discounts_after_taxes') && !$calcAllTaxesInside) {
                // calculate original item price without taxes...
                $p = new XLite_Model_Product($this->get('product_id'));
                $p->set('price', 100.00); // use a 100 dollar product
                $taxed100 = $p->getTaxedPrice(); // tax increment
                $orig_price = $price * 100 / $taxed100;
                $price = $orig_price;
            }
            if (!($this->getComplex('config.Taxes.prices_include_tax') && $this->getComplex('config.Taxes.discounts_after_taxes') && $calcAllTaxesInside)) {
                // take bonuses into account
                foreach ($this->getComplex('order.appliedBonuses') as $bonus) {
                    if ($bonus->get('bonusType') == "discounts" || $this->get('bonusItem')) {
                        $price = $bonus->getBonusPrice($this, $price);
                    }
                }
            }
            if ($this->getComplex('config.Taxes.prices_include_tax') && !$this->getComplex('config.Taxes.discounts_after_taxes') && !$calcAllTaxesInside) {
                $p = new XLite_Model_Product($this->get('product_id'));
                $p->set('price', $price);
                $price = $p->getTaxedPrice();
            }
        }

        // discount coupon price for this item
        if ($this->isDiscountCouponApplies()) {
            if ($this->getComplex('order.DC.type') == "absolute") {
                $price = max(0, $price - $this->getComplex('order.DC.discount'));
            }
            if ($this->getComplex('order.DC.type') == "percent") {
                $price *= (100 - $this->getComplex('order.DC.discount')) / 100;
            }
        }

        if ($this->config->getComplex('Promotion.only_positive_price')) {
            if ($price < 0) {
            	$price = 0;
            }
        }

        return $this->formatCurrency($price);
    }

    function get($name)
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
        	$price = $this->get('price');
        }
        parent::set($property, $value);
    }

    function isBonusApplies()
    {
        if ($this->get('bonusItem')) {
            return true;
        }
        if ($this->isComplex('order._bonusPrices') && !is_null($this->get('product'))) {
            // take bonuses into account
            foreach ($this->getComplex('order.appliedBonuses') as $bonus) {
                if ($bonus->get('conditionType') != "eachNth" || $this->get('bonusItem')) {
                    return true;
                }
            }
        }
        return false;
    }

    function isPromotionItem()
    {
        return $this->get('price') != $this->get('parentPrice');
    }

    function isDiscountCouponApplies()
    {
        if (!is_null($this->getComplex('order.DC'))) {
            // discount coupon is set
            if ($this->getComplex('order.DC.applyTo') == "product") {
                if ($this->get('product_id') == $this->getComplex('order.DC.product_id')) {
                    return true;
                }
            }
            if ($this->getComplex('order.DC.applyTo') == "category" && !is_null($this->get('product'))) {
                if ($this->order->_inCategoryRecursive($this->get('product'), $this->getComplex('order.DC.category'))) {
                    return true;
                }
            }
        }
        return false;
    }

    function getOrderby()
    {
        if ($this->_createPeerItem) {
            return $this->get('orderby');
        } else {
            return parent::getOrderby();
        }
    }

    function formatCurrency($price)
    {
    	$isNewFC = $this->xlite->get('PromotionNewFC');
    	if (!isset($isNewFC)) {
            $classMethods = array_map('strtolower', get_class_methods(get_parent_class(get_class($this))));
            $isNewFC = in_array('formatcurrency', $classMethods);
            $this->xlite->set('PromotionNewFC', $isNewFC);
        }

        if ($isNewFC) {
            return parent::formatCurrency($price);
        } else {
        	return round($price, 2);
        }
    }

    function isOfferCondition(&$offer)
    {
        if ($offer->get('allProducts')) {
            return true;
        }

        $product = $this->get('product');
        if (is_object($product)) {
            if ($offer->get('product_id') != 0 && $product->get('product_id') == $offer->get('product_id')) {
                return true;
            }
            if ($offer->get('category_id') != 0) {
                $cat = new XLite_Model_Category($offer->get('category_id'));
                if ($product->inCategory($cat)) {
                    return true;
                }
            }
            if ($offer->get('bonusType') == "discounts") {
                $offer->getProducts();
                foreach ($this->bonusProducts as $bp) {
                    if ($bp->get('product_id') == $product->get('product_id')) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}

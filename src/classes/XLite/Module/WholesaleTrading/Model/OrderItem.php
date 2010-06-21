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
class XLite_Module_WholesaleTrading_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
    public $_itemChanged = false;

    public function __construct()
    {
        $this->fields['wholesale_price'] = 0;
        parent::__construct();
    }

    function _getStoredWholesalePrice()
    {
        return $this->xlite->get('useStoredWholesale');
    }

    function _needStoredWholesalePrice()
    {
        return !$this->xlite->get('dontStoreWholesale');
    }

    function _needSetWholesalePrice()
    {
        return $this->isPersistent && !$this->_itemChanged;
    }

    function _setWholesalePrice($price)
    {
        $this->set('wholesale_price', $price);
        if ($this->_needSetWholesalePrice()) {
            $this->update();
        }
    }

    function _getWholesalePrice($parentPrice = false)
    {
        if ($parentPrice) {
            return parent::get('price');
        }

        if ($this->_getStoredWholesalePrice()) {
        	$price = $this->get('wholesale_price');
        	if ($price >= 0) {
            	return $this->get('wholesale_price');
            }
        }
        
        // if not a product, return parent value
        if (!$this->getComplex('product.product_id')) {
            return parent::get('price');
        }
        $product = $this->get('product');
        if (!isset($this->wholesale_prices)) {
            $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
            $this->wholesale_prices = $wp->getProductPrices($product->get('product_id'), $this->get('amount'), "OR membership='" . $this->getComplex('order.profile.membership') . "'");
        }
        if (count($this->wholesale_prices) == 0) {
            $price = parent::get('price');
    		if (strval($this->formatCurrency($this->get('wholesale_price'))) != strval($this->formatCurrency($price)) && $this->_needStoredWholesalePrice()) {
    			$this->_setWholesalePrice($price);
    		}
            return $price;
        }

        $price = $this->wholesale_prices[count($this->wholesale_prices) - 1]->get('price');
        if ($this->config->Taxes->prices_include_tax) {
            $product->set('price', $price);
            if (!$this->_skipTaxingWholesalePrice) {
                $price = $product->get('listPrice');
            }
        }
        if (strval($this->formatCurrency($this->get('wholesale_price'))) != strval($this->formatCurrency($price)) && $this->_needStoredWholesalePrice()) {
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

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
class XLite_Module_AOM_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    public $orderStatus = null;
    public $_productItems = null;
    public $_substatusChanged = false;
    public $_oldSubstatus = '';
    
    public $_save_totals = array();
    public $_save_fields = array('shipping_cost', "tax", "taxes", "total");

    public $doNotCheckInventory = false;
    public $doNotChangeShippingCost = false;
    public $doNotChangeGlobalDiscount = false;

    public function __construct($id = null) 
    {
        $this->fields['substatus'] = '';
        $this->fields['admin_notes'] = '';
        $this->fields['manual_edit'] = 0;
        parent::__construct($id);
    }
    
    function statusChanged($oldStatus, $newStatus) 
    {
        if ($this->xlite->is('adminZone')) {
            return;
        }

        parent::statusChanged($oldStatus, $newStatus);
     }

    function internalStatusChanged($oldStatus, $newStatus) 
    {
        if ($this->xlite->is('adminZone') && $oldStatus != $newStatus && strlen($newStatus . $oldStatus) == 2) {
        
        if ($oldStatus != 'P' && $oldStatus != 'C' && $oldStatus != 'Q' &&
                ($newStatus =='P' || $newStatus == 'C' || $newStatus == 'Q')) {
            $this->modulesCheckedOut();
        }
        if ($oldStatus == 'I' && $newStatus == 'Q') {
            $this->modulesQueued();
        }
        if ($oldStatus != 'P' && $oldStatus != 'C' &&
                ($newStatus =='P' || $newStatus == 'C')) {
            $this->modulesProcessed();
        }
        if (($oldStatus == 'P' || $oldStatus == 'C') &&
            $newStatus !='P' && $newStatus != 'C') {
            $this->modulesDeclined();
        }
        if (($oldStatus == 'P' || $oldStatus == 'C' || $oldStatus == 'Q') &&
            $newStatus !='P' && $newStatus != 'C' && $newStatus != 'Q') {
            $this->modulesUncheckedOut();
        }
        if ($oldStatus != 'F' && $oldStatus != 'D' &&
            ($newStatus == 'F' || $newStatus == 'D')) {
            $this->modulesFailed();
        }
    }
}

    function modulesFailed()
    {
        
    }

    function getLocationCountry() 
    {
        $country = new XLite_Model_Country();
        $country->find("code = '".$this->config->getComplex('Company.location_country')."'");
        return $country;
    }

    function getLocationState() 
    {
        $state = new XLite_Model_State($this->config->getComplex('Company.location_state'));
        return $state;
    }
    
    function modulesUncheckedOut()
    {
        if ($this->xlite->get('PromotionEnabled')) {
            $this->promotionStatusChanged(1);
        }

        if ($this->xlite->get('InventoryTrackingEnabled')) {
            if ($this->getComplex('config.InventoryTracking.track_placed_order')) {
    	        $this->changeInventory(false);
            }
        }

        if ($this->xlite->get('EgoodsEnabled')) {
            $this->Egoods_uncheckedOut();
        }

        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $this->changeGCDebit(1);
            $this->setGCStatus('D');
        }
    }

    function modulesCheckedOut() 
    {
        if ($this->xlite->get('PromotionEnabled')) {
            $this->promotionStatusChanged(-1);
        }

        if ($this->xlite->get('InventoryTrackingEnabled')) {
            if ($this->getComplex('config.InventoryTracking.track_placed_order')) {
                $this->changeInventory(true);
            }
        }

        if ($this->xlite->get('EgoodsEnabled')) {
            $this->Egoods_checkedOut();
        }

        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $this->changeGCDebit(-1);
        }

        if ($this->xlite->get('ProductAdviserEnabled')) {
            if (method_exists($this,"productadviser_checkedout")) {
                $this->ProductAdviser_checkedOut();
            }
        }
    }

    function modulesQueued()
    {
        if ($this->xlite->get('AffiliateEnabled')) {
            $this->chargePartnerCommissions();
        }
        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $this->setGCStatus('P');
        }
    }
    
    function modulesProcessed()
    {
        if ($this->xlite->get('PromotionEnabled')) {
            if ($this->config->getComplex('Promotion.earnBonusPointsRate')) {
                $this->addBonusPoints((int)($this->get('subtotal') * $this->config->getComplex('Promotion.earnBonusPointsRate')));
    	    }
        	$this->addBonusPointsSpecialOffer(1);
        }

        if ($this->xlite->get('EgoodsEnabled')) {
            $this->Egoods_processed();
        }

        if ($this->xlite->get('InventoryTrackingEnabled')) {
            if (!$this->getComplex('config.InventoryTracking.track_placed_order')) {
                $this->changeInventory(true);
            }
        }
        if ($this->xlite->get('AffiliateEnabled')) {
            if (method_exists($this,"affiliate_processed")) {
            	$this->Affiliate_processed();
            }
        }
        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $this->setGCStatus('A');
        }
        if ($this->xlite->get('WholesaleTradingEnabled')) {
            if (method_exists($this,"wholesaletrading_processed")) {
                $this->WholesaleTrading_processed();
            }
        }

    }

    function modulesDeclined()
    {
        if ($this->xlite->get('PromotionEnabled')) {
            if ($this->config->getComplex('Promotion.earnBonusPointsRate')) {
                $this->addBonusPoints(-(int)($this->get('subtotal') * $this->config->getComplex('Promotion.earnBonusPointsRate')));
            }
            $this->addBonusPointsSpecialOffer(-1);
        }

        if ($this->xlite->get('InventoryTrackingEnabled')) {
            if (!$this->getComplex('config.InventoryTracking.track_placed_order')) {
    	        $this->changeInventory(false);
        	}
        }

        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $this->setGCStatus('P');
        }

        if ($this->xlite->get('EgoodsEnabled')) {
        	$this->Egoods_declined();
        }
    }

    function _beforeSave()
    {
        if ($this->xlite->is('adminZone')) {
            if ($this->_statusChanged || $this->_substatusChanged) {
                $this->orderStatusChanged();
                $this->_statusChanged = false;
                $this->_substatusChanged = false;
            }
        }
        parent::_beforeSave();
    }
    
    function set($name, $value) {
        if ($name == "substatus") {
            $oldSubstatus = $this->get($name);
            parent::set($name, $value);
            if (!$this->_substatusChanged && $value != $oldSubstatus) {
                $this->_substatusChanged = true;
                $this->_oldSubstatus = $oldSubstatus;
                if (!$this->_statusChanged) {
                    $this->_statusChanged = true;
                    $this->_oldStatus = $this->get('status');
                }
            }
        } else {
            parent::set($name, $value);
        }
    }

    function orderStatusChanged()
    {
        $orderStatus = $this->get('orderStatus');
        $status = $orderStatus->get('status');
        $oldStatus = ($this->_oldSubstatus == '') ? $this->_oldStatus : $this->_oldSubstatus;
        if ($this->xlite->config->getComplex('AOM.status_inheritance')) {
            if ($orderStatus->get('parent') !== '') {
                $status = $orderStatus->get('parent');
            }
            $substatus = new XLite_Module_AOM_Model_OrderStatus();
            if ($substatus->find("status = '".$this->_oldSubstatus."'") && $substatus->get('parent') !== '') {
                $oldStatus = $substatus->get('parent');
            } else {
                $oldStatus = ($this->_oldSubstatus == '') ? $this->_oldStatus : $this->_oldSubstatus;
            }
        }
        $this->internalStatusChanged($oldStatus, $status);

        if (!$this->_disable_all_notifications) {
            $mail = new XLite_Model_Mailer();
            $mail->order = $this;
         
            if ($orderStatus->get('email')) {
                $mail->set('adminMail', true);
                $mail->compose(
    		            $this->config->getComplex('Company.site_administrator'),
        		        $this->config->getComplex('Company.orders_department'),
            		    "modules/AOM/status_changed_admin");
                $mail->send();
            }
            if ($orderStatus->get('cust_email')) {
                // Switch layout to castomer area
                $layout = XLite_Model_Layout::getInstance();
                $active_skin = $layout->get('skin');
                $layout->set('skin', XLite::getInstance()->getOptions(array('skin_details', 'skin')));
                $mail->set('adminMail', false);
    		    $mail->compose(
        		        $this->config->getComplex('Company.orders_department'),
           			    $this->getComplex('profile.login'),
                		"modules/AOM/status_changed");
                $mail->send();

                // Restore layout
                $layout->set('skin', $active_skin);
            }
        }
    }
    
    function getOrderHistory()  
    {
        $orderHistory = new XLite_Module_AOM_Model_OrderHistory();
        return array_reverse($orderHistory->findAll("order_id = " . $this->get('order_id'),"date"));
    }
    
    function setDetails($value)
    {
    	if (!is_array($value)) {
    		$value = unserialize($value);
    	}
    	if (!is_array($value)) {
    		$value = array();
    	}
        parent::setDetails($value);
    }

    function __clone()
    {
        $clone = parent::__clone();

        require_once LC_MODULES_DIR . 'AOM' . LC_DS . 'encoded.php';
        return aom_order_clone($this, $clone);
    } //  }}}

    function getProductItems()  
    {
        $orderItem = new XLite_Model_OrderItem();
        $this->_productItems = $orderItem->findAll("order_id='" .$this->get('order_id'). "' AND product_id <> 0");
        foreach ($this->_productItems as $key => $item) {
            $this->_productItems[$key]->order = $this;
        }
        return $this->_productItems;
    } //   }}}

    function getProductItemsCount() 
    {
        return count($this->get('productItems'));
    }
    
    function isSplit() 
    {
        return $this->get('productItemsCount') > 1;
    }
    
 	function getItems() 
    {
        if ($this->xlite->is('adminZone')) {
            $checkTaxesInside = $this->xlite->get('AOMcalcAllTaxesInside');
            if (!isset($checkTaxesInside)) {
    			if (!$this->config->getComplex('Taxes.prices_include_tax')) {
    				$this->xlite->set('AOMcalcAllTaxesInside', true);
    			} else {
                	$taxRates = new XLite_Model_TaxRates();
                	$profile = $this->get('profile');
                	if (isset($profile) && is_object($profile)) {
                    	$taxRates->setProfile($this->get('profile'));
                    	if (strpos($taxRates->_conditionValues['country'], ",EU country") === false) {
        					$this->xlite->set('AOMcalcAllTaxesInside', true);
                    	} else {
        					$this->xlite->set('AOMcalcAllTaxesInside', false);
                    	}
                    }
    			}
            }
        }

        parent::getItems();

        if (!$this->xlite->get('GiftCertificatesEnabled')) {
            for ($key=0; $key < count($this->_items); $key++) {
                if (!$this->_items[$key]->get('product_id')) {
                    unset($this->_items[$key]);
                }
            }
        }

        return $this->_items;
    }

    function getAppliedGC() 
    {
        $gcid = $this->get('gcid');
        if (!empty($gcid)) {
            $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($gcid);
        }
        return $gc;
    }
    
 	function getOrderGC()  
    {
        $items = parent::getItems();
        foreach ($items as $key => $item) {
            $gcid = $item->get('gcid');
            if (empty($gcid)) 
                unset($items[$key]);
            else 
                $gc[$item->get('item_id')] = new XLite_Module_GiftCertificates_Model_GiftCertificate($gcid);
        }
        return is_array($gc) ? $gc : false;
    }

    function getGCCopy()
    {
        return method_exists($this, 'getGC') ? $this->getGC() : null;
    }

    function getOrderDC() 
    {
        if (is_null($this->DC) && $this->get('order_id')) {
            $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
            $dc->_range = "";
            if ($dc->find("order_id=".$this->get('order_id'))) {
                $this->DC = $dc;
            }
        }
        return $this->DC;
        
    }
    
    function _getDiscountableSubtotal()
    {
        $subtotal = 0;
        foreach ($this->get('items') as $item) {
            $subtotal += $item->get('total');
        }
        return $subtotal;
    }

    function _calcSubTotal()  
    {
        $subtotal = $this->_getDiscountableSubtotal();
        $global_discount = $this->_getAppliedGlobalDiscount();

        $subtotal -= $global_discount;
        if ($subtotal < 0) $subtotal = 0;

        $this->set('subtotal', $this->formatCurrency($subtotal));
        return $subtotal;
    }

    function _getAppliedGlobalDiscount()
    {
        $global_discount = $this->get('global_discount');
        if (!$this->xlite->get('WholesaleTradingEnabled')) return 0;
        if ($global_discount <= 0) return 0;
        $applied_global_discount = 0;

        $gd = $this->get('appliedGlobalDiscount');
        if (!is_object($gd)) $gd = new XLite_Module_WholesaleTrading_Model_GlobalDiscount();

        if ($gd->get('discount_type') != "a") {
            // if percent (or undefined) discount value differs from its original value, then the discount is absolute
            $subtotal = $this->_getDiscountableSubtotal();
            $orig_discount = $this->formatCurrency($subtotal * $gd->get('discount') / 100);
            if ($orig_discount != $global_discount) {
                $gd->set('discount_type', "a");
            }
        }

        if ($this->config->getComplex('Taxes.prices_include_tax')) {
            $taxed_global_discount = $this->get('taxedGlobalDiscount');
            $applied_global_discount = ($taxed_global_discount > 0)?$taxed_global_discount:$global_discount;
        } else {
            $applied_global_discount = $global_discount;
        }
        return $applied_global_discount;
    }

    function _calcTotal()
    {
        $this->_saveTotals();
        $this->_calcSubTotal();
        $discount = $this->_getAppliedDiscount();
        $this->calcTax();

        $total = $this->get('subtotal') + $this->get('shipping_cost') - $discount - $this->get('payedByGC');
        if ( !$this->config->getComplex('Taxes.prices_include_tax') ) {
            $total += $this->get('tax');
        }
        if ( $this->config->getComplex('Taxes.prices_include_tax') ) {
            $total += $this->get('shippingTax');
        }

        if ($this->xlite->get('PromotionEnabled')) {
            $total = max(0,$total - $this->get('payedByPoints'));
        }
        $this->set('total', $this->formatCurrency($total));
        $this->_restoreTotals();
    }

    function _getAppliedDiscount()
    {
        $applied_discount = $this->get('discount');
        if (!$this->xlite->get('PromotionEnabled')) return 0;
        if ($applied_discount <= 0) return 0;

        $dc = $this->get('orderDC');
        if (!is_object($dc)) $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
        if ($dc->get('type') != "absolute") {
            // if percent (or undefined) discount value differs from its original value, then the discount is absolute
            if ($this->getComplex('config.Taxes.prices_include_tax') && $this->xlite->AOM_product_originalPrice) {
                // calculate taxed subtotal manually:
                global $calcAllTaxesInside;
                $calcAllTaxesInside_orig = $calcAllTaxesInside;
                $calcAllTaxesInside = true;
                $subtotal = 0;
                foreach ($this->get('items') as $i) {
                    $price = $i->get('price');
                    $p = new XLite_Model_Product($i->get('product_id'));
                    $p->set('price', $price);
                    $price = $p->getTaxedPrice();
                    $subtotal += $price * $i->get('amount');
                }
                $calcAllTaxesInside = $calcAllTaxesInside_orig;
            } else {
                $subtotal = $this->_getDiscountableSubtotal();
            }
            $orig_discount = $this->formatCurrency($subtotal * $dc->get('discount') / 100);
            if ($orig_discount != $applied_discount) {
                $dc->set('type', "absolute");
            }
        }

        if ($this->config->getComplex('Taxes.prices_include_tax')) {
            $taxed_discount = $this->get('taxedDiscount');
            $applied_discount = ($taxed_discount > 0)?$taxed_discount:$applied_discount;
        }
        $applied_discount = min($this->get('subtotal'), $applied_discount);
        return $applied_discount;
    }

    function calcTotal()
    {
        if ($this->xlite->get('AOM_skip_calcTotal')) {
            return;
        }

        $this->_saveTotals();

    	$this->xlite->set('preserveOriginalPrices', (bool) $this->xlite->is('adminZone'));
        parent::calcTotal();
    	$this->xlite->set('preserveOriginalPrices', false);

        $this->_restoreTotals();
    }

    function _saveTotals()
    {
        if ( $this->xlite->is('adminZone') && $this->get('manual_edit') ) {
            $this->_save_totals[] = array();
            foreach ($this->_save_fields as $v)
                $this->_save_totals[$v] = $this->get($v);
        }
    }

    function _restoreTotals()
    {
        if ( $this->xlite->is('adminZone') && $this->get('manual_edit') ) {
            foreach ($this->_save_fields as $v)
                $this->setComplex($v, $this->_save_totals[$v]);
        }
    }

    function setOrderStatus($value)  
    {
        $substatus = new XLite_Module_AOM_Model_OrderStatus();
        $substatus->find("status = '$value'");
        if ($substatus->get('parent') == '') {
            $_POST['status'] = $value;
            $this->set('status',$value);
            if (!$this->xlite->config->getComplex('AOM.status_inheritance')) {
                $_POST['substatus'] = "";
                $this->set('substatus',"");
            }
        } else {
            $this->set('status', $substatus->get('parent'));
            $this->set('substatus', $substatus->get('status'));
            $_POST['status'] = $substatus->get('parent');
            $_POST['substatus'] = $substatus->get('status');
        }

    }
    
    function getOrderStatus()  
    {
        $status = ($this->get('substatus') == '') ? $this->get('status') : $this->get('substatus');
        $this->orderStatus = new XLite_Module_AOM_Model_OrderStatus();
        $this->orderStatus->find("status = '$status'");
       	return $this->orderStatus;
 	}

    public function search(
        $profile = null,
        $id = null,
        $status = null,
        $startDate = null,
        $endDate = null,
        $start_total = null,
        $end_total = null,
        $shipping_id = null,
        $payment_method = null
    ) {
        $where = array();
        if (isset($profile)) {
            $where[] = "orig_profile_id='" .$profile->get('profile_id')."'";
        }
        if (!empty($id1)) {
            $where[] = "order_id>=".(int)$id1;
        }
        if (!empty($id2)) {
            $where[] = "order_id<=".(int)$id2;
        }
        if (!empty($start_total)) {
            $where[] = "total >= ".(int)$start_total;
        }
        if (!empty($end_total)) {
            $where[] = "total <= ".(int)$end_total;
        }
        if ($shipping_id > -1 ) {
            $where[] = "shipping_id =".(int)$shipping_id;
        }
        if (!empty($payment_method)) {
            $where[] = "payment_method ='".$payment_method."'";
        }
        
        if (!empty($status)) {
            $orderStatus = new XLite_Module_AOM_Model_OrderStatus();
            $orderStatus->find("status = '$status'");
            if ($orderStatus->get('parent')) {
                $where[] = "substatus = '$status'";
            } else {
             	$where[] = "status = '$status'";
             	$where[] = "(substatus = '$status' OR substatus='')";
            }
        }
        if ($startDate) {
            $where[] = "date>=$startDate";
        }
        if ($endDate) {
            $where[] = "date<=$endDate";
        }
        return $this->findAll(implode(' AND ', $where), "date DESC");
    }

    function isShipped()  
    {
        if ($this->xlite->is('adminZone')&&!$this->get('shipping_id')) {
            return false;
        }
        return parent::isShipped();
    }

    function calcAllItemsTaxedPrice()
    {
        $this->_items = null;
        if ( !$this->config->getComplex('Taxes.prices_include_tax') )
            return;
        $taxRates = new XLite_Model_TaxRates();
        $taxRates->set('order', $this);
        foreach ($this->get('items') as $item) {
            $calcAllTaxesAOM = $this->xlite->get('AOMcalcAllTaxesInside');
            $calcAllTaxesInside = (isset($calcAllTaxesAOM)) ? $calcAllTaxesAOM : true;
            if ($this->xlite->get('ProductOptionsEnabled') && !$calcAllTaxesInside) {
            	$options = $item->properties['options'];
    			$item->properties['options'] = null;
    		}
            $price = $item->get('originalPrice');
            $item->set('price', $price);
            $taxRates->set('orderItem', $item);
            // don't include any price modifications into the price
            $taxRates->_conditionValues['cost'] = $price;
            $taxRates->calculateTaxes();

            $result = array();
            $result = $this->_addTaxes($result, $taxRates->get('allTaxes'));

            $tax = (isset($result['Tax']) ? $result['Tax'] : 0);
            if ( $item->get('amount') > 0 )
                $tax = $tax / $item->get('amount');

            $taxedPrice = $price + $this->formatCurrency($tax);
            $item->set('price', $taxedPrice);
            if ($this->xlite->get('ProductOptionsEnabled') && !$calcAllTaxesInside) {
                $item->properties['options'] = $options;
            }
            $item->update();
        }
        $this->_items = null;
    }

    function get($name) 
    {
        switch($name) {
            case "detail_labels":
                return $this->getDetailLabels();
            break;
            default:
                return parent::get($name);
        }
    }

    function updateInventory($item)
    {
        if (!$this->xlite->get('InventoryTrackingEnabled')) return;
        if ($this->doNotCheckInventory) return;
        parent::updateInventory($item);
    }

    function calcShippingCost()
    {
        if ($this->doNotChangeShippingCost) return;
        parent::calcShippingCost();
    }

    function calcGlobalDiscount($subtotal)
    {
        $this->_applied_global_discount = null;
        if (!method_exists($this, "calcGlobalDiscount")) return $this->get('global_discount');
        if ($this->doNotChangeGlobalDiscount) {
            $global_discount = $this->get('global_discount');
            $this->_getAppliedGlobalDiscount(); // required to replace percent GD with absolute type in the object for ignored GD.
            return $global_discount;
        }
        $global_discount = parent::calcGlobalDiscount($subtotal);
        return $global_discount;
    }
}

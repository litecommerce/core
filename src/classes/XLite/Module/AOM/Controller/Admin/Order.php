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

namespace XLite\Module\AOM\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Controller\Admin\Order implements \XLite\Base\IDecorator
{
    // settings 	  
    public $page = "order_info";
    public $totals_row_counter = 0;
    public $profile_row_counter = 0;
    public $pages = array 
    (
        "order_info" 	=> "Order #%s Info",
        "order_history" => "Order History",
        "order_edit"	=> "Edit Order"
    );
            
    public $pageTemplates = array 
    (
        "order_info"	=> "modules/AOM/order.tpl",
        "order_history"	=> "modules/AOM/order_history.tpl",
        "order_edit"	=> "modules/AOM/order_edit.tpl",		
        "order_preview" => "modules/AOM/order_edit/preview.tpl"
    );
            
    public $clone_order 	= null;
    public $profile		= null;
    public $clone_profile 	= null;
    public $products	 	= null;
    public $productsFound 	= null;

 	   

    function init()  
    {
        $this->params[] = "page";
        $this->params[] = "mode";
        $this->params[] = "substring";
        $this->params[] = "search_productsku";
        $this->params[] = "subcategory_search";
        $this->params[] = "search_category";
        $this->params[] = "pageID";

        parent::init();

        // initialize cart instance
        $this->aom_cart_instance();

        // Set cart instance for correct tax calculation
        // on order info & preview pages
        if ($this->get('order') && $this->get('action') == "" && in_array($this->get('page'), array('order_info', "order_preview"))) {
            $this->_cart = $this->get('order');
        }

        $ordersUpdated = $this->session->get('ordersUpdated') ? $this->session->get('ordersUpdated') : array();

        if ($this->get('page') == "order_preview" && !isset($ordersUpdated[$this->get('order_id')])) {
            $this->redirect("admin.php?target=order&order_id=".$this->get('order_id')."&page=order_info");
        }
        if (isset($ordersUpdated[$this->get('order_id')])) {
            $order = $this->get('cloneOrder');
            if (!$order->isEmpty()) {
            	$this->pages['order_preview'] = "Review and Save Order";
        		if ($this->xlite->getComplex('mm.activeModules.AdvancedSecurity') && is_null($this->session->get('masterPassword')) && $order->getComplex('paymentMethod.payment_method') == "CreditCard") {
        		    $this->set('unsecureCC', true);
        		}
            } else {
                if ($this->page == "order_preview") {
                    $this->redirect("admin.php?target=order&order_id=".$this->get('order_id')."&page=order_edit");
                }
            }
        }

        foreach ($this->pages as $key => $page) 
            $this->pages[$key] = sprintf($page,$this->order_id);
        
 	}

    function getTemplate() 
    {
        if ($this->mode == "show" || $this->mode == "search") return "modules/AOM/add_products.tpl";
        if ($this->mode == "show_users" || $this->mode == "search_users") return "modules/AOM/add_users.tpl";
        return parent::getTemplate();
    } //}}}
    
    function getCloneOrder()  
    {
        require_once LC_MODULES_DIR . 'AOM' . LC_DS . 'encoded.php';
        return aom_get_clone_order($this);
    }

    function getProfile() 
    {
        require_once LC_MODULES_DIR . 'AOM' . LC_DS . 'encoded.php';
        return aom_get_profile($this);
    }
    
    function getCloneProfile()  
    {
        $order = $this->get('cloneOrder');
        $this->clone_profile = $order->get('profile');
        if (is_null($this->clone_profile)) {
                $this->clone_profile = new \XLite\Model\Profile();
                $this->clone_profile->set('order_id',$order->get('order_id'));
                $this->clone_profile->create();
                $order->set('profile_id',$this->clone_profile->get('profile_id'));
                $order->update();
        }
        return $this->clone_profile;

    }
    
    function getOrdersItems()  
    {
        $items = array();
    
        // Set cart instance for correct tax calculation
        $this->_cart = $this->get('order');

        $orderItems = $this->getComplex('order.productItems');
        $cloneItems = $this->getComplex('cloneOrder.productItems');
        foreach ($orderItems as $item) {
            $items[$item->get('uniqueKey')]['orderItem'] = $item;
            $items[$item->get('uniqueKey')]['cloneItem'] = null;
            }
        foreach ($cloneItems as $item) {
            if (!isset($items[$item->get('uniqueKey')]['orderItem'])) 
                $items[$item->get('uniqueKey')]['orderItem'] = null;
            $items[$item->get('uniqueKey')]['cloneItem'] = $item;
        }
        return $items;
    }

    function getOrdersTaxes() 
    {
        $order = $this->get('order');
        $clone = $this->get('cloneOrder');
        
        foreach ($order->getDisplayTaxes() as $name => $tax) {
            $ordersTaxes[$name]['order'] = $tax;
            $ordersTaxes[$name]['clone'] = null;
        }
        foreach ($clone->getDisplayTaxes() as $name => $tax) {
            if (!isset($ordersTaxes[$name]['order']))
                $ordersTaxes[$name]['order'] = null;
            $ordersTaxes[$name]['clone'] = $tax;
        }
        return $ordersTaxes;
    }
    
    function getOriginalValuesFields()
    {
        return array('global_discount', "discount", "payedByGC", "shipping_cost");
    }

    function saveOriginalValues($order) 
    {
        $originalValues = $order->getComplex('details.originalValues');
        if (is_null($originalValues)) {
            $fields = $this->get('originalValuesFields');
            foreach ($fields as $field)
                $originalValues[$field] = $order->get($field);
        }
        $order->setComplex('details.originalValues', $originalValues);
    }

    function saveCurrentValues($order, $calculate = true) 
    {
        // Set cart instance for correct tax calculation
        $this->_cart = $order;

        $fields = $this->get('originalValuesFields');
        $originalValues = $order->getComplex('details.originalValues');
        if ($calculate) {
            $order->calcAllItemsTaxedPrice();
            $order->calcTotal();
            $properties = $order->get('properties');
            $currentValues = $order->getComplex('details.currentValues');
            foreach ($fields as $field) {
                if ($properties[$field] != $originalValues[$field]) $originalValues[$field] = $properties[$field];
            }
            $order->setComplex('details.originalValues', $originalValues);
        } else {
            // Return "originalPrice" for product, called like: $item->getComplex('product.price');
            // Prevent tax calculation problem
            $_temp = $this->xlite->AOM_product_originalPrice;
            $this->xlite->AOM_product_originalPrice = true;

            $order->_calcTotal();

            // restore value
            $this->xlite->AOM_product_originalPrice = $_temp;

            foreach ($fields as $field) 
                $currentValues[$field] = $order->get($field);
            $order->setComplex('details.currentValues', $currentValues);
        }
        $order->update();
    }
    
// actions 

    function action_send()
    {
    	$mail = new \XLite\Model\Mailer();
        $order = $this->get('order');
        $mail->order = $order;
        $mail->compose(
        		$this->config->Company->site_administrator,
                $this->config->Company->orders_department,
                "modules/AOM/status_changed_admin");
        $mail->send();

        // Switch layout to castomer area
        $layout = \XLite\Model\Layout::getInstance();
        $active_skin = $layout->get('skin');
        $layout->set('skin', \XLite::getInstance()->getOptions(array('skin_details', 'skin')));

        $mail->compose(
                $this->config->Company->orders_department,
                $order->getComplex('profile.login'),
                "modules/AOM/status_changed");
        $mail->send();

        // Restore layout
        $layout->set('skin', $active_skin);
    }
    
    function action_calculate_totals()  
    {
        $order = $this->get('cloneOrder');
        if ($this->xlite->get('PromotionEnabled')) {
            $order->get('orderDC');
        }
        if ($this->clone['shipping_id'] == 0 && !isset($this->clone['shipping_cost'])) {
            $this->clone['shipping_cost'] = 0;
        }
        $order->set('properties', $this->clone);
        $this->saveCurrentValues($order);
    }
        
    function action_update_totals() 
    {
        $order = $this->get('cloneOrder');
        $this->saveOriginalValues($order);
        if ($this->clone['shipping_id'] == 0 && !isset($this->clone['shipping_cost'])) {
            $this->clone['shipping_cost'] = 0;
        }

        $edit_mode_changed = false;
        if ( $order->get('manual_edit') == 1 && $this->clone['manual_edit'] != 1 ) {
            $edit_mode_changed = true;
        }
        $this->clone['manual_edit'] = ( $this->clone['manual_edit'] == 1 ) ? 1 : 0;

        $order->set('properties', $this->clone);

        // Update taxes
        if ( $this->clone['manual_edit'] == 1 ) {
            $taxes = unserialize($order->get('taxes'));
            if ( is_array($taxes) && is_array($this->taxes) ) {
                foreach ($this->taxes as $k=>$v) {
                    $taxes[$k] = $v;
                }

                $taxes = serialize($taxes);
                $order->set('taxes', $taxes);
            }
        }

        // Recalc when switch manual -> auto mode
        if ( $edit_mode_changed ) {
            $order->calcAllItemsTaxedPrice();
            $order->calcTotal();
        }

        $this->updateOrderAsCart($order);
        $this->saveCurrentValues($order,false);
        $this->cloneUpdated(false);
    } //  }}}
    
    function action_update_profile()  
    {
        $profile = $this->get('cloneProfile');
        $profile->_AOMIgnoreMembershipChanged = true;
        $profile->set('properties',$this->cloned_profile);
        $profile->update();
        $profile->_AOMIgnoreMembershipChanged = false;

        $order = $this->get('cloneOrder');
        $order->calcTotal();
        $this->saveCurrentValues($order);
        $this->cloneUpdated(false);
    }

    function getUserProfileFields() 
    {
        $field_values = array ("login", "billing_title", "billing_firstname", "billing_lastname", "billing_company", "billing_phone", "billing_fax", "billing_address", "billing_city", "billing_state", "billing_country", "billing_zipcode", "shipping_firstname", "shipping_lastname", "shipping_company", "shipping_phone", "shipping_fax",  "shipping_address", "shipping_city", "shipping_state", "shipping_country", "shipping_zipcode", "shipping_title", "membership", "tax_id", "vat_number", "gst_number", "pst_number");
        return $field_values;
    }

    function action_fill_user() 
    {
        if ($this->get('profile_id')) {
            $selectedProfile = new \XLite\Model\Profile($this->get('profile_id'));
            $properties = $selectedProfile->get('properties');
            $cloneProfile = $this->get('cloneProfile');
            $field_values = $this->getUserProfileFields();
            $cloneProfile->_AOMIgnoreMembershipChanged = true;
            foreach ($field_values as $value) {
                $cloneProfile->setComplex($value, $properties[$value]);
            }
            $cloneProfile->update();
            $cloneProfile->_AOMIgnoreMembershipChanged = false;

    		$order = $this->get('cloneOrder');
            $order->set('orig_profile_id', $selectedProfile->get('profile_id'));
            $order->calcTotal();
            $this->saveCurrentValues($order);
            $this->cloneUpdated(false);
        }
        $this->set('returnUrl',"admin.php?target=".$this->get('target')."&mode=show_users&order_id=".$this->getComplex('order.order_id')."&reloaded=1");
    }
    
    function action_update_products()  
    {
        // Set cart instance for correct tax calculation
        $this->_cart = $this->get('order');

        $order = $this->get('cloneOrder');
        $items = $order->get('items');
        foreach ($order->get('items') as $item)
            $order->deleteItem($item);
        if ($this->get('clone_products')) {
            foreach ($this->get('clone_products') as $key => $product) {
                foreach ($items as $item) {
                    if ($item->get('uniqueKey') == stripslashes($key)) {
                        if (!empty($product['product_options'])) {
                            $item->set('productOptions', $product['product_options']);
                        }
                        $pitem = $item->get('product');
                        if ($this->config->Taxes->prices_include_tax) {
                            $prod = new \XLite\Model\Product();
                            $prod->set('price', $product['price']);
                            $item->set('price', $prod->get('listPrice'));
                        } else {
                            $item->set('price',$product['price']);
                        }
                        $item->set('originalPrice', $product['price']);
                        $item->set('amount',$product['amount']);
                        if ( $pitem->is('available') ) {
                            $order->addItem($item);
                        } else {
                            $order->_createItem($item);
                        }
                    }
                }
            }
        }
        $this->updateOrderAsCart($order);
        $this->saveCurrentValues($order);
        $this->cloneUpdated(false);
 	}
    
    /*
     * Transfere current order data to a Cart object and recalculate it.
     * Warning: before calling this function make sure, that the order is no more used in current PHP session,
     *          because the values are not pushed back to the Order object.
     */
    function updateOrderAsCart($order)
    {
        $orig_properties = $order->get('properties');
        $orig_details = $order->get('details');
        $profile = $order->get('profile');
        
        // the following lines are required for proper tax calculation:
        // profile-dependent taxes should apply to order's owner, but not to current admin profile
        $this->aom_cart_instance();
        $this->_cart->setProfile($profile);

        $cart = new \XLite\Model\Cart();
        $cart->set('order_id', $order->get('order_id'));
        $cart->setProfile(null);
        $cart->setProfile($profile);

        foreach ($order->get('items') as $k=>$item) {
            $cart->_items[$k] = $item;
            $cart->_items[$k]->order = $cart;
        }

        $cart->set('properties', $orig_properties); // prevent data loss during updating the cart
        $cart->set('details', $orig_details); // prevent CC data loss

        if ($this->xlite->get('PromotionEnabled')) {
            $cart->_appliedBonuses = null; // clear applied bonuses cache before cart recalculation
            $cart->doNotChangeGlobalDiscount = true; // don't recalculate global discount
            $cart->doNotChangeShippingCost = true; // don't recalculate shipping cost
            $cart->doNotCheckInventory = true; // ignore inventory status updating
            $cart->cartChanged(); // call cartChanged to apply appropriate special offers before checking discount coupons
            $cart->doNotCheckInventory = false;
            $cart->doNotChangeShippingCost = false;
            $cart->doNotChangeGlobalDiscount = false;
            if ($cart->_getAppliedDiscount() != $orig_properties['discount']) {
                // prepare discount coupon for proper discount calculation
                $cart->DC = new \XLite\Module\Promotion\Model\DiscountCoupon();
                $cart->DC->set('applyTo', "total");
                $cart->DC->set('minamount', 0.00);
                $cart->DC->set('type', "absolute");
                $cart->DC->set('discount', $orig_properties['discount']);
            }
        }
        
        $cart->doNotChangeGlobalDiscount = true; // don't recalculate global discount
        $cart->doNotChangeShippingCost = true; // don't recalculate shipping cost
        $cart->doNotCheckInventory = true; // ignore inventory status updating
        $cart->doNotCallCartChanged = true; // cartChanged is not necessary as it was called in above section
        $cart->calcTotals();
        $cart->update();
        $cart->doNotCheckInventory = false;
        $cart->doNotChangeShippingCost = false;
        $cart->doNotChangeGlobalDiscount = false;

        // restore date for current order
        $order->_items = null; // clean up items cache in order not to use cached values during further calculations
        $order->properties = array(); // reset properties, as the read() function sets only absent fields
        $order->set('order_id', $orig_properties['order_id']);
        $order->isRead = false;
        $order->getProperties(); // in order to read properties from database

        $order->set('date', $orig_properties['date']);
        $order->set('discount', $orig_properties['discount']);
        $order->update();
    }

 	function action_delete_products()  
    {
        if (!is_null($this->get('delete_items'))) {
            $order = $this->get('cloneOrder');
            foreach ($order->get('items') as $item) 
                foreach ($this->get('delete_items') as $item_id) 
                    if ($item->get('uniqueKey') == $item_id) {
                        $order->deleteItem($item);
                    }
            $this->updateOrderAsCart($order);
            $this->saveCurrentValues($order);
            $this->cloneUpdated(false);
        }
    }
    
    function action_add_products()  
    {
        $this->cloneUpdated(false);
 	    $order = $this->get('cloneOrder');
        if (!is_null($this->get('add_products'))) {
            foreach ($this->get('add_products') as $product_id) {
                $product = new \XLite\Model\Product($product_id);
                $item = new \XLite\Model\OrderItem();
           		$item->set('product',$product);
                $item->set('amount',1);
                if ($this->xlite->get('ProductOptionsEnabled')) {
                    $options = $product->get('productOptions');
                    if ($options) {
                        foreach ($options as $option) {
                            $_options = $option->get('productOptions');
                            $default_options[] = $_options[0];
                        }
                        $item->set('options',serialize($default_options));
                    } else 
                        $item->set('options',null);
                }
                $order->addItem($item);
            }
            $this->updateOrderAsCart($order);
            $this->saveCurrentValues($order);
        }
        $this->set('returnUrl',"admin.php?target=".$this->get('target')."&mode=search&order_id=".$this->getComplex('order.order_id')."&reloaded=1&substring=$this->substring&search_productsku=$this->search_productsku&search_category=$this->search_category&subcategory_search=$this->subcategory_search&pageID=$this->pageID&outOfStock=".$order->get('outOfStock'));
    } //   }}}

    function action_add_gc()  
    {
        $cloneOrder = $this->get('cloneOrder');
        $giftCertificate = new \XLite\Module\GiftCertificates\Model\GiftCertificate($this->get('add_gcid'));
        $item = new \XLite\Model\OrderItem();
        $item->set('GC',$giftCertificate);
        $cloneOrder->addItem($item);
        $this->saveCurrentValues($cloneOrder);
        $this->cloneUpdated(false);
    }

    function action_delete_gc()  
    {
        if (!is_null($this->get('delete_gc'))) {
            $order = $this->get('cloneOrder');
            foreach ($this->get('delete_gc') as $gcid) {
                foreach ($order->get('items') as $item)
                    if ($item->get('item_id') == $gcid)	$order->deleteItem($item);
            }
            $this->saveCurrentValues($order);
            $this->cloneUpdated(false);
        }
    }
    
    function action_pay_gc() 
    {
        $cloneOrder = $this->get('cloneOrder');
        $gc = new \XLite\Module\GiftCertificates\Model\GiftCertificate($this->get('add_gcid'));
        $cloneOrder->set('GC', $gc);
        $this->saveCurrentValues($cloneOrder);
        $this->cloneUpdated(false);
    }

    function action_clean_gc() 
    {
        $cloneOrder = $this->get('cloneOrder');
        $cloneOrder->set('GC', null);
        $this->saveCurrentValues($cloneOrder);
        $this->cloneUpdated(false);
    }
    
    function action_undo_changes()  
    {
        $order = new \XLite\Model\Order($this->get('order_id'));
        $orderGC = $order->get('GC');
        $cloneOrder = $this->get('cloneOrder');
        $cloneOrderGC = $cloneOrder->get('GC');
        $this->data_exchange($cloneOrder, $order);

        if ($this->xlite->get('PromotionEnabled')) {
            $dc = $cloneOrder->get('DC');
    		if ($dc) {
    			$this->action_del_dc();
    		}
            $dc = $order->get('DC');
            if ($dc) {
    			$this->add_dc = $dc->get('coupon_id');
    			$this->action_add_dc();
    		}
        }
        if ($this->xlite->get('GiftCertificatesEnabled')) {
    		if (is_object($cloneOrderGC)) {
                $this->action_clean_gc();
            }
    		if (is_object($orderGC)) {
    			$this->set('add_gcid', $orderGC->get('gcid'));
                $this->action_pay_gc();
            }
        }

        $this->cloneUpdated(true);
        $this->set('returnUrl',"admin.php?target=".$this->get('target')."&order_id=".$this->get('order_id')."&page=order_edit&mode=".$this->get('mode'));
    }
    
    function action_save_changes()  
    {
        $order = new \XLite\Model\Order($this->get('order_id'));
        $cloneOrder = $this->get('cloneOrder');
        $orderHistory = new \XLite\Module\AOM\Model\OrderHistory();
        $ordersItems = $this->get('ordersItems');
        $orderHistory->log($order, $cloneOrder, $ordersItems);

        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $old_gcid = $order->get('gcid');
            $old_payedByGC = $order->get('payedByGC');
        }

        $this->data_exchange($order,$cloneOrder);

        if ($this->xlite->get('PromotionEnabled')) {
            $status = $order->get('status');
            if ($status == "Q" || $status == "P" || $status == "C") {
                $order->promotionStatusChanged(1);
            }
            $dc = new \XLite\Module\Promotion\Model\DiscountCoupon();
            if ($dc->find("order_id = " .$order->get('order_id'))) {
                $dc->delete();
            }
            $dc = new \XLite\Module\Promotion\Model\DiscountCoupon();
            if ($dc->find("order_id = ". $cloneOrder->get('order_id'))) {
                $dc->set('order_id',$order->get('order_id'));
                $dc->set('coupon_id',null);
                $dc->create();
            }
            if ($status == "Q" || $status == "P" || $status == "C") {
                $order->promotionStatusChanged(-1);
            }
        }
        if ($this->xlite->get('GiftCertificatesEnabled')) {
            $gc_changed = (($old_gcid != $order->get('gcid')) || ($old_payedByGC != $order->get('payedByGC')));
            if ($gc_changed) {
                if ($old_payedByGC > 0) {
                    $cloneOrder->set('gcid', $old_gcid);
                    $cloneOrder->set('payedByGC', $old_payedByGC);
                    $cloneOrder->changeGCDebit(1);
                }
                if ($order->get('payedByGC') > 0) {
                    $order->changeGCDebit(-1);
                }
            }
        }

        $this->cloneUpdated(true);

        // Send notifications to specifiend email addresses when update order
        $emails = $this->config->AOM->order_update_notification;
        if (is_array($emails) && count($emails) > 0) {
            foreach ($emails as $email) {
                $mail = new \XLite\Model\Mailer();
                $mail->order = $order;

                $to_email = trim($this->config->get("Company.$email"));
                if ($to_email) {
                    $mail->compose(
                        $this->config->Company->site_administrator,
                        $to_email,
                        "modules/AOM/status_changed_admin");
                    $mail->send();
                }
            }
        }

        $this->set('returnUrl',"admin.php?target=order&order_id=".$this->get('order_id'));
 	}

    function action_clone_order()  
    {
        $order = new \XLite\Model\Order($this->get('order_id'));
        if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
            $clone = $order->cloneObject();
        } else {
            $clone = $order->clone();
        }

        if ($this->config->AOM->clone_silent) {
            $clone->_disable_all_notifications = true;
        }

        $clone->set('status', $order->get('status'));
        $clone->set('date', time());
        $clone->set('orig_profile_id', $order->get('orig_profile_id'));
        $clone->update();
        $this->updateOrderAsCart($clone);
        $orderHistory = new \XLite\Module\AOM\Model\OrderHistory();
        $orderHistory->log($clone, $order, null,"clone_order");
        $this->set('returnUrl',"admin.php?target=order&order_id=".$clone->get('order_id'));
     }

    function action_split_order()  
    {
        require_once LC_MODULES_DIR . 'AOM' . LC_DS . 'encoded.php';
        return aom_split_order($this);
    }
 
 	function _updateCartDC($cart, $dc)
    {
        $cart->set('DC', $dc);
        $cart->calcTotal();
        
        $orderDate = $cart->get('date');
        $cart->update();
        $order = new \XLite\Model\Order($cart->get('order_id'));
        $order->set('date', $orderDate);
        $order->update();
    }

 	function action_add_dc()  
    {
        $this->add_dc = addSlashes(trim($this->add_dc));
        $dc = new \XLite\Module\Promotion\Model\DiscountCoupon($this->add_dc);

        $order = $this->get('cloneOrder');
        $profile = $order->get('profile');

        $cart = \XLite\Model\Cart::getInstance();
        $cart->clear();
        $cart->set('order_id', $order->get('order_id'));

        $cart->setProfile(null);
        $cart->setProfile($profile);

        foreach ($order->get('items') as $item) {
            $cart->_items[] = $item;
        }

        if ($dc->checkCondition($order)) {
            if ($order->get('orderDC')) {
                $order->DC->delete();
                $order->DC = null;
            }
            $this->_updateCartDC($cart, $dc);
            $this->cloneUpdated(false);
        } else {
            $this->set('valid', false);
            $this->set('wrongDC', $dc);
            if ($dc->get('applyTo') == "total") {
                $pm = $this->getComplex('cloneOrder.paymentMethod');
                if (!is_object($pm) || $this->getComplex('cloneOrder.shipping_id') < 0) {
                    $this->set('wrongDCtotal', true);
                }
            }
        }
                
    }

    function action_del_dc()  
    {
        $order = $this->get('cloneOrder');
        if ($order->DC) {
            $order->DC->delete();
        }
        $order->DC = null;
        $status = $order->get('status');
        $order->set('discountCoupon', "");
        $profile = $order->get('profile');

        $cart = \XLite\Model\Cart::getInstance();
        $cart->clear();
        $cart->set('order_id', $order->get('order_id'));

        $cart->setProfile(null);
        $cart->setProfile($profile);

        foreach ($order->get('items') as $item) {
            $cart->_items[] = $item;
        }

        $this->_updateCartDC($cart, null);
        $this->cloneUpdated(false);
    }
   
    function action_update()  
    {
        $order = new \XLite\Model\Order($this->get('order_id'));
        $orderHistory = new \XLite\Module\AOM\Model\OrderHistory();
        $orderHistory->log($order);
        $order->set('orderStatus',$_POST['substatus']);
        parent::action_update();
        // Diplicate changes in the cloned order
        $_order = $this->get('cloneOrder');
        $_order->set('notes', $_POST['notes']);
        $_order->set('admin_notes', $_POST['admin_notes']);
        $_order->update();
    }

    function action_clear_history_cc_info() 
    {
        $order = new \XLite\Model\Order($this->get('order_id'));
        $history = $order->get('orderHistory');
        foreach ($history as $obj) {
            $changes = $obj->get('changes');
            foreach ($changes as $key=>$val) {
                if (is_array($val)) {
                    foreach ($val as $k=>$v) {
                        if ($obj->isSecureKey($k)) {
                            unset($changes[$key][$k]);
                        }
                    }

                    if (count($changes[$key]) <= 0) {
                        unset($changes[$key]);
                    }
                }
            }

            if (count($changes) <= 0) {
                $obj->delete();
            } else {
                $obj->set('changes', $changes);
                $obj->set('secureChanges', "");
                $obj->update();
            }
        }

        $this->page = "order_history";
    }


// search functions  

    function getPaymentMethods()  
    {
        
        return $paymentMethod->get('activeMethods');
    }

    function getShippingRates() 
    {
        $order = $this->get('cloneOrder');
        $order->doNotChangeGlobalDiscount = true; // don't recalculate global discount
        $rates = $order->getShippingRates();
        $order->doNotChangeGlobalDiscount = false;
        return $rates;
    }
    
    function getUsers()  
    {
        if ($this->mode != "search_users") return array();
        $userDialog = new \XLite\Controller\Admin\Users();
        $userDialog->mapRequest();
        return $userDialog->getUsers();
    }

    function getOutOfStockProduct($id) 
    {
         $product = new \XLite\Model\Product($id);
         return $product->get('name');
    }
    
    function getProducts()  
    {
        if ($this->mode != "search") {
            return null;
        }

        if (is_null($this->products)) {
            $product = new \XLite\Model\Product();
            $this->products = $product->advancedSearch($this->substring,
                                                    $this->search_productsku,
                                                    $this->search_category,
                                                    $this->subcategory_search,
                                                    false,true);
            $this->productsFound = count($this->products);
        }
    
        return $this->products;
    }
    
    function getDiscountCoupons() 
    {
        if ($this->mode != "search_dc") {
            return null;
        }
        $dc = new \XLite\Module\Promotion\Model\DiscountCoupon();
        return $dc->findAll("coupon LIKE '%".$this->get('coupon')."%' AND status = 'A' AND order_id = 0 AND expire > ". time());
    }

    function getGiftCertificates()  
    {
        if ($this->mode != "search_gc") {
            return null;
        }
        $gc = new \XLite\Module\GiftCertificates\Model\GiftCertificate();
        return $gc->findAll("gcid LIKE '%".$this->get('gcid')."%' AND status = 'A'");

    }


    
    function optionSelected($item, $option)  
    {
        $itemOptions = $item->get('productOptions');
        foreach ($itemOptions as $opt)
        {
            if ($opt->class == $option->class && $opt->option == $option->option)
                return true;
        }
        return false;
    }

    function data_exchange(&$order1, &$order2)  
    {
        $properties = $order1->get('properties');
        $orderProfile = $order1->get('profile');
        $cloneProfile = $order2->getComplex('profile.properties');
        $orderProfile->_AOMIgnoreMembershipChanged = true;
        $field_values = $this->getUserProfileFields();
        foreach ($field_values as $value) {
            $orderProfile->setComplex($value, $cloneProfile[$value]);
        }
        $orderProfile->update();
        $orderProfile->_AOMIgnoreMembershipChanged = false;
        
        $details = $order1->get('details');
        $detailLabels = $order1->get('detailLabels');
        if ($this->xlite->getComplex('mm.activeModules.AdvancedSecurity') && !is_null($this->session->get('masterPassword'))) {
            $details = $order1->getSecureDetails();
        }
        $order1->set('properties',$order2->get('properties'));
        $order1->set('details', $details);
        $order1->set('detailLabels', $detailLabels);
        $substatus = (!empty($properties['substatus']) ? $properties['substatus']: $properties['status']);
        $order1->set('orderStatus', $substatus);
        $order1->_substatusChanged = false;
        $order1->_statusChanged = false;

        if ($order2->get('payment_method') == "CreditCard") {
            $this->addDetails($order1);
        }
        $order1->set('order_id',$properties['order_id']);
        foreach ($order1->get('items') as $item)
            $order1->deleteItem($item);

        foreach ($order2->get('items') as $item) {
            $price = $item->get('originalPrice');
            $item->set('originalPrice', $price);
            if ( $item->getComplex('product.available') ) {
                $order1->addItem($item);
            } else {
                $order1->_createItem($item);
            }
        }
        
        $order1->set('profile_id',$properties['profile_id']);
        $order1->set('orig_profile_id',$properties['orig_profile_id']);
        $order1->refresh('profile');
        $order1->refresh('orig_profile');
        $order1->update();
        $this->updateOrderAsCart($order1);
    } //  }}}

    function getLabelDescription($label)
    {
        $ccLabels = array
        (  	
        	"cc_type" 	=> "Credit card type",
        	"cc_number" => "Credit card number",
            "cc_name" 	=> "Cardholder's name",
            "cc_date" 	=> "Expiration date",
            "cc_cvv2" 	=> "Credit Card Code"
        );

        if (array_key_exists($label, $ccLabels)) {
        	return $ccLabels[$label];
        }

        return $label;
    }

    function getCCDetails()
    {
        $ccDetails = array('cc_type', "cc_number", "cc_name", "cc_date", "cc_cvv2");
        return $ccDetails;
    }

    function addDetails($order)
    {
        if ($this->xlite->getComplex('mm.activeModules.AdvancedSecurity') && is_null($this->session->get('masterPassword'))) {
            return;
        }

        $details = $order->get('details');
        $detailLabels = $order->get('detailLabels');

        if (!empty($detailLabels['cc_type'])) {
            return;
        }

        $ccDetails = $this->getCCDetails();
        foreach ($ccDetails as $detail) {
            $details[$detail] = "";
            $detailLabels[$detail]	= $this->getLabelDescription($detail);
        }

        $order->set('details', $details);
        $order->set('detailLabels', $detailLabels);
    }
    
    function getRowClass($row, $css_class, $reserved = null) 
    {
        return (($this->totals_row_counter++ % 2) == 0 ? "" : $css_class);
    }

    function getProfileRow($css_class) 
    {
        return (($this->profile_row_counter++ % 2) == 0 ? "" : $css_class);
    }

    function isLast($key) 
    {
        $item = end(array_keys($this->get('ordersItems')));
        return $key == $item;
    }

    function cloneUpdated($update = false)  
    {
        $ordersUpdated = ($this->session->get('ordersUpdated') ? $this->session->get('ordersUpdated') : array());
        if ($update) {
            unset($ordersUpdated[$this->get('order_id')]);
        } else {
            $ordersUpdated[$this->get('order_id')] = 1;
        }
       $this->session->set('ordersUpdated',$ordersUpdated);
    }

    function isCloneUpdated()  
    {
        $ordersUpdated = ($this->session->get('ordersUpdated') ? $this->session->get('ordersUpdated') : array());
        return isset($ordersUpdated[$this->get('order_id')]);
                
    }

    function isUpdateAvailable()
    {
        $order = $this->get('cloneOrder');
        $login = $order->getComplex('profile.login');
        $paymentMethod = $order->get('paymentMethod');
        $shipping_id = $order->get('shipping_id');
        if (!isset($login) || empty($login) || !isset($paymentMethod) || $shipping_id < 0) {
            return false;
        }
        if ($this->get('unsecureCC')) {
            return false;
        }

        return true;
    }

    function getAllParams($exeptions=null)
    {
    	$allParams = parent::getAllParams($exeptions);
    	if ($this->mode == "search_users") {
    		$allParams['substring'] = $this->substring;
    		$allParams['membership'] = $this->membership;
    		$allParams['user_type'] = $this->user_type;
    		$allParams['mode'] = $this->mode;
    		$allParams['search'] = $this->search;
    	}

    	return $allParams;
    }

    function aom_cart_instance()
    {
        $this->xlite->set('AOM_skip_calcTotal', true);
        $this->_cart = \XLite\Model\Cart::getInstance();
        $this->xlite->set('AOM_skip_calcTotal', false);
    }

}

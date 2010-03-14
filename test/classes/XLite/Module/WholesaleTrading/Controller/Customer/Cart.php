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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{    
    public $updateErrors = null;    
    public $params = array("target","mode");    
    public $currentItem = null;
    
    /**
     * 'add' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        $amount = 1;
        $items = $this->cart->get('items');

        // alternative way to set product options
        if (
            $this->xlite->get("ProductOptionsEnabled")
            && is_object($this->getProduct())
            && isset(XLite_Core_Request::getInstance()->OptionSetIndex[$this->getProduct()->get("product_id")])
        ) {
            $options_set = $this->getProduct()->get("expandedItems");
            foreach ($options_set[XLite_Core_Request::getInstance()->OptionSetIndex[$this->getProduct()->get("product_id")]] as $_opt) {
                $this->product_options[$_opt->class] = $_opt->option_id;
            }
        }
        
        if (isset(XLite_Core_Request::getInstance()->amount) && XLite_Core_Request::getInstance()->amount > 0) {
            $amount = XLite_Core_Request::getInstance()->amount;
        }
        if (isset(XLite_Core_Request::getInstance()->wishlist_amount) && XLite_Core_Request::getInstance()->wishlist_amount > 0) {
            $amount = $XLite_Core_Request::getInstance()->wishlist_amount;
        }
        if (!isset(XLite_Core_Request::getInstance()->opt_product_qty)) {
            // min/max purchase amount check
            $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
            if ($pl->find("product_id=" . $this->getComplex('currentItem.product.product_id'))) {
                $exists_amount = 0;
                for ($i = 0; $i < count($items); $i++) {
                    if ($items[$i]->getComplex('product.product_id') == $this->getComplex('currentItem.product.product_id')) {
                        $exists_amount += $items[$i]->get('amount');
                    }
                }
                if ($amount + $exists_amount < $pl->get('min') || 
                    ($pl->get('max') > 0 && $pl->get('max') < $amount + $exists_amount)) {
                    $this->set("returnUrl", "cart.php?mode=add_error&error=range&max=" . $pl->get('max') . "&min=" . $pl->get('min') . "&added=" . $exists_amount);
                    return;
                }
            }
        }
        // check if product sale available
        $this->getProduct()->set("product_id", $this->product_id);
        if (!$this->getProduct()->is("saleAvailable")) {
            $this->set("returnUrl", "cart.php?mode=add_error");
            return;
        }

        $this->currentItem = parent::get("currentItem");
        $this->currentItem->set("amount", $amount);
 
        parent::action_add();

        if ($this->config->WholesaleTrading->direct_addition) {
            $this->getProduct()->assignDirectSaleAvailable(false);
        }
    }

    function action_update()
    {
        $items = $this->cart->get('items');
        $raw_items = array();
        $amounts = $this->get("amount");
        for ($i = 0; $i < count($items); $i++) {
            $key = $items[$i]->getComplex('product.product_id');
            if ($key == NULL) continue;
            (!isset($raw_items[$key])) ? $raw_items[$key] = $amounts[$i] : $raw_items[$key] += $amounts[$i];    
        }

        foreach($raw_items as $key => $amount) {
            $purchase_limit = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
            $limit = array();
            if ($purchase_limit->find("product_id = ". $key)) {
                $limit = $purchase_limit->get("properties");
                if (!empty($limit['min']) && $amount < intval($limit['min'])) {
                    $this->updateErrors[$key]['min'] = $limit['min'];    
                    $this->updateErrors[$key]['amount'] = $amount;
                }    
                if (!empty($limit['max']) &&  $amount > intval($limit['max'])) {
                    $this->updateErrors[$key]['max'] = $limit['max'];
                    $this->updateErrors[$key]['amount'] = $amount;
                }    
            }    
        }
        if (empty($this->updateErrors)) {
            $this->set("mode",null);
            parent::action_update();
        } else {
            foreach($this->updateErrors as $key => $error) {
                $product = new XLite_Model_Product($key);
                $this->updateErrors[$key]['name'] = $product->get("name");
            }
            
            $this->set("valid",false);
            $this->set("mode","update_error");
        }
            
    }

    function updateCart()
    {
        if($this->xlite == null || !$this->xlite->get("dont_update_cart"))
            parent::updateCart();
    }
}


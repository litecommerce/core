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
class XLite_Module_AOM_Controller_Admin_CreateOrder extends XLite_Controller_Admin_Order
{
    public $page = "order_edit";
    public $pages = array("order_edit" => "Create order #%s");
    public $pageTemplates = array 
    (
    	"order_edit" => "modules/AOM/order_edit.tpl",
        "order_preview" => "modules/AOM/order_edit/preview.tpl"
    );

    function init() 
    {
        $ordersUpdated = $this->session->get('ordersUpdated') ? $this->session->get('ordersUpdated') : array();
        parent::init();
        if(isset($ordersUpdated[$this->get('order_id')])) {
            $order = $this->get('cloneOrder');
            if (!$order->isEmpty()) {
                $this->pages['order_preview'] = "Review and Save Order";
            } else {
                if ($this->page == "order_preview") {
                    $this->redirect("admin.php?target=create_order&order_id=".$this->get('order_id')."&page=order_edit");
                }
            }
        }
    }

    function getCloneOrder() 
    {
        return parent::getOrder();
    }

    function getCloneProfile() 
    {
        if (!$this->get('order_id')) {
            return null;
        }
        return parent::getProfile();
    }

    function action_create_order() 
    {
        $order = new XLite_Model_Order();
        $order->set("date",time());
        $order->set("status","T");
        $order->set("shipping_id","-1");
        $order->create();
        $orderHistory = new XLite_Module_AOM_Model_OrderHistory();
        $orderHistory->log($order, null, null,"create_order");
                        
        $this->set("returnUrl","admin.php?target=create_order&page=order_edit&mode=products&order_id=".$order->get('order_id'));
    }

    function action_save_changes() 
    {
        $order = new XLite_Model_Order($this->get('order_id'));
        $order->set("orderStatus",$_POST['substatus']);
        if ($order->get('payment_method') == "CreditCard") {
            $this->addDetails($order);
        }
        $order->update();
        $this->cloneUpdated(true);
        $this->set("returnUrl","admin.php?target=order&order_id=".$this->get('order_id')."&page=order_info");
    }
    

    function preUpdateProducts()
    {
 	    $order = $this->get('cloneOrder');
        $this->originalShippingId = $order->get('shipping_id');
    }

    function postUpdateProducts()
    {
        if ($this->originalShippingId == -1) {
 	    	$order = $this->get('cloneOrder');
            $order->set("shipping_id", -1);
            $order->set("shipping_cost", 0);
            $order->set("tax", 0);
            $order->set("taxes", "");
            $order->set("subtotal", 0);
            $order->set("total", 0);
            $order->update();
        }
    }

    function action_update_products()
    {
 	    $this->preUpdateProducts();
        parent::action_update_products();
 	    $this->postUpdateProducts();
 	}
    
 	function action_delete_products()
    {
 	    $this->preUpdateProducts();
        parent::action_delete_products();
 	    $this->postUpdateProducts();
    }
    
    function action_add_products()
    {
 	    $this->preUpdateProducts();
        parent::action_add_products();
 	    $this->postUpdateProducts();
    }

    function action_update_profile()
    {
 	    $this->preUpdateProducts();
        parent::action_update_profile();
 	    $this->postUpdateProducts();
    }

    function action_fill_user()
    {
 	    $this->preUpdateProducts();
        parent::action_fill_user();
 	    $this->postUpdateProducts();
    }

    function action_add_gc()
    {
 	    $this->preUpdateProducts();
        parent::action_add_gc();
 	    $this->postUpdateProducts();
    }

    function action_delete_gc()
    {
 	    $this->preUpdateProducts();
        parent::action_delete_gc();
 	    $this->postUpdateProducts();
    }

    function action_pay_gc()
    {
 	    $this->preUpdateProducts();
        parent::action_pay_gc();
 	    $this->postUpdateProducts();
    }

    function action_clean_gc()
    {
 	    $this->preUpdateProducts();
        parent::action_clean_gc();
 	    $this->postUpdateProducts();
    }

 	function action_add_dc()
    {
 	    $this->preUpdateProducts();
        parent::action_add_dc();
 	    $this->postUpdateProducts();
    }

    function action_del_dc()
    {
 	    $this->preUpdateProducts();
        parent::action_del_dc();
 	    $this->postUpdateProducts();
    }
}

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

namespace XLite\Module\CDev\ProductAdviser\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    public $productsFound = 0;
    public $notifyPresentedHash = array();
    public $priceNotifyPresented = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        if ($this->is('relatedProductsEnabled')) {
            $this->pages['related_products'] = "Related products";
            $this->pageTemplates['related_products'] = "modules/CDev/ProductAdviser/RelatedProducts.tpl";
        }
    }

    public function getRelatedProducts($productId)
    {
        $product = new \XLite\Module\CDev\ProductAdviser\Model\Product($productId);
        $relatedProducts = $product->getRelatedProducts();
        return $relatedProducts;
    }

    function getProducts()
    {
        if ($this->get('mode') != "search") {
            return array();
        }

        $p = new \XLite\Model\Product();
        $result = $p->advancedSearch
        (
            $this->substring,
            $this->search_productsku,
            $this->search_category,
            $this->subcategory_search
        );
        if (is_array($result)) {
            $removedItems = array();
            foreach ($result as $p_key => $product) {
                if ($product->get('product_id') == $this->product_id) {
                    $removedItems[$p_key] = true;
                }
                if (!is_object($this->product)) {
                    $this->product = new $this->product_id;
                }
                if (is_object($this->product)) {
                    $rp = $this->product->getRelatedProducts();
                    if (is_array($rp) && count($rp) > 0) {
                        foreach ($rp as $rp_item) {
                            if ($rp_item->getComplex('product.product_id') == $product->get('product_id')) {
                        		$removedItems[$p_key] = true;
                            }
                        }
                    }
                }
            }
        	if (is_array($result) && $this->new_arrivals_search) {
        		for ($i=0; $i<count($result); $i++) {
                    if ($result[$i]->getNewArrival() == 0) {
                    	$removedItems[$i] = true;
                    }
        		}
    		}
    		if (count($removedItems) > 0) {
        		foreach ($removedItems as $i => $j) {
    				unset($result[$i]);
        		}
    		}
            $this->productsFound = count($result);
        }

        return $result;
    }

    function action_add_related_products()
    {
        if (!$this->is('relatedProductsEnabled')) {
            return;
        }

        if (isset($this->product_ids) && is_array($this->product_ids)) {
            $relatedProducts = array();
            foreach ($this->product_ids as $product_id => $value) {
                $relatedProducts[] = new \XLite\Model\Product($product_id);
            }
            $product = new \XLite\Model\Product($this->product_id);
            $product->addRelatedProducts($relatedProducts);
        }
    }

    function action_update_related_products()
    {
        if (!$this->is('relatedProductsEnabled')) {
            return;
        }

        if (isset($this->updates_product_ids) && is_array($this->updates_product_ids)) {
            foreach ($this->updates_product_ids as $product_id => $order_by) {
                $relatedProduct = new \XLite\Module\CDev\ProductAdviser\Model\RelatedProduct();
                $relatedProduct->set('product_id', $this->product_id);
                $relatedProduct->set('related_product_id', $product_id);
                $relatedProduct->set('order_by', $order_by);
                $relatedProduct->update();
            }
        }
    }

    function action_delete_related_products()
    {
        if (!$this->is('relatedProductsEnabled')) {
            return;
        }

        if (isset($this->delete_product_ids) && is_array($this->delete_product_ids)) {
            $relatedProducts = array();
            foreach ($this->delete_product_ids as $product_id => $value) {
                $relatedProducts[] = new \XLite\Model\Product($product_id);
            }
            $product = new \XLite\Model\Product($this->product_id);
            $product->deleteRelatedProducts($relatedProducts);
        }
    }

    function action_info()
    {
        parent::action_info();

        if (!isset($this->NewArrivalStatus)) {
            return;
        }

        $stats = new \XLite\Module\CDev\ProductAdviser\Model\ProductNewArrivals();
        $timeStamp = time();
        if (!$stats->find("product_id='".$this->get('product_id')."'")) {
        	$stats->set('product_id', $this->get('product_id'));
        	$stats->set('added', $timeStamp);
        	$stats->set('updated', $timeStamp);
            $stats->create();
        }

        $statusUpdated = false;

        switch ($this->NewArrivalStatus) {
            case 0:		// Unmark
                $stats->set('new', "N");
                $stats->set('updated', 0);
                $statusUpdated = true;
            break;
            case 1:		// Default period
                // (Forever || Unmark) --> Default period
                if ($stats->get('new') == "Y" || ($stats->get('new') == "N" && $stats->get('updated') == 0)) {
                    $stats->set('new', "N");
                    $stats->set('updated', $timeStamp);
                	$statusUpdated = true;
                }
            break;
            case 2:		// Forever
                $stats->set('new', "Y");
                $stats->set('updated', $timeStamp);
                $statusUpdated = true;
            break;
        }
        if ($statusUpdated) {
            $stats->update();
        }
    }

    function action_update_product_inventory()
    {
    	parent::action_update_product_inventory();

    	$this->checkNotification();
    }

    function action_update_tracking_option()
    {
    	parent::action_update_tracking_option();

    	$this->checkNotification();
    }

    function checkNotification()
    {
    	$inventoryChangedAmount = $this->xlite->get('inventoryChangedAmount');
        $this->session->set('inventoryNotify', null);
        
        $notification = new \XLite\Module\CDev\ProductAdviser\Model\Notification();
        $notification->createInventoryChangedNotification($inventoryChangedAmount);
    }

    function isNotifyPresent($inventory_id)
    {
    	if (!isset($this->notifyPresentedHash[$inventory_id])) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRODUCT . "'";
    		$check[] = "notify_key='" . addslashes($inventory_id) . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(' AND ', $check);

    		$notification = new \XLite\Module\CDev\ProductAdviser\Model\Notification();
    		$this->notifyPresentedHash[$inventory_id] = $notification->count($check);
    	}
        return $this->notifyPresentedHash[$inventory_id];
    }

    function isPriceNotifyPresent()
    {
    	if (!isset($this->priceNotifyPresented)) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
    		$check[] = "notify_key='" . $this->product_id . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(' AND ', $check);

    		$notification = new \XLite\Module\CDev\ProductAdviser\Model\Notification();
    		$this->priceNotifyPresented = $notification->count($check);
    	}
        return $this->priceNotifyPresented;
    }

    function isRelatedProductsEnabled()
    {
        return (($this->config->ProductAdviser->admin_related_products_enabled == "Y") ? true : false);
    }
}

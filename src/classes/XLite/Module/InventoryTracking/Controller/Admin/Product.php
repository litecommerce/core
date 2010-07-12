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

namespace XLite\Module\InventoryTracking\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    public $maxOrderBy = 1;
    protected $inventory = null;
    protected $orderItem = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['inventory_tracking'] = "Inventory tracking";
        $this->pageTemplates['inventory_tracking'] = "modules/InventoryTracking/product.tpl";
    }

    function init()
    {
        if (isset($_REQUEST['product_id']) && intval($_REQUEST['product_id']) > 0) {
            $product = new \XLite\Model\Product($_REQUEST['product_id']);
        	if (!$this->xlite->get('ProductOptionsEnabled') || ($this->xlite->get('ProductOptionsEnabled') && !$product->hasOptions())) {
            	if ($product->get('tracking') != 0 ) {
                	$product->set('tracking', 0);
                    $product->update();
                }
        	}
        }
        parent::init();
    }

    function getOrderItem()
    {
        if (is_null($this->orderItem)) {
            $this->orderItem = new \XLite\Model\OrderItem();
            $this->orderItem->set('product', $this->get('product'));
        }
        return $this->orderItem;
    }
    
    function getInventory()
    {
        if (is_null($this->inventory)) {
            $this->inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
            $found = $this->inventory->find("inventory_id='" . addslashes($this->getOrderItem()->get('key')) . "'");
            $this->set('cardFound', $found);
            // set card status to DISABLED in ADD mode
            if (!$found) {
                $this->inventory->set('enabled', 0);
            }
            if (isset($this->inventory_data)) {
                $this->inventory->set('properties', $this->inventory_data);
            }
        }
        return $this->inventory;
    }

    function action_tracking_selection()
    {
        if (!isset($this->tracking)) return;
        $product = new \XLite\Model\Product($this->product_id);
        $product->find("product_id = '".$this->product_id."'");
        $product->set('tracking',$this->tracking);
        $product->update();
    }

    function action_update_product_inventory()
    {
        $inventory = $this->get('inventory');
        if ($this->is('cardFound')) {
            $inventory->update();
        } else {
            $inventory->create();
        }
    }

    function getInventories()
    {
        $inventories = array();
        if (!$this->xlite->get('ProductOptionsEnabled')) {
            return $inventories;
        }
        $inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
        $inventories = $inventory->findAll("inventory_id LIKE '".$this->product_id."|%'");
        for ($k = 0; $k < count($inventories); $k++) {
            $inventory_id = $inventories[$k]->get('inventory_id');
            $this->set('maxOrderBy', max($this->get('maxOrderBy'), $inventories[$k]->get('order_by')));
            $options = explode("|", $inventory_id);
            $id  = $options[0];
            $opt = array();
            for ($i = 1; $i < count($options); $i++) {
                @list($class, $option) = explode(":", $options[$i]);
                $opt[] = empty($option) ? "<b>$class</b>" : "<b>$class:</b> $option";
            }
            $inventories[$k]->product_options[$id] = $opt;
        }
        return $inventories;
    }

    function getProductOptions()
    {
        $productOptions = array();
        if (!$this->xlite->get('ProductOptionsEnabled')) {
            return $productOptions;
        }
        $po = new \XLite\Module\ProductOptions\Model\ProductOption();
        $productOptions = $po->findAll("product_id=$this->product_id");
        return $productOptions;
    }

    function updateProductInventorySku() 
    {
        $product_id = addslashes($this->get('product_id'));
        $p = new \XLite\Model\Product();
        if ($p->find("product_id='$product_id'")) {
            $p->updateInventorySku();
        }
    }

    function action_delete_tracking_option()
    {
        $i = new \XLite\Module\InventoryTracking\Model\Inventory($this->inventory_id);
        $i->delete();
        $this->updateProductInventorySku();
    }

    function action_update_tracking_option()
    {
        $i = new \XLite\Module\InventoryTracking\Model\Inventory($this->inventory_id);
        $this->optdata['inventory_sku'] = preg_replace("/\|/", "-", $this->optdata['inventory_sku']);
        $i->set('properties', $this->optdata);
        $i->update();
        $this->updateProductInventorySku();
    }

    function action_add_tracking_option()
    {
        if (empty($this->optdata)) {
            return;
        }
        $inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
        $options[] = $this->product_id;
        foreach ($this->optdata as $class => $optdata) {
            if (isset($optdata['used'])) {
                $options[] = stripslashes(isset($optdata['option']) ?  "$class:" . $optdata['option'] : $class);
            }
        }
       	if (!$inventory->find("inventory_id='".addslashes(implode("|", $options))."'"))
        {
            $this->inventory_sku = preg_replace("/\|/", "-", $this->inventory_sku);
//			$this->changeProductInventorySku(null, $this->sku);
            $inventory->set('inventory_id', implode("|", $options));
            $inventory->set('inventory_sku', $this->inventory_sku);
            $inventory->set('amount', $this->amount);
       	    $inventory->set('low_avail_limit', $this->low_avail_limit);
            $inventory->set('enabled', $this->enabled);
            $inventory->set('order_by', $this->order_by);
            $inventory->create();
            $this->updateProductInventorySku();
        } else
            $this->params[] = "error";
            $this->set('error', true);
    }
}

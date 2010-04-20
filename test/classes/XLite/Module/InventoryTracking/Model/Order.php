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
 * Order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_InventoryTracking_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    /**
     * Constructor
     * 
     * @param mixed $id ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        $this->fields['inventory_changed'] = 0;

        parent::__construct($id);
    }

    /**
     * Calculates order totals and store them in the order properties:
     * total, subtotal, tax, shipping, etc
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calcTotals()
    {
        if (!$this->get('inventory_changed')) {

            // if inventory is not yet updated

            // update items amount, check inventory
            foreach ($this->getItems() as $item) {
                $this->updateInventory($item);
            }

            // clear items cache
            $this->_items = null;
        }

        parent::calcTotals();
    }

    /**
     * Update inventory 
     * 
     * @param XLite_Model_OrderItem $item Order item
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateInventory(XLite_Model_OrderItem $item)
    {
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();

        if (
            $this->xlite->get('ProductOptionsEnabled')
            && $item->getProduct()
            && $item->getProduct()->get('productOptions')
            && $item->getProduct()->get('tracking')
        ) {
            // DEVCODE
            /*
            Если у продукта есть опции, и Track with product options выставлено, то попадаем сюда
            Объясняю на примере:
                Есть продукт TEST, у него 2 опции - select box (A, B, C) и TextArea.
                Quantity in stock для опции A выставлен в 2.
                
                В карту добавили продукт TEST (A;"aaa"), и продукт TEST(A;"bbb"). Это будут
                2 РАЗНЫХ OrderItem'а, но на них действует одно Inventory-ограничение (для 
                опции A), и вот тут засада, т.к. updateInventory применяется последовательно к 
                OrderItem'ам, к одному за раз, а надо применять одновременно к нескольким. 
                Соответственно, два вложенных цикла, в которых последовательно определяется
                набор OrderItem'ов, для которых действует текущее InventoryTracking-ограничение.
                Не очень оптимально, но количество затронутых взаимосвязей минимизируется.
                
                Вообще, весь этот метод updateInventory надо отрефакторить
            */
            // /DEVCODE

            $inventories = $inventory->findAll(
                'inventory_id LIKE \'' . $item->get('product_id') . '|%\' AND enabled = 1',
                'order_by'
            );

            foreach ($inventories as $i) {
                $items = $item->findAll(
                    'product_id = ' . $item->get('product_id') . ' AND order_id = ' . $item->get('order_id')
                );
                foreach ($items as $subitem) {
                    // manual declration - findAll() do not this declaration
                    $subitem->order = $this; 
                }

                $suitableItems = array();
                foreach ($items as $tempItem) {
                    if ($i->keyMatch($tempItem->get('key'))) {
                        $suitableItems[] = $tempItem;
                    }
                }

                $this->updateItemInventory($i, $suitableItems);
            }

        } else {
            // DEVCODE
            /* В эту ветку попадаем в двух случаях 
              1) У продукта вообще нет опций. Всё просто и тупо.
              2) У продукта есть опции, но InventoryTracking для продукта 
              настроен как "without options tracking", т.е. все продукты с опциями
              необходимо считать "как один"
              
            Вот в этом втором случае в функцию $this->updateItemInventory необходимо передавать 
            массив продуктов, т.е. все продукты с одинаковым product_id, но разным набором
            опций. В функцию $this->updateItemInventory соответственно внесены небольшие изменения
            для работы с массивом, а не скаляром как раньше.
            */
            // /DEVCODE
            if ($inventory->find('inventory_id = \'' . $item->get('product_id') . '\' AND enabled = 1')) {
                $items = $item->findAll(
                    'product_id = \'' . $item->get('product_id') . '\''
                    . ' AND order_id = \'' . $item->get('order_id') . '\''
                );
                $this->updateItemInventory($inventory, $items);
            }
        }
    }

    /**
     * Update item inventory 
     * 
     * @param XLite_Module_InventoryTracking_Model_Inventory $inventory Item inventory
     * @param array                                          $items     Order items
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateItemInventory(XLite_Module_InventoryTracking_Model_Inventory $inventory, array $items)
    {
        $amount = $inventory->get('amount');

        // check inventory
        if (0 >= $amount) {
            // product out of stock, delete these items from cart/order
            foreach ($items as $item) {
                $item->getOrder()->deleteItem($item);
            }

            // set item id
            if (count($items) > 0) {
                $this->set('outOfStock', $items[0]->get('product_id'));
            }

        } else {

            $quantity = 0;
            foreach ($items as $item) {
                $quantity += $item->get('amount');
            }

            // trim items amount to available amount
            if (0 > $amount - $quantity) {
                $index = 0;
        
                while ($amount >= $items[$index]->get('amount')) {
                    $amount -= $items[$index]->get('amount');
                    $index++;
                }
        
                $items[$index]->updateAmount($amount);
                $items[$index]->set('outOfStock', true);
                $this->set('exceeding', $items[$index]->get('product_id'));
            }
        }
    }

    /**
     * Change inventory 
     * 
     * @param boolean $status Decrease inventory flag
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function changeInventory($status)
    {
        $inventoryChanged = false;

        // update product(s) inventory        
        foreach ($this->getItems() as $item) {
            $inventory = new XLite_Module_InventoryTracking_Model_Inventory();

            if (
                $this->xlite->get('ProductOptionsEnabled')
                && $item->getProduct()
                && $item->getProduct()->get('productOptions')
                && $item->getProduct()->get('tracking')
            ) {
                // product has product options
                $key = $item->get('key');
                $inventories = $inventory->findAll(
                    'inventory_id LIKE \'' . $item->get('product_id') . '|%\' AND enabled = 1',
                    'order_by'
                );
                foreach ($inventories as $i) {
                    if ($i->keyMatch($key)) {
                        $this->changeItemInventory($status, $i, $item);
                        $inventoryChanged = true;
                    }
                }

            } elseif (
                $inventory->find('inventory_id = \'' . $item->get('product_id') . '\' AND enabled = 1')
            ) {
                // product has NO product options
                $this->changeItemInventory($status, $inventory, $item);
                $inventoryChanged = true;
            }
        }

        if ($inventoryChanged) {
            $this->set('inventory_changed', $status);
        }
    }

    /**
     * Change item inventory 
     * 
     * @param boolean                                        $status    Decrease inventory flag
     * @param XLite_Module_InventoryTracking_Model_Inventory $inventory Inventory item
     * @param XLite_Model_OrderItem                          $item      Order item
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function changeItemInventory($status, XLite_Module_InventoryTracking_Model_Inventory $inventory, XLite_Model_OrderItem $item)
    {
        $amount = $status
            ? $inventory->get('amount') - $item->get('amount')
            : $inventory->get('amount') + $item->get('amount');
        $inventory->set('amount', $amount);
        $inventory->update();

        // check low_avail_limit
        if ($this->config->InventoryTracking->send_notification) {
            $inventory->checkLowLimit($item);
        }
    }

    /**
     * Order 'complete' event
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkedOut()
    {
        // decrease product(s) inventory  with placed order
        if ($this->config->InventoryTracking->track_placed_order) {
            $this->changeInventory(true);
        }

        parent::checkedOut();
    }
    
    /**
     * Order 'charge back' event
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function uncheckedOut()
    {
        if ($this->config->InventoryTracking->track_placed_order) {
            $this->changeInventory(false);
        }

        parent::uncheckedOut();
    }
    
    /**
     * Called when an order becomes processed, before saving it to the database
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processed()
    {
        // decrease product(s) inventory  with processed order
        if (!$this->config->InventoryTracking->track_placed_order) {
            $this->changeInventory(true);
        }

        parent::processed();
    }
     
    /**
     * Called when an order status changed from processed to not processed
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function declined()
    {
        // increase inventory if order was processed
        if ($this->_oldStatus == 'P' && !$this->config->InventoryTracking->track_placed_order) {
            $this->changeInventory(false);
        }

        parent::declined();
    }
}

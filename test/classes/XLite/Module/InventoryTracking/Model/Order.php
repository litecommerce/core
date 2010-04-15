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
class XLite_Module_InventoryTracking_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
	public function __construct($id = null)
	{
		$this->fields['inventory_changed'] = 0;
		parent::__construct($id);
	}

    function calcTotals()
    {
		// if inventory is not yet updated
		if (!$this->get("inventory_changed")) {
	        // update items amount, check inventory
    	    foreach ($this->get("items") as $item) {
        	    $this->updateInventory($item);
	        }
    	    // clear items cache
        	$this->_items = null;
		}
        parent::calcTotals();
    }

    function updateInventory($item)
    {
        require_once LC_MODULES_DIR . 'InventoryTracking' . LC_DS . 'encoded.php';
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
		if ($this->xlite->get("ProductOptionsEnabled") && $item->getComplex('product.productOptions')&& $item->getComplex('product.tracking')) {
            /* KOI8-R comment:
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
            $inventories = $inventory->findAll("inventory_id LIKE '".$item->get("product_id")."|%' AND enabled=1", "order_by");
            foreach ($inventories as $i) {
                $items = $item->findAll("product_id = " . $item->get("product_id") . " AND order_id = " . $item->get("order_id"));
                for ($j = 0; $j < count($items); $j++) {
                    // ручное выставление поля order, т.к. простое получение массива через findAll() этого не делает
                    $items[$j]->order = $this; 
                }
                $suitableItems = array();
                foreach ($items as $tempItem) {
                    $key = $tempItem->get("key");
                    if ($i->keyMatch($key)) {
                        $suitableItems[] = $tempItem;
                    }
                }
                func_update_inventory($this, $i, $suitableItems);
            }
        } else {
            /* KOI8-R comment:
            В эту ветку попадаем в двух случаях 
              1) У продукта вообще нет опций. Всё просто и тупо.
              2) У продукта есть опции, но InventoryTracking для продукта 
              настроен как "without options tracking", т.е. все продукты с опциями
              необходимо считать "как один"
              
            Вот в этом втором случае в функцию func_update_inventory необходимо передавать 
            массив продуктов, т.е. все продукты с одинаковым product_id, но разным набором
            опций. В функцию func_update_inventory соответственно внесены небольшие изменения
            для работы с массивом, а не скаляром как раньше.
            */
            if ($inventory->find("inventory_id='".$item->get("product_id")."' AND enabled=1")) {
                $items = $item->findAll("product_id='" . $item->get("product_id") . "' AND order_id='" . $item->get("order_id") . "'");
                func_update_inventory($this, $inventory, $items);
            }
        }
    }

    function changeInventory($status)
    {
		$inventory_changed = false;
        require_once LC_MODULES_DIR . 'InventoryTracking' . LC_DS . 'encoded.php';
        // update product(s) inventory        
        foreach ($this->get("items") as $item) {
            $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            $key = $item->get("key");
			if ($this->xlite->get("ProductOptionsEnabled") && $item->getComplex('product.productOptions') && $item->getComplex('product.tracking')) {
                // product has product options
                $inventories = $inventory->findAll("inventory_id LIKE '".$item->get("product_id")."|%' AND enabled=1", "order_by");
                foreach ($inventories as $i) {
                    if ($i->keyMatch($key)) {
                        func_change_inventory($this, $status, $i, $item);
						$inventory_changed = true;
                    }
                }
            } elseif ($inventory->find("inventory_id='".$item->get("product_id")."' AND enabled=1")) {
                // product has NO product options
                func_change_inventory($this, $status, $inventory, $item);
				$inventory_changed = true;
            }
        }
		if ($inventory_changed) {
			$this->set("inventory_changed", $status);
		}
    }

    function checkedOut()
    {
        // decrease product(s) inventory  with placed order
        if ($this->getComplex('config.InventoryTracking.track_placed_order')) {
            $this->changeInventory(true);
        }
        parent::checkedOut();
    }
    
    function uncheckedOut()
    {
        if ($this->getComplex('config.InventoryTracking.track_placed_order')) {
            $this->changeInventory(false);
        }
        parent::uncheckedOut();
    }
    
    function processed()
    {
        // decrease product(s) inventory  with processed order
        if (!$this->getComplex('config.InventoryTracking.track_placed_order')) {
            $this->changeInventory(true);
        }    
        parent::processed();
    }
     
    function declined()
    {
        // increase inventory if order was processed
        if ($this->_oldStatus == 'P' && !$this->getComplex('config.InventoryTracking.track_placed_order')) {
            $this->changeInventory(false);
        }
        parent::declined();
    }
}

<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Module_InventoryTracking
* @access public
* @version $Id$
*/
class XLite_Module_InventoryTracking_Model_Order extends XLite_Model_Order
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

    function updateInventory(&$item)
    {
        require_once "modules/InventoryTracking/encoded.php";
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
		if ($this->xlite->get("ProductOptionsEnabled") && $item->get("product.productOptions")&& $item->get("product.tracking")) {
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
                for ($i = 0; $i < count($items); $i++) {
                    // ручное выставление поля order, т.к. простое получение массива через findAll() этого не делает
                    $items[$i]->order = $this;
                } 
                func_update_inventory($this, $inventory, $items);
            }
        }
    }

    function changeInventory($status)
    {
		$inventory_changed = false;
        require_once "modules/InventoryTracking/encoded.php";
        // update product(s) inventory        
        foreach ($this->get("items") as $item) {
            $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            $key = $item->get("key");
			if ($this->xlite->get("ProductOptionsEnabled") && $item->get("product.productOptions") && $item->get("product.tracking")) {
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
        if ($this->get("config.InventoryTracking.track_placed_order")) {
            $this->changeInventory(true);
        }
        parent::checkedOut();
    }
    
    function uncheckedOut()
    {
        if ($this->get("config.InventoryTracking.track_placed_order")) {
            $this->changeInventory(false);
        }
        parent::uncheckedOut();
    }
    
    function processed()
    {
        // decrease product(s) inventory  with processed order
        if (!$this->get("config.InventoryTracking.track_placed_order")) {
            $this->changeInventory(true);
        }    
        parent::processed();
    }
     
    function declined()
    {
        // increase inventory if order was processed
        if ($this->_oldStatus == 'P' && !$this->get("config.InventoryTracking.track_placed_order")) {
            $this->changeInventory(false);
        }
        parent::declined();
    }
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

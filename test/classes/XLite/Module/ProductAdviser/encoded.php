<?php

/*
* * Hidden methods
* *
* * @version $Id$
* */

function ProductAdviser_updateInventory($_this, $item)
{
	if ($_this->xlite->get("PA_InventorySupport") && $_this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
		if ($item->get("outOfStock")) {
			$rejectedItemInfo = new StdClass();
        	$rejectedItem = $item;
        	$product = $item->get("product");
        	$rejectedItemInfo->product_id = $product->get("product_id");
        	$rejectedItem->set("product", $product);
        	if ($_this->xlite->get("ProductOptionsEnabled") && $product->hasOptions()) {
        	 	if (isset($_this->product_options)) {
            		$rejectedItem->set("productOptions", $_this->product_options);
            	}
        		$rejectedItemInfo->productOptions = $rejectedItem->get("productOptions");
        	}
			$rejectedItemInfo->itemKey = $rejectedItem->get("key");
			$rejectedItemInfo->requiredAmount = $requiredAmount;
			$rejectedItemInfo->availableAmount = $rejectedItem->get("amount");
        	$_this->session->set("rejectedItem", $rejectedItemInfo);
			$_this->xlite->set("rejectedItemPresented", true);
		}
	}
}

function ProductAdviser_checkedOut(&$products)
{
    $products = array_keys($products); 
    sort($products);

    foreach ($products as $product_id_idx => $product_id) {
    	for ($i=$product_id_idx+1; $i<count($products); $i++) {
			$statistic = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();
            if(!$statistic->find("product_id='".$product_id."' AND product_id_also_buy='".$products[$i]."'")) {
            	$statistic->set("product_id", $product_id);
            	$statistic->set("product_id_also_buy", $products[$i]);
            	$statistic->set("counter", 1);
                $statistic->create();
            } else {
            	$statistic->set("counter", $statistic->get("counter")+1);
                $statistic->update();
            }

			$statistic = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();
            if(!$statistic->find("product_id='".$products[$i]."' AND product_id_also_buy='".$product_id."'")) {
            	$statistic->set("product_id", $products[$i]);
            	$statistic->set("product_id_also_buy", $product_id);
            	$statistic->set("counter", 1);
                $statistic->create();
            } else {
            	$statistic->set("counter", $statistic->get("counter")+1);
                $statistic->update();
            }
		}
    }
}

function ProductAdviser_updateProduct($_this)
{
    if (!$_this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
    	return;
    }
	$price = $_this->xlite->get("productChangedPrice");
	if (isset($price) && is_array($price)) {
    	$check = array();
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
		$check[] = "notify_key='" . $price["product_id"] . "'";
		$check = implode(" AND ", $check);

		$notification = new XLite_Module_ProductAdviser_Model_Notification();
		$notifications = $notification->findAll($check);
		if (is_array($notifications) && count($notifications) > 0) {
			foreach($notifications as $notification) {
				$notification->set("status", CUSTOMER_REQUEST_UPDATED);
                $notification->update();
			}
		}
	}
}

function ProductAdviser_action_add($_this)
{
	if ($_this->xlite->get("PA_InventorySupport") && $_this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
		if (!is_null($_this->cart->get("outOfStock"))) {
			$rejectedItemInfo = new StdClass();
        	$rejectedItem = new XLite_Model_OrderItem();
        	$product = $_this->get("product");
        	$rejectedItemInfo->product_id = $product->get("product_id");
        	$rejectedItem->set("product", $product);
        	if ($_this->xlite->get("ProductOptionsEnabled") && $product->hasOptions() && isset($_this->product_options)) {
            	$rejectedItem->set("productOptions", $_this->product_options);
        		$rejectedItemInfo->productOptions = $rejectedItem->get("productOptions");
        	}
        	$_this->session->set("rejectedItem", $rejectedItemInfo);
		} else {
			if (!$_this->xlite->get("rejectedItemPresented")) {
        		$_this->session->set("rejectedItem", null);
        	}
		}
	}
}

?>

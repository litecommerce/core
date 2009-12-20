<?php
function func_moduleEgoods_send_files(&$order)
{
	check_module_license('Egoods');
	$items =& $order->get('items');

	for ($i = 0; $i < count($items); $i++) {
		if ($items[$i]->isEgood()) {
			$mail =& func_new("Module_Egoods_Mailer");
			$items[$i]->storeLinks();
			$mail->item =& $items[$i];
			$mail->set('clean_after_send', false);
			$mail->compose(
					$order->config->get("Company.site_administrator"),
					$order->get("profile.login"),
					"modules/Egoods/instructions");
			
			$linksAvailable = false;
			$product =& $items[$i]->get("product");
			if (isset($product) && is_object($product)) {
				$egoods =& $product->getEgoods();
    			for($j=0; $j<count($egoods); $j++) {
    				if ($egoods[$j]->get("delivery") == "L") {
    					$linksAvailable = true;
    					break;
    				}
    			}
    		}
			if ($linksAvailable) {
				$mail->send();
			}
			$mail->cleanMail();
			
			if (count($items[$i]->get('product.mailDeliveryFiles')) > 0) {
				foreach ($items[$i]->get('product.mailDeliveryFiles') as $mail_file) {
					$mail->mail->AddAttachment($mail_file->get('data'));
				}	
				$mail->compose(
						$order->config->get("Company.site_administrator"),
						$order->get("profile.login"),
						"modules/Egoods/files");
				$mail->send();
			}
		}
	}
}

function func_moduleEgoods_getPinCodes($item)
{
	check_module_license('Egoods');
	$result = array();
	
	if (!$item->is('pin')) {
		return $result;
	}
	if ($item->get('product.pin_type') == 'D') {
		$pin =& func_new('PinCode');
		$pin_objects = $pin->findAll("item_id = '" . $item->get('item_id') . "' and order_id=" . $item->get('order_id'));
		foreach($pin_objects as $pin_obj) {
			$result []= $pin_obj->get('pin');
		}
	} else if ($item->get('product.pin_type') == 'E') {
		for ($i = 0; $i < $item->get('amount'); $i++) {
			$result []= exec(func_moduleEgoods_parseCmdLine($item, $item->get('product.pinSettings.gen_cmd_line')));
		}
	}

	return $result;
}

function func_moduleEgoods_parseCmdLine($item, $cmd_line)
{
	check_module_license('Egoods');
	$cmd_line = str_replace("%d", escapeshellarg($item->get('order.order_id')), $cmd_line);
	$cmd_line = str_replace("%m", escapeshellarg($item->get('order.profile.login')), $cmd_line);
	$cmd_line = str_replace("%f", escapeshellarg($item->get('order.profile.billing_firstname')), $cmd_line);
	$cmd_line = str_replace("%l", escapeshellarg($item->get('order.profile.billing_lastname')), $cmd_line);
	$cmd_line = str_replace("%p", escapeshellarg($item->get('product.product_id')), $cmd_line);
	$cmd_line = str_replace("%n", escapeshellarg($item->get('product.name')), $cmd_line);

	return $cmd_line;
}

function func_moduleEgoods_send_pins(&$order)
{
	check_module_license('Egoods');
	$items =& $order->get('items');

	for ($i = 0; $i < count($items); $i++) {
		if ($items[$i]->isPin()) {
			$items[$i]->createPins();
			if ($items[$i]->get('pincodes') != '') {
				$mail =& func_new("Module_Egoods_Mailer");
				$mail->item =& $items[$i]; 
				$mail->compose(
						$order->config->get("Company.site_administrator"),
						$order->get("profile.login"),
						"modules/Egoods/pincodes");
				
				$mail->send();
			}
		}
	}
}
?>

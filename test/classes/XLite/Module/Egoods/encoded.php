<?php
function func_moduleEgoods_send_files($order)
{
    $items = $order->get('items');

    for ($i = 0; $i < count($items); $i++) {
        if ($items[$i]->isEgood()) {
            $mail = new XLite_Module_Egoods_Model_Mailer();
            $items[$i]->storeLinks();
            $mail->item = $items[$i];
            $mail->set('clean_after_send', false);
            $mail->compose(
                    $order->config->getComplex('Company.site_administrator'),
                    $order->getComplex('profile.login'),
                    "modules/Egoods/instructions");
            
            $linksAvailable = false;
            $product = $items[$i]->get('product');
            if (isset($product) && is_object($product)) {
                $egoods = $product->getEgoods();
    			for ($j=0; $j<count($egoods); $j++) {
    				if ($egoods[$j]->get('delivery') == "L") {
    					$linksAvailable = true;
    					break;
    				}
    			}
    		}
            if ($linksAvailable) {
                $mail->send();
            }
            $mail->cleanMail();
            
            if (count($items[$i]->getComplex('product.mailDeliveryFiles')) > 0) {
                foreach ($items[$i]->getComplex('product.mailDeliveryFiles') as $mail_file) {
                    $mail->mail->AddAttachment($mail_file->get('data'));
                }
                $mail->compose(
                        $order->config->getComplex('Company.site_administrator'),
                        $order->getComplex('profile.login'),
                        "modules/Egoods/files");
                $mail->send();
            }
        }
    }
}

function func_moduleEgoods_getPinCodes($item)
{
    $result = array();
    
    if (!$item->is('pin')) {
        return $result;
    }
    if ($item->getComplex('product.pin_type') == 'D') {
        $pin = new XLite_Module_Egoods_Model_PinCode();
        $pin_objects = $pin->findAll("item_id = '" . $item->get('item_id') . "' and order_id=" . $item->get('order_id'));
        foreach ($pin_objects as $pin_obj) {
            $result []= $pin_obj->get('pin');
        }
    } else if ($item->getComplex('product.pin_type') == 'E') {
        for ($i = 0; $i < $item->get('amount'); $i++) {
            $result []= exec(func_moduleEgoods_parseCmdLine($item, $item->getComplex('product.pinSettings.gen_cmd_line')));
        }
    }

    return $result;
}

function func_moduleEgoods_parseCmdLine($item, $cmd_line)
{
    $cmd_line = str_replace("%d", escapeshellarg($item->getComplex('order.order_id')), $cmd_line);
    $cmd_line = str_replace("%m", escapeshellarg($item->getComplex('order.profile.login')), $cmd_line);
    $cmd_line = str_replace("%f", escapeshellarg($item->getComplex('order.profile.billing_firstname')), $cmd_line);
    $cmd_line = str_replace("%l", escapeshellarg($item->getComplex('order.profile.billing_lastname')), $cmd_line);
    $cmd_line = str_replace("%p", escapeshellarg($item->getComplex('product.product_id')), $cmd_line);
    $cmd_line = str_replace("%n", escapeshellarg($item->getComplex('product.name')), $cmd_line);

    return $cmd_line;
}

function func_moduleEgoods_send_pins($order)
{
    $items = $order->get('items');

    for ($i = 0; $i < count($items); $i++) {
        if ($items[$i]->isPin()) {
            $items[$i]->createPins();
            if ($items[$i]->get('pincodes') != '') {
                $mail = new XLite_Module_Egoods_Model_Mailer();
                $mail->item = $items[$i];
                $mail->compose(
                        $order->config->getComplex('Company.site_administrator'),
                        $order->getComplex('profile.login'),
                        "modules/Egoods/pincodes");
                
                $mail->send();
            }
        }
    }
}
?>

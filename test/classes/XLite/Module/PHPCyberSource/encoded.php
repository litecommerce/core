<?php

function PaymentMethod_cybersource_process(&$_this, &$cart, $debug = false)
{
	if (PHP_OS == "FreeBSD")
		return FreeBSD_cybersource_process($_this, &$cart);

	$request 	= array();
    $response 	= array();
	$config		= array();	
	$params		= $_this->get("params");

	$config['merchantID'] 		= $params['merchantID'];
	$config['keysDirectory'] 	= $params['keysDirectory'];
	$config['sendToProduction'] = $params['testServer'] ? "false" : "true";
	$config['targetAPIVersion']	= '1.13';
//<!-- debug -->
	if ($debug) {
		$config['enableLog'] = "true";
		$config['logDirectory'] = getcwd()."/var/tmp";
	} else {
	    $config['enableLog'] = "false";
	}	
//<!-- /debug -->	

	$response = $_this->runAuth($cart,$config);
	if ($params['transactionType'] == 'capture' && $response['C:REPLYMESSAGE']['C:DECISION'] == 'ACCEPT') {
		$_this->runCapture($cart,$config,$response);
	 }
}

function FreeBSD_cybersource_process(&$_this, &$cart) 
{
	$order 	= $cart->get("properties");
	$profile = $cart->get("profile");
    $params = $_this->get("params");    
	$card 	= $_this->cc_info;
	
    if (isset($order["discount"])) {
    	$order["total"] += $order["discount"];
    }

	$order["sh_n_tax"] = doubleval(sprintf("%.2f", ($order["total"] - $order["subtotal"])));
	$order["sh_n_tax"] = ($order["sh_n_tax"] < 0) ? 0 : $order["sh_n_tax"];

    if (isset($order["discount"])) {
    	$order["subtotal"] -= $order["discount"];
    }

	$certdir = "./var/tmp";
	if (!is_dir($certdir)) mkdir($certdir);

	$post = "";
	$post[] = "ics_path=".$params['keysDirectory'];
	$post[] = "server_host=".($params['testServer'] ? "ics2test.ic3.com" : "ics2.ic3.com");
	$post[] = "server_port=80";
	$post[] = "ics_applications=ics_auth" .($params['transactionType'] == 'capture' ? ",ics_bill" : "");
	$post[] = "merchant_id=".$params['merchantID'];
	$post[] = "customer_firstname=".$profile->get('billing_firstname');
	$post[] = "customer_lastname=".$profile->get('billing_lastname');
	$post[] = "customer_email=".$profile->get('login');
	$post[] = "customer_phone=".$profile->get("billing_phone");
	$post[] = "bill_address1=".$profile->get('billing_address');
	$post[] = "bill_city=".$profile->get('billing_city');
	$post[] = "bill_state=".$profile->get('billingState.code');
	$post[] = "bill_zip=".$profile->get('billing_zipcode');
	$post[] = "bill_country=".$profile->get('billing_country');
	$post[] = "customer_cc_number=".$card['cc_number'];
	$post[] = "customer_cc_expmo=".substr($card["cc_date"],0,2);
	$post[] = "customer_cc_expyr=".(2000+substr($card["cc_date"],2,2));
	if (isset($card["cc_cvv2"]) && strlen($card["cc_cvv2"]) > 0) {
		$post[] = "customer_cc_cv_number=".$card["cc_cvv2"];
	} else {
		$post[] = "customer_cc_cv_indicator=0";
	}
	$post[] = "merchant_ref_number=".$params['prefix'].$order['order_id'];
	$post[] = "currency=".$params['currency'];

	$post[] = "offer0=offerid0^product_name:Products^merchant_product_sku:^product_code:^amount:". $order['subtotal'] ."^quantity:1";
	$post[] = "offer1=offerid1^product_name:Shipping_etc^merchant_product_sku:^product_code:^amount:".$order["sh_n_tax"]."^quantity:1";

	$tmpfile = @tempnam($certdir,"lctmp");
    $execline  = "./classes/modules/PHPCyberSource/bin/ics";
	putenv('ICSPATH='.$params['keysDirectory']);
	$execline .= " 1> ". $tmpfile ." 2>&1";
	$fp = popen($execline, "w");
	fputs($fp,join("\n",$post)); 
	pclose($fp);
	$return = file($tmpfile);
	@unlink($tmpfile);

	if($return)
		foreach($return as $v) { 
			list($a,$b) = split("=",$v,2); 
			$ret[$a] = trim($b); 
		}
	if ($ret['ics_rcode'] == 1) {
		$cart->set("status","P");
		$cart->set("details.request_id",$ret['request_id']);
		$cart->set("detailLabels.request_id","Request ID");
    	$cart->set("details.rmsg",$ret['ics_rmsg']);
        $cart->set("detailLabels.rmsg","Request message");
   		
	} else {
        $cart->set("details.error",$ret['ics_rmsg']);
		$cart->set("detailLabels.error","Request error");
		$cart->set("status","F");
	}
	$cart->update();	
}

?>

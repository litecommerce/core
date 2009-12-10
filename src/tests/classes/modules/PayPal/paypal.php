<?
// log file
//$fd = fopen("PAYPAL.LOG", "a"); fwrite($fd, "\n"); foreach ($_POST as $n => $v) fwrite($fd, "$n=$v\n"); flush($fd);
	if ($cmd == "_notify-validate") {
		if ($invalid) {
			print "INVALID";
		} else {
			print "VERIFIED";
		}
		exit;
	}
	ini_set("include_path", "../../../../lib".PATH_SEPARATOR.ini_get("include_path"));
	require_once "HTTP/Request.php";
	// IPN form values
	$exchange_rate = 1.5;
	$ipn = array(
		"payment_status" => $payment_status?$payment_status:"Completed",
		"memo" => "Customer\\'s note",
	    "payment_date" => strftime("%H:%M:%S %b,%d %Y"),
		"settle_amount" => $amount*$exchange_rate,
		"settle_currency" => $currency_code,
		"mc_currency" => $currency_code,
		"exchange_rate" => $exchange_rate,
		"txn_id" => "123".rand(),
		"txn_type" => "web_accept",
		"payer_email" => $email,
		"payment_type" => "instant",
		"receiver_email" => $business
	);
	$ipn = array_merge($ipn, $_POST);
	// verify data
	if ($business != "test" || $cmd != "_ext-enter" || !preg_match("/[0-9]+\\.[0-9][0-9]/", $amount)) {
		$ipn["payment_status"] == "Failed";
	}
	if ($ipn["payment_status"] == "Pending") {
		$ipn["pending_reason"] = "upgrade";
	}
    // send ipn
//fwrite($fd, "send ipn to $notify_url ... \n"); flush($fd);

	$r = new HTTP_Request($notify_url);
    $r->_allowRedirects = false;
	foreach ($ipn as $key => $value) {
		$r->addPostData($key, stripslashes($value));
	}	
	$r->setMethod("POST");
	$r->sendRequest();
//fwrite($fd, "ipn reply " . $r->getResponseBody() . "\n"); flush($fd);
	print $r->getResponseBody();
	if (!$test_mode) {
		// return to shopping cart
		?>
			<html>
            <!-- body onLoad="document.return_form.submit()"-->
            <body>
			<form name="return_form" action="<?=$return?>" method="POST">

			<?
			foreach ($ipn as $key=>$value) {
				?>
					<input type="hidden" name="<?=$key?>" value="<?=htmlspecialchars($value)?>">
					<?=$key?>=<?=htmlspecialchars($value)?><br>
					<?
			}
		?>
            <input type=submit>
			</form>
			<?
	}
?>	


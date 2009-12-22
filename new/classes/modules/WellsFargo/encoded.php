<?php
    function func_PaymentMethod_wells_fargo_cc_handleRequest(&$_this, &$cart)
    {
        $status = $cart->get("status");
		
		if ($status != "I" && $status != "F") {
			Header("Location: " . $cart->xlite->shopURL("cart.php"));
			return;
		}
		$errors=array(
				"0" => "Success",
				"-1" => "Authorization system not responding. Order accepted in Faith mode.",
				"1" => "Authorization system not responding. Please retry transaction.",
				"2" => "Authorization declined. Please retry with different credit card.",
				"3" => "No response from issuing institution. Order not accepted. Please retry.",
				"4" => "Authorization declined. Invalid credit card. Please retry with different credit card.",
				"5" => "Authorization declined. Invalid amount. Please retry.",
				"6" => "Authorization declined. Expired credit card. Please retry with different credit card.",
				"7" => "Authorization declined. Invalid transaction. Please retry with different credit card.",
				"8" => "Received unexpected reply. Order not accepted. Please retry.",
				"9" => "Authorization declined. Duplicate transaction.",
				"10" => "Other issue. Order not accepted. Please retry.",
				"11" => "We're sorry, but we are unable to process your request"
		);		

		if ($_GET["IOC_response_code"] == 0) {
			$status = 'P';
		} else {
			$status = 'F';
			$cart->set('details.error', $errors[$_GET["IOC_response_code"]]);
		}
		if (isset($_GET["IOC_order_total_amount"])) {
    		$total = $cart->get("total");
            if ($total != $_GET["IOC_order_total_amount"]) {
                $cart->set("details.error", "Hacking attempt!");
                $cart->set("detailLabels.error", "Error");
                $cart->set("details.errorDescription", "Total amount doesn't match: Order total=".$total.", WellsFargo amount=".$_GET["IOC_order_total_amount"]);
                $cart->set("detailLabels.errorDescription", "Hacking attempt details");
				$status = 'F';
            }
        }
        $cart->set("status", $status);
        $cart->update();

		Header("Location: " . $cart->xlite->shopURL("cart.php?target=checkout&action=return&order_id=") . $cart->get("order_id"));
    }
?>

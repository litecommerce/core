<?php
    function func_PaymentMethod_pay_systems_web_cc_handleRequest(&$_this, &$cart)
    {
		if (isset($_POST["cc_status"])) {
			if ($_POST["cc_status"] != "pass") {
				$status = "F";
				$cart->set("details.error", $_POST["cc_status"]);
			} else {
				$status = "P";
			}

			$cart->set("details.ps_order_id", $_POST["orderid"]);
			$cart->set("details.ps_sku", $_POST["sku"]);
			$cart->set("detailLabels.ps_order_id", "Order ID");
			$cart->set("detailLabels.ps_sku", "SKU");

			$cart->set("status", $status);
			$cart->update();
		}
		header ("Location: " . $cart->xlite->shopURL("cart.php?target=checkout&action=return&order_id=") . $cart->get("order_id"));
    	die();
    }
?>

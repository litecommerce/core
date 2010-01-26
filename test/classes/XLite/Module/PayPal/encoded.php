<?php
/*
* Hidden methods
*/

function PaymentMethod_paypal_handleRequest($_this, $order)
{
    // original code of PaymentMethod_paypal::handleRequest()

    // validate IPN
    /*
    if ($_this->getComplex('params.login') != $_POST["receiver_email"]) {
        die("IPN validation error: PayPal account doesn't match: ".$_POST["receiver_email"]. ". Please contact administrator.");
    }
    */
    // less secure email check
    if ($_this->getComplex('params.login') != $_POST["business"]) {
        die("IPN validation error: PayPal account doesn't match: ".$_POST["business"]. ". Please contact administrator.");
    }

	$order_status = $order->get("status");
    if ($_this->getComplex('params.use_queued')) {
		$order_status = "F";
    }
    
    // send notification
    $https = new XLite_Model_HTTPS();
    $https->url = $_this->getComplex('params.url');
    $_POST["cmd"] = "_notify-validate";
    $https->data = $_POST;
    $https->request();
    if ($https->error) {
        $order->set("details.error", $https->error);
        $order->set("detailLabels.error", "HTTPS Error");
    } else if (preg_match("/VERIFIED/i", $https->response)) {
		$txn_id = ($order->getComplex('details.reason') ? "" : $order->getComplex('details.txn_id')); 

        if ($_POST["txn_id"] == $txn_id) {
            $order->set("details.error", "Duplicate transaction ".$_POST["txn_id"]);
            $order->set("detailLabels.error", "Error");
            $order->update();
        } else {
	        $order->set("details.txn_id", $_POST["txn_id"]);
	        $order->set("detailLabels.txn_id", "Transaction ID");
	        $order->set("details.payment_status", $_POST["payment_status"]);
	        $order->set("detailLabels.payment_status", "Payment Status");
			if (isset($_POST["memo"])) {
	            $order->set("details.memo", $_POST["memo"]);
	            $order->set("detailLabels.memo", "Customer notes entered on the PayPal page");
	        }
			switch ($_POST["payment_status"]) {
				case "Pending" 	: 
					$order->set("status","Q");
		            $order->set("details.reason", $_this->pendingReasons[$_POST["pending_reason"]]);
		            $order->set("detailLabels.reason", "Pending Reason");
		            $order->set("details.error", null);
					break;
				case "Completed" : 
					$order->set("status","P");
		            $order->set("details.error", null);
					break;
				default 	:
					$order->set("status","F");
					$order->set("details.error", "Your order was declined. Please contact administrator.");
		            $order->set("detailLabels.error", "Error");
					break;
			}	 
        }
    } else {
        $order->set("details.error", "Your order was not verified");
        $order->set("detailLabels.error", "Error");
    }
	$order->set("status", $order_status);
    $order->update();
}
?>

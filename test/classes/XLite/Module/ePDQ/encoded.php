<?php
    function func_PaymentMethod_epdq_cc_handleRequest($_this, $cart)
    {    
			if ($_POST["transactionstatus"] == "Success") {
				$status = "P";
			} else {
				$status = "F";
				$cart->set("details.error", $_POST["transactionstatus"]);
			}	

			$cart->set("status", $status);
			$cart->update();
    }
?>

<?php

    function func_PaymentMethod_cc_hsbc_getUserId($_this, $cart)
    {
		return strrev(md5($cart->getComplex('profile.login')));
	}

    function func_PaymentMethod_cc_hsbc_getCwd($_this)
    {    
        $s = XLite::getInstance()->getOptions(array('primary_installation', 'path'));   
		if ($s!="") 		
		{
               $enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
               $path = '';
               for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
               $path .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
                }
        } else $path=".";

        return $path;
	}

    function func_PaymentMethod_cc_hsbc_handleRequest($_this, $cart)
    {
//////////////////////////////////
// debug code
//////////////////////////////////

//ob_start();
//echo "**** start ****\r\n";
//echo "---- POST -----\r\n";
//var_dump($_POST);
//echo "----  GET -----\r\n";
//var_dump($_GET);
//echo "***** end *****";
//$dump = ob_get_contents();
//ob_end_clean();
//
//$file = "var/debug";
//$fd   = @fopen($file, "ab");
//@fwrite($fd, $dump."\r\n");
//@fclose();

//////////////////////////////////
//payment method code
//////////////////////////////////
		if ($_POST["MerchantData"] != $cart->getComplex('details.secure_id')) {
			// user attempting to access order owned by another user
			die();
		}

		$params = $_this->get('params');

		if (isset($_POST["PurchaseAmount"])) {
    		$total = $_this->getTotalCost($cart);
            $total = sprintf("%0.2f", doubleval($total));
            $totalPost = sprintf("%0.2f", doubleval($_POST["PurchaseAmount"]));

            if ($total != $totalPost) {
                $cart->set("details.error", "Hacking attempt!");
                $cart->setComplex("detailLabels.error", "Error");
                $cart->set("details.errorDescription", "Total amount doesn't match: Order total=".$total.", HSBC amount=".$totalPost);
                $cart->set("detailLabels.errorDescription", "Hacking attempt details");
                $cart->set("status","F");
                $cart->update();   

    			die();
            }
        }
        if (isset($_POST["PurchaseCurrency"])) {
    		$currensy = $params["param04"];
            if ($currensy != $_POST["PurchaseCurrency"]) {
                $cart->set("details.error", "Hacking attempt!");
                $cart->setComplex("detailLabels.error", "Error");
                $cart->set("details.errorDescription", "Currency code doesn't match: Order currency=".$currency.", HSBC currency=".$_POST["PurchaseCurrency"]);
                $cart->set("detailLabels.errorDescription", "Hacking attempt details");
                $cart->set("status","F");
                $cart->update();   

    			die();
            }
        }

        if (isset($_POST["CpiResultsCode"]) && ($_POST["CpiResultsCode"] == "0" || $_POST["CpiResultsCode"] == "9")) {
            if ($_POST["CpiResultsCode"] == "0") {
			    $status = $params["status_processed"];
				$substatus = $_this->getStatusCode("status_processed");
            } elseif ($_POST["CpiResultsCode"] == "9") {
				$status = $params["status_queued"];
				$substatus = $_this->getStatusCode("status_queued");
                $cart->setComplex("detailLabels.Response", "Response");
                $cart->set("details.Response", "The transaction was placed in Review state by FraudShield");
            }
		} else {
			$status = $params["status_failed"];
			$substatus = $_this->getStatusCode("status_failed");
			$error_codes = array(
				"1"	 => "The user cancelled the transaction.",
				"2"	 => "The processor declined the transaction for an unknown reason.",
				"3"	 => "The transaction was declined because of a problem with the card. For example, an invalid card number or expiration date was specified.",
				"4"	 => "The processor did not return a response.",
				"5"	 => "The amount specified in the transaction was either too high or too low for the processor.",
				"6"	 => "The specified currency is not supported by either the processor or the card.",
				"7"	 => "The order is invalid because the order ID is a duplicate.",
				"8"	 => "The transaction was rejected by FraudShield.",
				"9"	 => "The transaction was placed in Review state by FraudShield.1",
				"10" => "The transaction failed because of invalid input data.",
				"11" => "The transaction failed because the CPI was configured incorrectly.",
				"12" => "The transaction failed because the Storefront was configured incorrectly.",
				"13" => "The connection timed out.",
				"14" => "The transaction failed because the cardholders browser refused a cookie.",
				"15" => "The customers browser does not support 128-bit encryption.",
				"16" => "The CPI cannot communicate with the Secure ePayment engine."
			);
            $cart->setComplex("detailLabels.error", "Error");
            $cart->setComplex('details.error', $error_codes[$_POST["CpiResultsCode"]]);
		}	
        
//////////////////////////////////
//end of method code
//////////////////////////////////
        $cart->set("status", $status);
		if ($substatus == $status) {
			$substatus = "";
		}
		$cart->set("substatus", $substatus);
        $cart->update();
    	die();
    }
?>

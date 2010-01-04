<?php

/**
* 
*
* @package PayPalPro
* @access private
* @version $Id$
*/
 
 	function Payment_method_paypalpro_checkServiceURL(&$_this)
 	{
		$params = $_this->get("params");
		switch($params["solution"]) 
		{
			case "standard":
				$sUrls = array
				(
					"live_url" => "https://www.paypal.com/cgi-bin/webscr",
					"test_url" => "https://www.sandbox.paypal.com/cgi-bin/webscr"
				);
				$paramsUpdated = false;
				foreach ($sUrls as $sUrlParam => $sUrl) {
    				if (!isset($params["standard"][$sUrlParam]) || (isset($params["standard"][$sUrlParam]) && strlen(trim($params["standard"][$sUrlParam])) == 0)) {
						$paramsUpdated = true;
    					$params["standard"][$sUrlParam] = $sUrl;
    				}
				}
				if ($paramsUpdated) {
					$_this->set("params", $params);
					$_this->update();
				}
			break;
		}
 	}

 	function Payment_method_paypalpro_process(&$_this,&$order) // {{{ 
	{
		switch($_this->get("params.solution")) 
		{
			case "standard": 
				standardRequest($_this, $order); 
			break;
			case "pro": 
				proRequest($_this, $order); 
			break;
		}
	} // }}} 

	function standardRequest(&$_this, $order) // {{{ 
	{
	    if (strcasecmp($_this->get("params.standard.login"),$_POST["business"]) != 0) {
        	die("IPN validation error: PayPal account doesn't match: ".$_POST["business"]. ". Please contact administrator.");
		}

		if (is_null($order->get("details.reason"))) {
    	    $request = new XLite_Model_HTTPS();
    	   	$request->url = $_this->get("params.standard.mode") ? $_this->get("params.standard.live_url") : $_this->get("params.standard.test_url");
    	    $_POST["cmd"] = "_notify-validate";
    	    $request->data = $_POST;
    	    $request->request();
    		
    		if ($request->error) {
    	        $order->set("details.error", $request->error);
    	        $order->set("detailLabels.error", "HTTPS Error");
    			$order->set("status","F");
				$order->update();
				return PAYMENT_FAILURE; 
     		} elseif (preg_match("/VERIFIED/i",$request->response)) {
    			$txn_id = ($order->get("details.reason") ? "" : $order->get("details.txn_id")); 
    		}	

    		$payment_status = $_POST["payment_status"];

			if (strcasecmp($payment_status,"Completed") == 0 || strcasecmp($payment_status, "Pending") == 0) {
        		if ($_POST["txn_id"] == $txn_id) {
					$total = sprintf("%.2f", $order->get("total"));
					$postTotal = sprintf("%.2f", $_POST["mc_gross"]);
					if ((strcasecmp($payment_status,"Completed") != 0) || 
						($total != $postTotal) || 
						($_this->get("params.standard.currency") != $_POST["mc_currency"])) {
						$order->set("details.error", "Duplicate transaction -".$_POST["txn_id"]);
						$order->set("detailLabels.error", "Error");
						$order->set("status","F");
						$order->update();
						return PAYMENT_FAILURE;
					} else {
						// ignore duplicate transaction
					}
        	    } else {
        	        $order->set("details.txn_id", $_POST["txn_id"]);
        	        $order->set("detailLabels.txn_id", "Transaction ID");
        	        $order->set("details.payment_status", $payment_status);
        	        $order->set("detailLabels.payment_status", "Payment Status");
        			if (isset($_POST["memo"])) {
        	            $order->set("details.memo", $_POST["memo"]);
        	            $order->set("detailLabels.memo", "Customer notes entered on the PayPal page");
        	        }

            		$total = sprintf("%.2f", $order->get("total"));
            		$postTotal = sprintf("%.2f", $_POST["mc_gross"]);
                    if ($total != $postTotal) {
                        $order->set("details.error", "Hacking attempt!");
                        $order->set("detailLabels.error", "Error");
                        $order->set("details.errorDescription", "Total amount doesn't match: Order total=".$total.", PayPal amount=".$_POST["mc_gross"]);
                        $order->set("detailLabels.errorDescription", "Hacking attempt details");
                        $order->set("status","F");
                        $order->update();   

                        die("IPN validation error: PayPal amount doesn't match. Please contact administrator.");
                    }
                    $currency = $_this->get("params.standard.currency");
                    if ($currency != $_POST["mc_currency"]) {
                        $order->set("details.error", "Hacking attempt!");
                        $order->set("detailLabels.error", "Error");
                        $order->set("details.errorDescription", "Currency code doesn't match: Order currency=".$currency.", PayPal currency=".$_POST["mc_currency"]);
                        $order->set("detailLabels.errorDescription", "Hacking attempt details");
                        $order->set("status","F");
                        $order->update();

                        die("IPN validation error: PayPal currency code doesn't match. Please contact administrator.");
                    }

					if (strcasecmp($payment_status,"Pending") == 0) {
    					$order->set("status", ($_this->get("params.standard.use_queued")) ? "Q" : "I");
    		            $order->set("details.reason", $_this->pendingReasons[$_POST["pending_reason"]]);
    		            $order->set("detailLabels.reason", "Pending Reason");
    		            $order->set("details.error", null);
						$order->set("details.errorDescription", null);
    				} else {
    					$order->set("status","P");
    		            $order->set("details.error", null);
						$order->set("details.errorDescription", null);
        			}	 

					$order->update();
        		}
			}	// if ("Completed" || "Pending")
		} else {	// if (is_null($order->get("details.reason")))
		    $order_payment_status = $order->get("details.payment_status");
		    $order_txn_id = $order->get("details.txn_id");
		    $order_reason = $order->get("details.reason");
    		$payment_status = $_POST["payment_status"];

			if ($order_payment_status == "Pending" && $order_txn_id == $_POST["txn_id"] && $order_reason == $_this->pendingReasons[$_POST["payment_type"]]) {
    	        $order->set("details.payment_status", $payment_status);
    	        $order->set("detailLabels.payment_status", "Payment Status");
    			if (isset($_POST["memo"])) {
    	            $order->set("details.memo", $_POST["memo"]);
    	            $order->set("detailLabels.memo", "Customer notes entered on the PayPal page");
    	        }

				if (strcasecmp($payment_status,"Completed") == 0 || strcasecmp($payment_status, "Pending") == 0) {
    				if (strcasecmp($payment_status,"Pending") == 0) {
    					$order->set("status", ($_this->get("params.standard.use_queued")) ? "Q" : "I");
    		            $order->set("details.reason", $_this->pendingReasons[$_POST["pending_reason"]]);
    		            $order->set("detailLabels.reason", "Pending Reason");
    		            $order->set("details.error", null);
    					$order->set("details.errorDescription", null);
    				} else {
    					$order->set("status","P");
    		            $order->set("details.error", null);
    					$order->set("details.errorDescription", null);
    				}
					
					$order->update();
    			}
    		}
		}
		
		return PAYMENT_SUCCESS; 
	} // }}}

    function proRequest(&$_this,&$order) // {{{
    {
		$response = PayPalPro_sendRequest($_this->get("params.pro"),$_this->getDirectPaymentRequest($order));
		if (is_null($response)) {
			$order->set("details.error", $response);
			$order->set("detailLabels.error", "HTTPS Error");
			$order->set("status","F");
		} else {
			$xml = new XLite_Model_XML();
			$response = $xml->parse($response);
			if (isset($response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SOAP-ENV:FAULT"])) {
				$responseFault = $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SOAP-ENV:FAULT"];
			}
			$response = $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["DODIRECTPAYMENTRESPONSE"];
			if ($response["ACK"] == 'Success' || $response["ACK"] == 'SuccessWithWarning') {
				$_this->get("params.pro.type") ? $order->set("status","P") : $order->set("status","Q");
				$order->set("details.avscode", $_this->avsResponses[$response["AVSCODE"]]);
				$order->set("detailLabels.avscode","AVS Code");	
                $order->set("details.cvvcode", $_this->cvvResponses[$response["CVV2CODE"]]);
                $order->set("detailLabels.cvvcode","CVV2 Code");
				
                $order->set("details.transaction_id", $response["TRANSACTIONID"]);
                $order->set("detailLabels.transaction_id","Transaction ID");
				$order->set("details.error", null);
				$order->set("details.errorDescription", null);
			} else {
				$order->set("status","F");
				$order->set("details.error",$response["ERRORS"]["ERRORCODE"].": ".$response["ERRORS"]["SHORTMESSAGE"]);
				$order->set("detailLabels.error", "Error");
				$order->set("details.errorDescription",$response["ERRORS"]["LONGMESSAGE"]);
			    $order->set("detailLabels.errorDescription", "Description");
			}

			if ($order->get("status") == "F") {
				if (!isset($response["ERRORS"]["ERRORCODE"]) && isset($responseFault)) {
					$order->set("details.error", $responseFault["FAULTCODE"] . " - " . $responseFault["FAULTSTRING"]);
					$order->set("details.errorDescription", $responseFault["DETAIL"]);
				}
			}
		}
		$order->update();
    } // }}}

  function paypalExpressHandleRequest(&$_this,&$order) // {{{ 
  {
	$request = new XLite_Model_HTTPS();
	if(is_null($order->get("details.token"))) {
		$express_checkout = new XLite_Module_PayPalPro_Controller_Customer_ExpressCheckout();	
		$express_checkout->action_profile();
	}
	$pm = new XLite_Model_PaymentMethod("paypalpro");
	$response = PayPalPro_sendRequest($pm->get("params.pro"),$_this->finishExpressCheckoutRequest($order,$pm->get("params.pro")));
    $xml = new XLite_Model_XML();
    $response = $xml->parse($response);

	if (isset($response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SOAP-ENV:FAULT"])) {
		$responseFault = $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SOAP-ENV:FAULT"];
	}
	$response 	= $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["DOEXPRESSCHECKOUTPAYMENTRESPONSE"];
	$details	= $response["DOEXPRESSCHECKOUTPAYMENTRESPONSEDETAILS"];
	
	if ($response["ACK"] == 'Success') {
		switch ($details["PAYMENTINFO"]["PAYMENTSTATUS"]) {
			case "Completed" :
			case "Processed" : 
				$order->set("status","P");
			break;
			case "Pending"	 : 
				$order->set("status","Q");		
		       	$order->set("details.pending_reason",$details["PAYMENTINFO"]["PENDINGREASON"]);
				$order->set("detailLabels.pending_reason", "Pending reason");
				$order->set("details.error", null);
			break;
		}
		$order->set("details.txn_id",$details["PAYMENTINFO"]["TRANSACTIONID"]);
		$order->set("detailLabels.txn_id", "Transaction ID");
		$order->set("details.payment_date",$details["PAYMENTINFO"]["PAYMENTDATE"]);
		$order->set("detailLabels.payment_date", "Payment date");
		$order->set("details.error", null);
		$order->set("details.errorDescription", null);
	} else {
		$order->set("status","F");
		$order->set("details.error",$response["ERRORS"]["ERRORCODE"].": ".$response["ERRORS"]["SHORTMESSAGE"]);
		$order->set("detailLabels.error", "Error");
		$order->set("details.errorDescription",$response["ERRORS"]["LONGMESSAGE"]);
		$order->set("detailLabels.errorDescription", "Description");
	}	

	if ($order->get("status") == "F") {
		if (!isset($response["ERRORS"]["ERRORCODE"]) && isset($responseFault)) {
			$order->set("details.error", $responseFault["FAULTCODE"] . " - " . $responseFault["FAULTSTRING"]);
			$order->set("details.errorDescription", $responseFault["DETAIL"]);
		}
	}

	$order->update();
	$status = $order->get("status");
	return ($status == "Q" || $status == "P") ? PAYMENT_SUCCESS : PAYMENT_FAILURE; 
  } // }}} 
 
	function PayPalPro_sendRequest(&$payment, &$data) // {{{
	{
/*
global $xlite;
$xlite->logger->log("\nREQUEST:\n".var_export($data, true));
//*/

		$request = new XLite_Model_HTTPS();
		$request->data			= $data;
        $request->cert			= $payment["certificate"];
        $request->method		= 'POST';
        $request->conttype		= "text/xml";
        $request->urlencoded 	= true;
		$request->url    		= ($payment["mode"]) ? "https://api.paypal.com:443/2.0/" : "https://api.sandbox.paypal.com:443/2.0/";
		$request->request();

/*
if (!$request->error) {
	$xml = new XLite_Model_XML();
	$xml_response = $xml->parse($request->response);
	$xlite->logger->log("\nRESPONSE:\n".var_export($xml_response, true)."\n");
} else {
	$xlite->logger->log("\nRESPONSE: FAILED!\n".var_export($request->error, true)."\n");
}
//*/

		return ($request->error) ? $request->error : $request->response;
 	}	// }}}

?>

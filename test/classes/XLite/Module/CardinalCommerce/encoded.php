<?php
/*
* Hidden methods
*/

function CardinalCommerce_isSupported($_this, $pm)
{
	if (!isset($pm)) {
    	$pm = $_this->cart->get("paymentMethod");
    	if (is_null($pm)) {
			return false;
    	}
    }
	$pm_name = $pm->get("payment_method");
	switch ($pm_name) {
		case "authorizenet_cc":
		case "verisign_cc":
		case "payflowpro_cc":
		case "networkmerchants_cc":
		case "netbilling_cc":
		return true;
		default:
		return false;
	}
}

function CardinalCommerce_checkout_cmpi($_this)
{
	if (!($_this->session->isRegistered("cmpiRequest") && $_this->session->get("cmpiRequest"))) {
		$_this->redirect("cart.php?target=cart");
		return;
	}

    $pm = $_this->cart->get("paymentMethod");
    if (is_null($pm)) {
		$_this->redirect("cart.php?target=cart");
		return;
	}
	
	$hash = array
	(
		'CardinalMPI' => array
		(
			"MsgType" 		=> "cmpi_authenticate",
			"Version" 		=> "1.5",
			"ProcessorId" 	=> $_this->config->getComplex('CardinalCommerce.processor_id'),
			"MerchantId" 	=> $_this->config->getComplex('CardinalCommerce.merchant_id'),
			"TransactionId" => $_this->session->get("cmpi_tid"),
			"PAResPayload"  => $_this->PaRes
		)
	);
	$xml = func_hash2xml($hash);

	for ($tryCounter=1; $tryCounter<=5; $tryCounter++) {
		if ($tryCounter > 1) {
        	$_this->cart->set("details.cmpi_conn_attempt", $tryCounter);
        	$_this->cart->set("detailLabels.cmpi_conn_attempt", "Authentication Attempt");
        }
		list($header, $res) = func_https_request2("POST", $_this->config->getComplex('CardinalCommerce.transaction_url'), array("cmpi_msg=".$xml));
    	$res = func_xml2hash($res);
		$res = $res["CardinalMPI"];
		if ($res['ErrorNo'] != 2010) {
			break;
		}	
	}

	$rechoosePayment = false;
	// Generate inner transaction status
	if($res['ErrorNo'] == 0) {
		
        switch ($res["PAResStatus"]) {
			case "Y":
				$res["PAResStatusDesc"] = "Successful authentication. Cardholder successfully authenticated with their Card Issuer.";
				if ($res["SignatureVerification"] != "Y") {
					$res["PAResStatusDesc"] = "Authentication could not be completed.";
					$rechoosePayment = true;
				}
			break;
			case "A":
				$res["PAResStatusDesc"] = "Attempts authentication. Cardholder authentication was attempted.";
			break;
			case "N":
				if ($res["SignatureVerification"] == "Y") {
					$res["PAResStatusDesc"] = "Failed authentication. Cardholder failed to successfully authenticate with their Card Issuer.";
					$rechoosePayment = true;
				}
			break;
			case "U":
				if ($res["SignatureVerification"] == "Y") {
					$res["PAResStatusDesc"] = "Authentication unavailable. Authentication with the Card Issuer was unavailable.";
                	$rechoosePayment = true;
				}	
			break;
			default:
				$res["PAResStatusDesc"] = "Inner error";
			break;
		}
	}

	$res['TransactionID'] = $_this->session->get("cmpi_tid");
	if(!empty($cmpi_spahf))
		$res['SPAHiddenFields'] = $_this->session->get("cmpi_spahf");

    $pm->set("CardinalMPI", $res);

	$oldRequest = $_this->session->get("cmpiRequest_data");
    $_this->mapRequest($oldRequest);
    if (is_array($oldRequest) && count($oldRequest) > 0) {
        foreach($oldRequest as $key => $value) {
        	if (!isset($_REQUEST[$key])) {
        		$_REQUEST[$key] = $value;
        	}
        	if (!isset($_POST[$key])) {
        		$_POST[$key] = $value;
        	}
        }
    }

	if($res['ErrorNo'] != 0 && !empty($res["ErrorDesc"])) {
		$cmpi_info = "CMPI Error (".$res["ErrorNo"]."): ".$res["ErrorDesc"];
        $_this->cart->set("details.error", $res["PAResStatusDesc"] . " Please go back to the payment form to select another form of payment.");
        $_this->cart->set("detailLabels.error", "Error");			
		$rechoosePayment = true;
	}
	
	if(!empty($res["PAResStatusDesc"])) {
		$cmpi_info = "CMPI Result: ".$res["PAResStatusDesc"];
		if ($rechoosePayment) {
			$cmpi_info = "CMPI Error: ".$res["PAResStatusDesc"];
            $_this->cart->set("details.error", $res["PAResStatusDesc"] . " Please go back to the payment form to select another form of payment.");
            $_this->cart->set("detailLabels.error", "Error");
		}
	}
    $_this->cart->set("details.cmpi_info", $cmpi_info);
    $_this->cart->set("detailLabels.cmpi_info", "Cardinal Commerce Response");
	$_this->cart->update();

	if ($rechoosePayment) {
        $_this->cart->set("status", "F");
    	$_this->cart->set("payment_method", "");
        $_this->cart->update();

		$_this->clear_cmpi_session();

		$_this->redirect("cart.php?target=checkout&mode=error&order_id=".$_this->cart->get("order_id"));
        exit;
    }

    $_this->action_checkout();
}

if (!function_exists("func_array2fields")) {
function func_array2fields($arrValue, $prefix="") {
	foreach($arrValue as $key => $value) {
		if (!is_array($value)) {
			echo "<INPUT type=hidden name=\"";
			if (strlen($prefix) > 0) {
				echo $prefix . "[" . $key . "]";
			} else {
				echo $key;
			}	
			echo "\" value=\"" . $value . "\">\n";
		} else {
			if (strlen($prefix) > 0) {
				$new_prefix = $prefix . "[" . $key . "]";
			} else {
				$new_prefix = $key;
			}
			func_array2fields($value, $new_prefix);
		}	
	}
}
}

// Covert XML string to hash array
if (!function_exists("func_xml2hash")) {
function func_xml2hash($str) {
	$str = (string) $str;
	$hash = array();
	for($x = 0; $x < strlen($str); $x++) {
		if ($str[$x] == '<') {
			$x++;
			if($str[$x] == "?") {
				$x = strpos(substr($str, $x), ">");
				continue;
			}
			$tmp = substr($str, $x);
			$c_name = substr($tmp, 0, strpos($tmp, ">"));
			$sub_data = array();
			$x += strlen($c_name)+1;
			$is_single = false;
			if (strpos($c_name, " ") !== false) {
				if(substr($c_name, -1) == '/') {
					$c_name = substr($c_name, 0, -1);
					$is_single = true;
				}
				$sub_data = explode(" ", $c_name);
				$c_name = trim(array_shift($sub_data));
			}
			if(empty($c_name))
				return false;
			$tmp = substr($str, $x);
			if(preg_match("/^(.*)<\/".preg_quote($c_name).">/USs", $tmp, $data)) {
				$hash[$c_name] = func_xml2hash($data[1]);
				$x += strlen($data[1])+2+strlen($c_name);
			} elseif(!$is_single) {
				return false;
			} elseif($sub_data) {
				foreach($sub_data as $sd) {
					list($key, $value) = explode("=", trim($sd));
					$hash[$c_name][][$key] = preg_replace("/^['\"](.+)['\"]$/S", "\\1", $value);
				}
			}
		}
	}

	if(empty($hash))
		$hash = $str;

	return $hash;
}
}

// Convert hash array to XML string
if (!function_exists("func_hash2xml")) {
function func_hash2xml($hash, $level = 0) {
	if(!is_array($hash) || empty($hash)) {
		return $hash;
	}	

	foreach($hash as $k => $v) {
		$xml .= str_repeat("\t", $level)."<$k>".func_hash2xml($v, $level+1)."</$k>\n";
	}
	if($level > 0) {
		$xml = "\n".$xml."\n".str_repeat("\t", $level);
	}

	return $xml;
}
}

if (!function_exists("func_https_request2")) {
function func_https_request2 ($method, $url, $vars) {
	$request = new XLite_Model_HTTPS();

    $_vars = array ();
    if (is_array($vars)) {
    	foreach ($vars as $k=>$v) {
        	list ($var_key, $var_value) = explode ("=", $v, 2);
            if (!isset($var_value)) {
            	$var_value = "";
            }

            $_vars [$var_key] = $var_value;
        }
    }

    $vars = $_vars;

    $request->url = $url;
    $request->data = $vars;

    if ($GLOBALS["debug"]) {
		echo "request->data:<pre>"; print_r($request->data); echo "</pre><br>";
    }
    $request->request ();

    if ($GLOBALS["debug"]) {
    	echo "request->response:<pre>"; print_r($request->response); echo "</pre><br>";
    }
    return array ("", $request->response);
}
}

?>

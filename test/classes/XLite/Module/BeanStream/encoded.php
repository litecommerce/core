<?php

/**
* BeanStream encoded processor unit.
*
* @package Module_BeanStream
* @access public
* @version $Id$
*/

function BeanStream_remove_errno($str)
{
    return preg_replace("/read:errno=[\d]+$/", "", $str);
}

function BeanStream_parse_str($str)
{
    $params = array();
    $parts = explode("&", $str);
    for($i = 0; $i < count($parts); $i++) {
        $tmp    = explode('=', $parts[$i]);
        $params[$tmp[0]] = (isset($tmp[1]) ? urldecode($tmp[1]) : "");
    }
    return $params;
}

function BeanStream_get_order_number($cart)
{
    $params = $cart->getComplex('paymentMethod.params');
	$order_id = ((!empty($params["order_prefix"]))?($params["order_prefix"] . "_"):"") . $cart->get("order_id");
	return $order_id;
}

function BeanStream_get_order_id($order_number)
{
	if (!preg_match("/_?(\d+)$/", $order_number, $matches)) {
		return false;
	}

    return ((empty($matches[1])) ? false : $matches[1]);
}

function BeanStream_processor_process($_this, $cart)
{
    $params  = $_this->get("params");
	$profile = $cart->get("profile");
	$order_id = BeanStream_get_order_number($cart);

    $trnType = strtoupper($params['trnType']);
    if (!in_array($trnType, array("P", "PA"))) {
        $trnType = "PA";
    }

    $request = new XLite_Model_HTTPS();
    $request->url = $_this->get("purchaseUrl");

    $request->data = array(
		"requestType"       => "BACKEND",
        "vbvEnabled"        => (($params["vbvEnabled"]) ? "1" : "0"),
        "scEnabled"         => (($params["scEnabled"]) ? "1" : "0"),
        "termUrl"           => $_this->xlite->shopUrl("classes/modules/BeanStream/callback.php", $_this->getComplex('config.Security.customer_security')),
        "merchant_id"		=> $params["merchant_id"],
		"username"			=> $params["username"],
		"password"			=> $params["password"],
		"trnType"			=> $trnType,
		"trnOrderNumber"	=> $order_id,
		"ordName"			=> $profile->get("billing_firstname")." ".$profile->get("billing_lastname"),
		"ordEmailAddress"	=> $profile->get("login"),
		"ordPhoneNumber"	=> $profile->get("billing_phone"),
		"ordAddress1"		=> $profile->get("billing_address"),
		"ordCity"			=> $profile->get("billing_city"),
		"ordProvince"		=> func_Protx_getState($profile, "billing_state", "billing_custom_state"),
		"ordPostalCode"		=> $profile->get("billing_zipcode"),
		"ordCountry"		=> $profile->get("billing_country"),
		"shipAddress1"		=> $profile->get("shipping_address"),
		"shipCity"			=> $profile->get("shipping_address"),
		"shipProvince"		=> func_Protx_getState($profile, "shipping_state", "shipping_custom_state"),
		"shipPostalCode"	=> $profile->get("shipping_zipcode"),
		"shipCountry"		=> $profile->get("shipping_country"),
		"shipPhoneNumber"	=> $profile->get("shipping_phone"),
		"trnAmount"			=> sprintf("%.02f", $cart->get("total")),
		"ref1"	=> sprintf("%.02f", $cart->get("total"))
    );

//$_this->xlite->logger->log("BeanStrean purchase request: ".var_export($request->data, true));

	$_this->initRequest($cart, $request);
    $request->request();
    $response = BeanStream_remove_errno($request->response);

//$_this->xlite->logger->log("BeanStrean purchase response: ".var_export($response, true));
//$_this->xlite->logger->log("Beanstream purchase parsed response:".var_export(BeanStream_parse_str($response), true));

    $cart->setComplex("details.error", null);
    $cart->setComplex("detailLabels.error", null);
    if (!$response || $request->error) {
        $cart->setComplex("details.error", $request->error);
        $cart->setComplex("detailLabels.error", "Error");
        $cart->set("status", $_this->get("orderFailStatus"));
        $cart->update();
    } else {
        $response = BeanStream_parse_str($response);
        if ($response['responseType'] == 'R') {
            // VBV validation required
            $_this->session->set("BeanStreamOrderID", $cart->get("order_id"));
            $_this->session->writeClose();
            echo $response['pageContents'];
            die;
        } else {
            BeanStream_parse_response($cart, $response, $_this);
        }
    }
}

function func_BeanStream_action_return($_this, $cart, $pm)
{
	$request = new XLite_Model_HTTPS();
	$request->url = $pm->get("authorizationUrl");
	$request->data = array(
		"PaRes"	=> $_this->get("PaRes"),
		"MD"	=> $_this->get("MD")
	);

//$_this->xlite->logger->log("BeanStream VBV Authorization request:".var_export($request->data, true));

	$request->request();
	$response = BeanStream_remove_errno($request->response);

//$_this->xlite->logger->log("BeanStream VBV Authorization response:".var_export($response, true));
//$_this->xlite->logger->log("BeanStream VBV Authorization parsed response:".var_export(BeanStream_parse_str($response), true));

	$cart->setComplex("details.error", null);
	$cart->setComplex("detailLabels.error", null);
	if (!$response || $request->error) {
		// error
		$cart->set("details.error", "VBV auth. ".$request->error);
		$cart->setComplex("detailLabels.error", "Error");
		$cart->set("status", $pm->get("orderFailStatus"));
		$cart->update();
	} else {
		// success
		$response = BeanStream_parse_str($response);
		BeanStream_parse_response($cart, $response, $pm);
	}
}


function BeanStream_parse_response($cart, $response=array(), $pm)
{
	$result = false;
    $status = $pm->get("orderFailStatus");
	$order_id = BeanStream_get_order_id($response["trnOrderNumber"]);

	$total = $cart->get("total");

    if ($order_id == $cart->get("order_id") && $response["responseType"] == "T" && $response["trnApproved"] == "1" && $response["ref1"] == $total) {
        // success
		$cart->setComplex("details.error", null);
		$cart->setComplex("detailLabels.error", null);

		$status = $pm->get("orderSuccessStatus");
		$result = true;
    } else {
        // failed
		$errorMsg = "";

		if ($response["ref1"] != $total) {
			$errorMsg = "Internal error. Please contact site administrator.";
			$cart->set("details.hacking", "Cart total: '$total'; Beanstream total: '".$response["ref1"]."'");
			$cart->set("detailLabels.hacking", "Hacking attempt");

		} else if ($order_id === true && $order_id != $cart->get("order_id")) {
			$errorMsg = "Internal error (oid)";
		} else if ($response["messageText"]) {
			$errorMsg = $response["messageText"];
		}
		
		$cart->setComplex("details.error", $errorMsg);
		$cart->setComplex("detailLabels.error", "Error");

		$status = $pm->get("orderFailStatus");
    }

	if ($response["messageText"]) {
		$cart->setComplex("details.messageText", $response["messageText"]);
		$cart->set("detailLabels.messageText", "Message Text");
	} else {
		$cart->setComplex("details.messageText", null);
		$cart->setComplex("detailLabels.messageText", null);
	}

	if ($response["authCode"]) {
		$cart->setComplex("details.authCode", $response["authCode"]);
		$cart->set("detailLabels.authCode", "Auth code");
	} else {
		$cart->setComplex("details.authCode", null);
		$cart->setComplex("detailLabels.authCode", null);
	}

	if ($response["trnId"]) {
		$cart->setComplex("details.transid", $response["trnId"]);
		$cart->set("detailLabels.transid", "Transaction Id");
	} else {
		$cart->setComplex("details.transid", null);
		$cart->setComplex("detailLabels.transid", null);
	}

	if ($response["avsMessage"]) {
		$cart->setComplex("details.avsMessage", $response["avsMessage"]);
		$cart->set("detailLabels.avsMessage", "AVS Message");
	} else {
		$cart->setComplex("details.avsMessage", null);
		$cart->setComplex("detailLabels.avsMessage", null);
	}

	$cart->set("status", $status);
	$cart->update();

	return $result;
}

function func_Protx_getState($profile, $field, $customField)
{
    $stateName = "";
    $state = new XLite_Model_State();
    if ($state->find("state_id='".$profile->get($field)."'")) {
        $stateName = $state->get("code");
    } else { // state not found
        $stateName = $profile->get($customField);
    }

    return $stateName;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

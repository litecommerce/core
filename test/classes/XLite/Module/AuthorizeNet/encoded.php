<?php
/*
* Hidden methods
*/
function AuthorizeNet_processor_process($_this, $cart, &$paymentMethod)
{
    // AuthorizeNet_processor::process() method code
    
    $params = $paymentMethod->get("params");

    $request = new XLite_Model_HTTPS();
    $request->url = $params["url"];
    $request->data = array
    (
    	'x_delim_data' => "true",
    	'x_relay_response' => "false",
        'x_delim_char' => ";,",
        'x_encap_char' => "\"",
        'x_Login' => $params["login"],
        'x_Tran_Key' => $params["key"],
        'x_Amount' => $cart->get("total"),
        'x_Type' => $params['type'],
        'x_Test_Request' => $params["test"], // TRUE|FALSE
        'x_Address' => $cart->getComplex('profile.billing_address'),
        'x_Ship_To_Address' => $cart->getComplex('profile.shipping_address'),
        'x_City' => $cart->getComplex('profile.billing_city'),
        'x_Ship_To_City' => $cart->getComplex('profile.shipping_city'),
        'x_Country' => $cart->getComplex('profile.billing_country'),
        'x_Ship_To_Country' => $cart->getComplex('profile.shipping_country'),
        'x_First_Name' => $cart->getComplex('profile.billing_firstname'),
        'x_Ship_To_First_Name' => $cart->getComplex('profile.shipping_firstname'),
        'x_Last_Name' => $cart->getComplex('profile.billing_lastname'),
        'x_Ship_To_Last_Name' => $cart->getComplex('profile.shipping_lastname'),
        'x_State' => $cart->getComplex('profile.billingState.code'),
        'x_Ship_To_State' => $cart->getComplex('profile.shippingState.code'),
        'x_Zip' => $cart->getComplex('profile.billing_zipcode'),
        'x_Ship_To_Zip' => $cart->getComplex('profile.shipping_zipcode'),
        'x_Phone' => $cart->getComplex('profile.billing_phone'),
        'x_Email' => $cart->getComplex('profile.login'),
        'x_Cust_ID' => $cart->getComplex('profile.profile_id'),
        'x_Merchant_Email' => $_this->config->getComplex('Company.orders_department'),
        'x_Currency_Code' => $params["currency"],
        'x_Invoice_Num' => $params["prefix"] . $cart->get("order_id"),
        'x_Description' => $cart->get("description"),
        'x_Version' => '3.1',
    );

    if ($request->data['x_Country'] != 'US') {
        $request->data['x_State'] = 'Non US';
        $request->data['x_Ship_To_State'] = 'Non US' ;
    }
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $request->data['x_Customer_IP'] = $_SERVER['REMOTE_ADDR'];
    }
    $paymentMethod->initRequest($cart, $request);

    $request->request();
    $response = explode(';,', $request->response);
    // strip '"'
    foreach ($response as $key => $val) {
        $response[$key] = substr($val, 1, strlen($val)-2);
    }
    $status = "I";
    if (count($response) < 38) {
        $error = "Can't connect to ".$params["url"];
        $status = "F";
    } else {
        $transid = $response[6];
        $cart->setComplex("details.transid", $transid);
        $cart->set("detailLabels.transid", "Authorize.Net Transaction ID");
        // md5 hash check, if configured
        $amount = sprintf("%.2f", $cart->get("total")); 
        if (!empty($params["md5HashValue"])) {
            $value = md5($params["md5HashValue"] . 
                $params["login"] . 
                $transid .
                $amount);
        }        
        if (!empty($params["md5HashValue"]) && 
            strcasecmp($value, $response[37])) {
            // MD5 mismatch
            $cart->set("details.error", $msg = "MD5 hash is invalid: $response[37]. Please contact administrator");
            $cart->setComplex("detailLabels.error", "Error");
            die("Your order won't go thru. $msg");
            // do not update order
            return;
        } else {
            if ($response[0] == '1' && $status != "F") {
                // success
                $error = "";
                $status = "P";
            } else {
                // failure
                $error = $response[3];
                $status = "F";
            }
            $cart->set("detailLabels.cvvMessage", "CVV message");
            if ($response[38]) {
                $cart->setComplex("details.cvvMessage", $_this->cvverr[$response[38]]);
            } else {
                $cart->setComplex("details.cvvMessage", null);
            }
            $cart->set("detailLabels.avsMessage", "AVS message");
            if ($response[5]) {
                $cart->setComplex("details.avsMessage", $_this->avserr[$response[5]]);
            } else {
                $cart->setComplex("details.avsMessage", null);
            }
        }
    }
    $cart->setComplex("details.error", $error);
    $cart->setComplex("detailLabels.error", "Error");
    $cart->set("status", $status);
    $cart->update();
}
?>

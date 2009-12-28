<?php
/*
* Hidden methods
*/
function AuthorizeNet_processor_process(&$_this, &$cart, &$paymentMethod)
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
        'x_Address' => $cart->get("profile.billing_address"),
        'x_Ship_To_Address' => $cart->get("profile.shipping_address"),
        'x_City' => $cart->get("profile.billing_city"),
        'x_Ship_To_City' => $cart->get("profile.shipping_city"),
        'x_Country' => $cart->get("profile.billing_country"),
        'x_Ship_To_Country' => $cart->get("profile.shipping_country"),
        'x_First_Name' => $cart->get("profile.billing_firstname"),
        'x_Ship_To_First_Name' => $cart->get("profile.shipping_firstname"),
        'x_Last_Name' => $cart->get("profile.billing_lastname"),
        'x_Ship_To_Last_Name' => $cart->get("profile.shipping_lastname"),
        'x_State' => $cart->get("profile.billingState.code"),
        'x_Ship_To_State' => $cart->get("profile.shippingState.code"),
        'x_Zip' => $cart->get("profile.billing_zipcode"),
        'x_Ship_To_Zip' => $cart->get("profile.shipping_zipcode"),
        'x_Phone' => $cart->get("profile.billing_phone"),
        'x_Email' => $cart->get("profile.login"),
        'x_Cust_ID' => $cart->get("profile.profile_id"),
        'x_Merchant_Email' => $_this->config->get("Company.orders_department"),
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
        $cart->set("details.transid", $transid);
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
            $cart->set("detailLabels.error", "Error");
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
                $cart->set("details.cvvMessage", $_this->cvverr[$response[38]]);
            } else {
                $cart->set("details.cvvMessage", null);
            }
            $cart->set("detailLabels.avsMessage", "AVS message");
            if ($response[5]) {
                $cart->set("details.avsMessage", $_this->avserr[$response[5]]);
            } else {
                $cart->set("details.avsMessage", null);
            }
        }
    }
    $cart->set("details.error", $error);
    $cart->set("detailLabels.error", "Error");
    $cart->set("status", $status);
    $cart->update();
}
?>

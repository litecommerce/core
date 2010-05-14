<?php

/**
* Hiden metods
*
* @version $Id$
*/

if (!defined('ESELECT_MPG_DEBUG_LOG')) {
    define('ESELECT_MPG_DEBUG_LOG', false);
}
if (!defined('ESELECT_MPI_DEBUG_LOG')) {
    define('ESELECT_MPI_DEBUG_LOG', false);
}

function func_eSelect_getXID()
{
    return sprintf("%'920d", time());
}

function func_eSelect_process($cart, $_this)
{
    // get eSelect params
    $params = $_this->get('params');

    $crypt_type='7';
    $purchase_amount = sprintf("%.02f", $cart->get('total'));

    // do transaction w/o VBV checking
    if ($params['vbv_check'] != "1") {
        return func_eSelect_performAuthPurchase($cart, $_this->cc_info, $_this, $purchase_amount, $crypt_type);
    }

    $xid = func_eSelect_getXID();
    $custid = $params['order_prefix'].$cart->get('order_id');

    $HTTP_ACCEPT = getenv('HTTP_ACCEPT');
    $HTTP_USER_AGENT = getenv('HTTP_USER_AGENT');

    $merchant_url = $_this->get('returnUrl');
    $pan = $_this->cc_info['cc_number'];
    $expiry = $_this->cc_info['cc_date'];
    $cvv2 = $_this->cc_info['cc_cvv2'];

    // reverse MM/YY to YY/MM format
    $expiry = substr($expiry, 2, 2) . substr($expiry, 0, 2);

    // prepare transaction details
    $txnArray = array(
        "type"		=> 'txn',
        "xid"		=> $xid,
        "amount"	=> $purchase_amount,
        "pan"		=> $pan,
        "expdate"	=> $expiry,
        "MD"		=> "xid=$xid&amp;pan=$pan&amp;expiry=$expiry&amp;cvv2=".$cvv2."&amp;amount=".$purchase_amount,
        "merchantUrl"	=> $merchant_url,
        "accept"		=> $HTTP_ACCEPT,
        "userAgent"	=> $HTTP_USER_AGENT
    );

    // prepare MPI request
    $request = func_eSelect_mpiTransactionXML($txnArray);

    // send MPI request
    $response = func_eSelect_mpiSendRequest($_this, $request);

    $mpi_response = $response['MPIRESPONSE'];
    $response_msg = strtoupper($mpi_response['MESSAGE']);

    // payment not configured or error
    if (!in_array($response_msg, array('Y', "U", "N"))) {
        $cart->setComplex('details.error', _replace_security_info($mpi_response['MESSAGE']));
        $cart->setComplex('detailLabels.error', "Error");
        $status = $_this->get('orderFailStatus');
        if ($cart->xlite->AOMEnabled) {
            $cart->set('orderStatus', $status);
        } else {
            $cart->set('status', $status);
        }
        $cart->update();
        
        return false;
    }

    // parse response
    if ($response_msg == "Y") {
        // VBV
        $_this->session->set('eSelectQueued', $cart->get('order_id'));
        $_this->session->writeClose();

        $redirectForm = func_eSelect_getMpiInLineForm($mpi_response);
        echo $redirectForm;

        exit();
    } else {
        // Usual
        if ($response_msg == 'U')
        {
            // merchant assumes liability for charge back (usu. corporate cards)
            $crypt_type='7';
        }
        else
        {
            // merchant is not liable for chargeback (attempt was made)
            $crypt_type='6';
        }

        func_eSelect_performAuthPurchase($cart, $_this->cc_info, $_this, $purchase_amount, $crypt_type);

    }

}


function func_eSelect_action_return($_this, $order, &$payment)
{
    parse_str($_this->get('MD'), $data);

    $cc_info = array(
        "cc_number" => $data['pan'],
        "cc_date"   => $data['expiry'],
        "cc_cvv2"   => $data['cvv2']
    );
    $purchase_amount = $data['amount'];

    $txnArray=array(
        "type"  => 'acs',
        "PaRes" => $_this->get('PaRes'),
        "MD"    => $_this->get('MD')
    );

    $request = func_eSelect_mpiTransactionXML($txnArray);
    $response = func_eSelect_mpiSendRequest($payment, $request);

    $mpi_response = $response['MPIRESPONSE'];
    if (strtolower($mpi_response['SUCCESS']) == "true") {
        func_eSelect_performAuthPurchase($order, $cc_info, $payment, $purchase_amount, null, $mpi_response['CAVV']);
    } else {
        $order->setComplex('details.error', _replace_security_info($mpi_response['MESSAGE']));
        $order->setComplex('detailLabels.error', "Error");
        $status = $payment->get('orderFailStatus');
        if ($order->xlite->AOMEnabled) {
            $order->set('orderStatus', $status);
        } else {
            $order->set('status', $status);
        }
        $order->update();
    }

}


function func_eSelect_performAuthPurchase($order, $cc_info, &$payment, $amount, $crypt_type=null, $cavv=null)
{
    // get eSelect params
    $params = $payment->get('params');

    $orderid = substr($params['order_prefix'].$order->get('order_id')."-ord-".date("dmy-G:i:s"), 0, 50);
    $custid = $params['order_prefix'].$order->get('order_id');

    $purchase_amount = sprintf("%.02f", $amount);
    $pan = $cc_info['cc_number'];
    $expiry = $cc_info['cc_date'];

    // build transaction type string

    $area_prefix = "";
    if ($payment->getComplex('params.account_type') != "CA") {
        $area_prefix = "us_";
        $transaction_type = array('us');
    }

    if (!is_null($cavv)) {
        $transaction_type[] = "cavv";
    }
    $transaction_type[] = (($params['trans_type'] == "purch") ? "purchase" : "preauth");
    $transaction_type = implode('_', $transaction_type);

    // reverse MM/YY to YY/MM format
    $expiry = substr($expiry, 2, 2) . substr($expiry, 0, 2);

    $txnArray = array(
        "type"	=> $transaction_type,
        "order_id"	=> $orderid,
        "cust_id"	=> $custid,
        "amount"	=> $purchase_amount,
        "pan"		=> $pan,
        "expdate"	=> $expiry,
        "commcard_invoice"	=> "",
        "commcard_tax_amount"	=> ""
    );

    if (!is_null($crypt_type)) {
        $txnArray['crypt_type'] = $crypt_type;
    }

    if (!is_null($cavv)) {
        $txnArray['cavv'] = $cavv;
    }


    // get order profile and fill transaction customer details
    $profile = $order->get('profile');

    // Make customer info
    $customerInfo = array();
    $cust_info = array(
        "email"			=> $profile->get('login'),
        "instructions"	=> "",
    );
    $billing = array(
        "first_name"	=> $profile->get('billing_firstname'),
        "last_name"		=> $profile->get('billing_lastname'),
        "company_name"	=> $profile->get('billing_company'),
        "address"		=> $profile->get('billing_address'),
        "city"			=> $profile->get('billing_city'),
        "province"		=> func_eSelect_getState($profile, "billing_state", "billing_custom_state"),
        "postal_code"	=> $profile->get('billing_zipcode'),
        "country"		=> $profile->get('billing_country'),
        "phone_number"	=> $profile->get('billing_phone'),
        "fax"		=> $profile->get('billing_fax'),
        "tax1"		=> "",
        "tax2"		=> "",
        "tax3"		=> "",
        "shipping_cost"	=> sprintf("%.02f", $order->get('shipping_cost'))
    );
    $shipping = array(
        "first_name"	=> $profile->get('shipping_firstname'),
        "last_name"		=> $profile->get('shipping_lastname'),
        "company_name"	=> $profile->get('shipping_company'),
        "address"		=> $profile->get('shipping_address'),
        "city"			=> $profile->get('shipping_city'),
        "province"		=> func_eSelect_getState($profile, "shipping_state", "shipping_custom_state"),
        "postal_code"	=> $profile->get('shipping_zipcode'),
        "country"		=> $profile->get('shipping_country'),
        "phone_number"	=> $profile->get('shipping_phone'),
        "fax"		=> $profile->get('shipping_fax'),
        "tax1"		=> "",
        "tax2"		=> "",
        "tax3"		=> "",
        "shipping_cost"	=> sprintf("%.02f", $order->get('shipping_cost'))
    );

    $customerInfo = array();
    $customerInfo['cust_info'][] = $cust_info;
    $customerInfo['billing'][] = $billing;
    $customerInfo['shipping'][] = $shipping;

    // order items
    $items = array();
    foreach ($order->get('items') as $item) {
        $product = $item->get('product');
        $node = array(
            "name"				=> $product->get('name'),
            "quantity"			=> $item->get('amount'),
            "product_code"		=> $product->get('sku'),
            "extended_amount"	=> $item->get('price')
        );

        $customerInfo['item'][] = $node;
    }


    if ($params['avs_cvd_check'] == "1") {
        // Make AVS info
        $avsInfo = array(
            "avs_street_number"	=> "",
            "avs_street_name"	=> $profile->get('billing_address'),
            "avs_zipcode"		=> $profile->get('billing_zipcode')
        );

        // Make CVD info
        $cvdInfo = array(
            "cvd_value"	=> $cc_info['cc_cvv2'],
            "cvd_indicator"	=> (($cc_info['cc_cvv2']) ? 1 : 9)
        );
    }

    $recurInfo = null;

    // prepare MPG transaction
    $request = func_eSelect_mpgTransactionXML($txnArray, $customerInfo, $avsInfo, $cvdInfo, $recurInfo, $area_prefix);

    // send MPG transaction
    $response = func_eSelect_mpgSendRequest($payment, $request);

    // parse response
    $receipt = $response['RESPONSE']["RECEIPT"];
    if ($receipt['RESPONSECODE'] < 50 && strtolower($receipt['COMPLETE']) == "true") {
        // approved
        $status = $payment->get('orderSuccessStatus');
        if ($order->xlite->AOMEnabled) {
            $order->set('orderStatus', $status);
        } else {
            $order->set('status', $status);
        }

        $order->setComplex('details.referenceNum', $receipt['REFERENCENUM']);
        $order->setComplex('details.responseCode', $receipt['RESPONSECODE']);
        $order->setComplex('details.authCode', $receipt['AUTHCODE']);
        $order->setComplex('details.message', $receipt['MESSAGE']);
        $order->setComplex('details.avs', $payment->getAVSMessageText($receipt['AVSRESULTCODE']));
        $order->setComplex('details.cvd', $payment->getCVDMessageText($receipt['CVDRESULTCODE']));

        $orderLabels = array(
            "referenceNum"	=> "Reference Num",
            "responseCode"	=> "Response Code",
            "authCode"	=> "Auth Code",
            "message"	=> "Message",
            "avs"	=> "AVS Message",
            "cvd"	=> "CVV Message"
        );
        $order->set('detailLabels', $orderLabels);

        $order->update();
    } else {
        // declined
        $status = $payment->get('orderFailStatus');
        if ($order->xlite->AOMEnabled) {
            $order->set('orderStatus', $status);
        } else {
            $order->set('status', $status);
        }

        $order->setComplex('details.error', _replace_security_info($receipt['MESSAGE']));
        $order->setComplex('detailLabels.error', "Error");

        $order->update();
    }

    $order->update();
}


function func_eSelect_xmlToArray($xmlData)
{
    $xmlData = substr($xmlData, 0, strrpos($xmlData, ">")+1);

    $xml = new XLite_Model_XML();
    $tree = $xml->parse($xmlData);
    if (!$tree) {
        return array();
    }

    return $tree;
}

function func_eSelect_getState($profile, $field, $customField)
{
    $stateName = "";
    $state = new XLite_Model_State();
    if ($state->find("state_id='".$profile->get($field)."'")) {
        $stateName = $state->get('code');
    } else { // state not found
        $stateName = $profile->get($customField);
    }

    return $stateName;
}

function _replace_security_info($message)
{
    $message = preg_replace("/(?:stored_id|api_token)\s*=\s*['\"][^'\"]+['\"]/s", '', $message);
    return preg_replace("/:\s*$/s", "", $message);
}


/////////////////////////////////////// TRANSPORT //////////////////////////////////////

function _stripTagsXML($data, $name="")
{
    $ignore = array('merchantUrl', "type", "PaRes", "MD");
    if (in_array($name, $ignore)) {
        return $data;
    }

    switch ($name) {
        case "order_id":
            $data = preg_replace("/[^\w\d\_\-\:\.\@\ ]/i", " ", $data);
        break;

        case "commcard_invoice":
            $data = preg_replace("/[^\w\d ]/i", " ", $data);
        break;

        default:
            $data = preg_replace("/[^\w\d\_\-\:\.\@\$\=\/]/i", " ", $data);
        break;
    }

    return $data;
}

///////////////////////////////////// MPG methods //////////////////////////////////////////

function func_eSelect_mpgTransactionXML($txn, $custInfo=null, $avsInfo=null, $cvdInfo=null, $recurInfo=null, $pre="us_")
{
    $txnTypes =array(
        $pre."preauth"	=> array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type'),
        $pre."completion"	=> array('order_id', 'comp_amount','txn_number', 'crypt_type', 'commcard_invoice','commcard_tax_amount'),
        $pre."purchase"	=> array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type', 'commcard_invoice','commcard_tax_amount'),
        $pre."purchasecorrection"	=> array('order_id', 'txn_number', 'crypt_type'),
        $pre."refund"			=> array('order_id', 'amount', 'txn_number', 'crypt_type'),
        $pre."ind_refund"		=> array('order_id','cust_id', 'amount','pan','expdate', 'crypt_type'),
        $pre."cavv_preauth"	=> array('order_id','cust_id', 'amount', 'pan','expdate', 'cavv'),
        $pre."cavv_purchase"	=> array('order_id','cust_id','amount','pan','expdate', 'cavv', 'commcard_invoice','commcard_tax_amount'),
        $pre."track2_preauth"	=> array('order_id','cust_id','amount','track2','pan','expdate','pos_code'),
        $pre."track2_completion"	=> array('order_id', 'comp_amount','txn_number','pos_code', 'commcard_invoice','commcard_tax_amount'),
        $pre."track2_purchase"	=>array('order_id','cust_id','amount','track2','pan','expdate', 'commcard_invoice','commcard_tax_amount','pos_code'),
        $pre."track2_purchasecorrection"	=> array('order_id', 'txn_number'),
        $pre."track2_refund"		=> array('order_id', 'amount', 'txn_number'),
        $pre."track2_ind_refund"	=> array('order_id','amount','track2','pan','expdate','cust_id','pos_code'),
        $pre."opentotals"	=> array('ecr_number'),
        $pre."batchclose"	=> array('ecr_number')
    );


    $xmlString = "";

//	foreach ($transactions as $txn) {
        $txnType = array_shift($txn);
//		$tmpTxnTypes = $this->txnTypes;
        $tmpTxnTypes = $txnTypes;
        $txnTypeArray = $tmpTxnTypes[$txnType];
        $txnTypeArrayLen = count($txnTypeArray); //length of a specific txn type

        $txnXMLString = "";

        for ($i = 0; $i < $txnTypeArrayLen; $i++) {
            $txnXMLString .= "<$txnTypeArray[$i]>"   //begin tag
            ._stripTagsXML($txn[$txnTypeArray[$i]], $txnTypeArray[$i]) // data
            ."</$txnTypeArray[$i]>"; //end tag
        }

        $txnXMLString = "<$txnType>$txnXMLString";

        if (is_array($recurInfo) && count($recurInfo) > 0) {
            $txnXMLString .= func_eSelect_mpgRecurringInfo($recurInfo);
        }

        if (is_array($avsInfo) && count($avsInfo) > 0) {
            $txnXMLString .= func_eSelect_mpgAvsInfo($avsInfo);
        }

        if (is_array($cvdInfo) && count($cvdInfo) > 0) {
            $txnXMLString .= func_eSelect_mpgCvdInfo($cvdInfo);
        }

        if (is_array($custInfo) && count($custInfo) > 0) {
            $txnXMLString .= func_eSelect_mpgCustInfo($custInfo);
        }

        $txnXMLString .="</$txnType>";
        $xmlString .=$txnXMLString;
//	}

    return $xmlString;
}

function func_eSelect_mpgCustInfo($params)
{
    $txnType = "cust_info";

    $level3template = array(
        "cust_info"=>array('email','instructions',
            "billing"=>array('first_name', 'last_name', 'company_name', 'address', 'city', 'province', 'postal_code', 'country', 'phone_number', 'fax','tax1', 'tax2','tax3', 'shipping_cost'),
            "shipping"=>array('first_name', 'last_name', 'company_name', 'address', 'city', 'province', 'postal_code', 'country', 'phone_number', 'fax','tax1', 'tax2', 'tax3', 'shipping_cost'),
            "item"=>array ('name', 'quantity', 'product_code', 'extended_amount')
        )
    );

    return _toXML_low($params, $level3template, "cust_info");

}

function _toXML_low($level3data, $template, $txnType)
{
    $xmlString = "";

    for ($x = 0; $x < count($level3data[$txnType]); $x++) {
        if ($x > 0) {
            $xmlString .="</$txnType><$txnType>";
        }
        $keys=array_keys($template);
        for ($i = 0; $i < count($keys); $i++) {
            $tag=$keys[$i];

            if (is_array($template[$keys[$i]])) {
                $data=$template[$tag];

                if (!count($level3data[$tag])) {
                    continue;
                }
                $beginTag = "<$tag>";
                $endTag = "</$tag>";

                $xmlString .= $beginTag;

                #if(is_array($data))
                {
                    $returnString = _toXML_low($level3data, $data, $tag);
                    $xmlString .= $returnString;
                }

                $xmlString .=$endTag;
            } else {
                $tag = $template[$keys[$i]];
                $beginTag = "<$tag>";
                $endTag = "</$tag>";
                $data = $level3data[$txnType][$x][$tag];

                $xmlString .= $beginTag._stripTagsXML($data, $tag).$endTag;
            }

        }//end inner for

    }//end outer for

    return $xmlString;
}


function func_eSelect_mpgRecurringInfo($params)
{
    $recurTemplate = array('recur_unit','start_now','start_date','num_recurs','period','recur_amount');

    $xmlString = "";
    foreach ($recurTemplate as $tag) {
        $xmlString .= "<$tag>"._stripTagsXML($params[$tag], $tag)."</$tag>";
    }

    return "<recur>$xmlString</recur>";
}

function func_eSelect_mpgAvsInfo($params)
{
    $avsTemplate = array('avs_street_number','avs_street_name','avs_zipcode');

    $xmlString = "";
    foreach ($avsTemplate as $tag) {
        $xmlString .= "<$tag>"._stripTagsXML($params[$tag], $tag)."</$tag>";
    }

    return "<avs_info>$xmlString</avs_info>";
}

function func_eSelect_mpgCvdInfo($params)
{
    $cvdTemplate = array('cvd_indicator','cvd_value');

    $xmlString = "";
    foreach ($cvdTemplate as $tag) {
        $xmlString .= "<$tag>"._stripTagsXML($params[$tag], $tag)."</$tag>";
    }

    return "<cvd_info>$xmlString</cvd_info>";
}


function func_eSelect_mpgSendRequest(&$payment, &$data)
{
    $params = $payment->get('params');

    $xmlString .= "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><request><store_id>".$params['store_id']."</store_id><api_token>".$params['api_token']."</api_token>$data</request>";

    $request = new XLite_Model_HTTPS();
    $request->data          = $xmlString;
    $request->method        = 'POST';
    $request->conttype      = "text/xml";
    $request->urlencoded    = true;
    $request->url = $payment->get('monerisMPG_URL');

if (ESELECT_MPG_DEBUG_LOG) {
$payment->xlite->logger->log("send MPG request:");
$payment->xlite->logger->log("URL: ".$request->url);
$payment->xlite->logger->log("REQUEST: ".$request->data);
}

    $request->request();
    $response = $request->response;

if (ESELECT_MPG_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE: ".$request->response."\n");
}

    if (!$response || $request->error) {
        $response = <<<EOT
<?xml version=\"1.0\"?><response><receipt>
<ReceiptId>Global Error Receipt</ReceiptId>
<ReferenceNum>null</ReferenceNum><ResponseCode>null</ResponseCode>
<ISO>null</ISO> <AuthCode>null</AuthCode><TransTime>null</TransTime>
<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>
<Message>Global Error Receipt</Message><TransAmount>null</TransAmount>
<CardType>null</CardType>
<TransID>null</TransID><TimedOut>null</TimedOut>
<CorporateCard>false</CorporateCard><MessageId>null</MessageId>
</receipt></response>
EOT;
    }

    return func_eSelect_xmlToArray($response);
}


///////////////////////////////////// MPI methods //////////////////////////////////////////
// VBV support

function func_eSelect_mpiTransactionXML(/*$transactions*/$txn)
{
    $txnTypes =array(
        txn	=> array('xid', 'amount', 'pan', 'expdate','MD', 'merchantUrl','accept','userAgent','currency','recurFreq', 'recurEnd','install'),
        acs	=> array('PaRes','MD')
    );

    $xmlString = "";

//	foreach ($transactions as $txn) {
        $txnType = array_shift($txn);
        $tmpTxnTypes = $txnTypes;
        $txnTypeArray = $tmpTxnTypes[$txnType];
        $txnTypeArrayLen = count($txnTypeArray); //length of a specific txn type

        $txnXMLString = "";
        for ($i = 0; $i < $txnTypeArrayLen; $i++) {
            $txnXMLString .= "<$txnTypeArray[$i]>"   //begin tag
                ._stripTagsXML($txn[$txnTypeArray[$i]], $txnTypeArray[$i]) // data
                ."</$txnTypeArray[$i]>"; //end tag
        }

        $txnXMLString = "<$txnType>$txnXMLString";
        $txnXMLString .= "</$txnType>";
        $xmlString .= $txnXMLString;
//	}

    return $xmlString;
}


function func_eSelect_mpiSendRequest(&$payment, &$data)
{
    $params = $payment->get('params');

    $xmlString = "<?xml version=\"1.0\"?><MpiRequest><store_id>".$params['store_id']."</store_id><api_token>".$params['api_token']."</api_token>$data</MpiRequest>";

    $request = new XLite_Model_HTTPS();
    $request->data          = $xmlString;
    $request->method        = 'POST';
    $request->conttype      = "text/xml";
    $request->urlencoded    = true;

    $request->url = $payment->get('monerisMPI_URL');

if (ESELECT_MPI_DEBUG_LOG) {
$payment->xlite->logger->log("send MPI request:");
$payment->xlite->logger->log("URL: ".$request->url);
$payment->xlite->logger->log("REQUEST: ".$request->data);
}

    $request->request();
    $response = $request->response;

if (ESELECT_MPI_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE: ".$request->response."\n");
}

    if (!$response || $request->error) {
        $response = <<<EOT
<?xml version=\"1.0\"?>
<MpiResponse>
<type>null</type>
<success>false</success>
<message>null</message>
<PaReq>null</PaReq>
<TermUrl>null</TermUrl>
<MD>null</MD>
<ACSUrl>null</ACSUrl>
<cavv>null</cavv>
PAResVerified>null</PAResVerified>
</MpiResponse>
EOT;
    }

    return func_eSelect_xmlToArray($response);
}

function func_eSelect_getMpiInLineForm($responseData)
{
$inLineForm ='
<html>
<head>
<title>Processing your 3-D Secure Transaction</title>
</head><SCRIPT LANGUAGE="Javascript" >' .
"<!--
function OnLoadEvent()
{
document.downloadForm.submit();
}
-->
</SCRIPT>" .
'<body onload="OnLoadEvent()">
<form name="downloadForm" action="' . $responseData['ACSURL'] . '" method="POST">
<noscript>
<br>
<br>
<center>
<h1>Processing your 3-D Secure Transaction</h1>
<h2>
JavaScript is currently disabled or is not supported
by your browser.<br>
<h3>Please click on the Submit button to continue
the processing of your 3-D secure
transaction.</h3>
<input type="submit" value="Submit">
</center>
</noscript>
<input type="hidden" name="PaReq" value="' . $responseData['PAREQ'] . '">
<input type="hidden" name="MD" value="' . $responseData['MD'] . '">
<input type="hidden" name="TermUrl" value="' . $responseData['TERMURL'] .'">
</form>
</body>
</html>';

return $inLineForm;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

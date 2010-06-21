<?php
/*
* Hidden methods
*/
function PaymentMethod_2checkout_handleRequest($_this, $cart)
{
    // PaymentMethod::2checkout_handleRequest() code
    
    $params = $_this->params;
    $status = "P";
    $error = null;

    $cart->setComplex('details.x_trans_id', $_POST['x_trans_id']);
    $cart->set('detailLabels.x_trans_id', "2Checkout.com Transaction ID");
    $cart->setComplex('details.x_response_code', $_POST['x_response_code']);
    $cart->set('detailLabels.x_response_code', "2Checkout.com Responce Code");
    $cart->setComplex('details.x_response_subcode', $_POST['x_response_subcode']);
    $cart->set('detailLabels.x_response_subcode', "2Checkout.com Responce Subcode");
    $cart->setComplex('details.x_response_reason_code', $_POST['x_response_reason_code']);
    $cart->set('detailLabels.x_response_reason_code', "2Checkout.com Responce Reason Code");

    if (isset($_POST['x_amount'])) {
    	$total = sprintf("%.2f", $cart->get('total'));
        $postTotal = sprintf("%.2f", $_POST['x_amount']);
        if ($total != $postTotal) {
            $cart->set('details.error', "Hacking attempt!");
            $cart->setComplex('detailLabels.error', "Error");
            $cart->set('details.errorDescription', "Total amount doesn't match: Order total=".$total.", 2Checkout amount=".$postTotal);
            $cart->set('detailLabels.errorDescription', "Hacking attempt details");
            $cart->set('status',"F");
            $cart->update();

            $_this->xlite->session->writeClose();

            die("<font color=red><b>Security check failed!</b></font> Please contact administrator <b>" . $_this->config->Company->site_administrator . "</b> .");
        }
    }

    // md5 hash check
    if (isset($_POST['x_Login'])) $x_Login = $_POST['x_Login'];
    if (isset($_POST['x_login'])) $x_Login = $_POST['x_login'];

    /* md5 changed in 2Checkout; can't use this procedure any more; */
    /*
    $md5 = md5($params->md5HashValue . $x_Login . $_POST['order_number'] . $_POST['x_amount']);

    if (strcasecmp($md5, $_POST['x_MD5_Hash'])) {
        // MD5 mismatch
        die("MD5 hash is invalid: " . $_POST['x_MD5_Hash'] . '. Please contact administrator');
    } else {
    */
        if ($_POST['x_response_code'] == 1) {
            // success
            $status = "P";
        } else {
            // failure
            $status = "F";
        }
    // }
    if (isset($_POST['x_cvv_code'])) {
        $cart->setComplex('details.cvvMessage', $_this->cvverr[$_POST['x_cvv_code']]);
        $cart->set('detailLabels.cvvMessage', "CVV message");
    } else {
        $details = $cart->get('details');
        if (isset($details['cvvMessage'])) {
        	unset($details['cvvMessage']);
        }
        $cart->set('details', $details);
        $details = $cart->get('detailLabels');
        if (isset($details['cvvMessage'])) {
        	unset($details['cvvMessage']);
        }
        $cart->set('detailLabels', $details);
    }
    if (isset($_POST['x_avs_code'])) {
        $cart->setComplex('details.avsMessage', $_this->avserr[$_POST['x_avs_code']]);
        $cart->set('detailLabels.avsMessage', "AVS message");
    } else {
        $details = $cart->get('details');
        if (isset($details['avsMessage'])) {
        	unset($details['avsMessage']);
        }
        $cart->set('details', $details);
        $details = $cart->get('detailLabels');
        if (isset($details['avsMessage'])) {
        	unset($details['avsMessage']);
        }
        $cart->set('detailLabels', $details);
    }
    $cart->setComplex('details.error', $error);
    $cart->setComplex('detailLabels.error', "Error");
    $cart->set('status', $status);
    $cart->update();
    $_this->xlite->session->writeClose();

    $location = "cart.php?target=checkout&action=return&order_id=".$cart->get('order_id');
    $location = $cart->xlite->getShopUrl($location);
    //header("Location: $location");
    PaymentMethod_2checkout_html_location($location);
}

function PaymentMethod_2checkout_v2_handleRequest($_this, $cart, $security_check=true)
{
    // PaymentMethod::2checkout_handleRequest() code
    
    $params = $_this->params;
    $status = "P";
    $error = null;

    $response_code = ($_POST['credit_card_processed'] == "Y") ? 1 : (($_POST['credit_card_processed'] == "K") ? 3 : 2);
    switch ($response_code) {
        case 1:
            // success
            $status = "P";
        break;
        default:
            // failure
            $status = "F";
        break;
    }
    if (!$security_check) {
        $status = "F";
        $cart->setComplex('details.security_check', "FAILED!");
        $cart->set('detailLabels.security_check', "2Checkout.com Order Security Check");
        $cart->set('details.error', "Security Check Failed!");
        $cart->setComplex('detailLabels.error', "Error");
    }

    if (!empty($_POST['tcoid']))	{
        $cart->setComplex('details.tcoid', $_POST['tcoid']);
        $cart->set('detailLabels.tcoid', "2Checkout.com Transaction ID");
    }
    if (!empty($_POST['order_number'])) {
        $cart->setComplex('details.order_number', $_POST['order_number']);
        $cart->set('detailLabels.order_number', "2Checkout.com Order number");
    }
    if (isset($_POST['2co_product_id'])) {
        $_POST['product_id'] = $_POST['2co_product_id'];
        unset($_POST['2co_product_id']);
    }

    $full_response = array();
    foreach ($_POST as $k=>$v) {
        $full_response[] = "\"$k\": $v";
    }
    $full_response = implode(", ", $full_response);

    $cart->setComplex('details.full_response', $full_response);
    $cart->set('detailLabels.full_response', "2Checkout.com Full response");

    $cart->set('status', $status);
    $cart->update();
    $_this->xlite->session->writeClose();

    if (!$security_check) {
        die("<font color=red><b>Security check failed!</b></font> Please contact administrator <b>" . $_this->config->Company->site_administrator . "</b> .<hr>Click <a href=\"" . $_this->xlite->getShopUrl("cart.php?target=checkout&mode=error&order_id=".$cart->get('order_id')) . "\"><u>here</u></a> to return into your cart.");
    }

    $location = "cart.php?target=checkout&action=return&order_id=".$cart->get('order_id');
    $location = $cart->xlite->getShopUrl($location);
    //header("Location: $location");
    PaymentMethod_2checkout_html_location($location);
}

function PaymentMethod_2checkout_html_location($url, $redirectTime=3)
{
    echo "<BR><BR>";
    echo "If the page is not updated in a $redirectTime seconds, please follow this link: <A href=\"" . $url . "\"><u>continue &gt;&gt;</u></A>";
    echo "<META http-equiv=\"Refresh\" content=\"$redirectTime;URL=$url\">";

    if (preg_match("/Apache(.*)Win/", getenv('SERVER_SOFTWARE'))) {
        echo str_repeat(' ', 2500);
    } elseif (preg_match("/(.*)MSIE(.*)\)$/", getenv('HTTP_USER_AGENT'))) {
        echo str_repeat(' ', 256);
    }

    if (function_exists('ob_flush')) {
        // for PHP >= 4.2.0
        ob_flush();
    }
    else {
        // for PHP < 4.2.0
        if (ob_get_length() !== FALSE) {
            ob_end_flush();
        }
    }

    flush();

    exit;
}

?>

<?php

function PaymentMethod_securetrading_handleRequest(&$pm, $order, $debug = false)
{
    $order->xlite->logger->log("SecureTrading: STResult=" . $_REQUEST['stresult'] . ", RemoteAddr=" . $_SERVER['REMOTE_ADDR']);

    $order->setComplex("details.error", null);
    if 
    (
        $_REQUEST['stresult'] == 1
        &&
        isset($_SERVER['REMOTE_ADDR'])
        &&
        (
            preg_match('/^195.224.56/i', $_SERVER['REMOTE_ADDR'])
            ||
            preg_match('/^194.74.4/i', $_SERVER['REMOTE_ADDR'])
            ||
            preg_match('/^213.205.139/i', $_SERVER['REMOTE_ADDR'])
        )
    )	{
        $status = "P";
    } else {
      	$status="F";
      	$order->set("details.error", "Your order is failed");
    }
    
    $order->setComplex("details.authcode", $_REQUEST['stauthcode']);
    $order->setComplex("detailLabels.authcode", "AuthCode");
    $order->setComplex("details.reference", $_REQUEST['streference']);
    $order->setComplex("detailLabels.reference", "Reference");
    $order->set("status", $status);
    $order->update();
}

?>

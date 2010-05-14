<?php

/*
* Hidden methods
*
* @version $Id$
*/

function PaymentMethod_PayFlowLink_handleRequest($_this, $cart)
{
    $params = $_this->params;
    $error = null;

##################################
$cart_details = $cart->get('details');
$cart_labels = array("connectionAttempts" => "Connection attempts","error"=>"Error");
if ($cart_details['connectionAttempts']) $cart_details['connectionAttempts'] = 1; else $cart_details['connectionAttempts']++;
$cart_details['error'] = $_GET['resp'];
$cart->set('details', $cart_details);
$cart->set('detailLabels', $cart_labels);
##################################

    $cart->set('status', "I");
    $cart->update();
    $_this->session->set('order_id', $cart->get('order_id'));
    $_this->session->writeClose();

    $cart->setComplex('details.authcode', $_GET['acode']); $cart->setComplex('detailLabels.authcode', "AuthCode");
    $cart->setComplex('details.reference', $_GET['ref']); $cart->setComplex('detailLabels.reference', "Reference");
    $cart->setComplex('details.reason', $_GET['resp']); $cart->setComplex('detailLabels.reason', "Response");
    
    $status = ($_GET['result']==0 && $_GET['resp']!="CSCDECLINED" && $_GET['resp']!="AVSDECLINED") ? "P" : "F";
    if ($_GET['result']==126) $status = "Q";
    $cart->set('status', $status);

    $cart->update();
    header("Location: cart.php?target=checkout&action=return&order_id=".$cart->get('order_id'));
}

?>
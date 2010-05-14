<?php

/**
*
* @package Module_ChronoPay
* @version $Id$
*/

/*
* Hidden methods
*/
function PaymentMethod_chronopay_handleRequest($_this, $cart)
{
    $answer = array(
        'onetime' => 'One time payment has been made, no repayment required',
        'initial' => 'First payment has been made, repayment required in corresponding period',
        'decline' => 'Charge request has been rejected',
        'rebill' => 'Repayment has been made together with initial transaction',
        'rebill' => 'repayments has been disabled',
        'expire' => 'customer\'s access to restricted zone membership has been expired',
        'refund' => 'request to refund has been received',
        'chargeback' => 'Request to chargeback has been received',
    );

    // PaymentMethod::chronopay_handleRequest() code
    $transaction_id = $_REQUEST['transaction_id'];
    $transaction_type = $_REQUEST['transaction_type'];
    if (!$_REQUEST['error'] && isset($_REQUEST['cs1']) && is_numeric($_REQUEST['cs1']) && $_REQUEST['cs1'] > 0 && isset($_REQUEST['cs2']) && $_REQUEST['cs2'] == "chronopay")  {
        if ($transaction_type == 'onetime' || $transaction_type == 'initial' || $transaction_type== 'rebill') {
            $cart->setComplex('details.transaction_type', $_REQUEST['transaction_type']);
            $cart->set('detailLabels.transaction_type', 'Transaction Type');
            $cart->setComplex('details.transaction_id', $_REQUEST['transaction_id']);
            $cart->set('detailLabels.transaction_id', 'Transaction ID');
            $cart->setComplex('details.customer_id', $_REQUEST['customer_id']);
            $cart->set('detailLabels.customer_id', 'Customer ID');

            $cart->set('status', 'P');
            $cart->update();
        } else {
            $cart->setComplex('details.error', $this->answer[$transaction_type]);
            $cart->setComplex('detailLabels.error', 'Error');
            $cart->set('status', 'F');
            $cart->update();
        }
    }else {
        $cart->set('details.error', 'Payment Error'.$_REQUEST['error_descr']);
        $cart->setComplex('detailLabels.error', 'Error');
        $cart->set('status', 'F');
        $cart->update();
    }
}


function PaymentMethod_chronopay_callback()
{
    if (isset($_REQUEST['cs1']) && is_numeric($_REQUEST['cs1']) && $_REQUEST['cs1'] > 0 && isset($_REQUEST['cs2']) && $_REQUEST['cs2'] == "chronopay") {
        $cart = new XLite_Model_Order($_REQUEST['cs1']);
        $pm = $cart->get('paymentMethod');

        # security issue
        if ($pm->get('payment_method') != "chronopay" || !$pm->getComplex('params.secure_ip') || !isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] != $pm->getComplex('params.secure_ip')) {
            $_REQUEST['error_descr'] = " (Wrong ChronoPay payment gateway IP)";
            $_REQUEST['error'] = "1";
        }

        if (isset($_REQUEST['amp;action']))
            $_REQUEST['action'] = $_REQUEST['amp;action'];
        if (isset($_REQUEST['amp;order_id']))
            $_REQUEST['order_id'] = $_REQUEST['amp;order_id'];
    }
}

///////////////////////////
// Тестовые данные:
///////////////////////////
// 
// username: LiteCommerce
// password: 8OeAR3jaIdtqX
// 
// User id: 003001-0001
// Prod. Id: 003001-0002-0002 - продукт нужно настроить под магазин.
// 
// 
// The test credit card has been activated:
// Credit card #: 4296010582436758
// CVV: any
// Expiry date: now + 1 month

?>
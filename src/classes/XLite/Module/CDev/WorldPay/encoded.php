<?php
    function func_PaymentMethod_worldpay_handleRequest($_this, $cart)
    {
        $status = "P";

        $cart->setComplex('details.transStatus', $_POST['transStatus']);
        $cart->set('detailLabels.transStatus', "Transaction Status");

        if ($_POST['transStatus'] === 'Y') {
            // success
            $status = "P";
        } else {
            // failure
            $status = "F";
        }
        if (isset($_POST['rawAuthMessage'])) {
            $cart->setComplex('details.rawAuthMessage', $_POST['rawAuthMessage']);
            $cart->set('detailLabels.rawAuthMessage', "Raw Auth Message");
        }
        if (isset($_POST['transId'])) {
            $cart->setComplex('details.transId', $_POST['transId']);
            $cart->set('detailLabels.transId', "Transaction ID");
        }

        if (isset($_POST['authAmount']) && $_this->getComplex('params.check_total')) {
    		$total = $cart->get('total');
            if ($total != $_POST['authAmount']) {
                $cart->set('details.error', "Hacking attempt!");
                $cart->setComplex('detailLabels.error', "Error");
                $cart->set('details.errorDescription', "Total amount doesn't match: Order total=".$total.", RBS WorldPay amount=".$_POST['authAmount']);
                $cart->set('detailLabels.errorDescription', "Hacking attempt details");
            	$status = "F";
            }
        }
        if (isset($_POST['authCurrency']) && $_this->getComplex('params.check_currency')) {
            $currency = $_this->getComplex('params.currency');
            if ($currency != $_POST['authCurrency']) {
                $cart->set('details.error', "Hacking attempt!");
                $cart->setComplex('detailLabels.error', "Error");
                $cart->set('details.errorDescription', "Currency code doesn't match: Order currency=".$currency.", RBS WorldPay currency=".$_POST['authCurrency']);
                $cart->set('detailLabels.errorDescription', "Hacking attempt details");
    			$status = "F";
            }
        }

        $cart->set('status', $status);
        $cart->update();

        $backUrl = $cart->xlite->getShopUrl("cart.php?target=checkout&action=return&order_id=".$cart->get('order_id'), $cart->config->Security->customer_security);
?>
<html>
<body onLoad="javascript: document.location = '<?php echo $backUrl;?>'">
If you are not redirected within 5 seconds, please <a href="<?php echo $backUrl;?>">click here to return to the shopping cart</a>.
</body>
</html>
<?php
    	exit();
    }
?>

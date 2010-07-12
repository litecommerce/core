<?php

    function PaymentMethod_nochex_handleRequest($order,$_this)
    {
        $order->setComplex('details.error', null);
        $order->setComplex('detailLabels.error', null);
        $order->setComplex('details.errorDescription', null);
        $order->setComplex('detailLabels.errorDescription', null);

        if ($_POST['to_email'] != $_this->getComplex('params.param01')) {
            $order->set('status',"F");
            $order->set('detailLabels.error', "Error :");
            $order->set('details.error', "Fraud attempt");
        } elseif ($_POST['transaction_id'] == $order->getComplex('details.transaction_id')) {
         	$order->set('status',"F");
            $order->setComplex('detailLabels.error', "Error");
            $order->set('details.error', "Duplicate transaction : " . $_POST['transaction_id']);
        } else {
            $request = new \XLite\Model\HTTPS();
    	    $request->url = "https://www.nochex.com:443/nochex.dll/apc/apc";
        	$request->data = $_POST;
            $request->request();
            $request->response = trim($request->response);
            if ($request->error) {
                $order->setComplex('details.error', $request->error);
                $order->set('detailLabels.error',"HTTPS Error");
                $order->set('status',"F");
            } else {
                $order->setComplex('details.status', $request->response);
                $order->set('detailLabels.status', "APC response :");
                $order->setComplex('details.transaction_id', $_POST['transaction_id']);
    		    $order->set('detailLabels.transaction_id', "Transaction ID :");
                if (preg_match('/AUTHORISED/S',$request->response))
                    $order->set('status',"P");
                else
                    $order->set('status',"F");
            }
        	if (isset($_POST['amount'])) {
        		$total = sprintf("%.2f", doubleval($order->get('total')));
        		$totalPost = sprintf("%.2f", doubleval($_POST['amount']));
                if ($total != $totalPost) {
                    $order->set('details.error', "Hacking attempt!");
                    $order->setComplex('detailLabels.error', "Error");
                    $order->set('details.errorDescription', "Total amount doesn't match: Order total=".$total.", Nochex amount=".$totalPost);
                    $order->set('detailLabels.errorDescription', "Hacking attempt details");
                    $order->set('status',"F");
                }
            }
        }
        $order->update();
    }
?>

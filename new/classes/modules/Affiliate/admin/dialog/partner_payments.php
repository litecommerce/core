<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_Affiliate
* @access public
* @version $Id$
*
*/
class Admin_Dialog_partner_payments extends Admin_Dialog
{
    var $crlf = "\r\n";
    var $hasReady = false;
    var $found = 0;

    function handleRequest()
    {
        if (substr($this->action, 0, 14) == "payment_upload" && !$this->checkUploadedFile()) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }    
    
    function action_export_payments() // {{{
    {
        $w = func_new("Widget");
        $w->component =& $this;
        $w->set("template", "modules/Affiliate/payments.tpl");
        $this->startDownload("payments.csv");
        $w->init();
        $w->display();

        // do not output anything
        $this->set("silent", true);
    } // }}}

    function &getDelimiter() // {{{
    {
        global $DATA_DELIMITERS;
        return $DATA_DELIMITERS[$this->delimiter];
    } // }}}

    function action_payment_upload() // {{{
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => array("order_id", "paid"),
            "delimiter"         => $this->delimiter,
			"return_error"		=> true,
            );
        $p =& func_new("PartnerPayment");
        $p->import($options);
		$this->importError = $p->importError;

		$text = "Upload process failed.";
		if (!$this->importError) $text = "Payments uploaded successfully.";
		$text = $this->importError.'<br>'.$text.' <a href="admin.php?target=partner_payments"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
    } // }}}

    function action_mark_paid() // {{{
    {
        foreach ((array)$this->get("payment_paid") as $id) {
            $p =& func_new("PartnerPayment");
            $p->pay($id);
        }
    } // }}}

    function &getPayments() // {{{
    {
        if (is_null($this->payments)) {
            $this->payments = array();
            $pp =& func_new("PartnerPayment");
            $payments = $pp->findAll();
            // summarize payments
            array_map(array(&$this, 'summarize'), $payments);
            if ($this->get("account_filter") == "ready") {
                $payments2 = array();
                foreach ($this->payments as $payment) {
                    if ($payment["ready"]) {
                        $payments2[] = $payment;
                    }
                }
                $this->payments = $payments2;
            }
            $this->found = count($this->payments);
        }
        return $this->payments;
    } // }}}
    
    function summarize($payment) // {{{
    {
        $id = $payment->get("partner_id");
        if (!isset($this->payments[$id])) {
            $this->payments[$id] = array();
            $this->payments[$id]["partner"] = $payment->get("partner");
            $this->payments[$id]["min_limit"] = $payment->get("partner.partnerPlan.payment_limit");
            $this->payments[$id]["approved"] = 0;
            $this->payments[$id]["pending"] = 0;
            $this->payments[$id]["paid"] = 0;
            $this->payments[$id]["ready"] = false;
        }
        if (!$payment->is("order.processed")) {
            $this->payments[$id]["pending"] = sprintf("%.02f", doubleval($this->payments[$id]["pending"] + $payment->get("commissions")));
        } elseif ($payment->get("paid")) {
            $this->payments[$id]["paid"] = sprintf("%.02f", doubleval($this->payments[$id]["paid"] + $payment->get("commissions")));
        } elseif (!$payment->get("paid")) {
            $this->payments[$id]["approved"] = sprintf("%.02f", doubleval($this->payments[$id]["approved"] + $payment->get("commissions")));
            $this->payments[$id]["ready"] = ($this->payments[$id]["approved"] >= $this->payments[$id]["min_limit"]);
            if ($this->payments[$id]["ready"]) {
                $this->hasReady = true;
            }
        }
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

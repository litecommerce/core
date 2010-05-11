<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_Controller_Admin_PartnerPayments extends XLite_Controller_Admin_Abstract
{	
    public $crlf = "\r\n";	
    public $hasReady = false;	
    public $found = 0;

    function handleRequest()
    {
        if (substr($this->action, 0, 14) == "payment_upload" && !$this->checkUploadedFile()) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }    
    
    function action_export_payments() 
    {
        $w = new XLite_View_Abstract();
        $w->component = $this;
        $w->set("template", "modules/Affiliate/payments.tpl");
        $this->startDownload("payments.csv");
        $w->init();
        $w->display();

        // do not output anything
        $this->set("silent", true);
    } 

    function getDelimiter() 
    {
        global $DATA_DELIMITERS;
        return $DATA_DELIMITERS[$this->delimiter];
    } 

    function action_payment_upload() 
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => array("order_id", "paid"),
            "delimiter"         => $this->delimiter,
			"return_error"		=> true,
            );
        $p = new XLite_Module_Affiliate_Model_PartnerPayment();
        $p->import($options);
		$this->importError = $p->importError;

		$text = "Upload process failed.";
		if (!$this->importError) $text = "Payments uploaded successfully.";
		$text = $this->importError.'<br>'.$text.' <a href="admin.php?target=partner_payments"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
    } 

    function action_mark_paid() 
    {
        foreach ((array)$this->get("payment_paid") as $id) {
            $p = new XLite_Module_Affiliate_Model_PartnerPayment();
            $p->pay($id);
        }
    } 

    function getPayments() 
    {
        if (is_null($this->payments)) {
            $this->payments = array();
            $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
            $payments = $pp->findAll();
            // summarize payments
            array_map(array($this, 'summarize'), $payments);
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
    } 
    
    function summarize($payment) 
    {
        $id = $payment->get("partner_id");
        if (!isset($this->payments[$id])) {
            $this->payments[$id] = array();
            $this->payments[$id]["partner"] = $payment->get("partner");
            $this->payments[$id]["min_limit"] = $payment->getComplex('partner.partnerPlan.payment_limit');
            $this->payments[$id]["approved"] = 0;
            $this->payments[$id]["pending"] = 0;
            $this->payments[$id]["paid"] = 0;
            $this->payments[$id]["ready"] = false;
        }
        if (!$payment->isComplex('order.processed')) {
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
    } 
}

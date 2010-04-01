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
* This implementation complies the following documentation: <br>
* Select Junior Integration Guide [Edition 1.22 for Software Version 4.4]
* http://support.worldpay.com/kb/integration_guides/junior/integration/help/sjig.html
*
* @package Module_WorldPay
* @access public
* @version $Id$
*/
class XLite_Module_WorldPay_Model_PaymentMethod_Worldpay extends XLite_Model_PaymentMethod_CreditCard
{	
	public $configurationTemplate = "modules/WorldPay/config.tpl";	
	public $formTemplate = "modules/WorldPay/checkout.tpl";	
	public $processorName = "RBS WorldPay";	
	public $hasConfugurationForm = true;

    function handleRequest(XLite_Model_Cart $cart)
    {
		require_once LC_MODULES_DIR . 'WorldPay' . LC_DS . 'encoded.php';
        func_PaymentMethod_worldpay_handleRequest($this, $cart);
    }

    function getWorldPayURL()
    {
        return ($this->getComplex('params.test') == "N") ? 'https://select.wp3.rbsworldpay.com/wcc/purchase' : "https://select-test.wp3.rbsworldpay.com/wcc/purchase";
    }

    function getTestMode()
    {
        return ($this->getComplex('params.test') == "N") ? "0" : "100";
    }

    function getCartId($oid)
    {
        return $this->getComplex('params.prefix').$oid;
    }

    function getNameField($cart)
    {
        switch ($this->getComplex('params.test')) {
            case 'A':
                $result = 'AUTHORISED';
                break;
            case 'R':
                $result = 'REFUSED';
                break;
            case 'E':
                $result = 'ERROR';
                break;
            case 'C':
                $result = 'CAPTURED';
                break;
            default:
                $result = $cart->profile->get("billing_firstname") . " " . $cart->profile->get("billing_lastname");
                break;
        }
        return $result;

    }

    /* calculate MD5 signature for transaction.
     * the same md5hashValue should be set on your WorldPay CMS. 
     */
    function getMD5Signature($cart)
    {
        if (!is_null($this->getComplex('params.md5HashValue'))) {
   
            $plain = $this->getComplex('params.md5HashValue') . ':' .
                $this->formatTotal($cart->get('total')) . ':' .
                $this->getComplex('params.currency') . ':' .
                $this->getCartId($cart->get('order_id'));
            $md5sum = md5($plain);
			$this->logger->log("Worldpay:getMD5Signature($plain): $md5sum");
            return $md5sum;
        }
        return NULL;
    }

    function formatTotal($total)
    {
        return number_format($total, 2, '.', '');
    }

	function handleConfigRequest()
	{
		if (!isset($_POST['params']['check_total'])) $_POST['params']['check_total'] = 0;
		if (!isset($_POST['params']['check_currency'])) $_POST['params']['check_currency'] = 0;
		parent::handleConfigRequest();
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

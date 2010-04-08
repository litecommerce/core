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
* AuthorizeNet processor unit. This implementation complies the following
* documentation: <br>
* http://www.authorizenet.com/support/guides.php
*
* @package AuthorizeNet
* @access public
* @version $Id$
*/
class XLite_Module_AuthorizeNet_Model_PaymentMethod_AuthorizenetCc extends XLite_Model_PaymentMethod_CreditCard
{	
    public $processor;	
    public $configurationTemplate = "modules/AuthorizeNet/config.tpl";	
    public $processorName = "Authorize.Net";

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->processor = new XLite_Module_AuthorizeNet_Processor();
    }
    
    function process($cart)
    {
        return $this->processor->process($cart, $this);
    }

    function handleConfigRequest()
    {
        return $this->processor->handleConfigRequest();
    }

    function initRequest($cart, &$request)
    {
        $request->data['x_Method'] = 'CC';
        $request->data['x_Card_Num'] = $this->cc_info["cc_number"];
        $request->data['x_Exp_Date'] = $this->cc_info["cc_date"];
        $cc_name = trim($this->cc_info["cc_name"]);
        if (strlen($cc_name)) {
            @list($fname, $lname) = explode(" ", $cc_name);
            if (is_string($fname) && is_string($lname)) {
                $request->data["x_First_Name"] = $fname;
                $request->data["x_Last_Name"]  = $lname;
            }    
        }
        if ($this->params['cvv2'] != '0') {
            $request->data['x_Card_Code'] = $this->cc_info["cc_cvv2"];
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

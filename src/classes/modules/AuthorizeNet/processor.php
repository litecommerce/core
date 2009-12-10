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
* @package Module_AuthorizeNet
* @access public
* @version $Id: processor.php,v 1.27 2008/10/23 11:51:58 sheriff Exp $
*/
class AuthorizeNet_processor extends Object
{
    var $cvverr = array(
            "M" => "Card Code matches",
            "N" => "Card Code does not match",
            "P" => "Card Code was not processed",
            "S" => "Card code should be on card but was not indicated",
            "U" => "Issuer was not certified for Card Code"
            );
    var $avserr = array(
            "A" => "Address (Street) matches, ZIP does not",
			"B"	=> "Address information not provided for AVS check",
            "E" => "AVS error",
			"G"	=> "Non-U.S. Card Issuing Bank ", 
            "N" => "No Match on Address (Street) or ZIP",
            "P" => "AVS not applicable for this transaction",
            "R" => "Please Retry. System unavailable or timed out",
            "S" => "AVS Service not supported by issuer",
            "U" => "Address information is unavailable",
            "W" => "9 digit ZIP matches, Address (Street) does not",
            "X" => "Address (Street) and 9 digit ZIP match",
            "Y" => "Address (Street) and 5 digit ZIP match",
            "Z" => "5 digit ZIP matches, Address (Street) does not"
            );

    function process(&$cart, &$paymentMethod)
    {
        require_once "modules/AuthorizeNet/encoded.php";
        AuthorizeNet_processor_process($this, $cart, $paymentMethod);
    }

    /**
    * Set the same parameters for both e-check & credit card methods.
    */
    function handleConfigRequest()
    {
        $params = $_POST["params"];
        $pm =& func_new("PaymentMethod", "authorizenet_cc");
        $pm->set("params", $params);
        $pm->update();
        $pm =& func_new("PaymentMethod", "authorizenet_ch");
        $pm->set("params", $params);
        $pm->update();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

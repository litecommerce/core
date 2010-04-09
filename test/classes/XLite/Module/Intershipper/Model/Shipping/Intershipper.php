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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Shipping_intershipper description.
*
* @package Module_Intershipper
* @access public
* @version $Id$
*/
class XLite_Module_Intershipper_Model_Shipping_Intershipper extends XLite_Model_Shipping_Online
{	
    public $error = "";	
    public $xmlError = false;	
    public $translations = array(
        "UGN" => "Ground (Non-Machinable)",
        "UGM" => "Ground (Machinable)",
        "UWE" => "World Wide Express",
        "UWP" => "Worldwide Express Plus",
        "UWX" => "World Wide Expedited",
        "UGD" => "Next Day Air");	
        
    public $carriers = array(    
        "DHL" => "DHL",
        "FDX" => "FedEx",
        "UPS" => "UPS",
        "USP" => "USPS");	

    public $configCategory = "Intershipper";	
    public $optionsFields = array("userid","password","delivery","pickup","length","width","height","dunit","packaging","contents","insvalue");

    function getModuleName()
    {
        return "Intershipper";
    }

    function getRates(XLite_Model_Order $order)
    {
        include_once LC_MODULES_DIR . 'Intershipper' . LC_DS . 'encoded.php';
        return Shipping_intershipper_getRates($this, $order);
    }

    function _prepareRequest($weight, $ZipOrigination, $CountryOrigination, 
                         $ZipDestination, $CountryDestination, $options, $cod)
    {
        $ZipOrigination = $this->_normalizeZip($ZipOrigination);
        $ZipDestination = $this->_normalizeZip($ZipDestination);

        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PEAR.php';
        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'HTTP' . LC_DS . 'Request2.php';

        $http = new HTTP_Request2("http://www.intershipper.com/Interface/Intershipper/XML/v2.0/HTTP.jsp");
        $http->_timeout = 5; // can't wait long when we are in shopping cart
        $http->_method = HTTP_REQUEST_METHOD_POST;
        $http->addPostData("Version","2.0.0.0");
        $http->addPostData("ShipmentID",""); // must be empty?
        $http->addPostData("QueryID","1");
        $http->addPostData("Username",$options->userid);
        $http->addPostData("Password",$options->password);
        $http->addPostData("TotalClasses","4");
        $http->addPostData("ClassCode1","GND");
        $http->addPostData("ClassCode2","1DY");
        $http->addPostData("ClassCode3","2DY");
        $http->addPostData("ClassCode4","3DY");
        $http->addPostData("DeliveryType",$options->delivery);
        $http->addPostData("ShipMethod",$options->pickup);
        $http->addPostData("OriginationPostal",$ZipOrigination);
        $http->addPostData("OriginationCountry",$CountryOrigination);
        $http->addPostData("DestinationPostal",$ZipDestination);
        $http->addPostData("DestinationCountry",$CountryDestination);
        $http->addPostData("Currency","USD");
        $http->addPostData("TotalPackages","1");
        $http->addPostData("BoxID1","box1");
        $http->addPostData("Weight1",$weight);
        $http->addPostData("WeightUnit1","OZ");
        $http->addPostData("Length1",$options->length);
        $http->addPostData("Width1",$options->width);
        $http->addPostData("Height1",$options->height);
        $http->addPostData("DimensionalUnit1",$options->dunit);
        $http->addPostData("Packaging1",$options->packaging);
        $http->addPostData("Contents1",$options->contents);
        $http->addPostData("Insurance1",$options->insvalue);
        $http->addPostData("TotalCarriers",4);
        $http->addPostData("CarrierCode1","UPS");
        $http->addPostData("CarrierCode2","FDX");
        $http->addPostData("CarrierCode3","USP");
        $http->addPostData("CarrierCode4","DHL");
        if ($cod) {
            $http->addPostData("Cod1",(int)($cod*100));
        }
        return $http;
    }

    function _queryRates($weight, $ZipOrigination, $CountryOrigination, 
                         $ZipDestination, $CountryDestination,$options, $cod)
    {
        $http = $this->_prepareRequest($weight, $ZipOrigination, 
            $CountryOrigination, $ZipDestination, $CountryDestination,$options, $cod);
        $http->sendRequest();
        $response = $http->getResponseBody();
        // parse response 
        if ($CountryDestination == $CountryOrigination) {
            $destination = "L"; // Local
        } else {
            $destination = "I"; // International
        }
        return $this->_parseResponse($response, $destination);
    }

    function cleanCache()
    {
        $this->_cleanCache("ints_cache");
    }
    
    function _parseResponse($response, $destination)
    {
        include_once LC_MODULES_DIR . 'Intershipper' . LC_DS . 'encoded.php';
        return Shipping_intershipper_parseResponse($this, $response, $destination);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

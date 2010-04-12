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

        require_once LC_EXT_LIB_DIR . 'PEAR.php';
        require_once LC_EXT_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

        $http = new HTTP_Request2('http://www.intershipper.com/Interface/Intershipper/XML/v2.0/HTTP.jsp', HTTP_Request2::METHOD_POST);
		$http->setConfig('timeout', 5);

        $http->addPostParameter('Version', '2.0.0.0');
        $http->addPostParameter('ShipmentID', ''); // must be empty?
        $http->addPostParameter('QueryID', 1);
        $http->addPostParameter('Username', $options->userid);
        $http->addPostParameter('Password', $options->password);
        $http->addPostParameter('TotalClasses', 4);
        $http->addPostParameter('ClassCode1', 'GND');
        $http->addPostParameter('ClassCode2', '1DY');
        $http->addPostParameter('ClassCode3', '2DY');
        $http->addPostParameter('ClassCode4', '3DY');
        $http->addPostParameter('DeliveryType', $options->delivery);
        $http->addPostParameter('ShipMethod', $options->pickup);
        $http->addPostParameter('OriginationPostal', $ZipOrigination);
        $http->addPostParameter('OriginationCountry', $CountryOrigination);
        $http->addPostParameter('DestinationPostal', $ZipDestination);
        $http->addPostParameter('DestinationCountry', $CountryDestination);
        $http->addPostParameter('Currency', 'USD');
        $http->addPostParameter('TotalPackages', 1);
        $http->addPostParameter('BoxID1', 'box1');
        $http->addPostParameter('Weight1', $weight);
        $http->addPostParameter('WeightUnit1', 'OZ');
        $http->addPostParameter('Length1', $options->length);
        $http->addPostParameter('Width1', $options->width);
        $http->addPostParameter('Height1', $options->height);
        $http->addPostParameter('DimensionalUnit1', $options->dunit);
        $http->addPostParameter('Packaging1', $options->packaging);
        $http->addPostParameter('Contents1', $options->contents);
        $http->addPostParameter('Insurance1', $options->insvalue);
        $http->addPostParameter('TotalCarriers', 4);
        $http->addPostParameter('CarrierCode1', 'UPS');
        $http->addPostParameter('CarrierCode2', 'FDX');
        $http->addPostParameter('CarrierCode3', 'USP');
        $http->addPostParameter('CarrierCode4', 'DHL');

        if ($cod) {
            $http->addPostParameter('Cod1', intval($cod * 100));
        }

        return $http;
    }

    function _queryRates($weight, $ZipOrigination, $CountryOrigination, 
                         $ZipDestination, $CountryDestination,$options, $cod)
    {
		try {
	        $http = $this->_prepareRequest($weight, $ZipOrigination, 
    	        $CountryOrigination, $ZipDestination, $CountryDestination,$options, $cod);
        	$response = $http->send()->getBodyt();

		} catch (Exception $e) {
			// TODO - add error processing
			$response = false;
        }

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

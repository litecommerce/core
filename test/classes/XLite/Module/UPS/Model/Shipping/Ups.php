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

define('US', "US");
define('EU', "EU");
define('CA', "CA");
define('PR', "PR");
define('MX', "MX");
define('OTHER_ORIGINS', "OTHER_ORIGINS");

/**
* This implementation complies the following documentation:
* UPS Rates & Service Selection XML Developer's Kit
*   http://www.ups.com/gec/techdocs/pdf/dtk_RateXML_V1.zip
*
* @package Module_UPS
* @access public
* @version $Id$
*/
class XLite_Module_UPS_Model_Shipping_Ups extends XLite_Model_Shipping_Online
{	
    public $error = "";	
    public $xmlError = false;	

    public $services; // initialized in constructor because of PHP bug	
    public $configCategory = "UPS";	
    public $optionsFields;

    public function __construct($param = null)
    {
        parent::__construct($param);
        $this->optionsFields = array("userid","password","accessKey","server","packaging","pickup","length","width","height","insured","sat_delivery","sat_pickup","residential","weight_unit");
        $this->services = array( // UPS service codes
        "01" => array(US => "Next Day Air", CA => "Express", PR => "Next Day Air"),
        "02" => array(US => "2nd Day Air", CA => "Expedited", PR => "2nd Day Air"),
        "03" => array(US => "Ground", PR => "Ground"),
        "07" => array(US => "Worldwide Express", EU => "Express", CA => "Worldwide Express", PR => "Worldwide Express", MX => "Express", OTHER_ORIGINS => "Worldwide Express"),
        "08" => array(US => "Worldwide Expedited", EU => "Expedited", CA => "Worldwide Expedited", PR => "Worldwide Expedited", MX => "Expedited", OTHER_ORIGINS => "Worldwide Expedited"),
        "11" => array(US => "Standard", EU => "Standard", CA => "Standard"),
        "12" => array(US => "3 Day Select", CA => "3 Day Select"),
        "13" => array(US => "Next Day Air Saver", CA => "Express Saver"),
        "14" => array(US => "Next Day Air Early A.M.", CA => "Express Early A.M.", PR => "Next Day Air Early A.M."),
        "54" => array(US => "Worldwide Express Plus", EU => "Worldwide Express Plus", CA => "Worldwide Express Plus", PR => "Worldwide Express Plus", MX => "Express Plus", OTHER_ORIGINS => "Worldwide Express Plus"),
        "59" => array(US => "2nd Day Air A.M."),
        "64" => array(EU => "Express NA1"),
        "65" => array(US => "Express Saver", EU => "Express Saver"),
        );
 
    }
    
    function getModuleName()
    {
        return "UPS";
    }

    function getRates(XLite_Model_Order $order)
    {
        require_once LC_MODULES_DIR . 'UPS' . LC_DS . 'encoded.php';
        return Shipping_ups_getRates($this, $order);
	}

    function _queryRates($pounds, $originZipCode, $originCountry, $destinationZipCode, $destinationCountry, $options, $codvalue)
    {
        $https = new XLite_Model_HTTPS();
        $https->url = $options->server;
        $https->method = "POST";
        $https->urlencoded = true;
        $request = $this->_createRequest($pounds, $originZipCode, $originCountry, $destinationZipCode, $destinationCountry, $options, $codvalue);
        $lines = explode("\n", $request);
        $https->data = '';
        foreach ($lines as $line) {
            $https->data .= trim($line);
        }
        if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
            $this->error = $https->error;
            return array();
        }
        if ($originCountry == $destinationCountry) {
            $destination = "L"; // Local; just for informational purposes
        } else {
            $destination = "I";
        }
        return $this->_parseResponse($https->response, $destination, $originCountry);
    }

    function _createRequest($pounds, $originZipCode, $originCountry, $destinationZipCode, $destinationCountry, $options, $codvalue)
    {
        $dimensions = "";
        if (!empty($options->width) && !empty($options->length) && !empty($options->height) && $options->width!=0 && $options->length!=0 && $options->height!=0) {
           $dimensions = <<<EOT
<Dimensions>
    <Width>$options->width</Width>
    <Length>$options->length</Length>
    <Height>$options->height</Height>
</Dimensions>
EOT;
        }
        $insured = "";
        if (!empty($options->insured) && $options->insured != 0) {
        // TODO: get a system currency
            $insured =<<<EOT
    <InsuredValue>
        <CurrencyCode>USD</CurrencyCode>
        <MonetaryValue>$options->insured</MonetaryValue>
    </InsuredValue>
EOT;
        }
        if ($options->sat_delivery) {
            $sat_delivery = "<SaturdayDeliveryIndicator/>";
        } else {
            $sat_delivery = "";
        }
        if ($options->sat_pickup) {
            $sat_pickup = "<SaturdayPickupIndicator/>";
        } else {
            $sat_pickup = "";
        }
        if ($options->residential) {
            $residential = "<ResidentialAddressIndicator/>";
        } else {
            $residential = "";
        }
        if ($codvalue) {
            $codvalue = sprintf("%.2f", $codvalue);
            $cod = <<<EOT
    <COD>
        <CODFundsCode>0</CODFundsCode>
        <CODCode>3</CODCode>
        <CODAmount>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>$codvalue</MonetaryValue>
        </CODAmount>
   </COD>
EOT;
        } else {
            $cod = "";
        }
        return <<<EOT
<?xml version='1.0'?>
<AccessRequest xml:lang='en-US'>
   <AccessLicenseNumber>$options->accessKey</AccessLicenseNumber>
   <UserId>$options->userid</UserId>
   <Password>$options->password</Password>
</AccessRequest>
<?xml version='1.0'?>
<RatingServiceSelectionRequest xml:lang='en-US'>
   <Request>
      <TransactionReference>
         <CustomerContext>Rating and Service</CustomerContext>
         <XpciVersion>1.0001</XpciVersion>
      </TransactionReference>
      <RequestAction>Rate</RequestAction>
      <RequestOption>shop</RequestOption>
   </Request>
   <PickupType>
      <Code>$options->pickup</Code>
   </PickupType>
   <Shipment>
      <Shipper>
         <Address>
            <PostalCode>$originZipCode</PostalCode>
            <CountryCode>$originCountry</CountryCode>
         </Address>
      </Shipper>
      <ShipTo>
         <Address>
            $residential
            <PostalCode>$destinationZipCode</PostalCode>
            <CountryCode>$destinationCountry</CountryCode>
         </Address>
      </ShipTo>
      <Package>
         <PackagingType>
            <Code>$options->packaging</Code>
         </PackagingType>
         <PackageWeight>
	  		<UnitOfMeasurement>
	  		<Code>$options->weight_unit</Code>
	  		</UnitOfMeasurement>
      		<Weight>$pounds</Weight>
         </PackageWeight>
         $dimensions
<PackageServiceOptions>
         $insured
         $cod
</PackageServiceOptions>
      </Package>
      <ShipmentServiceOptions>
        $sat_delivery
        $sat_pickup
      </ShipmentServiceOptions>
   </Shipment>
</RatingServiceSelectionRequest>

EOT;
    }

    function cleanCache()
    {
        $this->_cleanCache("ups_cache");
    }
    
    function _parseResponse($response, $destination, $originCountry)
    {
        require_once LC_MODULES_DIR . 'UPS' . LC_DS . 'encoded.php';
        return Shipping_ups_parseResponse($this, $response, $destination, $originCountry);
    }

    function _getServiceName($serviceCode, $originCountry)
    {
        $c = new XLite_Model_Country($originCountry);
        if ($originCountry == 'US')     $origin = US;
        else if($originCountry == 'PR') $origin = PR;
        else if($originCountry == 'MX') $origin = MX;
        else if($originCountry == 'CA') $origin = CA;
        else if($c->isEUMember())       $origin = EU;
        else                            $origin = OTHER_ORIGINS;

        if (isset($this->services[$serviceCode])) {
            if (isset($this->services[$serviceCode][$origin])) {
                return $this->services[$serviceCode][$origin];
            }
        }
        return null;
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

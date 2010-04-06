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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/

// FIXME
if (!defined('US')) define('US', 'US');
if (!defined('EU')) define('EU', 'EU');
if (!defined('CA')) define('CA', 'CA');
if (!defined('PR')) define('PR', 'PR');
if (!defined('MX')) define('MX', 'MX');
if (!defined('PL')) define('PL', 'PL');
if (!defined('OTHER_ORIGINS')) define('OTHER_ORIGINS', 'OTHER_ORIGINS');

class XLite_Module_UPSOnlineTools_Model_Shipping_Ups extends XLite_Model_Shipping_Online
{	
    public $error = 0;

    public function __construct($param = null)
    {
        parent::__construct($param);
        $this->services = $this->getServices();
    }
    

    function getModuleName() 
    {
        return "United Parcel Service";
    }

    function setAccount($userinfo, &$error)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_setAccount($this, $userinfo, $error);
    }

    function setConfig($name, $value)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		UPSOnlineTools_setConfig($this, $name, $value);
    }

    function getRates(XLite_Model_Order $order)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_getRates($this, $order);
    }

	function _getHTTPS()
	{
		return new XLite_Module_UPSOnlineTools_Model_HTTPS();
	}

    function _queryRates($pounds, $originAddress, $originState, $originCity, $originZipCode, $originCountry, $destinationAddress, $destinationState, $destinationCity, $destinationZipCode, $destinationCountry, $options, $containers=array())
    {
        $https = $this->_getHTTPS();
        $https->url = $options->get('server').'Rate';
        $https->method = "POST";
        $https->urlencoded = true;

        $request = $this->_createRequest($pounds, $originAddress, $originState, $originCity, $originZipCode, $originCountry, $destinationAddress, $destinationState, $destinationCity, $destinationZipCode, $destinationCountry, $options, $containers);

        $lines = explode("\n", $request);

		// log UPSOnlineTools request
		$this->xlite->logger->log("UPSOnlineTools request:\n".$request);

        $https->data = '';
        foreach ($lines as $line) {
            $https->data .= trim($line);
        }
        if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
			$this->xlite->logger->log("HTTPS_ERROR: ".$https->error);
            $this->error = $https->error;
            return array();
        }

        if ($originCountry == $destinationCountry) {
            $destination = "L"; // Local; just for informational purposes
        } else {
            $destination = "I";
        }

		// log UPSOnlineTools response
		$response_log = str_replace("><", ">\n<", $https->response);
		$this->xlite->logger->log("UPSOnlineTools response:\n".$response_log);

		preg_match("/<.*[^>].*>/msx", $https->response, $res);
		$_response = $res[0];

        return $this->_parseResponse($_response, $destination, $originCountry);
    }

    function formatCurrency($price)
    {   
    	$isNewFC = $this->xlite->get("UPSOTNewFC");
    	if (!isset($isNewFC)) {
			$classMethods = array_map("strtolower", get_class_methods(get_parent_class(get_class($this))));
			$isNewFC = in_array("formatcurrency", $classMethods);

			$this->xlite->set("UPSOTNewFC", $isNewFC);
		}

		if ($isNewFC) {
			return parent::formatCurrency($price);
		} else {
        	return round($price, 2);
        }
    }               

    function _createRequest($pounds, $originAddress, $originState, $originCity, $originZipCode, $originCountry, $destinationAddress, $destinationState, $destinationCity, $destinationZipCode, $destinationCountry, $options, $containers=array())
    {
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        $customer_classification_code = $options->get('customer_classification_code');
        if ($originCountry == "US" && !empty($customer_classification_code)) {
        $customer_classification_query=<<<EOT
	<CustomerClassification>
		<Code>$customer_classification_code</Code>
	</CustomerClassification>
EOT;
        }

		// Residential / commercial address indicator
		$residental_flag = '';
		if ($options->get("residential") == "Y") {
			$residental_flag = "\t\t\t\t<ResidentialAddressIndicator/>";
		}

        $UPS_accesskey = $options->get('UPS_accesskey');
        $UPS_username = $options->get('UPS_username');
        $UPS_password = $options->get('UPS_password');
        $pickup_type = $options->get('pickup_type');

		$packages = "";
		foreach ($containers as $container) {
			// get container dimnsions & weight
			list($width, $length, $height) = $container->getDimensions();
			$weight = $container->getWeight();

			// get packaging from the container details
			$packaging_type = $container->getContainerType();

			if ($packaging_type == 1) {
				$weight = 0.0; // UPS Letter
			}

			$inches_lbs = true;
			if (!in_array($originCountry, array("DO","PR","US"))) {
				$width = $width * 2.54;
				$length = $length * 2.54;
				$height = $height * 2.54;
				$weight = $weight / 2.20462;

				$inches_lbs = false;
			}

			$width = $this->formatCurrency(doubleval($width));
			$length = $this->formatCurrency(doubleval($length));
			$height = $this->formatCurrency(doubleval($height));

			$weight = max(MIN_PACKAGE_WEIGHT, $this->formatCurrency(doubleval($weight)));

			// Set Additional Handling option
			$options->set("AH", $container->isAdditionalHandling());

			// set declared value
			$options->set("iv_amount", $container->getDeclaredValue());
			$options->set("iv_currency", (($options->get("currency_code")) ? $options->get("currency_code") : "USD"));

			$packages .= $this->_createPackage($width, $length, $height, $originCountry, $destinationCountry, $packaging_type, $inches_lbs, $weight, $options);
		}

        $request=<<<EOT
<?xml version='1.0'?>
<AccessRequest xml:lang='en-US'>
	<AccessLicenseNumber>$UPS_accesskey</AccessLicenseNumber>
	<UserId>$UPS_username</UserId>
	<Password>$UPS_password</Password>
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
		<Code>$pickup_type</Code>
	</PickupType>
$customer_classification_query
	<Shipment>
		<Shipper>
			<Address>
				<AddressLine1>$originAddress</AddressLine1>
				<City>$originCity</City>
				<StateProvinceCode>$originState</StateProvinceCode>
				<PostalCode>$originZipCode</PostalCode>
				<CountryCode>$originCountry</CountryCode>
			</Address>
		</Shipper>
		<ShipFrom>
			<Address>
				<AddressLine1>$originAddress</AddressLine1>
				<City>$originCity</City>
				<StateProvinceCode>$originState</StateProvinceCode>
				<PostalCode>$originZipCode</PostalCode>
				<CountryCode>$originCountry</CountryCode>
			</Address>
		</ShipFrom>
		<ShipTo>
			<Address>
				<City>$destinationCity</City>
				<StateProvinceCode>$destinationState</StateProvinceCode>
				<PostalCode>$destinationZipCode</PostalCode>
				<CountryCode>$destinationCountry</CountryCode>
$residental_flag
			</Address>
		</ShipTo>
$packages
	</Shipment>
</RatingServiceSelectionRequest>
EOT;

        return $request;
    }

	function _createPackage($width, $length, $height, $originCountry, $destinationCountry, $packaging_type, $inches_lbs, $weight, $options)
	{
		$pkgparams = "";

		if ($inches_lbs) {
			$wunit = "LBS";
			$dunit = "IN";
		} else {
			$wunit = "KGS";
			$dunit = "CM";
		}

		$insvalue = $this->formatCurrency(doubleval($options->get('iv_amount')));

		$pkgopt = array();
		$srvopts = array();
		if ($insvalue > 0.1) {
			$iv_currency = $options->get('iv_currency');
			$pkgopt[] =<<<EOT
				<InsuredValue>
					<CurrencyCode>$iv_currency</CurrencyCode>
					<MonetaryValue>$insvalue</MonetaryValue>
				</InsuredValue>

EOT;
		}

		// delivery confirmation
		$delivery_conf = intval($options->get('delivery_conf'));
		if ($delivery_conf > 0 && $delivery_conf < 4) {
			$opt_grp = $originCountry != "US" ? 'srvopts' : 'pkgopt';
			${$opt_grp}[] =<<<EOT
				<DeliveryConfirmation>
					<DCISType>$delivery_conf</DCISType>
				</DeliveryConfirmation>\n
EOT;
		}

		// combine package service options
		if (count($pkgopt) > 0) {
			$pkgparams .= "\t\t\t<PackageServiceOptions>\n".join("",$pkgopt)."\t\t\t</PackageServiceOptions>\n";
		}

		$upsoptions = $options->get('upsoptions');
		if (is_array($upsoptions) && count($upsoptions) > 0) {
			foreach($upsoptions as $opt=>$val) {
				if ($val != 'Y') {
					 continue;
				}

				switch($opt) {
					case "SP": $srvopts[] = "\t\t\t\t<SaturdayPickupIndicator/>\n"; break;
					case "SD": $srvopts[] = "\t\t\t\t<SaturdayDeliveryIndicator/>\n"; break;
				}
			}
		}

		if ($options->get("AH")) {
			$pkgparams .= "\t\t\t<AdditionalHandling/>\n";
		}

		// combine shipment service options
		if (count($srvopts) > 0) {
			$pkgparams .= "\t\t\t<ShipmentServiceOptions>\n".join("", $srvopts)."\t\t\t</ShipmentServiceOptions>";
		}

		$packaging_type = sprintf("%02d", $packaging_type);

		// create package
		$package = <<<EOT
		<Package>
			<PackagingType>
				<Code>$packaging_type</Code>
			</PackagingType>
			<PackageWeight>
				<UnitOfMeasurement>
					<Code>$wunit</Code>
				</UnitOfMeasurement>
				<Weight>$weight</Weight>
			</PackageWeight>
			<Dimensions>
				<UnitOfMeasurement>
					<Code>$dunit</Code>
				</UnitOfMeasurement>
				<Length>$length</Length>
				<Width>$width</Width>
				<Height>$height</Height>
			</Dimensions>
$pkgparams
		</Package>

EOT;

		return $package;
	}

    function _parseResponse($response, $destination, $originCountry)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_parseResponse($this, $response, $destination, $originCountry);
    }


    function getOptions()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		$options = UPSOnlineTools_getOptions($this);

        switch ($options->get('account_type')) {
            case "01": {
                $options->set('customer_classification_code', "01");
                $options->set('pickup_type', "01");
                break;
            }
            case "02": {
                $options->set('customer_classification_code', "03");
                $options->set('pickup_type', "03");
                break;
            }
            default: { # "03"
                $options->set('customer_classification_code', "04");
                $options->set('pickup_type', "11");
            }
        }
        return $options;
    }

    function getServices()
	{
		$matrix = array(
			"01" => array(
				"Next Day Air" => array(US, PR),
				"Express" => array(CA)
			),
			"02" => array(
				"2nd Day Air" => array(US, PR)
			),
			"03" => array(
				"Ground" => array(US, PR)
			),
			"07" => array(
				"Worldwide Express" => array(US, PR, CA),
				"Express" => array(EU, PL, MX)
			),
			"08" => array(
				"Worldwide Expedited" => array(US, PR, CA, OTHER_ORIGINS),
				"Expedited" => array(MX)
			),
			"11" => array(
				"Standard" => array(US, CA, EU, PL)
			),
			"12" => array(
				"3 Day Select" => array(US, CA)
			),
			"13" => array(
				"Next Day Air Saver" => array(US),
				"Saver" => array(CA)
			),
			"14" => array(
				"Next Day Air Early A.M." => array(US, PR),
				"Express Early A.M." => array(CA)
			),
			"54" => array(
				"Worldwide Express Plus" => array(US, PR, PL, EU, OTHER_ORIGINS),
				"Express Plus" => array(MX)
			),
			"59" => array(
				"2nd Day Air A.M." => array(US)
			),
			"65" => array(
				"Saver" => array(US, PR, CA, MX, PL, EU, OTHER_ORIGINS)
			),
			"82" => array("Today Standard" => array(PL)),
			"83" => array("Today Dedicated Courrier" => array(PL)),
			"84" => array("Today Intercity" => array(PL)),
			"85" => array("Today Express" => array(PL)),
			"86" => array("Today Express Saver" => array(PL))
		);

		return $matrix;
    }

    function _getServiceName($serviceCode, $originCountry)
    {
		switch ($originCountry) {
			case "US": $origin = US; break;	// US - origin
			case "PR": $origin = PR; break;	// Puerto Rico - origin
			case "MX": $origin = MX; break;	// Mexico - origin
			case "CA": $origin = CA; break;	// Canada - origin
			case "PL": $origin = PL; break;	// Poland (EU) - origin

			default:
				$country = new XLite_Model_Country($originCountry);
				if ($country->isEUMember()) {
					$origin = EU;			// European country, EU - origin
				} else {
					$origin = OTHER_ORIGINS;
				}
			break;
		}

        if (isset($this->services[$serviceCode])) {
			foreach ($this->services[$serviceCode] as $service=>$origins) {
				if (in_array($origin, $origins))
					return $service;
			}
        }

        return null;
    }

    function checkAddress($shipping_country, $shipping_state, $shipping_custom_state, $shipping_city, $shipping_zipcode, &$av_result, &$request_result)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_checkAddress($this, $shipping_country, $shipping_state, $shipping_custom_state, $shipping_city, $shipping_zipcode, $av_result, $request_result);
    }

/////////////////////////////////
// Addition functions
/////////////////////////////////

    function request($request, $func, $tool, $use_auth = true)
	{

        $options = $this->getOptions();

        if ($use_auth) {
            $UPS_accesskey = $options->get('UPS_accesskey');
            $UPS_username = $options->get('UPS_username');
            $UPS_password = $options->get('UPS_password');

            $request = <<<EOT
<?xml version="1.0"?>
<AccessRequest>
    <AccessLicenseNumber>$UPS_accesskey</AccessLicenseNumber>
    <UserId>$UPS_username</UserId>
    <Password>$UPS_password</Password>
</AccessRequest>
$request
EOT;
        }

        $https = $this->_getHTTPS();
        $https->url = $options->get("server").$tool;
        $https->method = "POST";
        $https->urlencoded = true;
        $https->data = $request;

        if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
            $this->error = 'Connection failed';
            return array();
        }

		preg_match("/<.*[^>].*>/msx", $https->response, $res);
		$_response = $res[0];

//		$xml = new XLite_Model_XML();
		$xml = $this->getObjectXML();
        $tree = $xml->parse($_response);
        if (!$tree) {
            $this->error = $xml->error;
            $this->xmlError = true;
            $this->response = $xml->xml;
            return array();
        }
        if (!is_null($func))
            return $this->$func($tree);
        return $tree;
    }

    function u_elem_data_av($result)
	{
        $return = $this->u_base($result['ADDRESSVALIDATIONRESPONSE']['RESPONSE']);
        if(is_array($result['ADDRESSVALIDATIONRESPONSE']))
            foreach($result['ADDRESSVALIDATIONRESPONSE'] as $key=>$val)
                if (strpos($key, 'ADDRESSVALIDATIONRESULT') !== false) $return['address'][] = $val;
        return $return;
    }

    function u_base($result)
	{
        $return['statuscode'] = $result['RESPONSESTATUSCODE'];
        $return['statusdescr'] = $result['RESPONSESTATUSDESCRIPTION'];
        $return['errorcode'] = $result['ERROR']['ERRORCODE'];
        $return['errordescr'] = $result['ERROR']['ERRORDESCRIPTION'];
        return $return;
    }

	function getUPSCountries()
	{
		$ups_countries = array(
			"AR" => "Argentina",
			"AU" => "Australia",
			"AT" => "Austria",
			"BE" => "Belgium",
			"BR" => "Brazil",
			"CA" => "Canada",
			"CL" => "Chile",
			"CR" => "Costa Rica",
			"DK" => "Denmark",
			"DO" => "Dominican Republic",
			"FI" => "Finland",
			"FR" => "France",
			"DE" => "Germany",
			"GR" => "Greece",
			"GT" => "Guatemala",
			"HK" => "Hong Kong",
			"IN" => "India",
			"IE" => "Ireland",
			"IL" => "Israel",
			"IT" => "Italy",
			"JP" => "Japan",
			"LU" => "Luxembourg",
			"MY" => "Malaysia",
			"MX" => "Mexico",
			"NL" => "Netherlands",
			"NZ" => "New Zealand",
			"NO" => "Norway",
			"PA" => "Panama",
			"PE" => "Peru",
			"PH" => "Philippines",
			"PT" => "Portugal",
			"PR" => "Puerto Rico",
			"RO" => "Romania",
//			"RU" => "Russian Federation",
			"SG" => "Singapore",
			"ZA" => "South Africa",
			"KR" => "South Korea",
			"ES" => "Spain",
			"SE" => "Sweden",
			"CH" => "Switzerland",
			"GB" => "United Kingdom (Great Britain)",
			"US" => "United States",
			"VI" => "United States Virgin Islands",
			"VE" => "Venezuela"
		);

		return $ups_countries;
	}

    function getUPSStates()
	{
        $ups_states = array(
        "AB" => "Alberta (Canada)",
        "BC" => "British Columbia (Canada)",
        "MB" => "Manitoba (Canada)",
        "NB" => "New Brunswick (Canada)",
        "NF" => "Newfoundland/Labrador (Canada)",
        "NS" => "Nova Scotia (Canada)",
        "NT" => "NWT/Nunavut (Canada)",
        "ON" => "Ontario (Canada)",
        "PE" => "Prince Edward Island (Canada)",
        "QC" => "Quebec (Canada)",
        "SK" => "Saskatchewan (Canada)",
        "YT" => "Yukon (Canada)",
        "AA" => "Armed Forces Americas (US)",
        "AE" => "Armed Forces Europe (US)",
        "AL" => "Alabama (US)",
        "AK" => "Alaska (US)",
        "AP" => "Armed Forces Pacific (US)",
        "AZ" => "Arizona (US)",
        "AR" => "Arkansas (US)",
        "CA" => "California (US)",
        "CO" => "Colorado (US)",
        "CT" => "Connecticut (US)",
        "DE" => "Delaware (US)",
        "DC" => "District of Columbia (US)",
        "FL" => "Florida (US)",
        "GA" => "Georgia (US)",
        "GU" => "Guam (US)",
        "HI" => "Hawaii (US)",
        "ID" => "Idaho (US)",
        "IL" => "Illinois (US)",
        "IN" => "Indiana (US)",
        "IA" => "Iowa (US)",
        "KS" => "Kansas (US)",
        "KY" => "Kentucky (US)",
        "LA" => "Louisiana (US)",
        "ME" => "Maine (US)",
        "MD" => "Maryland (US)",
        "MA" => "Massachusetts (US)",
        "MI" => "Michigan (US)",
        "MN" => "Minnesota (US)",
        "MS" => "Mississippi (US)",
        "MO" => "Missouri (US)",
        "MT" => "Montana (US)",
        "NE" => "Nebraska (US)",
        "NV" => "Nevada (US)",
        "NH" => "New Hampshire (US)",
        "NJ" => "New Jersey (US)",
        "NM" => "New Mexico (US)",
        "NY" => "New York (US)",
        "NC" => "North Carolina (US)",
        "ND" => "North Dakota (US)",
        "OH" => "Ohio (US)",
        "OK" => "Oklahoma (US)",
        "OR" => "Oregon (US)",
        "PA" => "Pennsylvania (US)",
        "RI" => "Rhode Island (US)",
        "SC" => "South Carolina (US)",
        "SD" => "South Dakota (US)",
        "TN" => "Tennessee (US)",
        "TX" => "Texas (US)",
        "UT" => "Utah (US)",
        "VI" => "Virgin Islands (US)",
        "VT" => "Vermont (US)",
        "VA" => "Virginia (US)",
        "WA" => "Washington (US)",
        "WV" => "West Virginia (US)",
        "WI" => "Wisconsin (US)",
        "WY" => "Wyoming (US)"
        );
        return $ups_states;
    }

	function get($name)
	{
		$value = parent::get($name);

		if ($name == "name") {
			require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
			$value = UPSOnlineTools_getNameUPS($value);
		}

		return $value;
	}

	function getUPSContainersList()
	{
		return array(
			1 => "UPS Letter / UPS Express Envelope",
			2 => "Package",
			3 => "UPS Tube",
			4 => "UPS Pak",
			21 => "UPS Express Box",
			24 => "UPS 25 Kg Box&reg;",
			25 => "UPS 10 Kg Box&reg;",
			30 => "Pallet"
		);
	}

	function getUPSContainerDims($index, $inches_lbs=true)
	{
		$dims = array();

		// all dimension/weight set in inches/lbs
		switch ($index) {
			case "1":   // UPS Letter / UPS Express Envelope
				$dims = array(
					"name" => "UPS Letter / UPS Express Envelope",
					"width" => 12.5,
					"length" => 9.5,
					"height" => 0.25,
					"weight_limit" => 0 // weight limit not set (N/A)
				);
			break;

			case "3":   // UPS Tube
				$dims = array(
					"name" => "UPS Tube",
					"width" => 38,
					"length" => 6,
					"height" => 6,
					"weight_limit" => 0 // weight limit not set (N/A)
				);
			break;

			case "4":   // UPS Pak
				$dims = array(
					"name" => "UPS Pak",
					"width" => 16,
					"length" => 12.75,
					"height" => 2,
					"weight_limit" => 0 // weight limit not set (N/A)
				);
			break;

			case "21":  // UPS Express Box
				$dims = array(
					"name" => "UPS Express Box",
					"width" => 18,
					"length" => 13,
					"height" => 3,
					"weight_limit" => 30
				);
			break;

			case "24":  // UPS 25kg Box
				$dims = array(
					"name" => "UPS 25kg Box&reg;",
					"width" => 19.375,
					"length" => 17.375,
					"height" => 14,
					"weight_limit" => 55.1
				);
			break;

			case "25":  // UPS 10kg Box
				$dims = array(
					"name" => "UPS 10kg Box&reg;",
					"width" => 16.5,
					"length" => 13.25,
					"height" => 10.75,
					"weight_limit" => 22
				);
			break;

			case "30":  // Pallet
			case "2":   // Your (user-defined) package
			default:
				$dims = array(
					"name" => "Your package",
					"width" => $this->xlite->getComplex('config.UPSOnlineTools.width'),
					"length" => $this->xlite->getComplex('config.UPSOnlineTools.length'),
					"height" => $this->xlite->getComplex('config.UPSOnlineTools.height'),
					"weight_limit" => 0, // weight limit not set (N/A)
				);

				if ($index == 2) {
					$dims["weight_limit"] = 150; // lbs
				}
			break;
		}

		if ($index == 30) {
			$dims["name"] = "Pallet";
		}

		if ($inches_lbs) {
			$dims["units"] = "inches/lbs";
			$dims["length_unit"] = "inches";
			$dims["weight_unit"] = "lbs";
		} else {
			$dims["width"] = round($dims["width"] * 2.54);
			$dims["height"] = round($dims["height"] * 2.54);
			$dims["length"] = round($dims["length"] * 2.54);
			$dims["weight_limit"] = round($dims["weight_limit"] / 2.2);
			$dims["units"] = "kg/sm";
			$dims["length_unit"] = "sm";
			$dims["weight_unit"] = "kg";
		}

		return $dims;
	}

    function getLicense(&$license) 
    {
		$time = time();

		if ($this->session->get("ups_license_update_time")+3600 < $time) {
			$this->session->set("ups_license", "");
		}

        $license = $this->session->get("ups_license");
        if ($license)
            return 0;

        $devlicense = $this->config->getComplex('UPSOnlineTools.devlicense');

        $request=<<<EOT
<?xml version='1.0' encoding='ISO-8859-1'?>
<AccessLicenseAgreementRequest>
    <Request>
        <TransactionReference>
            <CustomerContext>License Test</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <RequestAction>AccessLicense</RequestAction>
        <RequestOption></RequestOption>
    </Request>
    <DeveloperLicenseNumber>$devlicense</DeveloperLicenseNumber>
    <AccessLicenseProfile>
        <CountryCode>US</CountryCode>
        <LanguageCode>EN</LanguageCode>
    </AccessLicenseProfile>
    <OnLineTool>
        <ToolID>TrackXML</ToolID>
        <ToolVersion>1.0</ToolVersion>
    </OnLineTool>
</AccessLicenseAgreementRequest>
EOT;

        $result = $this->request($request, null, 'License', false);

        if ($this->error) {
			$this->session->set("ups_license", "");
			$this->session->set("ups_license_update_time", 0);
			return 1;
		}

        $license = $result['ACCESSLICENSEAGREEMENTRESPONSE']['ACCESSLICENSETEXT'];
        $this->session->set("ups_license", $license);
		$this->session->set("ups_license_update_time", $time);
		$this->session->writeClose();

        return 0;
    }

    function getAgreement(&$license) 
    {
        if ($this->getLicense($license)) {
            $license = "<div align='justify'><font style='FONT-FAMILY: Courier; FONT-SIZE: 10px;'>"."Sorry, license agreement is temporary unavailable. Try again later."."</font></div>";
            return 1;
        }
        $license = preg_replace("/\s([0-9]{1,2}\.[0-9]*)([^0-9]+)/U", "<br><br><b>\\1</b>\\2\\3", $license);
        $license = preg_replace("/([^a-zA-Z]+)([\s]+)(\([a-h]+\))/", "\\1\\2<br><br><b>\\3</b>", $license);
        $license = preg_replace("/(\(\"UPS\"\).)[\s]*(This)/", "\\1<br><br>\\2", $license);
        $license = str_replace("DO YOU AGREE", "<br><br>DO YOU AGREE", $license);
        $license = "<div align='justify'><font style='FONT-FAMILY: Courier; FONT-SIZE: 10px;'>".$license."</font></div>";

        return 0;
    }

	function getShippingCacheExpiration()
	{
		return $this->xlite->config->getComplex('UPSOnlineTools.cache_autoclean') * 86400;
	}

	function _checkCache($table, $fields)
	{
		$object = new XLite_Model_Shipping_Online();
		if (!method_exists($object, "getShippingCacheExpiration")) 
		{
			// LC with old core version

			$cacheTable = $this->db->getTableByAlias($table);
			// garbage collection
			if ($this->get("shippingCacheExpiration") > 0) {
				$this->db->query("DELETE FROM $cacheTable WHERE date<".(time()-$this->get("shippingCacheExpiration")));
			}

			// check cache for fresh values
			$condition = array();
			foreach ($fields as $key => $value) {
				$condition[] = "$key='".addslashes($value)."'";
			}
			$condition = join(" AND ", $condition);
			$cacheRow = $this->db->getRow("SELECT rates FROM $cacheTable WHERE $condition");
			if (!is_null($cacheRow)) {
				return $this->unserializeCacheRates($cacheRow["rates"]);
			}
			return false;
		}

		return parent::_checkCache($table, $fields);
	}

	function getObjectXML()
	{
		$object = new XLite_Module_UPSOnlineTools_Model_XML();
		$object->parser_encode = "ISO-8859-1";

		return $object;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

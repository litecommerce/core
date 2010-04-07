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
* This implementation complies the following documentation:
* USPS WEB TOOLS (API) 
*   http://www.uspswebtools.com
*
* @package Module_USPS
* @access public
* @version $Id$
*/
class XLite_Module_USPS_Model_Shipping_Usps extends XLite_Model_Shipping_Online
{	
    public $error = "";	
    public $xmlError = false;	
    public $translations = array(
        "EXPRESS" => "Express Mail",
        "PRIORITY" => "Priority Mail",
        "PARCEL" => "Parcel Post",
        "LIBRARY" => "Library",
        "FIRST CLASS" => "First Class",
        "FIRSTCLASS" => "First Class",
        "MEDIA" => "Media",
        "BPM" => "Bound Printed Matter");	

    public $configCategory = "USPS";	
    public $optionsFields = array("userid","password","server","container_express","container_priority","mailtype","machinable","package_size","value_of_content","dim_lenght","dim_width","dim_height","dim_girth","fcmailtype");

    function getModuleName()
    {
        return "U.S.P.S.";
    }

    function getRates(XLite_Model_Order $order)
    {
        require_once LC_MODULES_DIR . 'USPS' . LC_DS . 'encoded.php';
        return Shipping_usps_getRates($this, $order);
    }

    function _getNationalRates($order)
    {
        $options = $this->getOptions();
        $ounces = $this->getOunces($order);
        $ZipOrigination = $this->config->getComplex('Company.location_zipcode');
    	if (is_null($order->get("profile"))) {
        	$ZipDestination = $order->config->getComplex('General.default_zipcode');
    	} else {
        	$ZipDestination = $order->getComplex('profile.shipping_zipcode');
    	}

        // check national shipping rates cache
        $fields = array
        (
            "ounces"    => $ounces,
            "ziporig"   => $ZipOrigination,
            "zipdest"   => $ZipDestination,
            "package_size" => $options->package_size,
            "machinable"=> $options->machinable,
            "container_priority" => $options->container_priority,
            "container_express" => $options->container_express,
            "dim_lenght" => $options->dim_lenght,
            "dim_width" => $options->dim_width,
            "dim_height" => $options->dim_height,
            "dim_girth" => $options->dim_girth,
            "fcmailtype" => $options->fcmailtype,
        );

        $cached = $this->_checkCache("usps_nat_cache", $fields);

        if ($cached) {
            return $cached;
        }
     
        $rates = $this->filterEnabled($this->_queryNationalRates(
            $ounces, $ZipOrigination, $ZipDestination, $options));

        // store the result in cache
        $this->_cacheResult("usps_nat_cache", $fields, $rates);
		// add shipping markups
		$rates = $this->serializeCacheRates($rates);
		$rates = $this->unserializeCacheRates($rates);
        return $rates;
	}

	function _checkUSPSError(&$response)
	{
        $this->error = "";
        $this->xmlError = false;
        $xml = new XLite_Model_XML();
        $tree = $xml->parse($response);
        if (!$tree) {
            $this->error = $xml->error;
            $this->xmlError = true;
            return true;
        }

        if (isset($tree["ERROR"])) {
        	$this->error = array();
        	$this->error[] = "<BR> ERROR:";
        	if (isset($tree["ERROR"]["NUMBER"])) {
        		$this->error[] = $tree["ERROR"]["NUMBER"];
        	}
        	if (isset($tree["ERROR"]["SOURCE"])) {
        		$this->error[] = "(" . $tree["ERROR"]["SOURCE"] . ")";
        	}
        	if (isset($tree["ERROR"]["DESCRIPTION"])) {
        		$this->error[] = $tree["ERROR"]["DESCRIPTION"];
        	}

        	$this->error = implode(" ", $this->error);
            return true;
        }

		return false;
	}

    function _queryNationalRates($ounces, $ZipOrigination, $ZipDestination, $options) 
    {
        // transform the #####-#### ZIP format into just #####
        $ZipOrigination = $this->_normalizeZip($ZipOrigination);
        $ZipDestination = $this->_normalizeZip($ZipDestination);

		// Express container type
		$containerExpress = strtoupper($options->container_express);
		if (!in_array($containerExpress, array("VARIABLE", "FLAT RATE ENVELOPE"))) {
			$containerExpress = "";
		}
		$container_express = "<Container>$containerExpress</Container>";

		// Priority container type
		$containerPriority = strtoupper($options->container_priority);
		if (!in_array($containerPriority, array("VARIABLE", "FLAT RATE BOX", "FLAT RATE ENVELOPE", "RECTANGULAR", "NONRECTANGULAR"))) {
			$containerPriority = "";
		}
		$container_priority = "<Container>$containerPriority</Container>";

		// Make Dimensions
    	$dimXml = $dimGirthXml = "";
    	$dimWidth = $options->dim_width;
    	$dimLength = $options->dim_lenght;
    	$dimHeight = $options->dim_height;
    	$dimGirth = $options->dim_girth;

    	if ($containerPriority == "RECTANGULAR" || $containerPriority == "NONRECTANGULAR") {
    		$dimXml =<<<EOT
    <Width>$dimWidth</Width>
    <Length>$dimLength</Length>
    <Height>$dimHeight</Height>
EOT;
    		if ($containerPriority == "NONRECTANGULAR") {
    			$dimGirthXml = "<Girth>$dimGirth</Girth>";
    		}
    	}

		$firstClassMailType = strtoupper($options->fcmailtype);
		$machinableFirstClass = ((in_array($firstClassMailType, array("LETTER", "FLAT"))) ? "<Machinable>".$options->machinable."</Machinable>" : "");

		$request =<<<EOT
<RateV3Request USERID="$options->userid" PASSWORD="$options->password">
  <Package ID="0">
    <Service>EXPRESS</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
	$container_express
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="1">
    <Service>FIRST CLASS</Service>
    <FirstClassMailType>$firstClassMailType</FirstClassMailType>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
	$machinableFirstClass
  </Package>
  <Package ID="2">
    <Service>PRIORITY</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
	$container_priority
    <Size>$options->package_size</Size>
	$dimXml
	$dimGirthXml
  </Package>
  <Package ID="3">
    <Service>PARCEL</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
    <Machinable>$options->machinable</Machinable>
  </Package>
  <Package ID="4">
    <Service>BPM</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="5">
    <Service>LIBRARY</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
  <Package ID="6">
    <Service>MEDIA</Service>
    <ZipOrigination>$ZipOrigination</ZipOrigination>
    <ZipDestination>$ZipDestination</ZipDestination>
    <Pounds>0</Pounds>
    <Ounces>$ounces</Ounces>
    <Size>$options->package_size</Size>
  </Package>
</RateV3Request>
EOT;

        $response = $this->_request("API=RateV3&XML=".urlencode(trim($request)), $options);
        if (!$this->error) {
			if ($this->_checkUSPSError($response)) {
            	return array();
			}
            return $this->_parseResponse($response, "L");
        } else {
            return array();
        }
    }

    function _getInternationalRates($order)
    {
        $ounces = $this->getOunces($order);
    	if (is_null($order->get("profile"))) {
    		$destinationCountry = $this->config->getComplex('General.default_country');
    	} else {
        	$destinationCountry = $order->getComplex('profile.shippingCountry.country');
    	}

        $options = $this->getOptions();
        // check international shipping rates cache
        $fields = array
        (
            "ounces"    		=> $ounces,
            "country"   		=> $destinationCountry,
            "mailtype"  		=> $options->mailtype,
            "value_of_content"  => $options->value_of_content,
        );

        $cached = $this->_checkCache("usps_int_cache", $fields);
        if ($cached) {
            return $cached;
        }
 
        $rates = $this->filterEnabled($this->_queryInternationalRates($ounces, $destinationCountry, $options));
        if (!$this->error) {
            // store the result in cache
            $this->_cacheResult("usps_int_cache", $fields, $rates);
			// add shipping markups
			$rates = $this->serializeCacheRates($rates);
			$rates = $this->unserializeCacheRates($rates);
        }
        return $rates;
    }

    function _queryInternationalRates($ounces, $destinationCountry, $options)
    {
    	$valueOfContent = intval($options->value_of_content);
		$valueOfContentXml = ($valueOfContent > 0) ? "<ValueOfContents>$valueOfContent</ValueOfContents>" : "";

        $request = <<<EOT
<IntlRateRequest USERID="$options->userid">
	<Package ID="0">
		<Pounds>0</Pounds>
		<Ounces>$ounces</Ounces>
		<MailType>$options->mailtype</MailType>
		$valueOfContentXml
		<Country>$destinationCountry</Country>
	</Package>
</IntlRateRequest>
EOT;
        $response = $this->_request("API=IntlRate&XML=".urlencode(trim($request)), $options);
        if (!$this->error) {
			if ($this->_checkUSPSError($response)) {
            	return array();
			}
            return $this->_parseResponse($response, "I");
        } else {
            return array();
        }
    }

    function cleanCache()
    {
        $this->_cleanCache("usps_int_cache");
        $this->_cleanCache("usps_nat_cache");
    }
    
    function _parseResponse($response, $destination)
    {
        require_once LC_MODULES_DIR . 'USPS' . LC_DS . 'encoded.php';
        return Shipping_usps_parseResponse($this, $response, $destination);
    }

    function _request($queryString, $options)
    {
        global $php_errormsg;
        $php_errormsg = "";
        $this->error = "";

		$url = trim($options->server);
		if (!preg_match('/^https/i', $url)) {

			require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PEAR.php';
		    require_once LC_ROOT_DIR . 'lib' . LC_DS . 'HTTP' . LC_DS . 'Request.php';

			$pearObj = new PEAR();

			$http = new HTTP_Request($url."?$queryString"); 
			$http->_timeout = 5; // can't wait long when we are in shopping cart
			$track_errors = ini_get("track_errors");
			ini_set("track_errors", 1);
			$result = @$http->sendRequest();
			ini_set("track_errors", $track_errors);

			if ($php_errormsg) {
				$this->error = $php_errormsg;
				return "";
			}

			if ($pearObj->isError($result)) {
				$this->error = $result->getMessage();
				return "";
			}

			return $http->getResponseBody();
		} else {
            $https = new XLite_Model_HTTPS();
            $https->data = $queryString;
            $https->method = "POST";
            $https->conttype = "application/xml";
            $https->urlencoded = true;
            $https->url = $url;

            if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
                $this->error = $https->error;
                return "";
            }

            return $https->response;
		}
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

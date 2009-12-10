<?php
/*
* Hiden methods
*/

function &Shipping_ups_getRates(&$_this, $order)
{
    // license check
    check_module_license("UPS");

    // original code of Shipping_ups::getRates()
    
    if ((is_null($order->get("profile")) && !$_this->config->get("General.def_calc_shippings_taxes")) || $order->get("weight") == 0) {
        return array();
    }

    $options = $_this->get("options");
	if (empty($options->userid) || empty($options->accessKey)) {
		return array();
	}

    $pounds = sprintf("%15.1f", $_this->getOunces($order)/16.00);
    $originZipCode = $_this->config->get("Company.location_zipcode");
    $originCountry = $_this->config->get("Company.location_country");
	if (is_null($order->get("profile"))) {
    	$destinationCountry = $_this->config->get("General.default_country");
    	$destinationZipCode = $_this->config->get("General.default_zipcode");
	} else {
        $destinationZipCode = $order->get("profile.shipping_zipcode");
        $destinationCountry = $order->get("profile.shipping_country");
	}
    if ($order->get("payment_method") == "cod") {
        $codvalue = $order->get("subtotal");
    } else {
        $codvalue = 0;
    }

    // check national shipping rates cache
    $fields = array
    (
        "pounds"            	=> $pounds,
        "origin_zipcode"    	=> $originZipCode,
        "origin_country"    	=> $originCountry,
        "destination_zipcode"   => $destinationZipCode,
        "destination_country"   => $destinationCountry,
        "packaging"         	=> $options->packaging,
        "width"             	=> $options->width,
        "height"            	=> $options->height,
        "length"            	=> $options->length,
        "codvalue"          	=> $codvalue,
        "insured"           	=> $options->insured,
        "pickup"            	=> $options->pickup,
        "sat_delivery"          => $options->sat_delivery,
        "sat_pickup"            => $options->sat_pickup,
        "residential"			=> $options->residential,
    );

    $cached = $_this->_checkCache("ups_cache", $fields);

    if ($cached) {
        return $cached;
    }
    $rates =  $_this->filterEnabled($_this->_queryRates(
        $pounds, $originZipCode, $originCountry, $destinationZipCode, $destinationCountry, $options, $codvalue));
    if (!$_this->error) {
        // store the result in cache
        $_this->_cacheResult("ups_cache", $fields, $rates);
    }
    return $rates;
}

function &Shipping_ups_parseResponse(&$_this, $response, $destination, $originCountry)
{
    // license check
    check_module_license("UPS");

    // original code
    $_this->error = "";
    $_this->xmlError = false;
    $xml = func_new("XML");
    $tree = $xml->parse($response);
    if (!$tree) {
        $_this->error = $xml->error;
        $_this->xmlError = true;
        $_this->response = $xml->xml;
        return array();
    }
    // check for errors
    $response = $tree["RATINGSERVICESELECTIONRESPONSE"]["RESPONSE"];
    if (!$response["RESPONSESTATUSCODE"]) {
        $_this->error = "UPS error #".$response["ERROR"]["ERRORCODE"].": ".
            $response["ERROR"]["ERRORDESCRIPTION"];
        return array();
    }
    // enumerate services
    $rates = array();
    foreach ($tree["RATINGSERVICESELECTIONRESPONSE"] as $tag => $service) {
        if (substr($tag, 0, strlen("RATEDSHIPMENT")) != "RATEDSHIPMENT") {
            continue;
        }
        $serviceCode = $service["SERVICE"]["CODE"];
        $serviceName = $_this->_getServiceName($serviceCode, $originCountry);
        if (is_null($serviceName)) {
            // there's no known service for that code
			if ($_this->xlite->is("adminZone")) {
            	print "there's no known service for that code $serviceCode\n";
            }
            continue;
        }
        $shipping = $_this->getService("ups", "UPS $serviceName", $destination);
        $id = $shipping->get("shipping_id");
        $rates[$id] = func_new("ShippingRate");
        $rates[$id]->shipping = $shipping;
        $rates[$id]->rate = (double)trim($service["TOTALCHARGES"]["MONETARYVALUE"]);
    }
    return $rates;
}

?>

<?php

// Canada address example
// 
// Department of Justice Canada
// 284 Wellington Street
// Ottawa, Ontario
// Canada  K1A 0H8

function Shipping_cps_getRates($_this, $order)
{
	if ((is_null($order->get("profile")) && !$_this->config->getComplex('General.def_calc_shippings_taxes')) || $order->get("weight") == 0 || $_this->config->getComplex('Company.location_country') != 'CA') {
        return array();
    }

	$options = $_this->get("options");
	if (empty($options->merchant_id)) {
		return array();
	}

	$options->packed == 'Y' ? $packed = "<readyToShip/>" : $packed = "";
	$originalZipcode = $_this->config->getComplex('Company.location_zipcode');
	$originalCountry = $_this->config->getComplex('Company.location_country');
	$options->insured ? $itemsPrice = $options->insured * $options->currency_rate : $itemsPrice = $order->get('subtotal') * $options->currency_rate;
	$weight		 = $_this->getKgs($order);
	$description = $order->get("description");
	if (is_null($order->get("profile"))) {
    	$destinationCountry = $_this->config->getComplex('General.default_country');
    	$destinationZipcode = $_this->config->getComplex('General.default_zipcode');
    	$destinationState   = "Other";
    	$destinationCity    = "City";
	} else {
    	$profile 	 = $order->get("profile");
    	$destinationCountry = $profile->get("shipping_country");
    	$destinationCity    = $profile->get("shipping_city");
    	$destinationZipcode = $profile->get("shipping_zipcode");
    	$destinationState   = $profile->getComplex('shippingState.code');
    	if (empty($destinationState)) $destinationState = "Other";
	}	

	$fields = array(
			"weight"			=> sprintf("%.02f", $weight),
			"origin_zipcode"	=> substr($originalZipcode, 0, 12),
			"origin_country"	=> substr($originalCountry, 0, 40),
			"dest_zipcode"		=> substr($destinationZipcode, 0, 12),
			"dest_city"			=> substr($destinationCity, 0, 40),
			"dest_country"		=> substr($destinationCountry, 0, 40),
			"dest_state"		=> substr($destinationState, 0, 40),
			"insured"			=> sprintf("%.02f", $options->insured),	 
			"packed"			=> $options->packed,	
			"height"			=> sprintf("%.02f", $options->height),
			"width"				=> sprintf("%.02f", $options->width),
			"length"			=> sprintf("%.02f", $options->length));

	if (($cached = $_this->_checkCache("cps_cache", $fields)) != false) {
    	//XLite_Model_Profiler::getInstance()->log('cps_cache');
		return $cached;
	}
	$rates = $_this->filterEnabled($_this->queryRates($options,$originalZipcode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry));
	$_this->_cacheResult("cps_cache", $fields, $rates);
	// add shipping markups
	$rates = $_this->serializeCacheRates($rates);
	$rates = $_this->unserializeCacheRates($rates);

	//XLite_Model_Profiler::getInstance()->log('cps_data');

	return $rates;

}

function Shipping_cps_parseResponse($_this,$response,$destination) 
{
	$xml = new XLite_Model_XML();
	$tree = $xml->parse($response);

	if (isset($tree["EPARCEL"]["ERROR"])) {
    	$_this->error = $tree["EPARCEL"]["ERROR"]["STATUSCODE"];
		$_this->xmlError = true;
		$_this->response = $response;
		return array();
	}

	$response = $tree["EPARCEL"]['RATESANDSERVICESRESPONSE'];
	$rates = array();
	$options = $_this->get("options");
	$options->currency_rate ? $currency_rate = $options->currency_rate : $currency_rate = 1;

	foreach($response["PRODUCT"] as $_rate) {
		$shipping = $_this->getService('cps','Canada Post ' . $_rate['NAME'],$destination);
        $id = $shipping->get('shipping_id');
        $rates[$id] = new XLite_Model_ShippingRate();
        $rates[$id]->shipping = $shipping;
        $rates[$id]->rate = (double)trim($_rate["RATE"]) / $currency_rate;
	}
    return $rates;
}
?>

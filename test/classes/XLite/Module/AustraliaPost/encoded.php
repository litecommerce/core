<?php

function Shipping_aupost_getRates($_this, $order)
{
    if ((is_null($order->get('profile')) && !$_this->config->getComplex('General.def_calc_shippings_taxes')) || $order->get('weight') == 0 || $_this->config->getComplex('Company.location_country') != "AU") {
        return array();
    }

    $options = $_this->get('options');

    $originalZipcode = $_this->config->getComplex('Company.location_zipcode');
    $weight = $_this->getWeightInGrams($order);
    if (is_null($order->get('profile'))) {
    	$destinationCountry = $_this->config->getComplex('General.default_country');
    	$destinationZipcode = $_this->config->getComplex('General.default_zipcode');
    } else {
    	$profile 	 = $order->get('profile');
    	$destinationCountry = $profile->get('shipping_country');
    	$destinationZipcode = $profile->get('shipping_zipcode');
    }

    $fields = array
    (
        "weight"			=> $weight,
        "origin_zipcode"	=> $originalZipcode,
        "dest_zipcode"		=> $destinationZipcode,
        "dest_country"		=> $destinationCountry,
        "height"			=> $options->height,
        "width"				=> $options->width,
        "length"			=> $options->length
    );

    if (($cached = $_this->_checkCache('aupost_cache', $fields)) != false) {
        return $cached;
    }
    $rates = $_this->filterEnabled($_this->queryRates($options, $originalZipcode, $destinationZipcode, $destinationCountry, $order));
    $_this->_cacheResult('aupost_cache', $fields, $rates);
    // add shipping markups
    $rates = $_this->serializeCacheRates($rates);
    $rates = $_this->unserializeCacheRates($rates);
    return $rates;

}

function Shipping_aupost_queryRates($_this, $options, $originalZipcode, $destinationZipcode, $destinationCountry, $weight, $weight_unit=null) 
{
    $ap_host = "http://drc.edeliver.com.au";
    $ap_url = "/ratecalc.asp";

    $stypes = array
    (
        "STANDARD"	=> "Australia Post Regular Parcels",
        "EXPRESS"	=> "Australia Post Express Parcels",
        "AIR"		=> "Australia Post Air Mail",
        "SEA"		=> "Australia Post Sea Mail",
        "ECI_D"     => "Australia Post Express Courier International Document",
        "ECI_M"     => "Australia Post Express Courier International Merchandise",
        "EPI"       => "Australia Post Express Post International"        
    );
    
    $weight = $_this->getWeightInGrams($weight, $weight_unit);

    $rates_data = array();
    $error_arose = false;
    $php_last_errormsg = "";
    $_this->last_error = "";

    require_once LC_EXT_LIB_DIR . 'PEAR.php';
    require_once LC_EXT_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

    foreach ($stypes as $stype => $stype_name) {
        $_this->error = "";

        try {
            $http = new HTTP_Request2($ap_host . $ap_url);
            $http->setConfig('timeout', 5);

            $http->addPostParameter('Pickup_Postcode', $originalZipcode);
            $http->addPostParameter('Destination_Postcode', $destinationZipcode);
            $http->addPostParameter('Country', $destinationCountry);
            $http->addPostParameter('Weight', ceil($weight));
            $http->addPostParameter('Length', ceil($options->length));
            $http->addPostParameter('Width', ceil($options->width));
            $http->addPostParameter('Height', ceil($options->height));
            $http->addPostParameter('Quantity', '1');
            $http->addPostParameter('Service_type', $stype);

            $result = $http->send()->getBody();

        } catch (Exception $e) {
            $error_arose = true;
            $_this->error = $e->getMessage();
            $_this->last_error = $_this->error;
            continue;
        }

        $return = array();

        if (preg_match_all("/^([^=]+)=(.*)$/m", $result, $preg)) {
            foreach ($preg[1] as $k => $v) {
                $return[$v] = trim($preg[2][$k]);
            }
        }
        if ($return['err_msg'] == "OK") {
            $rates_data[] = array 
            (
            	"rate" 			=> $return['charge'], 
            	"shipping_time" => $return['days'],
            	"name" 			=> $stype_name,
            );
        }
    }

    if ($error_arose) {
        $_this->error = $_this->last_error;
        $php_errormsg = $php_last_errormsg;
    }
    if (is_array($rates_data) && count($rates_data) > 0) {
        $destination = ("AU" == $destinationCountry) ? "L" : "I";
        return $_this->parseResponse($rates_data, $destination);
    } else {
        return array();
    }
}

function Shipping_aupost_parseResponse($_this, $response, $destination) 
{
    $rates = array();
    $options = $_this->get('options');
    $currency_rate = ($options->currency_rate) ? $options->currency_rate : 1;

    if (is_array($response)) {
    	foreach ($response as $_rate) {
    		$shipping = $_this->getService('aupost', $_rate['name'], $destination);
            $shipping->shipping_time = $_rate['shipping_time'];
            $id = $shipping->get('shipping_id');
            $rates[$id] = new XLite_Model_ShippingRate();
            $rates[$id]->shipping = $shipping;
            $rates[$id]->rate = (double) ($_rate['rate'] / $currency_rate);
    	}
    }
    return $rates;
}
?>

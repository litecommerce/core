<?php
/*
* Hidden methods
*/

function Shipping_intershipper_parseResponse($_this, $response, $destination)
{
    // original code of Shipping_intershipper::_parseResponse()
    $_this->error = "";
    $_this->xmlError = false;
    $xml = new XLite_Model_XML();
    $tree = $xml->parse($response);
// print "<pre>"; print_r($tree);
    if (!$tree) {
        $_this->error = $xml->error;
        $_this->xmlError = true;
        $_this->response = $xml->xml;
        return array();
    }
    $_this->response = htmlspecialchars($response);

    // check for error
    if (isset($tree["SHIPMENT"]["ERROR"])) {
        $_this->error = $tree["SHIPMENT"]["ERROR"];
        return array();
    }
    // enumerate services
    $rates = array();
    if (isset($tree["SHIPMENT"]["PACKAGE"][1]["QUOTE"])) {
        foreach ($tree["SHIPMENT"]["PACKAGE"][1]["QUOTE"] as $quote) {
            $carrier = $_this->carriers[$quote["CARRIER"]["CODE"]];
            $service = $quote["SERVICE"]["NAME"];
            $serviceCode = $quote["SERVICE"]["CODE"];
            if (isset($_this->translations[$serviceCode])) {
                $service = $_this->translations[$serviceCode];
            }
            // strip the shipping service code  off
            // if service starts with "UPS ...", "DHL..." etc
            $servCode = substr($service, 0, 4);
            if ($servCode == 'FDX ' || $servCode == 'UPS ' || $servCode == 'USP ' || $servCode == 'DHL ') {
                $service = substr($service, 4);
            }
            // if service starts with "FedEx ...."
            $servCode = substr($service, 0, 5);
            if ($servCode == "FedEx") {
                $service = substr($service, 6);
            }

            $serviceName = "$carrier $service";
            // TODO - add 4 argument for getService()
            $shipping = $_this->getService("intershipper", $serviceName, $destination);
            $id = $shipping->get("shipping_id");
            $rates[$id] = new XLite_Model_ShippingRate();
            $rates[$id]->shipping = $shipping;
            $rates[$id]->rate = (double)$quote["RATE"]["AMOUNT"] / 100.0;
        }
    }
    return $rates;
}

function Shipping_intershipper_getRates($_this, $order)
{
    // original code of Shipping_intershipper::getRates()
    
    if (is_null($order->get("profile")) && !$_this->config->getComplex('General.def_calc_shippings_taxes')) {
        return array();
    }

    $options = $_this->getOptions();
    if (empty($options->userid)) {
        return array();
    }

    $weight = $_this->getOunces($order);
    $ZipOrigination = $_this->config->getComplex('Company.location_zipcode');
    $CountryOrigination = $_this->config->getComplex('Company.location_country');
    if (is_null($order->get("profile"))) {
        $ZipDestination = $_this->config->getComplex('General.default_zipcode');
        $CountryDestination = $_this->config->getComplex('General.default_country');
    } else {
        $ZipDestination = $order->getComplex('profile.shipping_zipcode');
        $CountryDestination = $order->getComplex('profile.shipping_country');
    }

    if ($order->get("payment_method") == "COD") {
        $cod = $order->get("subtotal");
    } else {
        $cod = 0;
    }

    // check the shipping rates cache
    $fields = array(
        "pounds"        => $weight,
        "orig_country"  => $CountryOrigination,
        "dest_country"  => $CountryDestination,
        "orig_zipcode"  => $ZipOrigination,
        "dest_zipcode"  => $ZipDestination,
        "delivery"      => $options->delivery,
        "pickup"        => $options->pickup,
        "length"        => $options->length,
        "width"         => $options->width,
        "height"        => $options->height,
        "packaging"     => $options->packaging,
        "contents"      => $options->contents,
        "codvalue"      => $cod,
        "insvalue"      => $options->insvalue);

    $rates = $_this->_checkCache("ints_cache", $fields);
    if ($rates === false) {
        $rates = $_this->_queryRates(
                    $weight, $ZipOrigination, $CountryOrigination, 
                    $ZipDestination, $CountryDestination, 
                    $options, $cod);

        if (!$_this->error) {
            // store the result in the cache
            $_this->_cacheResult("ints_cache", $fields, $rates);
            // add shipping markups
            $rates = $_this->serializeCacheRates($rates);
            $rates = $_this->unserializeCacheRates($rates);
        }
    }
    $rates = $_this->filterEnabled($rates);
    return $rates;

}
?>

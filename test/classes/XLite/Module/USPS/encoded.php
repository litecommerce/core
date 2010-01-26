<?php
/*
* Hidden methods
*/

function Shipping_usps_getRates($_this, $order)
{
    // original code
    
    if ((is_null($order->get("profile")) && !$_this->config->getComplex('General.def_calc_shippings_taxes')) || $order->get("weight") == 0 || $order->get("payment_method") == "COD") {
        return array();
    }

	$options = $_this->getOptions();
	if (empty($options->userid) || empty($options->server)) {
		return array();
	}

	if (is_null($order->get("profile"))) {
    	$destinationCountry = $_this->config->getComplex('General.default_country');
	} else {
    	$destinationCountry = $order->getComplex('profile.shipping_country');
	}
    if ($destinationCountry != $_this->config->getComplex('Company.location_country')) {
        return $_this->_getInternationalRates($order);
    } else { 
        return $_this->_getNationalRates($order);
    }
}

function Shipping_usps_parseResponse($_this, $response, $destination)
{
    // original code
    $_this->error = "";
    $_this->xmlError = false;
    $xml = new XLite_Model_XML();
    $tree = $xml->parse($response);
    if (!$tree) {
        $_this->error = $xml->error;
        $_this->xmlError = true;
        $_this->response = $xml->xml;
        return array();
    }

    // enumerate services
    $rates = array();
    if ($destination == "I") {
        if (isset($tree["INTLRATERESPONSE"]["PACKAGE"][0]["ERROR"])) {
            $_this->error = $tree["INTLRATERESPONSE"]["PACKAGE"][0]["ERROR"]["DESCRIPTION"];
            return $rates;
        }
        if (is_array($tree["INTLRATERESPONSE"]["PACKAGE"][0]["SERVICE"])) {
            foreach ($tree["INTLRATERESPONSE"]["PACKAGE"][0]["SERVICE"] as $service) {
                $serviceName = $service["SVCDESCRIPTION"];
                $shipping = $_this->getService("usps", "U.S.P.S. $serviceName", "I");
                $id = $shipping->get("shipping_id");
                $rates[$id] = new XLite_Model_ShippingRate();
                $rates[$id]->shipping = $shipping;
                $rates[$id]->rate = (double)trim($service["POSTAGE"]);
            }
        }
    } else {
    	if (is_array($tree["RATEV3RESPONSE"]["PACKAGE"])) {
            foreach ($tree["RATEV3RESPONSE"]["PACKAGE"] as $service) {
                if (isset($service["ERROR"])) {
                    $_this->error = $service["ERROR"]["DESCRIPTION"];
                    continue;
                }

				$index = "";
				while (isset($service["POSTAGE$index"])) {
					$postage = $service["POSTAGE$index"];
					$index = (empty($index)) ? 1 : $index + 1;
					$serviceName = $postage["MAILSERVICE"];
	                $shipping = $_this->getService("usps", "U.S.P.S. $serviceName", "L");
    	            $id = $shipping->get("shipping_id");
        	        $rates[$id] = new XLite_Model_ShippingRate();
            	    $rates[$id]->shipping = $shipping;
                	$rates[$id]->rate = (double)trim($postage["RATE"]);
				}
			}
        } else if (is_array($tree["RATERESPONSE"]["PACKAGE"])) { 
     		foreach ($tree["RATERESPONSE"]["PACKAGE"] as $service) { 
         		if (isset($service["ERROR"])) { 
             		$_this->error = $service["ERROR"]["DESCRIPTION"]; 
             		continue; 
         		} 
         		if (!isset($_this->translations[$service["SERVICE"]])) { 
             		continue; // fo not know anything about $service["SERVICE"] 
         		} 
         		$serviceName = $_this->translations[$service["SERVICE"]]; 
         		$shipping = $_this->getService("usps", "U.S.P.S. $serviceName", "L"); 
         		$id = $shipping->get("shipping_id"); 
         		$rates[$id] = new XLite_Model_ShippingRate(); 
         		$rates[$id]->shipping = $shipping; 
         		$rates[$id]->rate = (double)trim($service["POSTAGE"]); 
            } 
    	}
	}
    return $rates;

}

?>

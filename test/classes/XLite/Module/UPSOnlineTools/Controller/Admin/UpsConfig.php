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
* Class description.
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*
*/

class XLite_Module_UPSOnlineTools_Controller_Admin_UpsConfig extends XLite_Controller_Admin_Abstract
{

    function getOptions() 
    {
        if (!$this->options)
            $this->options = $this->config->get('UPSOnlineTools');
        return $this->options;
    }

	function getPackingTypeList()
	{
		$ups = new XLite_Module_UPSOnLineTools_Model_Shipping_Ups();	
		return $ups->get("upscontainerslist");
	}

    function action_update() 
    {
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        $settings = $this->get('settings');

		if (!isset($settings["upsoptions"]))
			$settings["upsoptions"] = array();

		// Normalize setting values
		$settings["cache_autoclean"] = intval(abs($settings["cache_autoclean"]));

		$fields = array("width", "height", "length");
		foreach ($fields as $key) {
			if (isset($settings[$key])) {
				$settings[$key] = max(MIN_DIM_SIZE, $settings[$key]);
			}
		}

        if (is_array($settings)) {
            $cc = $this->config->getComplex('Company.location_country');
            $settings['dim_units'] = (in_array($cc, array("CA","DO","PR","US"))?'inches':'cm');
            foreach($settings as $name=>$value) {
                $config = new XLite_Model_Config();
                $config->set('category', 'UPSOnlineTools');
                $config->set('name', $name);
                if ($name == 'upsoptions' && is_array($value)) {
                    $res = null;
                    foreach($value as $val) $res[$val] = 'Y';
                    $value = serialize($res);
                }
                $config->set('value', $value);
                $config->update();
            }
        }

		// Clear UPSOnlineTools cache
		$ups = new XLite_Module_UPSOnLineTools_Model_Shipping_Ups();
		$ups->_cleanCache("ups_online_tools_cache");
    }

	function getWeightUnit()
	{
		$originCountry = $this->config->getComplex('Company.location_country');
		if (in_array($originCountry, array("DO","PR","US"))) {
			return "lbs";
		} else {
			return "kg";
		}
	}

    function action_test() 
    {
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        $this->ups = new XLite_Module_UPSOnLineTools_Model_Shipping_Ups();

		$ptype = $this->xlite->getComplex('config.UPSOnlineTools.packing_algorithm');
		$total_weight = $this->get("pounds");
		$ups_containers = array();
		$container = new XLite_Module_UPSOnlineTools_Model_Container();
		switch ($ptype) {
			case BINPACKING_SIMPLE_FIXED_SIZE:
			default:
				// fixed-size container

				$container->setDimensions($this->xlite->getComplex('config.UPSOnlineTools.width'), $this->xlite->getComplex('config.UPSOnlineTools.length'), $this->xlite->getComplex('config.UPSOnlineTools.height'));
				$container->setWeightLimit(0);
				$packaging_type = 2;
			break;
			case BINPACKING_SIMPLE_MAX_SIZE:	// Max size
				$container->setDimensions(10, 10, 10);
				$container->setWeightLimit(0);
				$packaging_type = 2;
			break;
			case BINPACKING_NORMAL_ALGORITHM:	// pack all items in one package
			case BINPACKING_OVERSIZE_ALGORITHM:	// pack items in similar containers
				$packaging_type = $this->xlite->getComplex('config.UPSOnlineTools.packaging_type');
				$packData = $this->ups->getUPSContainerDims($packaging_type);
				$container->setDimensions($packData["width"], $packData["length"], $packData["height"]);
				$container->setWeightLimit($packData["weight_limit"]);
			break;
		}
		$container->setContainerType($packaging_type); // Package type
		$container->setWeight($total_weight);
		$ups_containers[] = $container;

        // Get company state
		$state_id = $this->config->getComplex('Company.location_state');
		if ($state_id != -1) {
		    $state = new XLite_Model_State($state_id);
		    $originState = $state->get('code');
		    unset($state);
		} else {
		    $originState = $this->config->getComplex('Company.custom_location_state');
		}

		// Get destination state
		$state_id = $this->get("destinationState");
		if ($state_id != -1) {
		    $state = new XLite_Model_State($state_id);
		    $destinationState = $state->get('code');
		    unset($state);
		} else {
		    $destinationState = $this->get('destination_custom_state');
		}

        $this->rates = $this->ups->_queryRates($this->get("pounds"), $this->config->getComplex('Company.location_address'), $originState, $this->config->getComplex('Company.location_city'), $this->config->getComplex('Company.location_zipcode'), $this->config->getComplex('Company.location_country'), $this->get("destinationAddress"), $destinationState, $this->get("destinationCity"), $this->get("destinationZipCode"), $this->get("destinationCountry"), $this->ups->get("options"), $ups_containers);
        $this->testResult = true;
        $this->valid = false;
    }
    
	function isGDlibEnabled()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_gdlib_valid();
	}

	function isUseDGlibDisplay()
	{
		if ($this->isGDlibEnabled() && $this->config->getComplex('UPSOnlineTools.display_gdlib'))
			return true;

		return false;
	}

    function getCountriesStates()
    {
	    $countriesArray = array();

        $country = new XLite_Model_Country();
        $countries = $country->findAll("enabled='1'");
        foreach($countries as $country) {
            $countriesArray[$country->get("code")]["number"] = 0;
            $countriesArray[$country->get("code")]["data"] = array();

            $state = new XLite_Model_State();
            $states = $state->findAll("country_code='".$country->get("code")."'");
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get("code")]["number"] = count($states);
                foreach($states as $state) {
                    $countriesArray[$country->get("code")]["data"][$state->get("state_id")] = $state->get("state");
                }
            }
        }

        return $countriesArray;
    }

    function isUseDynamicStates()
	{
	    $version = $this->config->getComplex('Version.version');
		$ver = explode(".", $version);
		unset($version);
		return $ver[0]>1 && $ver[1]>1;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Shipping module.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Shipping extends XLite_Model_Abstract implements XLite_Base_ISingleton
{
    var $fields = array(
        "shipping_id" => "",
        "class" => "", // see kernel/Shipping/*.php
        "destination" => "L", // Local/International
        "name" => "",
        "order_by" => 0,
        "enabled" => 1);

    var $alias = "shipping";
    var $autoIncrement = "shipping_id";
    var $defaultOrder = "order_by, name";

	public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

	function __construct($id = null)
	{
		global $registeredShippingModules;

		parent::__construct($id);

		if ($id && ($class = $this->get("class"))) {
			if (!is_array($registeredShippingModules) || array_search($class, $registeredShippingModules)===false) {
				// unset the class, if it is not registerred within active shipping modules
				$this->set("class", null);
			}
		}
	}

    /**
    * Obtain reference to a module by the module class
    * if $class is not specified, then obtain the current shipping module
    */
    function getInstanceByClass($class)
    {
        static $instances;
        if (!isset($instances)) {
            $instances = array();
        }

        if (!isset($instances[$class])) {
            $ClassName = "Shipping_" . $class;
            if (!func_class_exists($ClassName)) {
                $ClassName = "Shipping";
            }
            $instances[$class] = new $ClassName();
            $instances[$class]->set("class", $class);
        }
        return $instances[$class];
    }

    /**
    * Display name for "offline", "intershipper", etc.
    */
    function getModuleName()
    {
        $this->_die("getModuleName is not implemented for abstract class Shipping"); 
    }

    function getModules()
    {
        $sp = self::getInstance();
        $modules = array(
            "offline" => $sp->getInstanceByClass("offline")
        );
        global $registeredShippingModules;
        if (isset($registeredShippingModules)) {
            foreach ($registeredShippingModules as $class) {
                $modules[$class] = $sp->getInstanceByClass($class);
            }
        }    
        return $modules;
    }

    function isRegisteredModule($shipping_id) 
    {
		$query = $this->_buildSelect("shipping_id='$shipping_id'");
        if ($shipping = $this->db->getRow($query)) {
        	if ($shipping["class"] == "offline") {
            	return true;
            }
     
			global $registeredShippingModules;

			if (isset($registeredShippingModules)) {
            	foreach ($registeredShippingModules as $class) {
                	if ($class == $shipping["class"]) {
                    	return true;
                    }
				}
			}
		}

		return false;
     }
 
    function registerShippingModule($className)
    {
        global $registeredShippingModules;
        if (!isset($registeredShippingModules)) {
            $registeredShippingModules = array();
        }
        $registeredShippingModules[] = $className;
		$this->xlite->_shippingMethodRegistered = 1;
    }

    /**
    * Retrieves all shipping methods relevant to $this shipping module
    */
    function getShippingMethods()
    {
        return $this->findAll("class='".$this->get("class")."'");
    }
    
    function getRates(&$order)
    {
        $this->_die("getRates(): Not implemented in abstract class Shipping");
    }
    
    function calculate(&$order)
    {
        $s = $order->get("shippingRates");
        if (!is_array($s)){
            $this->_die(gettype($s));
        }
        if (!is_null($s) && array_key_exists($order->get("shipping_id"), $s)) {
            return $s[$order->get("shipping_id")]->rate;
        }
        return false; // N/A
    }

    function _updateProperties(array $properties = array()) // {{{
    {
        parent::_updateProperties($properties);

		$savedProperties = $this->properties;
		$shipping = self::getInstance();	
		$this->properties = $savedProperties;
    } // }}}


    /**
    * Used by real-time shipping methods to collect shipping services in
    * the xlite_shipping tables. It will create a shipping $name of class
    * $class and destination $destination (L/I) if there is no such
    * method and return an existing or a newly created one.
    */
    function getService($class, $name, $destination) 
    {
        $name = $this->_normalizeName($name);
        // search for the shipping method specified by ($class, $name)
        $shipping = new XLite_Model_Shipping();
        if ($shipping->find("class='$class' AND name='". addslashes($name)."' AND destination='$destination'")) {
            return $shipping;
        } else {
            // create a new service, disabled
            $shipping->set("class", $class);
            $shipping->set("name", $name);
            $shipping->set("destination", $destination);
            $shipping->set("enabled", 0);
            $shipping->create();
            return $shipping;
        }
    }

    function _normalizeName($name)
    {
        $name = preg_replace('/\s+/', ' ', $name);
        return trim($name);
    }

    /**
    * Return only enabled services from the $methods list
    */
    function filterEnabled($methods)
    {
        $filtered = array();
        foreach ($methods as $id => $rate) {
            if ($rate->shipping->is("enabled")) {
                $filtered[$id] = $rate;
            }
        }
        return $filtered;
    }

    function getZone(&$order)
    {
        $zone = $order->get("profile.shippingState.shipping_zone");
        if ($zone) {
            return $zone;
        }
        $zone = $order->get("profile.shippingCountry.shipping_zone");
        if ($zone) {
            return $zone;
        }
        $defaultCountry = new XLite_Model_Country($this->config->get("General.default_country"));
        if (is_object($defaultCountry)) {
        	$zone = $defaultCountry->get("shipping_zone");
        	if ($zone) {
            	return $zone;
			}
		}
        return 0;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

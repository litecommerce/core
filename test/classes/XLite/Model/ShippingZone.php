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
* ShippingZone description.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_ShippingZone extends XLite_Model_Abstract
{	
    public $fields = array(
        "shipping_zone" => 0
        );	
    public $isRead = true;    

    public function __construct($zone = null)
    {
        parent::__construct();
        if (isset($zone)) {
            $this->set("shipping_zone", $zone);
        }
    }
    
    function findAll($where = null, $orderby = null, $groupby = null, $limit = null)
    {
        $states = $this->db->getTableByAlias("states");
        $countries = $this->db->getTableByAlias("countries");
        $array1 = $this->db->getAll("SELECT DISTINCT shipping_zone from $states order by shipping_zone"); 
        $array2 = $this->db->getAll("SELECT DISTINCT shipping_zone from $countries order by shipping_zone"); 
        $array = array_merge($array1, $array2); // state zones first
        return $this->_zonesArray($array);
    }

    function findCountryZones()
    {
        $countries = $this->db->getTableByAlias("countries");
        $array = $this->db->getAll("SELECT DISTINCT shipping_zone from $countries order by shipping_zone"); 
        return $this->_zonesArray($array);
    }

    function findStateZones()
    {
        $states = $this->db->getTableByAlias("states");
        $array = $this->db->getAll("SELECT DISTINCT shipping_zone from $states order by shipping_zone"); 
        return $this->_zonesArray($array);
    }

    function _zonesArray($array)
    {
        $zones = array();
        foreach ($array as $zone) {
            $zone_object = new XLite_Model_ShippingZone();
            $zone_object->_updateProperties($zone);
            $zones[$zone["shipping_zone"]] = $zone_object;
        }
        if (!isset($zones[0])) {
            $z = new XLite_Model_ShippingZone();
            $z->set("shipping_zone", 0);
            $zones[0] = $z;
        }
        return $zones;
    }

    function find($where, $order = null)
    {
        $this->doDie("find() not applicable on ShippingZone");
    }

    function update()
    {
        $this->doDie("update() not applicable on ShippingZone");
    }

    function delete()
    {
        $this->doDie("Not implemented");    
    }

    function create()
    {
        $states = $this->db->getTableByAlias("states");
        $countries = $this->db->getTableByAlias("countries");
        $max1 = $this->db->getOne("SELECT MAX(shipping_zone) from $states"); 
        $max2 = $this->db->getOne("SELECT MAX(shipping_zone) from $countries");
        $this->set("shipping_zone", max($max1, $max2)+1);
    }

    function getCountries()
    {
        if (!isset($this->countries)) {
            $c = new XLite_Model_Country();
            $this->countries = $c->findAll("shipping_zone='".$this->get("shipping_zone")."'");
        }
        return $this->countries;
    }

    function getStates()
    {
        if (!isset($this->states)) {
            $c = new XLite_Model_State();
            $this->states = $c->findAll("shipping_zone='".$this->get("shipping_zone")."'", "country_code, state");
        }
        return $this->states;
    }

    function hasCountries()
    {
        $countries = $this->get("countries");
        return count($countries)>0;
    }

    function hasStates()
    {
        $states = $this->getStates();
        return count($states)>0;
    }

    function setCountries($countries)
    {
        $c = new XLite_Model_Country();
        foreach ($countries as $country)
        {
            $c->set("code", $country);
            $c->set("shipping_zone", $this->get("shipping_zone"));
            $c->update();
        }
        if (isset($this->countries)) {
        	unset($this->countries);
        }
    }

    function setStates($states)
    {
        $c = new XLite_Model_State();
        foreach ($states as $state)
        {
            $c->set("state_id", $state);
            $c->set("shipping_zone", $this->get("shipping_zone"));
            $c->update();
        }
        if (isset($this->states)) {
        	unset($this->states);
        }
    }

    function get($name)
    {
        if ($name == "name") {
            if ($this->get("shipping_zone") == 0) {
                return "Default zone";
            } else {
                return "Zone ".$this->get("shipping_zone");
            }
        }
        return parent::get($name);
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

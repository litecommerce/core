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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Module_UPS_Controller_Admin_Ups extends XLite_Controller_Admin_ShippingSettings
{	
    public $params = array("target", "updated");	

    public $settings;	
    public $error = '';	
    public $updated = false;	
    public $testResult = false; // this is a test request	

    public $page = "ups";	

    public $pickups = array( // pickup types
        "01" => "Daily Pickup (wholesale)",
        "03" => "Customer Counter",
        "06" => "One Time Pickup",
        "07" => "On Call Air Pickup",
        "19" => "Letter Center",
        "20" => "Air Service Center");	
    
  	public $weight_units = array (
		"LBS"	=> "lbs",
		"KGS"	=> "kgs");	 

    public $packagings = array(
        "00" => "Unknown",
        "01" => "UPS letter",
        "02" => "Package",
        "03" => "UPS Tube",
        "04" => "UPS Pak",
        "21" => "UPS Express Box",
        "24" => "UPS 25Kg Box",
        "25" => "UPS 10Kg Box");

    public function __construct(array $params)
    {
        parent::__construct($params); 
        $ups = new XLite_Module_UPS_Model_Shipping_Ups();
        $this->settings = $ups->get("options");
    }

    function action_update()
    {
        $ups = new XLite_Module_UPS_Model_Shipping_Ups();
        if (!isset($_POST["sat_delivery"])) {
            $_POST["sat_delivery"] = 0;
        }
        if (!isset($_POST["sat_pickup"])) {
            $_POST["sat_pickup"] = 0;
        }
        $ups->set("options", (object)$_POST);
        $this->set("updated","1");
    }

    /*
    * Test data
    */
    function getPounds()
    {
        return isset($this->pounds) ? $this->pounds : 1;
    }
    function getDestinationCountry()
    {
        return isset($this->destinationCountry) ? $this->destinationCountry : "US";
    }
    function getDestinationZipCode()
    {
        return isset($this->destinationZipCode) ? $this->destinationZipCode : "73003";
    }
    function getDestinationCity()
    {
        return isset($this->destinationCity) ? $this->destinationCity : "";
    }
     
    /**
    * live international test
    */
    function action_test()
    {
        $this->ups = new XLite_Module_UPS_Model_Shipping_Ups();
        $this->rates = $this->ups->_queryRates($this->get("pounds"), $this->config->getComplex('Company.location_zipcode'), $this->config->getComplex('Company.location_country'), $this->get("destinationZipCode"), $this->get("destinationCountry"), $this->ups->get("options"), 0);
        $this->testResult = true;
        $this->valid = false;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

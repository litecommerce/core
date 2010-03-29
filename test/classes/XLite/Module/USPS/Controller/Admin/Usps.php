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

class XLite_Module_USPS_Controller_Admin_Usps extends XLite_Controller_Admin_ShippingSettings
{	
    public $params = array("target", "updated");	
    public $settings;	
    public $error = '';	
    public $updated = false;	
    public $testResult = false; // this is a test request
    // test data
    //	 public $ounces = 1;
    //	 public $destinationCountry = "United Kingdom (Great Britain)";
    //	 public $ZipDestination = "73003";	
    public $page = "usps";	

    public $mailtypes = array
    (
        "Package" => "Package",
        "Postcards or Aerogrammes" => "Postcards or Aerogrammes",
        "Matter for the Blind" => "Matter for the Blind",
        "Envelope" => "Envelope"
    );	
    public $containers_express = array
    (
        "NONE"					=> "None",
		"FLAT RATE ENVELOPE"	=> "Express Mail Flat Rate Envelope",
	);	
    public $containers_priority = array
    (
        "NONE"					=> "None",
		"FLAT RATE ENVELOPE"	=> "Priority Mail Flat Rate Envelope",
		"FLAT RATE BOX"			=> "Priority Mail Flat Rate Box",
		"RECTANGULAR"			=> "Priority Mail Rectangular (Large)",
		"NONRECTANGULAR"		=> "Priority Mail Non Rectangular (Large)",
	);	
    public $fcmailtypes = array
    (
        "LETTER" 	=> "Letter",
        "FLAT" 		=> "Flat",
        "PARCEL"	=> "Parcel",
    );

    public function __construct(array $params)
    {
        parent::__construct($params);
        $usps = new XLite_Module_USPS_Model_Shipping_Usps();
        $this->settings = $usps->get("options");
    }

    function action_update()
    {
        $usps = new XLite_Module_USPS_Model_Shipping_Usps();
        $usps->set("options", (object)$_POST);
        $this->set("updated","1");
    }

    function getOunces()
    {
        return isset($this->ounces) ? $this->ounces : 1;
    }
    
    function getDestinationCountry()
    {
        return isset($this->destinationCountry) ? $this->destinationCountry : "United Kingdom (Great Britain)";
    }
    
    function getZipDestination()
    {
        return isset($this->ZipDestination) ? $this->ZipDestination : "73003";
    }
    
    /**
    * live international test
    */
    function action_int_test()
    {
        $this->usps = new XLite_Module_USPS_Model_Shipping_Usps();
        $this->set("properties", $_GET);
        $this->rates = $this->usps->_queryInternationalRates($this->get("ounces"), $this->get("destinationCountry"), $this->usps->get("options"));
        $this->testResult = true;
        $this->valid = false;
    }

    /**
    * live international test
    */
    function action_nat_test()
    {
        $this->usps = new XLite_Module_USPS_Model_Shipping_Usps();
        $this->set("properties", $_GET);
        $this->ZipOrigination = $this->config->getComplex('Company.location_zipcode');
        $this->rates = $this->usps->_queryNationalRates($this->get("ounces"), $this->get("ZipOrigination"), $this->get("ZipDestination"), $this->usps->get("options"));
        $this->testResult = true;
        $this->valid = false;
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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

class XLite_Module_Intershipper_Controller_Admin_Intershipper extends XLite_Controller_Admin_ShippingSettings
{	
    public $params = array("target", "updated");	
    public $settings;	
    public $error = '';	
    public $updated = false;	
    public $testResult = false; // this is a test request	
    public $page = "intershipper";	

    public $deliveries = array(
        "COM" => "Commercial delivery",
        "RES" => "Residential delivery",
        );	
    public $pickups = array(
        "DRP" => "Drop of at carrier location",
        "SCD" => "Regularly Scheduled Pickup",
        "PCK" => "Schedule A Special Pickup"
        );	
    public $dunits = array(
        "IN" => "Inches",
        "CM" => "Centimeters"
        );	
    public $packagings = array(
        "BOX" => "Box",
        "ENV" => "Envelope",
        "LTR" => "Letter",
        "TUB" => "Tube"
        );	
    public $contents_types = array(
        "OTR" => "Other: Most shipments will use this code",
        "LQD" => "Liquid",
        "AHM" => "Accessible HazMat",
        "IHM" => "Inaccessible HazMat"
        );

    public function __construct(array $params)
    {
        parent::__construct($params);
        $intershipper = new XLite_Module_Intershipper_Model_Shipping_Intershipper();
        $this->settings = $intershipper->get("options");
    }

    function action_update()
    {
        $intershipper = new XLite_Module_Intershipper_Model_Shipping_Intershipper();
        $intershipper->set("options", (object)$_POST);
        $this->set("updated","1");
    }

    /**
    * live international test
    */

    /* test data */
    function getDestinationCountry()
    {
        return isset($this->destinationCountry) ? $this->destinationCountry : "US";
    }
    function getDestinationZipCode()
    {
        return isset($this->destinationZipCode) ? $this->destinationZipCode : "73003";
    }
    function getOunces()
    {
        return isset($this->ounces) ? $this->ounces : 1;
    }

    function action_test()
    {
        $this->intershipper = new XLite_Module_Intershipper_Model_Shipping_Intershipper();
        $this->set("properties", $_GET);
        
        $this->rates = $this->intershipper->_queryRates($this->get("ounces"), $this->config->getComplex('Company.location_zipcode'), $this->config->getComplex('Company.location_country'), $this->get("destinationZipCode"), $this->get("destinationCountry"), $this->intershipper->get("options"), 0);
        $this->testResult = true;
        $this->valid = false; // don't returect
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

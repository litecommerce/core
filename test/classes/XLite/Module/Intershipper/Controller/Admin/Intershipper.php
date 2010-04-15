<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
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

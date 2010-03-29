<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package CanadaPost
* @access public
* @version $Id$
*/

class XLite_Module_CanadaPost_Controller_Admin_Cps extends XLite_Controller_Admin_ShippingSettings
{	
	public $params = array("target", "updated");	
	public $page		="cps";		
	public $updated 	= false;		
	public $testResult = false;	
	public $settings;		
	public $rates 		= array();

	public function __construct(array $params) // {{{ 
	{
		parent::__construct($params);

		$cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
		$this->settings = $cps->get("options");
	} // }}}
	
	function action_update() // {{{ 
	{
		$cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
		if (!isset($_POST['test_server'])) {
			$_POST['test_server'] = 0;
		}	
		$cps->set("options",(object)$_POST);
		$this->set("updated", true);

	} // }}}
	
	function action_test() // {{{ 
	{
		if (empty($this->weight)) 
			$this->weight = 1; 
		if (empty($this->destinationZipcode)) 
			$this->destinationZipcode = $this->config->getComplex('Company.location_zipcode');
        if (empty($this->destinationCountry)) 
			$this->destinationCountry = $this->config->getComplex('Company.location_country');
        $state = new XLite_Model_State($this->destinationState);
		$state = $state->get("code");
		if (empty($state)) $state = "Other";
 
		$this->cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
		$options = $this->cps->get("options");
		$options->packed == 'Y' ? $packed = "<readyToShip/>" : $packed = "";

		$this->rates = $this->cps->queryRates(
				$options, 
				$this->config->getComplex('Company.location_zipcode'), 
				$this->config->getComplex('Company.location_country'), 
				0, 
				$this->weight,
				"Test ",
				$packed, 
				$this->destinationCity,
				$this->destinationZipcode,
				$state,
				$this->destinationCountry);
		$this->testResult = true;	
		$this->valid	  = false;
	} // }}}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

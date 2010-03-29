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
* @package AustraliaPost
* @access public
* @version $Id$
*/

class XLite_Module_AustraliaPost_Controller_Admin_Aupost extends XLite_Controller_Admin_ShippingSettings
{	
	public $params = array("target", "updated");	
	public $page		="aupost";		
	public $updated 	= false;		
	public $testResult = false;	
	public $settings;		
	public $rates 		= array();

	public function __construct(array $params) // {{{ 
	{
		parent::__construct($params);

		$aupost = new XLite_Module_AustraliaPost_Model_Shipping_Aupost();
		$this->settings = $aupost->get("options");
	} // }}}
	
	function action_update() // {{{ 
	{
		$aupost = new XLite_Module_AustraliaPost_Model_Shipping_Aupost();
		$currency_rate = $_POST["currency_rate"];
		if (((double) $currency_rate) <= 0) {
			$_POST["currency_rate"] = 1;
		}
		$aupost->set("options", (object)$_POST);
		$this->set("updated", true);

	} // }}}
	
	function action_test() // {{{ 
	{
		if (empty($this->weight)) 
			$this->weight = 1; 
		if (empty($this->sourceZipcode)) 
			$this->sourceZipcode = $this->config->getComplex('Company.location_zipcode');
		if (empty($this->destinationZipcode)) 
			$this->destinationZipcode = $this->config->getComplex('Company.location_zipcode');
        if (empty($this->destinationCountry)) 
			$this->destinationCountry = $this->config->getComplex('General.default_country');
 
		$this->aupost = new XLite_Module_AustraliaPost_Model_Shipping_Aupost();
		$options = $this->aupost->get("options");

		$this->rates = $this->aupost->queryRates
		(
			$options, 
			$this->sourceZipcode,
			$this->destinationZipcode,
			$this->destinationCountry,
			$this->weight,
			$this->weight_unit
		);
		$this->testResult = true;	
		$this->valid	  = false;
	} // }}}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

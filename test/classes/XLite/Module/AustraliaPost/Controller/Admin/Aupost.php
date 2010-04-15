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

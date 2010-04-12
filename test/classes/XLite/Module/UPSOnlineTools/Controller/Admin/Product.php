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
* Product modify dialog
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/
class XLite_Module_UPSOnlineTools_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{	
	public $product_oversize = false;	
	public $product_overweight = false;	
	public $current_packaging = null;

	function handleRequest()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$product = $this->get("product");

		if ($product->get("ups_packaging") > 0) {
			$properties = array(
				"width" => $product->get("ups_width"),
				"height" => $product->get("ups_height"),
				"length" => $product->get("ups_length"),
				"handle_care" => $product->get("ups_handle_care")
			);
			$item = new XLite_Module_UPSOnlineTools_Model_PackItem();
			$item->set("properties", $properties);
			$fake_items[] = $item;

			$packaging = $this->get("currentPackaging");

			// oversize check
			$skiped = UPSOnlineTools_orientItems($packaging["width"], $packaging["length"], $packaging["height"], $fake_items);
			if (is_array($skiped) && count($skiped) > 0) {
				$this->product_oversize = true;
			}

			// overweight check
			require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
			$weight = UPSOnlineTools_convertWeight($product->get("weight"), $this->config->getComplex('General.weight_unit'), "lbs", 2);

			if ($packaging["weight_limit"] > 0 && $weight > $packaging["weight_limit"]) {
				$this->product_overweight = true;
			}
		}

		parent::handleRequest();
	}

	public function __construct(array $params)
	{
		parent::__construct($params);
		$this->pages["ups_settings"] = "UPS Settings";
		$this->pageTemplates["ups_settings"] = "modules/UPSOnlineTools/product.tpl";
	}

	function action_settings_update()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$properties = array();

		$fields = array("weight", "ups_declared_value");
		foreach ($fields as $field) {
			$properties[$field] = abs($this->get($field));
		}

		$fields = array("ups_width", "ups_height", "ups_length");
		foreach ($fields as $field) {
			$properties[$field] = max(XLite_Module_UPSOnlineTools_Model_PackItem::MIN_DIM_SIZE, $this->get($field));
		}

		$properties["ups_packaging"] = $this->get("ups_packaging");
		$properties["ups_handle_care"] = (($this->get("ups_handle_care")) ? 1 : 0);
		$properties["ups_add_handling"] = (($this->get("ups_add_handling")) ? 1 : 0);
		$properties["ups_declared_value_set"] = (($this->get("ups_declared_value_price")) ? 0 : 1);

		$product = $this->get("product");
		$product->set("properties", $properties);
		$product->update();
	}

	function getUPSPackagingList()
	{
		$ups = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();


		$list = array();
		foreach ((array)$ups->get("upscontainerslist") as $key=>$name) {
			$list[$key] = $ups->getUPSContainerDims($key);
		}

		return $list;
	}

	function getCurrentPackaging()
	{
		$ups = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
		$dims = $ups->getUPSContainerDims($this->getComplex('product.ups_packaging'));

		return $dims;
	}

	function isProductOversize()
	{
		return $this->product_oversize;
	}

	function isProductOverweight()
	{
		return $this->product_overweight;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

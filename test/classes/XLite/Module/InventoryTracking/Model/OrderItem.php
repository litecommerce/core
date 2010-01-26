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
* @package Module_InventoryTracking
* @access public
* @version $Id$
*/
class XLite_Module_InventoryTracking_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
	public function __construct() // {{{
	{
		parent::__construct();
		$this->fields["product_sku"] = "";
	} // }}}

	function set($name, $value) // {{{
	{
		$result = parent::set($name, $value);
		if (!$this->xlite->get("ProductOptionsEnabled")) return $result;
		if ($name == "options") {
			$this->assignProductSku();
		}
		return $result;
	} // }}}

	function assignProductSku() // {{{
	{
		$this->set("product_sku", parent::get('sku'));
		if (!$this->xlite->get("ProductOptionsEnabled")) return false;
		if (!$this->getComplex('product.tracking')) return false;

		$options = (array) $this->get("productOptions");
		if (empty($options)) return false;

		$key = $this->get("key");
		$inventory = new XLite_Module_InventoryTracking_Model_Inventory();
		$inventories = (array) $inventory->findAll("inventory_id LIKE '".$this->get("product_id")."|%'", "order_by");
		foreach ($inventories as $i) {
			if ($i->keyMatch($key)) {
				$sku = $i->get("inventory_sku");
				if (!empty($sku)) {
					$this->set("product_sku", $sku);
					return true;
				}
			}
		}
		return false;
	} // }}}

	function get($name) // {{{
	{
		$value = parent::get($name);
		if ($name == 'sku') {
			$sku = parent::get('product_sku');
			if (!empty($sku)) $value = $sku;
		}
		return $value;
	} // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

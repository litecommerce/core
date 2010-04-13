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
* Admin_Dialog_Order description.
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/

define("DISABLE_UPS_EDIT_ORDER", true);

class XLite_Module_UPSOnlineTools_Controller_Admin_Order extends XLite_Controller_Admin_Order implements XLite_Base_IDecorator
{
	// visual coords for default box image "ups_box.gif"	
	public $_img_left = 57;	
	public $_img_top = 17;	
	public $_img_width = 82;	
	public $_img_height = 86;

	function init()
	{
		parent::init();

		// Disable edit order if shipping method UPSOnlineTools
		if (DISABLE_UPS_EDIT_ORDER === true && $this->getComplex('order.shippingMethod.class') == "ups" && $this->xlite->get("AOMEnabled")) {
			unset($this->pages["order_edit"]);
			unset($this->pageTemplates["order_edit"]);
			if ($this->get("page") == "order_edit") {
				$this->redirect("admin.php?target=order&order_id=".$this->get("order_id")."&page=order_info");
				return;
			}
		}
	}

    function getTemplate()
    {
        if ($this->get("mode") == "container_details") {
            // container details
            return "modules/UPSOnlineTools/container_details.tpl";
        }

        return parent::getTemplate();
    }

	function getUPSContainers()
	{
		return $this->getComplex('order.ups_containers');
	}

	function countUPSContainers()
	{
		return count((array)$this->get("upscontainers"));
	}


	function hasUPSValidContainers()
	{
		$order = $this->get("order");
		if ($order->getComplex('shippingMethod.class') != "ups") {
			return false;
		}

		$containers = $this->get("upscontainers");
		return (is_array($containers) && count($containers) > 0) ? true : false;
	}


	function getUPSContainerName($container_type)
	{
		$sm = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
		$desc = $sm->getUPSContainerDims($container_type);
		return $desc["name"];
	}


	function getUPSOrderItems()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$order_id = $this->get("order_id");
		$containers = $this->get("uPSContainers");

		$items_list = array();
		$extra_items = array();

		foreach ($containers as $container) {
			foreach ((array)$container["levels"] as $level) {
				foreach ((array)$level["items"] as $item) {
					$global_id = $item["global_id"];
					if (isset($items_list[$global_id]))
						continue;

					$items_list[$global_id] = $item;
				}
			}

			// extra items
			if (is_array($container["extra_item_ids"])) {
				foreach ($container["extra_item_ids"] as $item_id) {
					$temp = array();
					$temp["item_id"] = $item_id;
					$temp["global_id"] = "-";

					$extra_items[] = $temp;
				}
			}


		}

		// merge usual & extra items
		$items_list = array_merge($items_list, $extra_items);

		ksort($items_list);

		$export_fields = array("name", "weight", "ups_width", "ups_length", "ups_height", "ups_handle_care");
		foreach ($items_list as $k=>$item) {
			$oi = new XLite_Model_OrderItem();

			if (!$oi->find("item_id='".$item["item_id"]."' AND order_id='$order_id'"))
				continue;

			$items_list[$k]["amount"] = $oi->get("amount");
			$product = $oi->get("product");

			foreach ($export_fields as $field_name) {
				$items_list[$k][$field_name] = $product->get($field_name);
			}

			$items_list[$k]["weight_lbs"] = UPSOnlineTools_convertWeight($product->get("weight"), $this->config->getComplex('General.weight_unit'), "lbs", 2);
		}

		return array_values($items_list);
	}

	function countUPSOrderItems()
	{
		return count((array)$this->get("UPSOrderItems"));
	}

	function displayLevel($container_id, $level)
	{
		$level_id = $level["level_id"];

		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$order = $this->get("order");

		if (
			XLite_Module_UPSOnlineTools_Main::isGDlibEnabled()
			&& $this->config->UPSOnlineTools->display_gdlib
		) {
			// GDlib
			return '<img src="cart.php?target=image&mode=ups_container_level_details&order_id='.$order->get("order_id").'&container='.$container_id.'&level='.$level_id.'&id='.$this->xlite->session->getID().'" border=2 alt="Container #'.($container_id + 1).', Layer #'.($level_id + 1).'">';
		}

		$containers = $order->get("ups_containers");

		if (!isset($containers[$container_id])) {
			return "<b>Resource not found!</b>";
		}

		$container = $containers[$container_id];

		if (!isset($container["levels"][$level_id])) {
			return "<b>Resource not found!</b>";
		}

		$level = $container["levels"][$level_id];


		// collect product ids
		$pids = array();
		foreach ($level["items"] as $item) {
			$pids[] = $item["item_id"];
		}
		$pids = array_unique($pids);

		// get products names by ids
		$names = array();
		foreach ($pids as $id) {
			$po = new XLite_Model_Product($id);
			$names[$id] = $po->get("name");
		}

		// assign product name to title
		foreach ($level["items"] as $k=>$v) {
			$id = $v["item_id"];
			$level["items"][$k]["title"] = $names[$id];
		}

		// display container details based on <div>...</div>
		$result = UPSOnlineTools_displayLevel_div($container["width"], $container["length"], $level["items"], $level["dirt_spaces"], $this->config->getComplex('UPSOnlineTools.visual_container_width'), $level_id);

		return $result;
	}

	function displayContainer($container_id)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$order = $this->get("order");
		$containers = $order->get("ups_containers");

		if (!isset($containers[$container_id])) {
			return "<b>Resource not found!</b>";
		}

		$container = $containers[$container_id];

		return UPSOnlineTools_displayContainer_div($this, $container, $this->_img_left, $this->_img_top, $this->_img_width, $this->_img_height);
	}

	function UPSInc1($value)
	{
		return (intval($value) + 1);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

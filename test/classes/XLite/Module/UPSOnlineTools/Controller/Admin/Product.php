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

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
 * @subpackage Model
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
class XLite_Module_UPSOnlineTools_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{

	function getDeclaredValue()
	{
		return $this->getComplex('product.declaredValue') * $this->get("amount");
	}

	function getPackItem()
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

		$p = $this->get("product"); 

		// dimension
		$item = new XLite_Module_UPSOnlineTools_Model_PackItem();
		foreach (array("width", "height", "length") as $field) {
			$item->setComplex($field, $p->get("ups_".$field));
		}

		// weight
		$weight = UPSOnlineTools_convertWeight($p->get("weight"), $this->config->getComplex('General.weight_unit'), "lbs", 2);
		if ($weight === false) {
			$weight = $item->getComplex('product.weight');
		}
		$item->set("weight", $weight);

		// declared_value
		$declared_value = $p->get("declaredValue");

		// misc
		$item->set("handle_care", $p->get("ups_handle_care"));
		$item->set("OrderItemId", $this->get("item_id"));
		$item->set("packaging", $p->get("ups_packaging"));
		$item->set("declaredValue", $declared_value);
		$item->set("additional_handling", $p->get("ups_add_handling"));

		return $item;
	}

}

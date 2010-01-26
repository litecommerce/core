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
* Represent Container class
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/

class XLite_Module_UPSOnlineTools_Model_Container extends XLite_Base
{	
	public $container_id;	
	public $width, $length, $height;	
	public $weight_limit;	
	public $weight;	
	public $levels;	

	public $threshold;	
	public $optimize_method;	

	public $container_type;

	// shipping params	
	public $additional_handling = false;	
	public $declared_value = 0;	
	public $declared_value_set = false;	

	public $extra_item_ids = null;

	public function __construct()
	{
		$this->setThreshold(5);
		$this->getWeightLimit(0);
		$this->setOptimizeMethod(OPTIMIZE_ALL);

		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
	}


	function progressive_solve(&$items)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_progressive_solve($this, $items);
	}

	function progressive_placeItem(&$level, &$items, $item_weight_limit)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_progressive_placeItem($this, $level, $items, $item_weight_limit);
	}

	function setDimensions($_width, $_length, $_height)
	{
		$this->width = round($_width, 2);
		$this->length = round($_length, 2);
		$this->height = round($_height, 2);

		$threshold = (($_width + $_length + $_height) / 60);
		$this->setThreshold(sprintf("%.02f", doubleval($threshold)));
	}

	function setWeightLimit($_weight)
	{
		$this->weight_limit = round($_weight, 2);
	}

	function getDimensions()
	{
		return array($this->width, $this->length, $this->height);
	}

	function getWeightLimit()
	{
		return round($this->weight_limit, 2);
	}

	function getWeight()
	{
		if ($this->getLevelsCount() <= 0)
			return $this->weight;

		$weight = 0;

		foreach ($this->getLevels() as $level) {
			$weight += $level->getWeight();
		}

		return round($weight, 2);
	}

	function setWeight($_weight)
	{
		$this->weight = round($_weight, 2);
	}

	function addLevel($_level)
	{
		if (!is_array($this->levels))
			$this->levels = array();

		$_level->finalize($this->getLevelsCount());
		$this->levels[] = $_level;
	}

	function getLevels()
	{
		if (!is_array($this->levels))
			$this->levels = array();

		return $this->levels;
	}


	function getNextLevel($use_overlaped=true)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_getNextLevel($this, $use_overlaped);
	}


	function getLevelsCount()
	{
		return count($this->getLevels());
	}

	function setThreshold($val)
	{
		$this->threshold = $val;
	}

	function setOptimizeMethod($val)
	{
		$this->optimize_method = $val;
	}


	function setContainerType($type)
	{
//		$this->container_type = sprintf("%02d", intval($type));
		$this->container_type = intval($type);
	}

	function getContainerType()
	{
		return $this->container_type;
	}

	function setAdditionalHandling($value=true)
	{
		$this->additional_handling = $value;
	}

	function isAdditionalHandling()
	{
		return $this->additional_handling;
	}

	function setDeclaredValue($value)
	{
		$this->declared_value = round($value, 2);
		$this->declared_value_set = true;
	}

	function getDeclaredValue()
	{
		if ($this->declared_value_set || $this->getLevelsCount() <= 0)
			return $this->declared_value;
			
		$summ = 0;
		foreach ((array)$this->getLevels() as $level) {
			foreach ((array)$level->getItems() as $item) {
				$summ += $item->getComplex('orderItem.product.declaredValue');
			}
		}

		return $summ;
	}

	function setExtraItemIds($_item_ids)
	{
		if (is_array($_item_ids) && count($_item_ids) > 0) {
			$this->extra_item_ids = array_unique($_item_ids);
		}
	}

	function addExtraItemIds($item_id)
	{
		if (!is_array($this->extra_item_ids))
			$this->extra_item_ids = array();

		$this->extra_item_ids[] = $item_id;
		$this->extra_item_ids = array_unique($this->extra_item_ids);
	}

	function export()
	{
		$vars = array();
		$vars["container_id"] = $this->container_id;
		$vars["width"] = $this->width;
		$vars["length"] = $this->length;
		$vars["height"] = $this->height;
		$vars["weight"] = $this->getWeight();
		$vars["weight_limit"] = $this->weight_limit;
		$vars["container_type"] = $this->container_type;
		$vars["additional_handling"] = (($this->isAdditionalHandling()) ? 1 : 0);
		$vars["declared_value"] = $this->getDeclaredValue();

		$_levels = array();
		foreach ((array)$this->levels as $level) {
			$_levels[] = $level->export();
		}
		$vars["levels"] = $_levels;

		if (!is_null($this->extra_item_ids)) {
			$vars["extra_item_ids"] = $this->extra_item_ids;
		}

		return $vars;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

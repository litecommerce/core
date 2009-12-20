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
* Represent Container Level class
* Container Level object contain ContainerItems & Sub-spaces.
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/
class ContainerLevel extends Object
{
	var $level_id;
	var $bottom;
	var $height;

	var $subspaces;
	var $items;

	var $used_spaces;
	var $dirt_spaces;

	// Initialize level
	function init($_bottom, $_width, $_length, $_height)
	{
		$subspace =& func_new("Subspace");
		$subspace->init($_width, $_length);

		$this->subspaces = array();
		$this->subspaces[] = $subspace;

		$this->bottom = $_bottom;
		$this->height = $_height;
	}


	////////////////////////////////////////////////
	// Subspace work functions
	function getSubspaces()
	{
		if (!is_array($this->subspaces))
			$this->subspaces = array();

		return $this->subspaces;
	}

	function setSubspaces($_subspaces)
	{
		$this->subspaces = array();

		if (!is_array($_subspaces)) {
			return;
		}

		// refine subspaces - remove all 'null'
		foreach ($_subspaces as $subspace) {
			if (!$subspace->isNull())
				$this->subspaces[] = $subspace;
		}
	}



	//////////////////////////////////////////////////
	// Items work functions
	function addItem($_item)
	{
		if (!is_array($this->items))
			$this->items = array();

		$this->items[] = $_item;
	}

	function getItems()
	{
		if (!is_array($this->items))
			$this->items = array();

		return $this->items;
	}

	function getItemsCount()
	{
		return count($this->getItems());
	}


	////////////////////////////////////////////////
	// Level dimensional functions
	function getHeight()
	{
		return $this->height;
	}

	function getMaxHeight()
	{
		$max = 0;
		foreach ($this->getItems() as $item) {
			$max = max($max, $item->getHeight());
		}

		return $max;
	}

	function getMediumHeight()
	{
		$count = 0;
		$height = 0;

		foreach ($this->getItems() as $item) {
			$count++;
			$height += $item->getHeight();
		}

//		return ceil($height / $count); // << round to 1 inch - not useful
		return round(($height / $count), 1);
	}

	function getBottomHeight()
	{
		return $this->bottom;
	}

	function getWeight()
	{
		$weight = 0;

		foreach ($this->getItems() as $item) {
			$weight += $item->getWeight();
		}

		return round($weight, 2);
	}


	//////////////////////////////////////////////
	// finalize level
	function finalize($_id)
	{
		$this->level_id = $_id;
		$this->height = $this->getMaxHeight();
	}



	/////////////////////////////////////////////
	// dirt spaces work functions
	// dirt - space that used by ContainerItem(s) from lower level(s)
	function getDirtSpaces()
	{
		if (!is_array($this->dirt_spaces)) {
			$this->dirt_spaces = array();
		}

		return $this->dirt_spaces;
	}

	function setDirtSpaces($_spaces)
	{
		$this->dirt_spaces = (array)$_spaces;
	}



	/////////////////////////////////////////////
	// used spaces work functions
	function getUsedSpaces()
	{
		if (!is_array($this->used_spaces)) {
			$this->used_spaces = array();
		}

		return $this->used_spaces;
	}

	function setUsedSpaces($_spaces)
	{
		$this->used_spaces = (array)$_spaces;
	}

	function addUsedSpace($space)
	{
		if (!is_array($this->used_spaces)) {
			$this->used_spaces = array();
		}

		$this->used_spaces[] = $space;
	}

	function optimizeSubspaces($method)
	{
		include_once "modules/UPSOnlineTools/encoded.php";

		// optimize: try to combine subspaces
		UPSOnlineTools_optimize_subspaces($this->subspaces, $method);
	}


	function export()
	{
		$vars = array();
		$vars["level_id"] = $this->level_id;
		$vars["bottom"] = $this->getBottomHeight();
		$vars["height"] = $this->height;

/*
		foreach((array)$this->subspaces as $subspace) {
			$vars["subspaces"][] = $subspace->export();
		}
//*/

		foreach ((array)$this->items as $item) {
			$vars["items"][] = $item->export();
		}

		// ignore, do not export: used_spaces;

		foreach ((array)$this->dirt_spaces as $dirt_space) {
			$vars["dirt_spaces"][] = $dirt_space->export();
		}

		return $vars;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

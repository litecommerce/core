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
*
* @package Module_FlyoutCategories
* @access public
* @version $Id$
*/

class XLite_Module_FlyoutCategories_Model_FCategoriesScheme extends XLite_Model_Abstract
{
	var $fields = array
	(
		"scheme_id" 	=> 0,
		"name"			=> "",
		"order_by" 		=> 0,
		"templates"		=> "",
		"max_depth"		=> "7",
		"explorer"		=> 0,
		"options"		=> '',
	);
	var $primaryKey = array("scheme_id");
    var $autoIncrement = "scheme_id";
	var $defaultOrder = "order_by, name";
	var $alias = "fcategories_schemes";

	var $commercialSkins = array(
        "Icons" => array("FashionBoutique", "GiftsShop", "SummerSports", "WinterSports"),
        "Explorer" => array("FashionBoutique", "GiftsShop", "SummerSports", "WinterSports"),
        "Horizontal Menu" => array("FashionBoutique", "GiftsShop", "SummerSports", "WinterSports"),
        "Candy" => array("FashionBoutique", "GiftsShop", "SummerSports", "WinterSports")
	);

	function getFileName()
	{
		$fname = sprintf("%03d", $this->get("scheme_id")) . "_" . str_replace(" ", "_", $this->get("name"));
		return $fname;
	}

	function getTemplate($template_type)
	{
		$tplMap = array
		(
			"custom_template" => "cat_template",
			"sc_custom_template" => "scat_template",
		);
        
        return $this->get($tplMap[$template_type]);
	}

	function get($name)
	{
		$value = parent::get($name);
		if ($name == "options") {
			$value = unserialize($value);
			if (!is_array($value))
				$value = array();
			$value = $this->updateCommercialSkinsColors($value);
		}

		return $value;
	}

	function set($name, $value)
	{
		if ($name == "options") {
			if (!is_array($value))
				$value = array();

			// set default value
			foreach ($value as $key=>$val) {
				if ($val["type"] != "select_box")
					continue;

				if (strlen(trim($val["value"])) <= 0)
					$value[$key]["value"] = $val["points"][0];
			}

			$value = serialize($value);
		}

		parent::set($name, $value);
	}

	function isDefaultScheme()
	{
		$id = $this->get("scheme_id");
		return ( $id > 0 && $id <= 2 ) ? true : false;
	}

	function updateCommercialSkinsColors($options) {
		$selected_skin = $this->config->get("Skin.skin");
		$scheme = parent::get("name");
		$all_modules = (array) $this->xlite->get("mm.activeModules");
		$modules = array();
		foreach ($all_modules as $name=>$active) {
			if ($active) $modules[] = $name;
		}

		if (!empty($this->commercialSkins[$scheme]) && is_array($this->commercialSkins[$scheme])) {
			foreach ($this->commercialSkins[$scheme] as $skin) {
				// if commercial skin is installed or its module is active:
				if (($selected_skin == $skin) || in_array($skin, $modules)) {
					// add new color model to current scheme:
					if (!in_array($skin, $options['color']['points'])) {
						$options['color']['points'][] = $skin;
					}
				} else {
					// remove unused color model from current scheme:
					if ($key = array_search($skin, $options['color']['points'])) {
						unset($options['color']['points'][$key]);
					}
				}
			}
		}

		return $options;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

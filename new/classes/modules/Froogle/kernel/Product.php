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
* @package Module_Froogle
* @access public
* @version $Id$
*/
class Module_Froogle_Product extends Product
{
	function getFroogleHeadLine()
	{
		$head = array("link", "title", "description", "image_link", "product_type", "price", "brand", "condition", "expiration_date", "id"); 
// OLD VARIANT: $head = array("product_url","name","description","image_url","category","price");
		return $head;
	}

    function export($layout, $delimiter, $where = null, $orderby = null, $groupby = null)
    {
        if ($layout === "froogle") {
            // write Froogle head line
            $head = $this->getFroogleHeadLine();
            print implode("\t", $head) . "\n";
        }
        parent::export($layout, $delimiter, $where, $orderby, $groupby);
    }

    function _stripSpecials($value)
    {
		$value = parent::_stripSpecials($value);
		$value = str_replace("\t", "", $value);
		$value = str_replace("\r", "", $value);
		$value = str_replace("\n", "", $value);
		$value = strip_tags($value);
        return $value;
    }
				
    function _export($layout, $delimiter)
    {
        if ($layout === "froogle") {
            // export to froogle format
            $data = array();

            // product_url
			$data[] = $this->getFroogleProductUrl();

            // name
            $data[] = $this->getFroogleName();

            // description
            $data[] = $this->getFroogleDescription();

            // image_url (available only for images stored on disk
            $data[] = $this->getFroogleImageUrl();

			// label
			$data[] = $this->getFroogleLabel();

            // price
			$data[] = $this->getFrooglePrice();

			// brand
			$data[] = $this->getFroogleBrand();

			// condition
			$data[] = $this->getFroogleCondition();

			// expiration_date
			$data[] = $this->getFroogleExpirationDate();

			$data[] = $this->getFroogleId();

			// additional data
			$this->prepareFroogleAdditionalData($layout, $delimiter, $data);

            $data = implode("\t", $data);
            print "$data";
			if ($this->xlite->get("BaseHasImprovedExport")) {
            	print "\n";
			}
            ob_flush();
            return array();
        }
        return parent::_export($layout, $delimiter);
    }

	function getFroogleProductUrl()
	{
		return $this->xlite->shopUrl("cart.php") . "?target=product&product_id=" . $this->get("product_id");
	}

	function getFroogleName()
	{
		$name = trim($this->_stripSpecials($this->get("name")), '"');
		if (strlen($name) > 80) {
			$name = substr($name, 0, 75) . "...";
		}

		return $name;
	}

	function getFroogleDescription()
	{
		$description = trim($this->_stripSpecials($this->get("description")), '"');
		if(strlen($description) > 1000) {
			$description = substr($description, 0, 995) . "...";
		}

		return $description;
	}

	function getFroogleImageUrl()
	{
		$image = "";
		if ($this->hasImage()) {
			$img = $this->getImage();
			if ($img->get("source") == "F") {
				$image = $this->xlite->shopUrl("images/") . $img->get("data");
			}
		}

		return $image;
	}

	function getFroogleLabel()
	{
		$label = "";
		switch ($this->get("config.Froogle.export_label")) {
			case "meta_tags":
				$label = $this->_stripSpecials($this->get("meta_tags"));
			break;
			case "meta_title":
				$label = $this->_stripSpecials($this->get("meta_title"));
			break;
			case "meta_desc":
				$label = $this->_stripSpecials($this->get("meta_desc"));
			break;
			case "custom":
				$label = $this->get("config.Froogle.export_custom_label");
			break;
			default:
				// category
				if (!isset($this->_CategoriesFromProducts)) {
					$this->_CategoriesFromProducts = func_new("_CategoriesFromProducts");
				}
				$this->_CategoriesFromProducts->prodId = $this->get("product_id");
				if ($this->_CategoriesFromProducts->find("")) {
					$label = str_replace("/", " > ", trim($this->_stripSpecials($this->_CategoriesFromProducts->get("stringPath")), '"'));
				} else {
					$label = "";
				}
			break;
		}

		return $label;
	}

	function getFrooglePrice()
	{
		if (!$this->config->get("Taxes.prices_include_tax")) {
			$price = $this->get("price");
		} else {
			$price = $this->get("listPrice");
		}

		return sprintf("%.02f", $this->formatCurrency($price));
	}

	function getFroogleBrand()
	{
		return $this->xlite->get("config.Froogle.froogle_brand");
	}

	function getFroogleCondition()
	{
		return "new";
	}

	function getFroogleExpirationDate()
	{
		$exp_date = time() + ($this->xlite->get("config.Froogle.froogle_expiration") * 86400);
		return date("Y-m-d", $exp_date);
	}

	function getFroogleId()
	{
		$out = $this->xlite->get("config.Froogle.froogle_id_format");

		if (strpos($out, "%psku") !== false) {
			$out = str_replace("%psku", $this->get("sku"), $out);
		}

		if (strpos($out, "%pname") !== false) {
			$out = str_replace("%pname", $this->get("name"), $out);
		}

		if (strpos($out, "%pid") !== false) {
			$out = str_replace("%pid", $this->get("product_id"), $out);
		}

		return $out;
	}

	function prepareFroogleAdditionalData(&$layout, &$delimiter, &$data)
	{
	}

    function formatCurrency($price)
    {   
    	$isNewFC = $this->xlite->get("FroogleNewFC");
    	if (!isset($isNewFC)) {
			$classMethods = array_map("strtolower", get_class_methods(get_parent_class(get_class($this))));
			$isNewFC = in_array("formatcurrency", $classMethods);
			$this->xlite->set("FroogleNewFC", $isNewFC);
		}

		if ($isNewFC) {
			return parent::formatCurrency($price);
		} else {
        	return round($price, 2);
        }
    }               
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

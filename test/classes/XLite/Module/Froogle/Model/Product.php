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
class XLite_Module_Froogle_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
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
		return $this->xlite->getShopUrl("cart.php") . "?target=product&product_id=" . $this->get("product_id");
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
				$image = $this->xlite->getShopUrl("images/") . $img->get("data");
			}
		}

		return $image;
	}

	function getFroogleLabel()
	{
		$label = "";
		switch ($this->getComplex('config.Froogle.export_label')) {
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
				$label = $this->getComplex('config.Froogle.export_custom_label');
			break;
			default:
				// category
				if (!isset($this->_CategoriesFromProducts)) {
					$this->_CategoriesFromProducts = new XLite_Model_CategoriesFromProducts();
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
		if (!$this->config->getComplex('Taxes.prices_include_tax')) {
			$price = $this->get("price");
		} else {
			$price = $this->get("listPrice");
		}

		return sprintf("%.02f", $this->formatCurrency($price));
	}

	function getFroogleBrand()
	{
		return $this->xlite->getComplex('config.Froogle.froogle_brand');
	}

	function getFroogleCondition()
	{
		return "new";
	}

	function getFroogleExpirationDate()
	{
		$exp_date = time() + ($this->xlite->getComplex('config.Froogle.froogle_expiration') * 86400);
		return date("Y-m-d", $exp_date);
	}

	function getFroogleId()
	{
		$out = $this->xlite->getComplex('config.Froogle.froogle_id_format');

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

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
class XLite_Module_AOM_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
	public function __construct($id = null) 
	{
		$this->fields["product_name"] = '';
		$this->fields["product_sku"] = '';
		$this->fields["aom_extra"] = '';
		parent::__construct($id);
	}  

	function get($name)  
	{
		if($this->xlite->is("adminZone")) {
			$value = parent::get($name);
			if (($name == "product_name" || $name == "product_sku") && empty($value)) {
				preg_match("/^product_(.+)$/",$name, $matches);
				$value = parent::get($matches[1]);
			} 

			switch ($name) {
            	case "aom_extra":
					$value = unserialize($value);
					if (!is_array($value)) {
						$value = array();
					}
				break;

				case "properties":
					$value["aom_extra"] = unserialize($value["aom_extra"]);
					if (!is_array($value["aom_extra"])) {
						$value["aom_extra"] = array();
					}
				break;

				case "product.price":
	   				$product = $this->get("product");
   					if (!$product->is("available") || $this->xlite->AOM_product_originalPrice) {
   						$value = $this->get("originalPrice");
   					}
				break;
				
				default:
				break;
			}

			return $value;
		}	
		return parent::get($name);
	}  

    function set($name, $value)
    {
        if($this->xlite->is("adminZone")) {
            if ($name == "aom_extra") {
                $val = (is_array($value)) ? $value : array();
                $value = serialize($val);
            }
        }

        if ($this->xlite->get("preserveOriginalPrices") && $name == "price") {
        	$value = $this->getOriginalPrice();
        }

        parent::set($name, $value);
    }

    function isOriginalPrice()
    {
        $val = $this->get("aom_extra");
        return ( empty($val["original_price"]) ) ? false : true;
    }

    function getOriginalPrice()
    {
        $val = $this->get("aom_extra");
        return ( empty($val["original_price"]) ) ? 0 : $val["original_price"];
    }

    function setOriginalPrice($value)
    {
        $val = $this->get("aom_extra");
        $val["original_price"] = ( empty($value) ) ? 0 : $value;
        $this->set("aom_extra", $val);
    }

	function getKey() 
	{
		$keyValue = parent::getKey();
		if ($this->xlite->get("ProductOptionsEnabled") && strlen($keyValue) > 250) {
			$keyValue = md5($keyValue);
		}

		return $keyValue;
	} 

	function getUniqueKey()
	{
		$key = parent::getKey();
		$key .= "|".$this->get("bonusItem");
		return urlencode($key);
	}

	function setProduct($product)  
	{
		parent::setProduct($product);

		if (!is_null($product)) {
			$this->set("product_name", $product->get("name"));
			$this->set("product_sku", $product->get("sku"));
			$this->set("originalPrice", $product->get("price"));
		}
		
 	} 

	function hasWholesalePricing() 
	{
		if ($this->xlite->getComplex('mm.activeModules.WholesaleTrading')) {
			$wholesale = new XLite_Module_WholesaleTrading_Model_WholesalePricing() 	;
			return count($wholesale->findAll("product_id='" . $this->getComplex('product.product_id') . "' AND amount<= '" . $this->get("amount") . "' AND (membership='all' OR membership='" . $this->getComplex('order.profile.membership') . "')"));
		} else {
			return false;
		}
	} 

	function getProductOptionValue($optclass)
	{
		$productOptions = $this->get("productOptions");
		if (is_array($productOptions)) {
			foreach ($productOptions as $option) {
				if ($option->class == $optclass) {
					return $option->option;
				}
			}
		}
		return "";
	}

	function _buildInsert()
    {
        if (isset($this->properties['aom_extra']) && is_array($this->properties['aom_extra'])) {
            $this->properties['aom_extra'] = serialize($this->properties['aom_extra']);
        }

        return parent::_buildInsert();
    }
}

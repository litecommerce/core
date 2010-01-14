<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package AOM
* @access public
* @version $Id$
*/
class XLite_Module_AOM_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
	public function __construct($id = null) // {{{
	{
		$this->fields["product_name"] = '';
		$this->fields["product_sku"] = '';
		$this->fields["aom_extra"] = '';
		parent::__construct($id);
	} // }}} 

	function get($name) // {{{ 
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
	} // }}} 

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

	function getKey() // {{{
	{
		$keyValue = parent::getKey();
		if ($this->xlite->get("ProductOptionsEnabled") && strlen($keyValue) > 250) {
			$keyValue = md5($keyValue);
		}

		return $keyValue;
	} // }}}

	function getUniqueKey()
	{
		$key = parent::getKey();
		$key .= "|".$this->get("bonusItem");
		return urlencode($key);
	}

	function setProduct($product) // {{{ 
	{
		parent::setProduct($product);

		if (!is_null($product)) {
			$this->set("product_name", $product->get("name"));
			$this->set("product_sku", $product->get("sku"));
			$this->set("originalPrice", $product->get("price"));
		}
		
 	} // }}}

	function hasWholesalePricing() // {{{
	{
		if ($this->xlite->get("mm.activeModules.WholesaleTrading")) {
			$wholesale = new XLite_Module_WholesaleTrading_Model_WholesalePricing() 	;
			return count($wholesale->findAll("product_id='" . $this->get("product.product_id") . "' AND amount<= '" . $this->get("amount") . "' AND (membership='all' OR membership='" . $this->get("order.profile.membership") . "')"));
		} else {
			return false;
		}
	} // }}}

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

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

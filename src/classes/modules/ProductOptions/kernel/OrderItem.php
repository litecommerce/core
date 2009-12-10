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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Module_ProductOptions
* @access public
* @version $Id: OrderItem.php,v 1.30 2008/10/23 11:59:10 sheriff Exp $
*/
class Module_ProductOptions_OrderItem extends OrderItem
{
    var $options = array();

    function constructor()
    {
        parent::constructor();
        $this->fields["options"] = "";
    }

    function hasOptions()
    {
        $product =& $this->get("product");
        if (is_object($product)) {
        	return $product->hasOptions();
        } else {
        	return false;
        }
    }
    
    function setProductOptions(&$options)
    {
		$resolved_options = array();
		foreach($options as $key => $option)
			$resolved_options[stripslashes($key)] = stripslashes($option);
		
        $options = $resolved_options;
        // remove empty options
        foreach ($options as $k => $v) {
            if (!strlen(trim($options[$k]))) {
                if (isset($options[$k])) {
                	unset($options[$k]);
                }
            } else {
                $options[$k] = nl2br($options[$k]); // \n -> <br>
            }
        }
        // get product options, change option indexes to option values
        $product_options = $this->get("product.productOptions");
        foreach ($product_options as $product_option) {
            $class = $product_option->get("optclass");
            if (isset($options[$class])) {
                $option_values = $product_option->get("productOptions");
                if (!empty($option_values)) {
                    foreach ($option_values as $opt) {
                        if (strcmp($options[$class], $opt->option_id) == 0) {
                            $resolved_options[$class] = $opt->option;
                            $this->options[] = $opt;
                        }
                    }
                } else {
                    $this->options[] = (object) array(
                            "class" => $class,
                            "option" => $options[$class],
                            "surcharge" => "0"
                            );
                }
            }
        }
        // check for option exceptions
        $exceptions_list = $this->get("product.optionExceptions");
        foreach ($exceptions_list as $k => $v) {
            $exceptions = array();
            $exception = $v->get("exception");
            $columns = explode(";", $exception);
            // Trim exceptions
            foreach ($columns as $subvalue) {
                $exception = explode ("=", $subvalue);
                $exception_optclass = trim($exception[0]);
                $exception_option = trim($exception[1]);
                $exceptions[$exception_optclass] = $exception_option;
            }
            $ex_size = sizeof($exceptions);
            $ex_found = 0;
            foreach ($exceptions as $subkey => $subvalue) {
                if ($resolved_options[$subkey] == $subvalue) {
                    $ex_found++;
                }
            }
            // exception for options found
			$result = true;
            if ($ex_found == $ex_size) {
                // fill the options array from request with resolved values
                $this->set("invalidOptions", $resolved_options);
                $result = false;
            }
        }
        $this->set("options", serialize($this->options));
        return $result;
    }

    function &getProductOptions()
    {
        $options = $this->get("options");
        if (empty($options)) {
            return array();
        }
        return unserialize($options);
    }
    
    function &get($name)
    {
		$options_names = array('price', 'weight');
		if (in_array($name, $options_names)) {
			if ($name == 'price') {
				$func_name = 'calculateSurcharge';
			} else if ($name == 'weight') {
				$func_name = 'calculateWeight';
			}	
			$_opt = parent::get($name);
			$options = $this->get("options");
			if (empty($options)) {
				return $_opt;
			}
			$options = unserialize($options);
			foreach ($options as $option) {
				$_opt += $this->$func_name($option);
			}
			return $_opt;
		}
        return parent::get($name);
    }

    function getKey()
    {
        // calculate item key based on options
        $key = parent::getKey();
        $option_keys = array();
        $options = $this->get("options");
        if (empty($options)) {
            return $key;
        }
        $options = unserialize($options);
        foreach ($options as $option) {
            $option_keys[] = sprintf("%s:%s", $option->class, $option->option);
        }
        return empty($option_keys) ? $key : "$key|".implode("|", $option_keys);
    }

    function calculateSurcharge($option)
    {
		global $calcAllTaxesInside;

        if ($option->surcharge == 0) {
            return 0;
        }

        if (!$calcAllTaxesInside) {
            $product =& $this->getProduct();
            if (is_object($product)) {
                $po =& func_new("ProductOption");
                $po->set("product_id", $product->get("product_id"));

                $originalPrice = $product->get("listPrice");

				$full_price = null;
				if ($this->xlite->get("WholesaleTradingEnabled")) {
					$p = func_new("Product", $this->get("product_id"));
					$full_price = $p->getFullPrice($this->get("amount"));
					if (doubleval($full_price) != $full_price) $full_price = null;
					if (!is_null($full_price)) {
						$originalPrice = $full_price;
						if ($this->config->get("Taxes.prices_include_tax")) {
							$full_price = $p->get("price"); // restore product full price without taxes
						}
					}
				}

    			$modifiedPrice = $po->_modifiedPrice($option, false, $full_price);
                $surcharge = $modifiedPrice - $originalPrice;
            } else {
            	$surcharge = 0;
            }
        } else {
            $price = parent::get("price");

			$full_price = null;
			if ($this->xlite->get("WholesaleTradingEnabled")) {
				$p = func_new("Product", $this->get("product_id"));
				$full_price = $p->getFullPrice($this->get("amount"));
				if (doubleval($full_price) != $full_price) $full_price = null;
				if (!is_null($full_price)) {
					if ($this->config->get("Taxes.prices_include_tax")) {
						$full_price = $p->get("price"); // restore product full price without taxes
					}
					$price = $full_price;
				}
			}

            $surcharge = 0;
            // calculate percent surcharge
            if (isset($option->percent)) {
                $surcharge = $price / 100 * $option->surcharge;
            }
            // calculate absolute surcharge
            elseif (isset($option->absolute)) {
                $surcharge = $option->surcharge;
            }
        }

        return $surcharge;
    }
	
    function calculateWeight($option)
	{
		if ($option->weight_modifier == 0) {
			return 0;
		}
		$weight = parent::get("weight");
		$subweight = 0;
		// calculate percent surcharge
		if (isset($option->weight_percent)) {
			$subweight = $weight / 100 * $option->weight_modifier;
		}
		// calculate absolute surcharge
		elseif (isset($option->weight_absolute)) {
		    $subweight = $option->weight_modifier * $this->get("amount");
		}
		return $subweight;
	}
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

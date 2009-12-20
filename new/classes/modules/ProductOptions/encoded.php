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

/**
* @package Module_ProductOptions
* @access public
* @version $Id$
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

function func_get_product_options(&$_this)
{
    // check for module license
    check_module_license("ProductOptions");

    $result = array();

    if ($_this->is("empty")) {
        return $result;
    }
    $options = explode("\n", $_this->get("options"));
    $option_id = 0;
    foreach ($options as $option_line) {
        $option_line = trim(chop($option_line));
        if (strlen($option_line) == 0) {
            continue;
        }
        $option = "";
        if (strpos($option_line, "=")) {
            $option = substr($option_line, 0, strpos($option_line, "="));
        } else {
            $option = substr($option_line, 0);
        }
		
// BEGIN
		$surcharge = "";
		$weight_modifier = "";
		$changes_line = strstr($option_line, "=");
        $changes_line = str_replace("=", "", $changes_line);
        $changes_line = str_replace(" ", "", $changes_line);
		if ($changes_line != "") {
			$changes = explode(";", $changes_line);
			foreach ($changes as $line) {
				if ($line{0} == "w") { // change weight
					$weight_modifier = substr($line, 1);
        			$weight_modifier_type = (strstr($weight_modifier, "%") ? "weight_percent" : "weight_absolute");
        			$weight_modifier = str_replace("%", "", $weight_modifier);
				} else { // change price
					$surcharge = $line;
        			$surcharge_type = (strstr($surcharge, "%") ? "percent" : "absolute");
        			$surcharge = str_replace("%", "", $surcharge);
				}
			}
   			if ($surcharge == "") {
       			$surcharge = "0";
   			}
   			if ($weight_modifier == "") {
      			$weight_modifier = "0";
				$weight_modifier_type = "weight_null";
   			}
		}
// END		
        $opt = func_new("stdClass");
        $opt->class     = $_this->get("optclass");
        $opt->option_id = $option_id++;
        $opt->option    = trim($option);
        $opt->surcharge = $surcharge;
        $opt->surcharge_sign = substr($surcharge, 0, 1);
        $opt->surcharge_sign = ($opt->surcharge_sign == "+" || $opt->surcharge_sign == "-") ? $opt->surcharge_sign : "";
        $opt->surcharge_abs = sprintf("%.2f",abs($surcharge));
        $opt->isZero    = ($surcharge == 0) ? true : false;
		if ($surcharge_type) $opt->$surcharge_type = true;

// BEGIN
		$opt->weight_modifier = $weight_modifier;
        $opt->weight_modifier_sign = substr($weight_modifier, 0, 1);
        $opt->weight_modifier_sign = ($opt->weight_modifier_sign == "+" || $opt->weight_modifier_sign == "-") ? $opt->weight_modifier_sign : "";
        $opt->weight_modifier_abs = abs($weight_modifier);
		if ($weight_modifier_type) $opt->$weight_modifier_type = true;
		$opt->isWeightZero = ($weight_modifier == "" || $weight_modifier == "0") ? true : false; // XXX ivf???
		$opt->modifyParams = (!$opt->isWeightZero || !$opt->isZero);
// END

        $result[] = $opt;
    }

    return $result;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

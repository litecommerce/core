<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

function func_get_product_options($_this)
{
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
        $opt = new StdClass();
        $opt->class     = $_this->get("optclass");
        $opt->option_id = $option_id++;
        $opt->option    = trim($option);
        $opt->surcharge = $surcharge;
        $opt->surcharge_sign = substr($surcharge, 0, 1);
        $opt->surcharge_sign = ($opt->surcharge_sign == "+" || $opt->surcharge_sign == "-") ? $opt->surcharge_sign : "";
        $opt->surcharge_abs = sprintf("%.2f",abs($surcharge));
        $opt->isZero = $surcharge == 0;
        if (isset($surcharge_type)) $opt->$surcharge_type = true;

// BEGIN
        $opt->weight_modifier = $weight_modifier;
        $opt->weight_modifier_sign = substr($weight_modifier, 0, 1);
        $opt->weight_modifier_sign = ($opt->weight_modifier_sign == "+" || $opt->weight_modifier_sign == "-") ? $opt->weight_modifier_sign : "";
        $opt->weight_modifier_abs = abs($weight_modifier);
        if (isset($weight_modifier_type) && $weight_modifier_type) {
            $opt->$weight_modifier_type = true;
        }
        $opt->isWeightZero = $weight_modifier == "" || $weight_modifier == "0";
        $opt->modifyParams = (!$opt->isWeightZero || !$opt->isZero);
// END

        $result[] = $opt;
    }

    return $result;
}


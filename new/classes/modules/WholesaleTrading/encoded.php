<?php
function func_wholesaleTrading_selections($src, &$result, $tmp_val = null)
{
	check_module_license("WholesaleTrading");
	if (is_null($tmp_val)) {
		$tmp_val = array();
	}	
	if (!is_array($src[0])) {
		return;
	}	
	foreach ($src[0] as $el) {

		$c = array_slice($src, 1);
		$t2 = $tmp_val;
		$t2 []= $el;
		if (count($c) > 0) {
			func_wholesaleTrading_selections($c, $result, $t2);
		} else {
			$result[] = $t2;
		}
	}
}

function func_wholesaleTrading_parse_access($groups)
{
	check_module_license("WholesaleTrading");
	if (empty($groups)) {
		return "";
	}	

	if (in_array("all", $groups)) {
		return "all";
	}

	if (in_array("registered", $groups)) {
		return "registered";
	}

	return implode(",", $groups);
}

function func_wholesaleTrading_get_access_list($access)
{
	check_module_license("WholesaleTrading");
	return split(",", $access);
}

function func_wholesaleTrading_set_membership(&$_this, &$profile, $product)
{
	check_module_license("WholesaleTrading");
	$membership = $profile->get("membership");
	$membership_exp_date = $profile->get("membership_exp_date");

	if (!empty($membership) && $membership != $product->get('selling_membership') && !$_this->config->get("WholesaleTrading.override_membership")) return; 

	$period = array(
			"d" => 0,
			"m" => 0,
			"y" => 0
			);
	$val_period = $product->get('validaty_period');
	$p_stamp = $val_period{0};
	$p_time = substr($val_period, 1);

	// Store membership in history
	$history = $profile->get("membership_history");
	foreach($history as $hn_idx => $hn) {
		if (isset($hn["current"]) && $hn["current"]) {
			unset($history[$hn_idx]);
			break;
		}
	}

	$history_node = array();
	$history_node["membership"] = $membership;
	$history_node["membership_exp_date"] = ( empty($membership) ) ? 0 : $membership_exp_date;
	$history_node["date"] = time();
	$history_node["current"] = false;

	$history[] = $history_node;
	$profile->set("membership_history", $history);

	if ( $membership != $product->get("selling_membership") ) {
		$profile->set("membership", $product->get("selling_membership"));
		$c_time = time();
		$period['d'] = date('d', $c_time);
		$period['m'] = date('m', $c_time);
		$period['y'] = date('Y', $c_time);
	} else {
		$temp_exp_date = ( $membership_exp_date > 0 ) ? $membership_exp_date : time();
		$period['d'] = date('d', $temp_exp_date);
		$period['m'] = date('m', $temp_exp_date);
		$period['y'] = date('Y', $temp_exp_date);
	}

	switch ($p_stamp) {
		case "D":
			$period['d'] = (int)$period['d'] + (int)$p_time;			
		break;
		case "W":
			$period['d'] = (int)$period['d'] + (int)$p_time * 7;
		break;
		case "M":
			$period['m'] = (int)$period['m'] + (int)$p_time;
		break;
		case "Y":
			$period['y'] = (int)$period['y'] + (int)$p_time;
		break;
	}
	$exp_date = mktime(0, 0, 0, $period['m'], $period['d'], $period['y']);
	if (empty($p_time)) $exp_date = 0; // unset expiration date, if not defined for the product
	$profile->set("membership_exp_date", $exp_date);

	$history_node = array();
	$history_node["membership"] = $profile->get("membership");
	$history_node["membership_exp_date"] = $exp_date;
	$history_node["date"] = time();
	$history_node["current"] = true;

	$history[] = $history_node;
	$profile->set("membership_history", $history);

	$profile->update();
}

function func_wholesaleTrading_calc_global_discount(&$_this, $subtotal)
{
	check_module_license("WholesaleTrading");
	$global_discount = 0;
	$gd =& func_new('GlobalDiscount');
	$gd->set('defaultOrder', 'subtotal');
    $profile = $_this->get("profile");
	$membership = (is_object($profile)) ? $profile->get("membership") : "";
	$discounts = $gd->findAll("subtotal < $subtotal AND (membership = 'all' OR membership = '$membership')");
	if (count($discounts) != 0) {
		$applied_gd =& $discounts[count($discounts) - 1];
		if ($applied_gd->get('discount_type') == 'a') {
			$global_discount = $applied_gd->get('discount');
		} else if ($applied_gd->get('discount_type') == 'p') {
			$global_discount = $_this->formatCurrency(($subtotal * $applied_gd->get('discount')) / 100);
		}
		$_this->_applied_global_discount =& $applied_gd;
	} else {
		$_this->_applied_global_discount =& func_new('GlobalDiscount');
	}
	return $global_discount;
}
?>

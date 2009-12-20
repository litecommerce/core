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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

define(DEFAULT_DC_EXPIRATION, 3600*24*7); // a week
define(DIALOG_SORT_MODE_ALL, 0);
define(DIALOG_SORT_MODE_ACTIVE, 1);
define(DIALOG_SORT_MODE_DISABLED, 2);
define(DIALOG_SORT_MODE_USED, 3);

/**
* Admin_Dialog_DiscountCoupons description.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class Admin_Dialog_DiscountCoupons extends Admin_Dialog
{
	var $couponExists = false;

	function init()
	{
		$this->params[] = "pageID";

		if (!isset($_REQUEST["sort_mode"])) {
			// restore current filter
			$sm = $this->session->get("coupon_search_mode");
			if (is_array($sm) && (!empty($sm))) {
				$_REQUEST["sort_mode"] = $sm;
			}
		}

		parent::init();
	}

	function fillForm() // {{{
    {
    	if (!isset($this->sort_mode)) {
			$this->sort_mode = array(0=>true);
		}
		
        // default coupon properties for add form
        $this->set("properties", array(
            "coupon" => $this->generateCouponCode(),
            "times"  => 1,
            "status" => "A",
            "discount" => "0.00",
            "type"     => "absolute",
            "applyTo"  => "total",
            "expire"   => time() + DEFAULT_DC_EXPIRATION,
            "minamount"=> "0.00"));
        parent::fillForm();
		// save current filter
		$this->session->set("coupon_search_mode", $this->sort_mode);
    } // }}}

	function isSortSelected($sortMode)
	{
	    $sortMode = intval($sortMode);
		if (isset($this->sort_mode) && is_array($this->sort_mode) && isset($this->sort_mode[$sortMode]) && $this->sort_mode[$sortMode]) {
			return true;
		}
		return false;
	}

    function prepareSortConditions()
    {
		$sortConditions = array();

		if (!$this->isSortSelected(DIALOG_SORT_MODE_ALL)) {
			$sortConditionsRules = array
			(
				DIALOG_SORT_MODE_ACTIVE		=> "status='A'",
				DIALOG_SORT_MODE_DISABLED	=> "status='D'",
				DIALOG_SORT_MODE_USED		=> "status='U'",
			);
			foreach($sortConditionsRules as $rule => $ruleCond) {
				if ($this->isSortSelected($rule)) {
					$sortConditions[] = $ruleCond;
				}
			}
		}

		return $sortConditions;
    }

	function getCouponsNumber()
    {
    	$this->getCoupons();
		$couponsNumber = 0;
		if (is_array($this->_couponsArray)) {
			$couponsNumber = count($this->_couponsArray);
		}

		return $couponsNumber;
    }

    function &getCoupons() // {{{
    {
    	if (isset($this->_couponsArray)) {
    		return $this->_couponsArray;
    	}

		$dc =& func_new("DiscountCoupon");

		$condition = array("order_id='0'");
		$sortConditions = $this->prepareSortConditions();
		if (count($sortConditions) > 0) {
			$sortConditions = "(" . implode(" OR ", $sortConditions) . ")";
			$condition[] = $sortConditions;
		}

		$condition = implode(" AND ", $condition);

		$dc->fetchKeysOnly = true;
		$dc->fetchObjIdxOnly = true;

		$coupons =& $dc->findAll($condition);

		$this->_couponsArray =& $coupons;
		return $coupons;
	} // }}}

	function generateCouponCode() { // {{{
		return generate_code();
	} // }}}

	function _action_postprocess()
	{
		if (!$this->isSortSelected(DIALOG_SORT_MODE_ALL)) {
			$sortConditionsRules = array
			(
				DIALOG_SORT_MODE_ACTIVE		=> "sort_mode%5B1%5D",
				DIALOG_SORT_MODE_DISABLED	=> "sort_mode%5B2%5D",
				DIALOG_SORT_MODE_USED		=> "sort_mode%5B3%5D",
			);
			foreach($sortConditionsRules as $rule => $ruleCond) {
				if ($this->isSortSelected($rule)) {
					$this->params[] = $ruleCond;
					$this->set($ruleCond, $rule);
				}
			}
		}
	}

	function action_add() // {{{
    {
        $dc =& func_new("DiscountCoupon");
        if ($dc->find("coupon='" . $this->get("coupon") . "' AND order_id='0'")) {
            $this->valid = false;
            $this->couponExists = true;
        } else {
			$_POST['discount'] = abs($_POST['discount']);
            $dc->set("properties", $_POST);
            $dc->set("expire", $this->get("expire"));
            $dc->create();
        }

		$this->_action_postprocess();
    } // }}}

	function action_update() // {{{
	{
		if (isset($_POST["status"])) {
			foreach ($_POST["status"] as $coupon_id => $status) {
				$dc = func_new("DiscountCoupon",$coupon_id);
				$dc->set("status", $status);
				$dc->update();
			}
		}

		$this->_action_postprocess();
	} // }}}

	function action_delete() // {{{
	{
		$dc = func_new("DiscountCoupon",$this->get("coupon_id"));
		$dc->delete();

		$this->_action_postprocess();
	} // }}}

	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row,$odd_css_class, $even_css_class)
	{
		return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
	}

	function canShowChildren($dc)
	{
		if (!$dc->getChildrenCount()) return false;
		if ($this->xlite->get("config.Promotion.auto_expand_coupon_orders")) return true;
		if ($dc->get("coupon_id") != $this->get("children_coupon_id")) return false;
		return true;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

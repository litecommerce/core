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

/**
* SpecialOffer add/edit dialog. Consists of two parts: selection of
* condition/bonus type and condition/bonus options.
*
* @package Module_Promotion
* @access public
* @version $Id: SpecialOffer.php,v 1.18 2008/10/23 11:59:16 sheriff Exp $
*/
class Admin_Dialog_SpecialOffer extends Admin_Dialog
{
    var $params = array("target", "offer_id", "mode");
	var $bonusAllCountries = 1;
    var $countries = null;

	function &getCountries()
	{
        if (is_null($this->countries)) {
            $c =& func_new("Country");
            $this->countries = $c->findAll();
        }
        return $this->countries;
    }

	function &hasMemberships()
	{
		$memberships = $this->get("config.Memberships.memberships");
		return !empty($memberships);
	}

    function &getSpecialOffer()
    {
        if (is_null($this->specialOffer)) {
            if (!$this->get("offer_id")) {
                // default special offer
                $this->specialOffer =& func_new("SpecialOffer");
                // default values
                $this->specialOffer->set("conditionType", 'productAmount');
                $this->specialOffer->set("bonusType", 'discounts');
            } else {
                $this->specialOffer =& func_new("SpecialOffer",$this->get("offer_id"));
            }
        }
        return $this->specialOffer;
    }

    function fillForm()
    {
        // default form values
		$this->set("properties", $this->get("specialOffer.properties"));

	    parent::fillForm();
		
    }

    function init()
    {
        parent::init();
		$this->product =& $this->get("specialOffer.product");
		$this->category =& $this->get("specialOffer.category");
		$this->products =& $this->get("specialOffer.products");
		$this->bonusProducts =& $this->get("specialOffer.bonusProducts");
		$this->bonusPrices =& $this->get("specialOffer.bonusPrices");
		$this->bonusCategory =& $this->get("specialOffer.bonusCategory");
        $this->conditionType =& $this->get("specialOffer.conditionType");
        $this->bonusType =& $this->get("specialOffer.bonusType");
	}

	function isSelectedMembership($selected_membership)
	{
		$membership =& func_new("SpecialOfferMembership");
		$memberships = $membership->findAll("offer_id = " . $this->get("offer_id"));
		foreach($memberships as $membership_) 
			if ($selected_membership == $membership_->get("membership")) return true;
		return false;
	}
    /**
    * Submit the first form (special offer type)
    */
	
	function action_update1()
	{
		if ($_POST["conditionType"] == "eachNth") {
			$_POST["bonusType"] = "specialPrices";
		}
		$this->set("specialOffer.properties", $_POST);
		// if a new offer, adds one
		if (!$this->is("specialOffer.isPersistent")) {
			$this->call("specialOffer.create");
            $this->set("offer_id",  $this->get("specialOffer.offer_id"));
		} else {
			$this->call("specialOffer.update");
		}
        $this->set("mode", "details");
	}

    /**
    * Submit the second form (special offer details)
    */
	function action_update2()
	{
		$stayHere = false; // something is added/removed from this page
		if (!isset($_POST["bonusAllProducts"])) {
			$_POST["bonusAllProducts"] = 0; // unchecked checkbox
		} else {
			$_POST["bonusCategory_id"] = 0;
		}
		if (!isset($_POST["allProducts"])) { 
			$_POST["allProducts"] = 0; // unchecked checkbox
		} elseif ($_POST["allProducts"]) {
			$_POST["product_id"] = 0; 
			$_POST["category_id"] = 0;
		}
		$_POST["start_date"] = mktime(0,0,0,$_POST['start_dateMonth'],$_POST['start_dateDay'],$_POST['start_dateYear']);
        $_POST["end_date"] = mktime(23,59,59,$_POST['end_dateMonth'],$_POST['end_dateDay'],$_POST['end_dateYear']);

		if ($_POST['start_date'] <= time()&&$_POST['end_date'] >= time()) $_POST['status'] = 'Available'; 
		elseif ($_POST['end_date'] < time()) $_POST['status'] = 'Expired'; 
		else $_POST['status'] = 'Upcoming';
		
		$_POST['status'] == 'Expired' ? $_POST['enabled'] = 0 : $_POST['enabled'] = 1;	 
        $specialOffer =& $this->get("specialOffer");
		$specialOffer->set("properties", $_POST);
		if ($this->get("conditionType") == 'hasMembership')	{
			$membership =& func_new("SpecialOfferMembership");
			$memberships = $membership->findAll('offer_id =' . $this->get('offer_id'));
			foreach($memberships as $membership_) {
				$membership_->delete();
			}
			if (is_array($_POST['customer_memberships']))
			foreach($_POST['customer_memberships'] as $membership_) {
				$membership->set('offer_id',$this->get('offer_id'));
				$membership->set('membership',$membership_);
				$membership->create();
			}
		}	
		if ($this->get("deleteProduct")) {
			$stayHere = true;
			foreach($this->get("deleteProduct") as $product_id => $checked) {
				$specialOffer->deleteProduct(func_new("Product",$product_id), 'C');
			}
		}
		if ($this->get("deleteBonusProduct")) {
			$stayHere = true;
			foreach($this->get("deleteBonusProduct") as $product_id=>$checked) {
				$specialOffer->deleteProduct(func_new("Product",$product_id), 'B');
			}
		}
		if ($this->get("bonusAllProducts")) {
			$stayHere = true;
			$so_product =& func_new("SpecialOfferProduct");
			$so_products =& $so_product->findAll("offer_id='". $specialOffer->get("offer_id") . "' AND type='B'");
			foreach($so_products as $product) {
				$specialOffer->deleteProduct(func_new("Product", $product->get("product_id")), "B");
			}
		}
		if ($this->get("deleteBonusPrice")) {
			$stayHere = true;
			foreach($this->get("deleteBonusPrice") as $product_id => $checked) {
				list ($product_id, $category_id) = explode('_', $product_id);
				if ($product_id) {
					$product =& func_new("Product",$product_id);
				} else {
					$product = null;
				}
				if ($category_id) {
					$category =& func_new("Category",$category_id);
				} else {
					$category = null;
				}
				$specialOffer->deleteBonusPrice($product, $category);
			}
		}
		if ($this->get("changeBonusPrice")) {
			$stayHere = true;
			foreach($this->get("changeBonusPrice") as $product_id => $price) {
				list ($product_id, $category_id) = explode('_', $product_id);
				if ($product_id) {
					$product =& func_new("Product",$product_id);
				} else {
					$product = null;
				}
				if ($category_id) {
					$category =& func_new("Category",$category_id);
				} else {
					$category = null;
				}
				$specialOffer->changeBonusPrice($product, $category, $price);
			}
		}
		if ($this->get("addBonusProduct_id")) {
			$stayHere = true;
			// add bonus product
			$specialOffer->addProduct(func_new("Product",$this->get("addBonusProduct_id")), 'B');
		}
		if ($this->get("addProduct_id")) {
			$stayHere = true;
			// add product
			$specialOffer->addProduct(func_new("Product",$this->get("addProduct_id")), 'C');
		}
		if ($this->get("addBonusPriceProduct_id") || $this->get("addBonusPriceCategory_id")) {
			$stayHere = true;
			// add bonus price
			if ($this->get("addBonusPriceProduct_id")) {
				$product =& func_new("Product",$this->get("addBonusPriceProduct_id"));
			} else {
				$product = null;
			}
			if ($this->get("addBonusPriceCategory_id")) {
				$category =& func_new("Category",$this->get("addBonusPriceCategory_id"));
			} else {
				$category = null;
			}
			$specialOffer->addBonusPrice($product, $category, $this->get("addBonusPrice"), $this->get("addBonusType"));
		}
		$specialOffer->update();
		// sometimes, return back to the same page
		if (!$stayHere) {
            //$this->set("returnUrl", "admin.php?target=SpecialOffers");
            // strange behavior !?
		}
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

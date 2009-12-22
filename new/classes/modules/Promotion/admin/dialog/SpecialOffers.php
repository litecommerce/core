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
* Admin_Dialog_SpecialOffers manages a list of special offers
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class Admin_Dialog_SpecialOffers extends Admin_Dialog
{
    var $specialOffers = null;

	function getSpecialOffers()
    {
        switch ($this->get("sort")) {
            case "date_asc":
                $s_order = "date ASC";
                break;
            case "date_desc":
                $s_order = "date DESC";
                break;
            case "title_asc":
                $s_order = "title ASC";
                break;
            case "title_desc":
                $s_order = "title DESC";
                break;
            case "active_asc":
                $s_order = "enabled ASC";
                break;
            case "active_desc":
                $s_order = "enabled DESC";
                break;
            case "s_date_asc":
                $s_order = "start_date ASC";
                break;
            case "s_date_desc":
                $s_order = "start_date DESC";
                break;
            case "e_date_asc":
                $s_order = "end_date ASC";
                break;
            case "e_date_desc":
                $s_order = "end_date DESC";
                break;
            default:
                $s_order = "date ASC";
                break;
        }    
        if (is_null($this->specialOffers)) {
            $sp = func_new("SpecialOffer");
			$sp->collectGarbage();
            $this->specialOffers = $sp->findAll("status <> 'Trash'", $s_order);
        }
		foreach($this->specialOffers as $key => $offer) {
			if ($this->specialOffers[$key]->get("end_date") < time())
				$this->specialOffers[$key]->set("status","Expired"); 
			if ($this->specialOffers[$key]->get("status") == "Expired")
				$this->specialOffers[$key]->set("enabled",0);
				$this->specialOffers[$key]->update();
		}
        return $this->specialOffers;
	}

	function action_update()
	{
		// set 'active' fields
		$so = func_new("SpecialOffer");
		foreach ($so->findAll() as $specialOffer) {
			if (isset($_POST["active"]) && $_POST["active"][$specialOffer->get("offer_id")]) {
				$specialOffer->set("enabled", 1);
			} else {
				$specialOffer->set("enabled", 0);
			}
			$specialOffer->update();
		}
	}

	function action_delete()
	{
		if (!empty($this->offer_ids)) {
			foreach($this->offer_ids as $key => $value) {
		       $so = func_new("SpecialOffer",$key);
		       $so->delete();
			}
		}
	}

	function action_clone()
	{
        if (!empty($this->offer_ids)) {
            foreach($this->offer_ids as $key => $value) {
               	$so = func_new("SpecialOffer",$key);
				if ( function_exists("func_is_clone_deprecated") && func_is_clone_deprecated() ) {
	               	$clone = $so->cloneObject();
				} else {
					$clone = $so->clone();
				}
			 	$clone->set("title",$so->get("title")." (CLONE)");
				$clone->update();	
            }
        }
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

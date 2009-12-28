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
* @package WholesaleTrading
* @access public
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Controller_Customer_Profile extends XLite_Controller_Customer_Profile
{
	function init()
	{
		parent::init();
		$this->params[] = "payed_membership";
		$this->params[] = "membership_name";
	}
	
    function action_register()
    {
		parent::action_register();
        if ($this->registerForm->is("valid")) {
            $product = new XLite_Model_Product();
			if ($this->registerForm->get("profile.pending_membership") != "" && $product->find("selling_membership='" . $this->registerForm->get("profile.pending_membership") . "'")) {
				$oi = new XLite_Model_OrderItem();
				$oi->set('product', $product);
				$this->cart->addItem($oi);
				$this->updateCart();
				$this->payed_membership = true;
				$this->membership_name = $this->registerForm->get("profile.pending_membership");
			}
        }
    }

	function expDate($period)
	{
		$modifier = array(
			"month" => "m",
			"day"	=> "d",
			"year"	=> "Y"
		);
		
		return date($modifier[$period], $this->auth->get("profile.membership_exp_date"));
	}

	function isShowWholesalerFields()
	{
		if (
			$this->get('xlite.config.WholesaleTrading.WholesalerFieldsTaxId') 	== "Y" ||
			$this->get('xlite.config.WholesaleTrading.WholesalerFieldsVat') 	== "Y" ||
			$this->get('xlite.config.WholesaleTrading.WholesalerFieldsGst') 	== "Y" ||
			$this->get('xlite.config.WholesaleTrading.WholesalerFieldsPst') 	== "Y" 
			) {
				return true;
			}
			return false;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
class XLite_Module_WholesaleTrading_Controller_Admin_Profile extends XLite_Controller_Admin_Profile implements XLite_Base_IDecorator
{
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

	function action_register()
	{
		parent::action_register();
		if ($this->registerForm->get("mode") == "success") {
			$exp_type = $this->get('membership_exp_type');
			$exp_date = mktime(0, 0, 0, $this->get('membership_exp_dateMonth'), $this->get('membership_exp_dateDay'), $this->get('membership_exp_dateYear'));
			$newMembership = $this->registerForm->profile->get("membership");
			if (($exp_type == "never") || empty($newMembership)) $exp_date = 0;
			$this->registerForm->profile->set("membership_exp_date", $exp_date);
			$this->registerForm->profile->update();
		}
	}


	function action_modify()
	{
		$oldProfile = $this->profileForm->profile;
		$oldMembership = $oldProfile->get("membership");

		parent::action_modify();

		$profile = $this->profileForm->profile;
		if ( $this->profileForm->get("success") ) {
			$exp_type = $this->get('membership_exp_type');
			$exp_date = mktime(0, 0, 0, $this->get('membership_exp_dateMonth'), $this->get('membership_exp_dateDay'), $this->get('membership_exp_dateYear'));

			$newMembership = $profile->get("membership");
			if (($exp_type == "never") || empty($newMembership)) $exp_date = 0;

			if (($oldMembership != $newMembership) || ($oldProfile->get("membership_exp_date") != $exp_date)) {
				$history = $profile->get("membership_history");
        		foreach($history as $hn_idx => $hn) {
        			if (isset($hn["current"]) && $hn["current"]) {
        				unset($history[$hn_idx]);
        				break;
        			}
        		}

				if ((!empty($oldMembership)) || ($oldProfile->get("membership_exp_date") > 0)) {
    				$history_node = array();
    				$history_node["membership"] = $oldMembership;
    				$history_node["membership_exp_date"] = $oldProfile->get("membership_exp_date");
					$history_node["date"] = time();
					$history_node["current"] = false;
					$history[] = $history_node;
    			}

				$history_node = array();
				$history_node["membership"] = $newMembership;
				$history_node["membership_exp_date"] = $exp_date;
				$history_node["date"] = time();
				$history_node["current"] = true;
				$history[] = $history_node;

				$this->profileForm->profile->set("membership_history", $history);
				$this->profileForm->profile->set("membership_exp_date", $exp_date);
				$this->profileForm->profile->update();
			}
		}
	}

	function getMembershipHistory()
	{
		if (!is_object($this->profileForm->profile)) {
			return;
		}

		$history = $this->profileForm->profile->get("membership_history");
		if (is_array($history) && count($history) > 0) {
			$history = array_reverse($history);
		}

		return $history;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

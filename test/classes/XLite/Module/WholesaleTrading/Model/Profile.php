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
class XLite_Module_WholesaleTrading_Model_Profile extends XLite_Model_Profile implements XLite_Base_IDecorator
{
	var $_membershipChanged = false;
	var $_oldMembership = "";

	public function __construct($p_id = null)
	{
		$this->fields["membership_exp_date"] = 0;
		$this->fields["tax_id"] = '';
		$this->fields["vat_number"] = '';
		$this->fields["gst_number"] = '';
		$this->fields["pst_number"] = '';
		$this->fields["membership_history"] = '';
        $this->_securefields["membership_exp_date"] = "";
		$this->_securefields["membership_history"] = "";
		parent::__construct($p_id);
	}

	function _initMembershipHistory($history)
	{
		if (!is_array($history)) {
			$history = unserialize($history);
			$history = ( is_array($history) ) ? $history : array();
		}
		if (is_array($history)) {
			foreach($history as $mh_idx => $mh) {
				if (isset($mh["membership_exp_date"]) && intval($mh["membership_exp_date"]) <= 0) {
					$history[$mh_idx]["membership_exp_date"] = 0;
				}
			}
		}

		return $history;
	}

	function read()
	{
		$readStatus = parent::read();

		$this->properties["membership_history"] = $this->_initMembershipHistory($this->properties["membership_history"]);

		if ($readStatus && ($this->get('membership_exp_date') > 0) && (time() > $this->get('membership_exp_date')) ) {
			$mail = new XLite_Model_Mailer();
			$mail->profile = $this;

			// Notify customer
			$mail->adminMail = false;
			$mail->compose(
					$this->config->get("Company.orders_department"),
					$this->get("login"),
					"modules/WholesaleTrading/membership_expired");
			$mail->send();

			// Notify admin
			$mail->adminMail = true;
			$mail->compose(
					$this->config->get("Company.site_administrator"),
					$this->config->get("Company.orders_department"),
					"modules/WholesaleTrading/membership_expired_admin");
			$mail->send();

			// Unset membership
			$this->set('membership', '');
			$this->set('membership_exp_date', 0);
			$this->update();

			// Restore membership from history
			$history = $this->get("membership_history");
			if ( is_array($history) && count($history) > 0 ) {
				while (count($history) > 0) {
					$value = array_pop($history);
					$exp_date = $value["membership_exp_date"];
					if ( $exp_date > 0 && time() > $exp_date )
						continue;

					$this->set("membership", $value["membership"]);
					$this->set("membership_exp_date", $exp_date);
					$this->update();
					break;
				}

				$this->set("membership_history", $history);
				$this->update();
			}
		}

		return $readStatus;
	}

    function get($name)
    {
        $value = parent::get($name);
        if ( $name == "membership_history" ) {
        	if (!is_array($value)) {
            	$value = unserialize($value);
        	}

			$value = $this->_initMembershipHistory($value);
        }

        return $value;
    }

	function set($name, $value)
	{
		if ( $name == "membership_history" ) {
			if ( !is_array($value) )
				$value = array();
			parent::set($name, serialize($value));
		} else {
			$oldMembership = $this->get("membership");
			parent::set($name, $value);
			if ( $name == "membership" ) {
				if (!$this->_membershipChanged && $value != $oldMembership) {
					$this->_membershipChanged = true; // call membershipChanged later
					$this->_oldMembership = $oldMembership;
				}
			}
		}
	}

	function _beforeSave()
	{
		if ($this->_membershipChanged) {
			$this->membershipChanged($this->_oldMembership, $this->get("membership"));
			$this->_membershipChanged = false;
		}
		if (is_array($this->properties["membership_history"])) {
			$this->properties["membership_history"] = serialize($this->properties["membership_history"]);
		}
		parent::_beforeSave();
	}

	function membershipChanged($oldMembership, $newMembership)
	{
		$mail = new XLite_Model_Mailer();
		$mail->profile = $this;
		$mail->oldMembership = $oldMembership;
		$mail->newMembership = $newMembership;

		// Changed
		$template = "modules/WholesaleTrading/membership_changed";
		if ( empty($oldMembership) ) {		// Assigned
			$template = "modules/WholesaleTrading/membership_assigned";
		} elseif ( empty($newMembership) ) {// Unassigned
			$template = "modules/WholesaleTrading/membership_unassigned";
		}

		$mail->adminMail = false;
		$mail->compose(
				$this->config->get("Company.orders_department"),
				$this->get("login"),
				$template);
		$mail->send();
	}

    function isShowWholesalerFields()
    {
        if (
            $this->get('xlite.config.WholesaleTrading.WholesalerFieldsTaxId')   == "Y" ||
            $this->get('xlite.config.WholesaleTrading.WholesalerFieldsVat')     == "Y" ||
            $this->get('xlite.config.WholesaleTrading.WholesalerFieldsGst')     == "Y" ||
            $this->get('xlite.config.WholesaleTrading.WholesalerFieldsPst')     == "Y"
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

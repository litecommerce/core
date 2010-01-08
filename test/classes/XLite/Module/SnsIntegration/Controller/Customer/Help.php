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
* @package SnsIntegration
* @access public
* @version $Id$
*/

class XLite_Module_SnsIntegration_Controller_Customer_Help extends XLite_Controller_Customer_Help implements XLite_Base_IDecorator
{
	
    function action_contactus() // {{{
    {
		parent::action_contactus();
		
		require_once("modules/SnsIntegration/include/misc.php");
		$snsClientId  = func_get_sns_client_id();
		
		$actions = array();
		$action = "name=FillContactForm";
        $country = new XLite_Model_Country($this->get("country_id"));
        $state = new XLite_Model_State($this->get("state_id"));
        $action .= "&billing_country=" . urlencode($country->get("country"));
		$action .= "&billing_city=" . urlencode($this->get("city"));
		$action .= "&billing_fax=" . urlencode($this->get("fax"));
		$action .= "&billing_phone=" . urlencode($this->get("phone"));
		$action .= "&billing_address=" . urlencode($this->get("address"));
        $action .= "&billing_state=" . urlencode($state->get("code"));
		$action .= "&billing_zipcode=" . urlencode($this->get("zipcode"));
		$action .= "&email=" . urlencode($this->get("email"));
		$action .= "&billing_firstname=" . urlencode($this->get("firstname"));
		$action .= "&billing_lastname=" . urlencode($this->get("lastname"));
        $action .= "&enquiry=" . urlencode("Subject: ".$this->get("subj")."\n ".$this->get("body"));
		$actions[]= $action;
		func_sns_request($this->config, $snsClientId, $actions);
    } // }}}

	function handleRequest() // {{{
	{
		$mode = $this->get("mode");
		if ($mode == "privacy_statement" ||
		$mode == "terms_conditions") {

			require_once("modules/SnsIntegration/include/misc.php");
			$snsClientId  = func_get_sns_client_id();

			$actions = array();
			$action = "name=ViewLegalInfo";
			$action .= "&pageName=".urlencode($mode);
			$actions[]= $action;
			func_sns_request($this->config, $snsClientId, $actions);
			}
		parent::handleRequest();
	} // }}}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

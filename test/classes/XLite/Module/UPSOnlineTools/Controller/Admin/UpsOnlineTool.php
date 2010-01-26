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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Class description.
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*
*/

class XLite_Module_UPSOnlineTools_Controller_Admin_UpsOnlineTool extends XLite_Controller_Admin_Abstract
{

    function getCurrentStep() {
        $tmp = $this->session->get("ups_step");
        return intval($tmp);
    }

    function getLicense(&$ret) {
        $obj = new XLite_Module_UPS_Model_Shipping_Ups();
        return $obj->getAgreement($ret);
    }

    function getHaveAccount() {
        return ($this->config->getComplex('UPSOnlineTools.UPS_username') && $this->config->getComplex('UPSOnlineTools.UPS_password') && $this->config->getComplex('UPSOnlineTools.UPS_accesskey'));
    }

    function processStep_2() {
        if ($this->get("confirmed") != 'Y') {
            $this->set("returnUrl", 'admin.php?target=ups_online_tool&error=license');
            return false;
        }
        elseif($this->getLicense($license)) {
            $this->set("returnUrl", 'admin.php?target=ups_online_tool&error=http');
            return false;
        }
        return true;
    }

    function processStep_3() {
        $obj = new XLite_Module_UPS_Model_Shipping_Ups();
        $ret = $this->getReg();
        if ($tmp= $obj->setAccount($ret, $error)) {
            $this->session->set('ups_message', $error);
            return false;
        }
        return true;
    }

    function action_next() {
        $cs = $this->getCurrentStep();
        $func = 'processStep_'.$cs;
        if(method_exists($this, $func)) 
            if (!$this->$func()) return;
        $cs++;
        $tmp = $this->session->set("ups_step", $cs);
    }

    function action_cancel() {
        $tmp = $this->session->set("ups_step", 0);
    }

    function action_showlicense() {
        $result = $this->getLicense($license);
        echo $license;

		if ($result == 0) {
			echo <<<EOT
<p>
<div align='justify'><font style='FONT-FAMILY: Courier; FONT-SIZE: 10px;'>
DO YOU AGREE TO ACCESS THE UPS SYSTEMS IN ACCORDANCE WITH AND BE BOUND BY EACHOF THE TERMS AND CONDITIONS SET FORTH ABOVE?<br>

<input type="radio" name="confirmed" value="Y" OnClick="setConfirmed('Y');"> Yes, I Do Agree
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="confirmed" value="N" OnClick="setConfirmed('N');"> No, I Do Not Agree
</div>

<script language="Javascript" type="text/javascript">
	para = window.parent;
	if (para) {
		doc = para.document;

		buttons = doc.getElementById('manage_buttons');
		if (buttons)
			buttons.style.display = '';
	}

function setConfirmed(val)
{
	para = window.parent;
	if (para) {
		doc = para.document;

		obj = doc.getElementById('confirmedLicense');
		if (obj)
			obj.value = val;
	}
}
</script>
EOT;

		}

        exit(0);
    }

    function getMessage()
	{
        if (!$this->message) {
            $this->message = $this->session->get('ups_message');
            $this->session->set('ups_message', '');
        }
        return $this->message; 
    }

    function getReg()
	{
        $ret = $this->session->get("ups_profile");
		if (!is_array($ret)) {
			$ret = array();
		}

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ret = array_merge($ret, $_POST);
            $this->session->set("ups_profile", $ret);
        }

        if (empty($ret['software_installer'])) $ret['software_installer'] = 'yes';
        return $ret;
    }

    function getProfileArray()
	{
        $ret = array();

        $profile = $this->auth->getComplex('profile.properties');
        $ret['contact_name'] = $profile['billing_firstname'].' '.$profile['billing_lastname'];
        $ret['title_name'] = $profile['billing_title'];
        $ret['company'] = $profile['billing_company'];
        $ret['address'] = $profile['billing_address'];
        $ret['city'] = $profile['billing_city'];
//        $ret['state'] = $profile['billing_state'];
        $ret['country'] = $profile['billing_country'];
        $ret['postal_code'] = $profile['billing_zipcode'];
        $ret['phone'] = $profile['billing_phone'];
        $ret['email'] = $profile['login'];

		$ret['state'] = $this->auth->getComplex('profile.billingState.code');

        return $ret;
    }
 
    function action_fill_from_profile()
	{
        $profile_arr = $this->getProfileArray();
        $profile_arr = array_merge($this->getReg(), $profile_arr);
        $this->session->set("ups_profile", $profile_arr);
    }

	function getUPSStates()
	{
		$obj = new XLite_Module_UPS_Model_Shipping_Ups();
		return $obj->get("upsstates");
	}

	function getUPSCountries()
	{
		$obj = new XLite_Module_UPS_Model_Shipping_Ups();
		return $obj->get("upscountries");
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

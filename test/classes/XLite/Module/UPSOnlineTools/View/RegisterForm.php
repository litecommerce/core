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
* Registration form component.
*
* @package Module_UPSOnlineTools
* @version $Id$
*/

class XLite_Module_UPSOnlineTools_View_RegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{	
    public $upsError = false;

    function checkAddress() {
        if ($this->xlite->get("adminZone"))
			return true;

        $action_type = $_REQUEST['action_type'];

        if ($action_type == 1) { // Use suggestion
            $suggest = $this->get('suggest');
            $value = $this->session->get('ups_av_result');
            $value = $value[$suggest];

            $_REQUEST['shipping_country'] = 'US';
            $obj = new XLite_Model_State();
            if ($obj->find("country_code='US' and code='$value[state]'")) {
                $_REQUEST['shipping_state'] = $obj->get("state_id");
				$_REQUEST['shipping_custom_state'] = "";
            } else {
                $_REQUEST['shipping_state'] = -1;
				$_REQUEST['shipping_custom_state'] = $value["state"];
			}

            $_REQUEST['shipping_city'] = $value['city'];
            $_REQUEST['shipping_zipcode'] = $value['zipcode'];

            $this->session->set('ups_av_result', null);
            return true;
        }
        $this->session->set('ups_av_result', null);
		$this->session->set('ups_av_error', null);
        if($action_type == 2) { // Keep current address
            return true;
        }
        elseif($action_type == 3) { // Re-enter address
            return false;
        }
        else {
            $obj = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
            $av_result = array();

			# copy billing to shipping
            $arr = array('billing_country' => 'shipping_country', 'billing_state' => 'shipping_state', 'billing_city'=>'shipping_city', 'billing_zipcode'=>'shipping_zipcode', 'billing_custom_state'=>'shipping_custom_state');
            foreach($arr as $bil=>$ship) {
                if (empty($_REQUEST[$ship]) || ($ship == 'shipping_state' && $_REQUEST[$ship] == -1))
					$ups_used[$ship] = $_REQUEST[$bil];
                else $ups_used[$ship] = $_REQUEST[$ship];
		            $this->session->set('ups_used', $ups_used);
			}

            $result = $obj->checkAddress($ups_used["shipping_country"], $ups_used['shipping_state'], $ups_used["shipping_custom_state"], $ups_used['shipping_city'], $ups_used['shipping_zipcode'], $av_result, $request_result);
            $this->session->set('ups_av_result', $av_result);
            unset($_REQUEST['action_type']);
            $this->session->set('ups_av_profile', $_REQUEST);

			if ($result !== true && count($av_result) <= 0) { // AV return error
				$this->session->set('ups_av_error', 1);
				$this->session->set('ups_av_errorcode', $request_result["errorcode"]);
				$this->session->set('ups_av_errordescr', $request_result["errordescr"]);
			} else {
				$this->session->set('ups_av_error', 0);
				$this->session->set('ups_av_errorcode', "");
				$this->session->set('ups_av_errordescr', "");
			}

            return $result;
        }
    }

    function action_register() {
        if ($this->checkAddress()) return parent::action_register();
        $this->set("valid", false);
        $this->set('upsError', true);
    }

    function action_modify() {
        if ($this->checkAddress()) return parent::action_modify();
        $this->set("valid", false);
        $this->set('upsError', true);
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

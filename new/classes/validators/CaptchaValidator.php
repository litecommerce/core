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
* @package $Package$
* @version $Id$
*/
class CCaptchaValidator extends CFieldValidator
{
    var $template = "common/captcha_validator.tpl";
    var $validation_required = array( // array: target => array(action, option_name);
                    'help'                 => array('action'=>'contactus', 'option'=>'on_contactus'),
                    'profile'              => array('action'=>'register',  'option'=>'on_register'),
                    'partner_profile'      => array('action'=>'register',  'option'=>'on_partner_register'),
                    'add_gift_certificate' => array('action'=>'add',       'option'=>'on_add_giftcert')
                );

    function isValid()
    {
        $id = $this->get("id");

        if(!$this->isGDLibLoaded()){
            return true;
        }

        if(!$this->isActiveCaptchaPage($id))
            return true;

        if (!parent::isValid()) {
            return false;
        }

        if(!isset($_POST['action']))
            return true;

        $code = $this->session->get("captcha_".$this->get("id"));
        if(!isset($code) && $this->xlite->get("captchaValidated")) {
			return true;
        }
        $code_submitted = strtoupper(trim($_POST[$this->get("field")]));

        $result = (isset($_POST[$this->get("field")]) && !empty($_POST[$this->get("field")]) && $code == $code_submitted);
        if ($result) {
        	$this->session->set("captcha_".$this->get("id"), null);
        	$this->xlite->set("captchaValidated", true);
        }
        return $result;
    }

    function isValidationUnnecessary()
    {
        return !array_key_exists($_REQUEST['target'], $this->validation_required) || 
               !$this->validation_required[$_REQUEST['target']]['action'] == $_REQUEST['action'] ||
               !$this->isActiveCaptchaPage($requests[$_REQUEST['target']]['option']);
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

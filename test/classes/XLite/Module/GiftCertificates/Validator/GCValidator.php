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
* Checks the field to be in the range specified by min and max attributes.
*
* @package $Package$
* @version $Id$
*/
class XLite_Module_GiftCertificates_Validator_GCValidator extends XLite_Validator_Abstract
{
    var $template = "modules/GiftCertificates/gc_validator.tpl";
    var $doesnotexist = false;
    var $expired = false;
    var $notactive = false;
    var $gcid = null;
    
    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }
        if (isset($_POST[$this->get("field")])) {
            $this->gcid = $_POST[$this->get("field")] = trim($_POST[$this->get("field")]);

			// Pass validation if cert already related with current order
			$cart = XLite_Model_Cart::getInstance();
			if (is_object($cart) && !is_null($cart) && $cart->get("gcid") == $this->gcid) {
				return true;
			}

            // validate
			$gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->gcid);
            if (strlen($this->gcid) == 0) {
            	$gcStatus = GC_DOESNOTEXIST;
            } else {
				$gcStatus = $gc->validate();
            }
            switch ($gcStatus) {
            	case GC_OK: 
            		return true;
            	case GC_DOESNOTEXIST: 
            		$this->doesnotexist = true; 
            	break;
            	case GC_EXPIRED: 
            		$this->expired = true; 
            	break;
            	case GC_DISABLED: 
            		$this->notactive = true; 
            	break;
            }
            return false;
        }
        return true;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

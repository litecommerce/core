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
* @version $Id: MembershipValidator.php,v 1.8 2008/10/23 12:06:27 sheriff Exp $
*/
class CMembershipValidator extends CFieldValidator
{
    var $template = "common/membership_validator.tpl";
    
    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }
		
		if (!isset($_POST["action"])) {
			return true;
		}
		
		if ($_POST["action"] != $this->action) {
			return true;
		}

		$dialog =& $this->get("dialog");
		if (is_object($dialog) && $dialog->get("actionProcessed")) {
			return true;
		}

		preg_match('/^(.+)\[(.+)\]$/',$this->get("field"),$field);
        $result = !empty($_POST[$field[1]][$field[2]]) || !isset($_POST[$field[1]][$field[2]]);
        if ($result && isset($_POST[$field[1]][$field[2]])) {
            $membershipData = $_POST[$field[1]][$field[2]];
    		if (strlen($membershipData) == 0) {
    			return false;
    		}
			$membership =& func_new("Membership");
			if ($membershipData != $membership->stripInvalidData($membershipData)) {
				$this->set("dataInvalid", true);
				return false;
			}

    		if (strlen($membershipData) > 32) {
				$this->set("dataInvalid", true);
    			return false;
    		}
    		$memberships = $membership->findAll();
    		foreach($memberships as $membership_)
    		if ($membership_->get("membership") == $membershipData) {
    			return false;
    		}
    		$result = true;
    	}
        return $result;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

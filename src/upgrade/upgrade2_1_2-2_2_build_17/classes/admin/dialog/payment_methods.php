<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id: payment_methods.php,v 1.1 2006/07/11 06:38:15 sheriff Exp $
*
*/
class Admin_Dialog_payment_methods extends Admin_Dialog
{
	var $configurableMethods;

	function hasConfigurableMethods()
	{
		if (isset($this->configurableMethods)) {
			return $this->configurableMethods;
		}

		$this->configurableMethods = false;
	    $pm =& func_new("PaymentMethod");
	    $methods = $pm->readAll();
	    foreach($methods as $pm) {
	    	if (!is_null($pm->get("configurationTemplate"))) {
	    		$this->configurableMethods = true;
	    		break;
	    	}
	    }

	    return $this->configurableMethods;
	}

    function action_update()
    {
    	$default_offline_payment = $this->config->get("Payments.default_offline_payment");

        foreach ($this->data as $id => $data) {
            if (array_key_exists("enabled", $data)) {
                $data["enabled"] = 1;
            } else {
                $data["enabled"] = 0;
            }

            if ($data["payment_method"] == $default_offline_payment && !$data["enabled"]) {
        		$cfg =& func_new("Config");
                $cfg->createOption("Payments", "default_offline_payment", "");
            }

            $payment_method = func_new("PaymentMethod");
            $payment_method->setProperties($data);
            $payment_method->read();
            $payment_method->update();
        }
    }

    function action_default_payment()
    {
		$cfg =& func_new("Config");
        $cfg->createOption("Payments", "default_offline_payment", $this->default_payment);
		$cfg->createOption("Payments", "default_select_payment", $this->default_select_payment);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

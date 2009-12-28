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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Waiting IP list for admin zone.
*
* @package Kernel
* @access public
* @version $Id$
*/

define("NOTIFY_INTERVAL", 24 * 60 *60);

class XLite_Model_WaitingIP extends XLite_Model_Abstract
{
    var $fields = array(
                    "id" => "0",
                    "ip" => "",
                    "unique_key" => "",
                    "first_date" => "0",
                    "last_date" => "0",
                    "count" => "0"
                    );

    var $autoIncrement = "id";
    var $alias = "waitingips";

    function generateUniqueKey()
    {
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float) $sec + ((float) $usec * 1000000);
        if (isset($_SERVER["REMOTE_ADDR"])) $seed += (float) ip2long($_SERVER["REMOTE_ADDR"]);
        if (isset($_SERVER["REMOTE_PORT"])) $seed += (float) $_SERVER["REMOTE_PORT"];
        srand($seed);
        $key = md5(uniqid(rand(), true));
        return $key;
    }

    function addNew($ip)
    {
        $now = time();
        $this->set("ip", $ip);
        $this->set("first_date", $now);
        $this->set("last_date", $now);
        $this->set("count", "1");
        $this->set("unique_key", $this->generateUniqueKey());
        $this->create();
    }

    function notifyAdmin()
    {
        $mail = new XLite_Model_Mailer();
        $mail->waiting_ip = $this;
        $mail->adminMail = true;
        $mail->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mail->compose(
                $this->config->get("Company.site_administrator"),
                $this->config->get("Company.site_administrator"),
                "new_ip_notify_admin");
        $mail->send();
    }

    function canNotify()
    {
        $now = time();
        $last_date = (int) $this->get("last_date");

        return (($now - $last_date) >= NOTIFY_INTERVAL);
    }

    function approveIP()
    {
        $ip = $this->get("ip");
        $valid_ips_object = new XLite_Model_Config();

        if(!$valid_ips_object->find("category = 'SecurityIP' AND name = 'allow_admin_ip'")) {
        	$admin_ip = serialize(array());
			$valid_ips_object->createOption("SecurityIP", "allow_admin_ip", $admin_ip, "serialized");
            return;
        }

        $list = unserialize($valid_ips_object->get("value"));
        foreach($list as $ip_array) {
            if($ip_array['ip'] == $ip) {
            	return;
            }
        }

        $list[] = array("ip" => $ip, "comment" => "");

        $valid_ips_object->set("value", serialize($list));
        $valid_ips_object->update();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

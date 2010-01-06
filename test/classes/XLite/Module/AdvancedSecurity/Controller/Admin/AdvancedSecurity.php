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
* Module AdvancedSecurity
*
* @package Module_AdvancedSecurity
* @access public
* @version $Id$
*/
class XLite_Module_AdvancedSecurity_Controller_Admin_AdvancedSecurity extends XLite_Controller_Admin_Abstract
{
    var $params = array("target", "mode");
    var $sample = "The quick brown fox jumps over the lazy dog.";
    
    function action_orders() // {{{
    {
        $gpg = $this->get("gpg");
        $pubkey = $gpg->getPublicKey();
        $seckey = $gpg->getSecretKey();
        $this->set("valid", !empty($pubkey) && $gpg->isKeyValid($pubkey, "PUBLIC") && !empty($seckey) && $gpg->isKeyValid($seckey, "PRIVATE"));
        if (!$this->is("valid")) {
            $this->set("invalidKeyring", true);
            return;
        }
        if ($this->get("decrypt_orders") && !$gpg->isPasswordValid($this->get("passphrase"))) {
            $this->set("valid", false);
            $this->set("invalidOrderPassword", true);
            return;
        }
        $this->session->set("masterPassword", null); // to avoid update conflict
        $order = new XLite_Model_Order();
        $orders = $order->findAll("payment_method='CreditCard'"); 
        $this->startDump();
        for ($i = 0; $i < count($orders); $i++) {
            if ($this->get("decrypt_orders")) {
                print "Decrypting order #" . $orders[$i]->get("order_id") . " ... ";
                $orders[$i]->decrypt($this->get("passphrase"));
                print "[OK]<br>\n";
            } elseif ($this->get("encrypt_orders")) {
                print "Encrypting order #" . $orders[$i]->get("order_id") . " ... ";
                $orders[$i]->encrypt();
                print "[OK]<br>\n";
            }
        }
?>
<br><br>Order(s) processed successfully. <a href="admin.php?target=advanced_security#order_management"><u>Click here to return to admin interface</u></a>
<?php
    } // }}}
    
    function testEncrypt() // {{{
    {
        $gpg = $this->get("gpg");
        $this->encryptResult = $gpg->encrypt($this->sample);
    } // }}}

    function testDecrypt() // {{{
    {
        $gpg = $this->get("gpg");
        $this->decryptResult = $gpg->decrypt($this->encryptResult, $this->get("passphrase"));
    } // }}}
    
    function action_test() // {{{
    {
        // see template for testing details
        $this->set("valid", false); // no NOT redirect after test
    } // }}}

    function action_download_secret_key() // {{{
    {
        $gpg = $this->get("gpg");
        $downloadPass = $this->get("download_password");
        if (!is_null($downloadPass) && $gpg->isPasswordValid($downloadPass)) {
            $this->set("silent", true);
            $this->startDownload("secring.asc");
            print $gpg->get("secretKey");
        } else {
            $this->set("invalidPassword", true);
            $this->set("valid", false);
        }
    } // }}}

    function getSecurityOptions() // {{{
    {
        $config = new XLite_Model_Config();
        $options = $config->getByCategory("AdvancedSecurity");
        return $options;
    } // }}}
    
    function action_options() // {{{
    {
        $config = new XLite_Model_Config();
        $options = $config->getByCategory("AdvancedSecurity");
        for ($i=0; $i<count($options); $i++) {
            $name = $options[$i]->get("name");
            $type = $options[$i]->get("type");
            if ($type=='checkbox') {
                if (empty($_POST[$name])) {
                    $val = 'N';
                } else {
                    $val = 'Y';
                }
            } else {
				if (isset($_POST[$name])) {
	                $val = trim($_POST[$name]);
				} else {
					continue;
				}
            }

            $options[$i]->set("value", $val);
            $options[$i]->update();
        }
    } // }}}

    function action_delete_keys()
    {
        $gpg = new XLite_Module_AdvancedSecurity_Model_GPG();
        $gpg->deleteKeys();
    }
    
    function action_upload_keys()
    {
        $gpg = new XLite_Module_AdvancedSecurity_Model_GPG();
        $this->set("valid", $gpg->uploadKeys());
    }

    function getGPG() // {{{
    {
        if (is_null($this->gpg)) {
            $this->gpg = new XLite_Module_AdvancedSecurity_Model_GPG();
        }
        return $this->gpg;
    } // }}}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

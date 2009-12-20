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
* Module_AdvancedSecurity Mailer class.
*
* @package $Package$
* @version $Id$
*/
class Module_AdvancedSecurity_Mailer extends Mailer
{
    function compose($from, $to, $dir, $customHeaders = array())
    {
        parent::compose($from, $to, $dir, $customHeaders);
        // encrypt message with a public key if necessary
        if ($this->get("adminMail") && $this->get("order") && $this->get("order.details")) {
            $gpg =& func_new("GPG");
			if ($this->get("config.AdvancedSecurity.gpg_crypt_mail")) {
	            $this->logger->log("Module_AdvancedSecurity_Mailer::getBody() encrypt message");
    	        // send as a plain mail
        	    $this->mail->IsHTML(false);
            	$this->mail->Encoding = "7bit";
	            $this->mail->AltBody = "";
    	        $this->mail->Body = $gpg->encrypt($this->decodeHTML($this->get("body")));
			}

			if ($this->get("config.Email.show_cc_info")) {
				$filename = "details.txt.gpg";
				$data = $this->get("order.properties.secureDetailsText");
				if (!empty($data)) {
					$cur = count($this->mail->attachment);
					$this->mail->attachment[$cur][0] = $data;
					$this->mail->attachment[$cur][1] = $filename;
					$this->mail->attachment[$cur][2] = $filename;
					$this->mail->attachment[$cur][3] = "base64";
					$this->mail->attachment[$cur][4] = "application/pgp-encrypted";
					$this->mail->attachment[$cur][5] = true; // isStringAttachment
					$this->mail->attachment[$cur][6] = "inline";
					$this->mail->attachment[$cur][7] = $filename."@mail.lc"; // CID
				}
			}
        }
    }

    function decodeHTML($string)
    {
        $trans_tbl = get_html_translation_table (HTML_ENTITIES); 
        $trans_tbl = array_flip ($trans_tbl); 
        return str_replace("&nbsp;", "", strip_tags(strtr($string, $trans_tbl))); 
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

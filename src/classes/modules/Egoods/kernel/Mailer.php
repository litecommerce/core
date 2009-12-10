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
* @package Module_Egoods
* @access public
* @version $Id: Mailer.php,v 1.4 2008/10/23 11:53:41 sheriff Exp $
*/
class Module_Egoods_Mailer extends Mailer
{
	var $clean_after_send = true;

    function constructor() // {{{
    {
        parent::constructor();
        // Initialize PHPMailer
        require_once "PHPMailer/class.phpmailer.php";
        $this->mail = new PHPMailer();
    } // }}}

    function compose($from, $to, $dir, $customHeaders = array()) // {{{
    {
        // initialize internal properties
        $this->set("from", $from);
        $this->set("to",   $to);
        $this->set("customHeaders", $customHeaders);

        $dir .= '/';
        $this->set("subject", $this->compile($dir.$this->get("subjectTemplate")));
        $this->set("signature", $this->compile($this->get("signatureTemplate")));
        $this->set("body", $this->compile($dir.$this->get("bodyTemplate"), false));

        // find all images and fetch them; replace with cid:...
        $imageParser = func_new("MailImageParser");
        $imageParser->source = $this->get("body");
        $imageParser->webdir = $this->xlite->shopUrl("");
        $imageParser->parse();

        $this->set("body", $imageParser->result);
        $this->set("images", $imageParser->images);
		
		if (is_null($this->mail)) {
			require_once "PHPMailer/class.phpmailer.php";
			$this->mail = new PHPMailer();
		}	

        $this->mail->SetLanguage($this->get("langLocale"),
                                 $this->get("langPath"));
        $this->mail->IsHTML(true);
        $this->mail->AddAddress($this->get("to"));
        $this->mail->Encoding = "quoted-printable";
        $this->mail->From     = $this->get("from");
        $this->mail->FromName = $this->get("from");
        $this->mail->Subject  = $this->get("subject");
        $this->mail->AltBody  = strip_tags($this->get("body"));
        $this->mail->Body     = $this->get("body");

        // add custom headers
        foreach ($customHeaders as $hdr) {
            $this->mail->AddCustomHeader($hdr);
        }
        
        // attach document images
        $images = $this->get("images");
        if (is_array($images)) {
            foreach ($images as $img) {
                // Append to $attachment array
                $cur = count($this->mail->attachment);
                $this->mail->attachment[$cur][0] = $img["data"];
                $this->mail->attachment[$cur][1] = $img["name"];
                $this->mail->attachment[$cur][2] = $img["name"];
                $this->mail->attachment[$cur][3] = "base64";
                $this->mail->attachment[$cur][4] = $img["mime"];
                $this->mail->attachment[$cur][5] = true; // isStringAttachment
                $this->mail->attachment[$cur][6] = "inline";
                $this->mail->attachment[$cur][7] = $img["name"]."@mail.lc"; // CID
            }
        }
    } // }}}

	function send()
	{
		parent::send();
		if ($this->clean_after_send) {
			$this->mail = null;
		}	
	}

	function cleanMail()
	{
		require_once "PHPMailer/class.phpmailer.php";
		$this->mail = new PHPMailer();
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

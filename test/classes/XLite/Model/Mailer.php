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

define('MAIL_SKIN', 'mail');
define('CRLF', "\r\n");

/**
* Class Mailer is used for sending mail notifications (to administrators and
* customers).
*
* Usage: 
*   Create new Mailer instance with 
*       $mailer = new XLite_Model_Mailer();
*
*   Compose message with 
*       $mailer->compose(&$dsn,
*                   $From = from_addr,
*                   $To = to_addr,
*                   $dir = directory
*                   $customHeaders = array()
*                   );
*
*   Send mail with 
*       $mailer->send();
*
* Have fun!
* 
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Mailer extends XLite_View_Abstract
{	
    public $subjectTemplate	  = "subject.tpl";	
    public $bodyTemplate	  = "body.tpl";	
    public $signatureTemplate = "signature.tpl";	
    public $langLocale		  = "en";	
    public $langPath		  = "lib/PHPMailer/language/";	
    public $charset			  = "iso-8859-1";	
    public $templatesSkin	  = null;

    public function __construct() // {{{
    {
        // TODO - check this place
        // parent::__construct();
        $this->set("name", "MailMessage");
    } // }}}

	/**
	 * getDefaultTemplate 
	 * 
	 * @return string
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getDefaultTemplate()
    {
		return '';
	}

    function set($name, $value)
    {
    	switch($name) {
    		// Prevent the attack works by placing a newline character 
    		// (represented by \n in the following example) in the field 
    		// that asks for the user's e-mail address. 
    		// For instance, they might put:
    		// joe@example.com\nCC: victim1@example.com,victim2@example.com
    		case "to":
    		case "from":
				$value_ = $value;
    			$value = str_replace("\\t", "\t", $value);
    			$value = str_replace("\t", "", $value);
    			$value = str_replace("\\r", "\r", $value);
    			$value = str_replace("\r", "", $value);
    			$value = str_replace("\\n", "\n", $value);
    			$value = explode("\n", $value);
    			if (is_array($value) && count($value) > 0) {
    				$value = $value[0];
    			} else {
    				// ???
					$value = $value_;
    			}
    		break;
    	}

    	parent::set($name, $value);
    }

    function selectCustomerLayout()
    {
		// Switch layout to castomer area
		$layout = XLite_Model_Layout::getInstance();
		$this->templatesSkin = $layout->get("skin");
		$layout->set("skin", XLite::getInstance()->getOptions(array('skin_details', 'skin')));
    }

    /**
    * Composes mail message.
    *
    * @param string $from   The sender email address
    * @param string $to    The email address to send mail to
    * @param string $dir   The directiry there mail parts template located
    * @param string $customHeaders  The headers you want to add/replace to. 
    * @return void
    *
    * @access public
    */
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
		$fname = tempnam(LC_COMPILE_DIR, 'mail');
		file_put_contents($fname, $this->get("body"));

        $imageParser = new XLite_Model_MailImageParser();
        $imageParser->webdir = $this->xlite->getShopUrl("");
        $imageParser->parse($fname);

        $this->set("body", $imageParser->result);
        $this->set("images", $imageParser->images);

        // Initialize PHPMailer
		require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PHPMailer' . LC_DS . 'class.phpmailer.php';
        $this->mail = new PHPMailer();

        $this->mail->SetLanguage($this->get("langLocale"),
                                 $this->get("langPath"));

        // --- SMTP settings ---
        if ($this->xlite->getComplex('config.Email.use_smtp')) {
            $this->mail->Mailer = "smtp";
            $this->mail->Host = $this->xlite->getComplex('config.Email.smtp_server_url');
            $this->mail->Port = $this->xlite->getComplex('config.Email.smtp_server_port');
            if ($this->xlite->getComplex('config.Email.use_smtp_auth')) {
                $this->mail->SMTPAuth = true;
                $this->mail->Username = $this->xlite->getComplex('config.Email.smtp_username');
                $this->mail->Password = $this->xlite->getComplex('config.Email.smtp_password');	
            }
            if (in_array($this->xlite->getComplex('config.Email.smtp_security'), array('ssl', 'tls'))) {
                $this->mail->SMTPSecure = $this->xlite->getComplex('config.Email.smtp_security');
            }
        }

        $this->mail->CharSet = $this->get("charset");
        $this->mail->IsHTML(true);
        $this->mail->AddAddress($this->get("to"));
        $this->mail->Encoding = "quoted-printable";
        $this->mail->From     = $this->get("from");
        $this->mail->FromName = $this->get("from");
        $this->mail->Sender   = $this->get("from");
        $this->mail->Subject  = $this->get("subject");
        $this->mail->AltBody  = $this->createAltBody($this->get("body"));
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

		if (file_exists($fname)) {
			unlink($fname);
		}

    } // }}}

    /**
    * Create alt body (text.plain)
    * @param string $html html string
    * @return string
    *
    * @access protected
    */
    function createAltBody($html) // {{{
    {
        // html_entity_decode('&nbsp;') = nbsp; !!!
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        $trans_tbl['&nbsp;'] = ' '; // Default: ['&nbsp;'] = 0xa0 (in ISO-8859-1)

        // remove html tags & convert html entities to chars
        $txt = strtr(strip_tags($html), $trans_tbl);

        return preg_replace('/^\s*$/m', '', $txt); 
    } // }}}

    /**
    * Sends a message.
    */
    function send() // {{{
    {
        if (!($this->get("to") == '')) {
            if (!isset($this->mail)) {
                $this->logger->log("Mail FAILED: unknown error");
            }

            ob_start();
            $result = $this->mail->Send();
            ob_end_clean();

            if (!$result) {
                $this->logger->log("Mail FAILED: " . $this->mail->ErrorInfo);
            }
        }

        if (isset($this->templatesSkin)) {
			// Restore layout
			$layout = XLite_Model_Layout::getInstance();
			$layout->set("skin", $this->templatesSkin);
			$this->templatesSkin = null;
        }
    } // }}}

    /**
    * Compiles the mail message part template to HTML
    */
    function compile($template, $switchLayout = true) // {{{
    {
        // replace layout with mailer skinned
        if ($switchLayout) {
            $layout = XLite_Model_Layout::getInstance();
            $skin = $layout->get("skin");
            $layout->set("skin", MAIL_SKIN);
        } else {
            // FIXME
            $template = "../../mail/en/" . $template;
        }
        $this->template = $template;
        $this->init();
        ob_start();
        $this->display();
        $text = trim(ob_get_contents());
        ob_end_clean();

        // restore old skin
        if ($switchLayout) {
            $layout->set("skin", $skin);
        }    
        return $text;
    } // }}}

    /**
    * Assebmles all headers to mail header.
    */
    function getHeaders() // {{{
    {
        $headers = "";
        foreach ($this->headers as $name => $value) {
            $headers .= $name . ": " . $value . CRLF;   
        }
        return $headers; 
    } // }}}

   function mail() // {{{
    {
        $this->doDie("Mailer::mail() API is obsoleted");
    } // }}}
}


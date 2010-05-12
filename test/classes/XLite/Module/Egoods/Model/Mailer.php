<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Egoods_Model_Mailer extends XLite_Model_Mailer
{
    public $clean_after_send = true;

    public function __construct() 
    {
        parent::__construct();
        // Initialize PHPMailer
        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PHPMailer' . LC_DS . 'class.phpmailer.php';
        $this->mail = new PHPMailer();
    }

    function compose($from, $to, $dir, $customHeaders = array()) 
    {
        // initialize internal properties
        $this->set("from", $from);
        $this->set("to",   $to);
        $this->set("customHeaders", $customHeaders);

        $dir .= '/';
        $this->set("subject", $this->compile($dir.$this->get('subjectTemplate')));
        $this->set("signature", $this->compile($this->get('signatureTemplate')));
        $this->set("body", $this->compile($dir.$this->get('bodyTemplate'), false));

        // find all images and fetch them; replace with cid:...
        $imageParser = new XLite_Model_MailImageParser();
        $imageParser->source = $this->get('body');
        $imageParser->webdir = $this->xlite->getShopUrl("");
        $imageParser->parse();

        $this->set("body", $imageParser->result);
        $this->set("images", $imageParser->images);
        
        if (is_null($this->mail)) {
            require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PHPMailer' . LC_DS . 'class.phpmailer.php';
            $this->mail = new PHPMailer();
        }

        $this->mail->SetLanguage($this->get('langLocale'),
                                 $this->get('langPath'));
        $this->mail->IsHTML(true);
        $this->mail->AddAddress($this->get('to'));
        $this->mail->Encoding = "quoted-printable";
        $this->mail->From     = $this->get('from');
        $this->mail->FromName = $this->get('from');
        $this->mail->Subject  = $this->get('subject');
        $this->mail->AltBody  = strip_tags($this->get('body'));
        $this->mail->Body     = $this->get('body');

        // add custom headers
        foreach ($customHeaders as $hdr) {
            $this->mail->AddCustomHeader($hdr);
        }
        
        // attach document images
        $images = $this->get('images');
        if (is_array($images)) {
            foreach ($images as $img) {
                // Append to $attachment array
                $cur = count($this->mail->attachment);
                $this->mail->attachment[$cur][0] = $img['data'];
                $this->mail->attachment[$cur][1] = $img['name'];
                $this->mail->attachment[$cur][2] = $img['name'];
                $this->mail->attachment[$cur][3] = "base64";
                $this->mail->attachment[$cur][4] = $img['mime'];
                $this->mail->attachment[$cur][5] = true; // isStringAttachment
                $this->mail->attachment[$cur][6] = "inline";
                $this->mail->attachment[$cur][7] = $img['name']."@mail.lc"; // CID
            }
        }
    }

    function send()
    {
        parent::send();
        if ($this->clean_after_send) {
            $this->mail = null;
        }
    }

    function cleanMail()
    {
        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PHPMailer' . LC_DS . 'class.phpmailer.php';
        $this->mail = new PHPMailer();
    }
}

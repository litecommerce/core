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
class XLite_Module_AdvancedSecurity_Model_Mailer extends XLite_Model_Mailer implements XLite_Base_IDecorator
{
    function compose($from, $to, $dir, $customHeaders = array())
    {
        parent::compose($from, $to, $dir, $customHeaders);
        // encrypt message with a public key if necessary
        if ($this->get("adminMail") && $this->get("order") && $this->getComplex('order.details')) {
            $gpg = new XLite_Module_AdvancedSecurity_Model_GPG();
			if ($this->getComplex('config.AdvancedSecurity.gpg_crypt_mail')) {
	            $this->logger->log("Module_AdvancedSecurity_Mailer::getBody() encrypt message");
    	        // send as a plain mail
        	    $this->mail->IsHTML(false);
            	$this->mail->Encoding = "7bit";
	            $this->mail->AltBody = "";
    	        $this->mail->Body = $gpg->encrypt($this->decodeHTML($this->get("body")));
			}

			if ($this->getComplex('config.Email.show_cc_info')) {
				$filename = "details.txt.gpg";
				$data = $this->getComplex('order.properties.secureDetailsText');
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

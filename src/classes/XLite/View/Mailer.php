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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Mailer 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Mailer extends \XLite\View\AView
{
    const MAIL_SKIN = 'mail';
    const CRLF = "\r\n";

    /**
     * Subject template file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $subjectTemplate = 'subject.tpl';

    /**
     * Body template file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $bodyTemplate = 'body.tpl';

    /**
     * Signature template file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $signatureTemplate = 'signature.tpl';

    /**
     * Language locale (for PHPMailer)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $langLocale = 'en';

    /**
     * Languages directory path (for PHPMailer)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $langPath = 'lib/PHPMailer/language/';

    /**
     * PHPMailer object
     * 
     * @var    PHPMailer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mail = null;

    /**
     * Message charset 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $charset = 'iso-8859-1';

    /**
     * Saved templates skin
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $templatesSkin = null;

    /**
     * Current template 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $template = null;

    /**
     * Embedded images list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $images = array();

    /**
     * Error message set by PHPMailer class 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $errorInfo = null;


    /** 
      * Get default template  
      *  
      * @return string 
      * @access protected 
      * @see    ____func_see____ 
      * @since  3.0.0 
      */ 
    protected function getDefaultTemplate() 
    { 
        return $this->template; 
    }

    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        if (in_array($name, array('to', 'from'))) {
            /**
             * Prevent the attack works by placing a newline character 
             * (represented by \n in the following example) in the field 
             * that asks for the user's e-mail address. 
             * For instance, they might put:
             * joe@example.com\nCC: victim1@example.com,victim2@example.com
             */
            $value = str_replace('\t', "\t", $value);
            $value = str_replace("\t", '', $value);
            $value = str_replace('\r', "\r", $value);
            $value = str_replace("\r", '', $value);
            $value = str_replace('\n', "\n", $value);
            $value = explode("\n", $value);
            $value = $value[0];
        }

        parent::set($name, $value);
    }

    /**
     * Swicth layout to customer interface
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function selectCustomerLayout()
    {
        $layout = \XLite\Model\Layout::getInstance();
        $this->templatesSkin = $layout->getSkin();
        $layout->setCustomerSkin();
    }

    /**
     * Composes mail message.
     *
     * @param string $from          The sender email address
     * @param string $to            The email address to send mail to
     * @param string $dir           The directiry there mail parts template located
     * @param array  $customHeaders The headers you want to add/replace to. OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function compose($from, $to, $dir, $customHeaders = array())
    {
        // initialize internal properties
        $this->set('from', $from);
        $this->set('to', $to);
        $this->set('customHeaders', $customHeaders);

        $dir .= '/';
        $this->set('subject', $this->compile($dir . $this->get('subjectTemplate')));
        $this->set('signature', $this->compile($this->get('signatureTemplate')));
        $this->set('body', $this->compile($dir . $this->get('bodyTemplate'), false));

        // find all images and fetch them; replace with cid:...
        $fname = tempnam(LC_COMPILE_DIR, 'mail');
        file_put_contents($fname, $this->get('body'));

        $imageParser = new \XLite\Model\MailImageParser();
        $imageParser->webdir = $this->xlite->getShopUrl();
        $imageParser->parse($fname);

        $this->set('body', $imageParser->result);
        $this->set('images', $imageParser->images);

        // Initialize PHPMailer
        require_once LC_LIB_DIR . 'PHPMailer' . LC_DS . 'class.phpmailer.php';
        $this->mail = new \PHPMailer();

        $this->mail->SetLanguage(
            $this->get('langLocale'),
            $this->get('langPath')
        );

        // SMTP settings
        if ($this->config->Email->use_smtp) {
            $this->mail->Mailer = 'smtp';
            $this->mail->Host = $this->config->Email->smtp_server_url;
            $this->mail->Port = $this->config->Email->smtp_server_port;
            if ($this->config->Email->use_smtp_auth) {
                $this->mail->SMTPAuth = true;
                $this->mail->Username = $this->config->Email->smtp_username;
                $this->mail->Password = $this->config->Email->smtp_password;
            }
            if (in_array($this->config->Email->smtp_security, array('ssl', 'tls'))) {
                $this->mail->SMTPSecure = $this->config->Email->smtp_security;
            }
        }

        $this->mail->CharSet = $this->get('charset');
        $this->mail->IsHTML(true);
        $this->mail->AddAddress($this->get('to'));
        $this->mail->Encoding = 'quoted-printable';
        $this->mail->From     = $this->get('from');
        $this->mail->FromName = $this->get('from');
        $this->mail->Sender   = $this->get('from');
        $this->mail->Subject  = $this->get('subject');
        $this->mail->AltBody  = $this->createAltBody($this->get('body'));
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
                $this->mail->AddEmbeddedImage(
                    $img['data'],
                    $img['name'] . '@mail.lc',
                    $img['name'],
                    'base64',
                    $img['mime']
                );
            }
        }

        if (file_exists($fname)) {
            unlink($fname);
        }
    }

    /**
     * Create alternative message body (text/plain)
     * 
     * @param string $html Message body
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createAltBody($html)
    {
        $transTbl = array_flip(get_html_translation_table(HTML_ENTITIES));
        $transTbl['&nbsp;'] = ' '; // Default: ['&nbsp;'] = 0xa0 (in ISO-8859-1)

        // remove html tags & convert html entities to chars
        $txt = strtr(strip_tags($html), $transTbl);

        return preg_replace('/^\s*$/m', '', $txt);
    }

    /**
     * Send message
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function send()
    {
        if ($this->get('to') != '') {
            if (!isset($this->mail)) {
                $this->logger->log('Mail FAILED: unknown error');
            }

            ob_start();
            $result = $this->mail->Send();
            ob_end_clean();

            if (!$result) {
                $this->logger->log('Mail FAILED: ' . $this->mail->ErrorInfo);
            }
        }

        if (isset($this->templatesSkin)) {
            // Restore layout
            $layout = \XLite\Model\Layout::getInstance();
            $layout->setSkin($this->templatesSkin);
            $this->templatesSkin = null;
        }

        $this->errorInfo = $this->mail->ErrorInfo;
    }

    /**
     * Compile template
     * 
     * @param string  $template     Template path
     * @param boolean $switchLayout Switch laout flag OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function compile($template, $switchLayout = true)
    {
        // replace layout with mailer skinned
        if ($switchLayout) {
            $layout = \XLite\Model\Layout::getInstance();
            $skin = $layout->getSkin();
            $layout->setMailSkin();

        } else {
            // FIXME
            $template = '../../' . self::MAIL_SKIN . '/en/' . $template;
        }

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue($template); 
        $this->template = $template; 
        $this->init(); 
        $text = $this->getContent();

        // restore old skin
        if ($switchLayout) {
            $layout->setSkin($skin);
        }

        return $text;
    }

    /**
     * Get headers as string
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHeaders()
    {
        $headers = '';
        foreach ($this->headers as $name => $value) {
            $headers .= $name . ': ' . $value . self::CRLF;
        }

        return $headers;
    }


    /**
     * Return decription of the last occured error
     * 
     * @return string|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastError()
    {
        return $this->errorInfo;
    }
}

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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\View;

/**
 * Mailer 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Mailer extends \XLite\View\AView
{
    const CRLF = "\r\n";

    /**
     * Mail separator symbol 
     */
    const MAIL_SEPARATOR = ',';

    /**
     * Compose runned 
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $composeRunned = false;

    /**
     * Subject template file name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $subjectTemplate = 'subject.tpl';

    /**
     * Body template file name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $bodyTemplate = 'body.tpl';

    /**
     * Signature template file name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $signatureTemplate = 'signature.tpl';

    /**
     * Language locale (for PHPMailer)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $langLocale = 'en';

    /**
     * Languages directory path (for PHPMailer)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $langPath = 'lib/PHPMailer/language/';

    /**
     * PHPMailer object
     * 
     * @var   \PHPMailer
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $mail = null;

    /**
     * Message charset 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $charset = 'UTF-8';

    /**
     * Saved templates skin
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $templatesSkin = null;

    /**
     * Current template 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $template = null;

    /**
     * Embedded images list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $images = array();

    /**
     * Error message set by PHPMailer class 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $errorInfo = null;


    /**
     * Check - is copose procedure runned or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isComposeRunned()
    {
        return static::$composeRunned;
    }
 
    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
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
     * Composes mail message.
     *
     * @param string $from          The sender email address
     * @param string $to            The email address to send mail to
     * @param string $dir           The directiry there mail parts template located
     * @param array  $customHeaders The headers you want to add/replace to. OPTIONAL
     * @param string $interface     Interface to use for mail OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function compose($from, $to, $dir, $customHeaders = array(), $interface = \XLite::CUSTOMER_INTERFACE)
    {
        static::$composeRunned = true;

        // initialize internal properties
        $this->set('from', $from);
        $this->set('to', $to);

        $this->set('customHeaders', $customHeaders);

        $dir .= '/';

        $this->set('subject', $this->compile($dir . $this->get('subjectTemplate'), $interface));
        $this->set('signature', $this->compile($this->get('signatureTemplate'), $interface));
        $this->set('body', $this->compile($dir . $this->get('bodyTemplate'), $interface));

        // find all images and fetch them; replace with cid:...
        $fname = tempnam(LC_COMPILE_DIR, 'mail');

        file_put_contents($fname, $this->get('body'));

        $imageParser = new \XLite\Model\MailImageParser();

        $imageParser->webdir = \XLite::getInstance()->getShopURL();

        $imageParser->parse($fname);

        $this->set('body', $imageParser->result);
        $this->set('images', $imageParser->images);

        ob_start();

        // Initialize PHPMailer from configuration variables (it should be done once in a script execution)
        $this->initMailFromConfig();

        // Initialize Mail from inner set of variables.
        $this->initMailFromSet();

        $output = ob_get_contents();

        ob_end_clean();

        if ('' !== $output) {

            $this->logger->log('Mailer echoed: "' . $output . '". Error: ' . $this->mail->ErrorInfo);
        }

        if (file_exists($fname)) {

            unlink($fname);
        }

        static::$composeRunned = false;
    }

    /**
     * Send message
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function send()
    {
        if ('' !== $this->get('to')) {

            if (!isset($this->mail)) {

                $this->logger->log('Mail FAILED: not initialized inner mailer');
            }

            ob_start();

            $result = $this->mail->Send();

            ob_end_clean();

            if (!$result) {

                $this->logger->log('Mail FAILED: ' . $this->mail->ErrorInfo);
            }
        }

        // Restore layout
        if (isset($this->templatesSkin)) {

            \XLite\Core\Layout::getInstance()->setSkin($this->templatesSkin);

            $this->templatesSkin = null;
        }

        $this->errorInfo = $this->mail->ErrorInfo;
    }


    /**
     * Return decription of the last occured error
     * 
     * @return string|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastError()
    {
        return $this->errorInfo;
    }


    /** 
      * Get default template  
      *  
      * @return string 
      * @see    ____func_see____ 
      * @since  3.0.0 
      */ 
    protected function getDefaultTemplate() 
    { 
        return $this->template; 
    }

    /**
     * Create alternative message body (text/plain)
     * 
     * @param string $html Message body
     *  
     * @return string
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
     * Inner mailer initialization from set variables
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initMailFromSet()
    {
        $this->mail->SetLanguage(
            $this->get('langLocale'),
            $this->get('langPath')
        );

        $this->mail->CharSet = $this->get('charset');

        $this->mail->From     = $this->get('from');
        $this->mail->FromName = $this->get('from');
        $this->mail->Sender   = $this->get('from');

        $emails = explode(static::MAIL_SEPARATOR, $this->to);

        foreach ($emails as $email) {

            $this->mail->AddAddress($email);
        }

        $this->mail->Subject  = $this->get('subject');
        $this->mail->AltBody  = $this->createAltBody($this->get('body'));
        $this->mail->Body     = $this->get('body');

        // add custom headers
        foreach ($this->get('customHeaders') as $header) {

            $this->mail->AddCustomHeader($header);
        }

        if (is_array($this->get('images'))) {

            foreach ($this->get('images') as $image) {

                // Append to $attachment array
                $this->mail->AddEmbeddedImage(
                    $image['data'],
                    $image['name'] . '@mail.lc',
                    $image['name'],
                    'base64',
                    $image['mime']
                );
            }
        }
    }

    /**
     * Inner mailer initialization from DB configuration
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initMailFromConfig()
    {
        if (!isset($this->mail)) {

            // Initialize PHPMailer
            include_once LC_LIB_DIR . 'PHPMailer' . LC_DS . 'class.phpmailer.php';

            $this->mail = new \PHPMailer();

            // SMTP settings
            if (\XLite\Core\Config::getInstance()->Email->use_smtp) {

                $this->mail->Mailer = 'smtp';

                $this->mail->Host = \XLite\Core\Config::getInstance()->Email->smtp_server_url;
                $this->mail->Port = \XLite\Core\Config::getInstance()->Email->smtp_server_port;

                if (\XLite\Core\Config::getInstance()->Email->use_smtp_auth) {

                    $this->mail->SMTPAuth = true;
                    $this->mail->Username = \XLite\Core\Config::getInstance()->Email->smtp_username;
                    $this->mail->Password = \XLite\Core\Config::getInstance()->Email->smtp_password;
                }

                if (in_array(\XLite\Core\Config::getInstance()->Email->smtp_security, array('ssl', 'tls'))) {
                    $this->mail->SMTPSecure = \XLite\Core\Config::getInstance()->Email->smtp_security;
                }
            }

            $this->mail->IsHTML(true);
            $this->mail->Encoding = 'quoted-printable';
        }
    }

    /**
     * Compile template
     * 
     * @param string  $template     Template path
     * @param string  $interface    Interface OPTIONAL
     * @param boolean $switchLayout Switch laout flag OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function compile($template, $interface = \XLite::CUSTOMER_INTERFACE, $switchLayout = true)
    {
        // replace layout with mailer skinned
        if ($switchLayout) {

            $layout = \XLite\Core\Layout::getInstance();

            $skin = $layout->getSkin();

            $layout->setMailSkin($interface);
        }

        $this->widgetParams[static::PARAM_TEMPLATE]->setValue($template); 

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
}

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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Helpdesk request
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Helpdesk extends XLite_Controller_Admin_Abstract
{
    /**
     * Service server URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $url = 'https://zoo-xb-kai.crtdev.local/customer.php?area=center&target=customer_info&create_topic=1';

    /**
     * Send request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSend()
    {
        $name = $this->auth->getProfile()->get('billing_firstname')
            . ' '
            . $this->auth->getProfile()->get('billing_lastname');


        $email = $this->config->Company->site_administrator;
        $country = strtolower($this->config->Company->location_country);
        $phone = $this->config->Company->company_phone;

        $subject = XLite_Core_Request::getInstance()->subject;
        $message = XLite_Core_Request::getInstance()->message;

        $charset = $this->getCharset();

        if (function_exists('iconv') && $charset) {
            $s = @iconv($charset, 'UTF-8', $subject);
            if ($s) {
                $subject = $s;
            }
            $m = @iconv($charset, 'UTF-8', $message);
            if ($m) {
                $message = $m;
            }

        } else {
            $subject = preg_replace('/[\x7f-\xff]/Ss', '', $subject);
            $message = preg_replace('/[\x7f-\xff]/Ss', '', $message);
        }

        $subject = str_replace(array('<', '>'), array('&lt;', '&gt;'), $subject);
        $message = str_replace(array('<', '>'), array('&lt;', '&gt;'), $message);

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <profile>
        <name>
            <formatted>$name</formatted>
        </name>
        <email>$email</email>
        <phone>$phone</phone>
        <countryCode>$country</countryCode>
    </profile>
    <ticket>
        <preset>LC</preset>
        <name>$subject</name>
        <body>$message</body>
    </ticket>
</rsp>
XML;

        $url = $this->url;
        $xml = htmlspecialchars($xml);

        $html = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body onload="javascript: document.request_form.submit();">
<form action="$url" method="POST" name="request_form">
<input type="hidden" name="user_info" value="$xml" />
<noscript>
If you are not redirected within 5 seconds, please <input type="submit" value="press here" /> to go to Helpdesk.
</noscript>
</form>
</body>
</html>
HTML;

        echo ($html);
        die ();
    }
}

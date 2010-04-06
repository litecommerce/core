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

require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Net' . LC_DS . 'URL.php';

/**
* HTTPS_CardinalCommerce description.
*
* @package Module_CardinalCommerce
* @access public
* @version $Id$
*/

class XLite_Module_CardinalCommerce_Model_HTTPS extends XLite_Model_HTTPS implements XLite_Base_IDecorator
{
    function requestLibCurl()
    {
        if ($this->LibCurl_detect() == XLite_Model_HTTPS::HTTPS_ERROR) {
            return XLite_Model_HTTPS::HTTPS_ERROR;
        }
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, $this->method=="POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $this->getPost());
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($c, CURLOPT_SSL_VERIFYHOST, 0);

    	// set TimeOut parameter
		$timeout = $this->xlite->get("httpsTimeOut");
    	if (!empty($timeout)) {
    		curl_setopt ($c, CURLOPT_TIMEOUT, $timeout);
    	}

        if (!empty($this->referer)) {
            $header_string = "Referer: " . $this->referer . "\r\n";
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, $header_string);
            curl_setopt($c, CURLOPT_HEADER, 1);
        }

//        curl_setopt($c, CURLOPT_VERBOSE, true); // for debug
//        curl_setopt($c, CURLOPT_NOPROGRESS, false); // for debug
        ob_start();
        curl_exec($c);
        $this->response = ob_get_contents();
        ob_end_clean();
        $this->curlErrorCode = curl_errno($c);
        $this->error = curl_error($c);
        curl_close ($c);
        
        if ($this->curlErrorCode) {
            return XLite_Model_HTTPS::HTTPS_ERROR;
        }
        return XLite_Model_HTTPS::HTTPS_SUCCESS;
    }

    function requestCurl()
    {
        if ($this->Curl_detect() == XLite_Model_HTTPS::HTTPS_ERROR) {
            return XLite_Model_HTTPS::HTTPS_ERROR;
        }
        $supports_insecure = $this->supports_insecure;

        $execline = "$this->curlBinary -k";
        $execline .= ' -d "'.$this->getPost().'"';
        if($this->cert) {
            $execline .= " --cert \"$this->cert\"";
        } else {
            if (!$supports_insecure) {
                $this->error = "curl version must be > 7.10 or you must use SSL certificates";
                return XLite_Model_HTTPS::HTTPS_ERROR;
            }
        }
        if($this->kcert) {
            $execline .= " --key \"$this->kcert\"";
        }
        if($this->conttype != "application/x-www-form-urlencoded")
        {
            $execline .= " -H \"Content-Type: $this->conttype\"";
        }
        if(!empty($this->referer)) {
            $execline .= " -H \"Referer: $this->referer\"";
        }
        if ($this->method=="GET") {
            $execline .= " --get";
        }

    	// set TimeOut parameter
		$timeout = $this->xlite->get("httpsTimeOut");
    	if (!empty($timeout)) {
    		$execline.= " --connect-timeout ".$timeout." -m ".$timeout;
    	}

        exec($cmd="$execline -s $this->url", $this->response, $this->curlErrorCode);
        $this->response = join('', $this->response);
        if ($this->curlErrorCode) {
            if (isset($this->curlErrors[$this->curlErrorCode])) {
                $this->error = "Curl error $this->curlErrorCode: ".$this->curlErrors[$this->curlErrorCode];
                $url = new Net_URL($this->url);
                $this->error = str_replace("PROTO", $url->protocol,  $this->error);
                $this->error = str_replace("FULLURL", $this->url,  $this->error);
                $this->error = str_replace("HOST",  $url->host,  $this->error);
            } else {
                $this->error = "Curl error #$this->curlErrorCode";
            }
            // get detailed error message
            exec("$execline $this->url 2>&1", $erromsg, $this->curlErrorCode);
            $erromsg = join('', $erromsg);
            $this->error .= " '" . $erromsg . "'";
            return XLite_Model_HTTPS::HTTPS_ERROR;
        }
        return XLite_Model_HTTPS::HTTPS_SUCCESS;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

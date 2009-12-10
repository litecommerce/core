<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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

define('HTTPS_ERROR', 1);
define('HTTPS_SUCCESS', 2);

require_once "Net/URL.php";

/**
* HTTPS allows to make HTTP requests thru ssl secure
* connection.
*
* Properties: 
*
* @param string $method     'POST'|'GET' - default POST
* @param string $url        www.vasia.ru:443/path/to/script.asp
* @param array  $data       form data in form of 'variable=value'
* @param string $conttype   content-type header, default "application/x-www-form-urlencoded"
* @param string $cert       .pem certificate file
* @param string $kcert      .pem certificate file
* @access public
* @version $Id: HTTPS.php,v 1.1 2006/07/11 06:38:24 sheriff Exp $
*/
class HTTPS extends Object
{
    var $method = 'POST';
    var $url = '';
    var $data = array();
    var $conttype = "application/x-www-form-urlencoded";
    var $cert = null;
    var $kcert = null;
    var $response = null;
    var $curlErrorCode = 0;
    var $urlencoded = false;

    function request()
    {
        
        if ($this->config->Security->httpsClient == "autodetect") {
            $software = $this->AutoDetect();
            if (!$software) {
                $this->error = "Can't detect client software to use for SSL connections: nither libcurl extension is installed nor curl/openssl binaries are found in PATH";
                return HTTPS_ERROR;
            }
        } else {
            $software = $this->config->Security->httpsClient;
        }
        if ($software == "libcurl") {
            $result = $this->requestLibCurl();
        } else if ($software == "curl") {
            $result = $this->requestCurl();
        } else {
            $result = $this->requestOpenSSL();
        }
        if ($result == HTTPS_SUCCESS) {
            $this->error = "";
        }    
        return $result;
    }

    function getPost()
    {
        if (is_array($this->data)) {
            $params = array();
            foreach ($this->data as $k => $v) {
                if ($this->urlencoded) {
                    $params[] = "$k=$v";
                } else {
                    $params[] = "$k=".urlencode($v);
                }
            }
            return join('&',$params);
        } else {
            if ($this->urlencoded) { 
                return $this->data;
            } else {
                return urlencode($this->data);
            }
        }
    }

    function AutoDetect()
    {
        if ($this->LibCurl_detect() == HTTPS_SUCCESS) {
            return 'libcurl'; // a preferrable way
        }
        if (!stristr(PHP_OS, "win") && func_find_executable("openssl")) {
            // can't open bidirectional pipes in win
            return 'openssl';
        }
        if ($this->Curl_detect() == HTTPS_SUCCESS) {
            return 'curl';
        }
        return false;
    }
    
    function LibCurl_detect()
    {
        if (!function_exists('curl_init')) {
            $this->error = "libcurl extension is not found";
            return HTTPS_ERROR;
        }
        $version = curl_version();
        $supports_insecure = false;
/*        commented because it works on EasyPHP with libCurl 7.9
        # insecure key is supported by curl since version 7.10
        if( preg_match('/libcurl\/([^ $]+)/', $version, $m) ) {
            $parts = explode(".",$m[1]);
            if( $parts[0] > 7 || ($parts[0] = 7 && $parts[1] >= 10) ) {
                $supports_insecure = true;
            }
        }
        if (!$supports_insecure) {
            $this->error = "libcurl must be version 7.10 or higher";
            return HTTPS_ERROR;
        }
*/        
        return HTTPS_SUCCESS;
    }

    function libCurl_init()
    {
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, $this->method=="POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $this->getPost());
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($c, CURLOPT_SSL_VERIFYHOST, 0);

        if( $this->cert )
     	{
        	curl_setopt ($c, CURLOPT_SSLCERT, $this->cert);
        	if( $this->kcert ) curl_setopt ($c, CURLOPT_SSLKEY, $this->kcert);
     	}

        if (!empty($this->referer)) {
            $header_string = "Referer: " . $this->referer . "\r\n";
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, $header_string);
            curl_setopt($c, CURLOPT_HEADER, 1);
        }

//        curl_setopt($c, CURLOPT_VERBOSE, true); // for debug
//        curl_setopt($c, CURLOPT_NOPROGRESS, false); // for debug

		return $c;
    }

    function requestLibCurl()
    {
        if ($this->LibCurl_detect() == HTTPS_ERROR) {
            return HTTPS_ERROR;
        }

        $c = $this->libCurl_init();

        ob_start();
        curl_exec($c);
        $this->response = ob_get_contents();
        ob_end_clean();
        $this->curlErrorCode = curl_errno($c);
        $this->error = curl_error($c);
        curl_close ($c);
        
        if ($this->curlErrorCode) {
            return HTTPS_ERROR;
        }
        return HTTPS_SUCCESS;
    }

    function Curl_detect()
    {
        $this->curlBinary = func_find_executable("curl");
        if (!$this->curlBinary) {
            $this->error = "curl executable is not found";
            return HTTPS_ERROR;
        }
        exec("$this->curlBinary --version", $output);
        $version = @$output[0];
        # -k|--insecure key is supported by curl since version 7.10
        $this->supports_insecure = false;
        if( preg_match('/curl ([^ $]+)/', $version, $m) ){
            $parts = explode(".",$m[1]);
            if( $parts[0] > 7 || ($parts[0] = 7 && $parts[1] >= 10) )
                $this->supports_insecure = true;
        }
        return HTTPS_SUCCESS;
    }
    
    function requestCurl()
    {
        if ($this->Curl_detect() == HTTPS_ERROR) {
            return HTTPS_ERROR;
        }
        $supports_insecure = $this->supports_insecure;

        $execline = "$this->curlBinary -k";
        $execline .= ' -d "'.preg_replace("/\"/","\\\"",$this->getPost()).'"';
        if($this->cert) {
            $execline .= " --cert \"$this->cert\"";
        } else {
            if (!$supports_insecure) {
                $this->error = "curl version must be > 7.10 or you must use SSL certificates";
                return HTTPS_ERROR;
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
        exec($cmd="$execline -s $this->url", $this->response, $this->curlErrorCode);
        $this->response = join('', $this->response);
        if ($this->curlErrorCode) {
            if (isset($this->curlErrors[$this->curlErrorCode])) {
                $this->error = "Curl error $this->curlErrorCode: ".$this->curlErrors[$this->curlErrorCode];
                $url = func_new("Net_URL",$this->url);
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
            return HTTPS_ERROR;
        }
        return HTTPS_SUCCESS;
    }

    function requestOpenSSL()
    {
        $opensslBinary = func_find_executable("openssl");
        if (!$opensslBinary) {
            $this->error = "openssl executable is not found";
            return HTTPS_ERROR;
        }
        $url = new Net_URL($this->url);
        if ($url->port == 80 ) { // default port?
            $url->port = 443;
        }    
        $args = array();
        $args[] = "-connect $url->host:$url->port";
        if($this->cert) $args[] = "-cert $this->cert";
        if($this->kcert) $args[] = "-key $this->kcert";
        $cmdline = "$opensslBinary s_client ".join(' ',$args)." -quiet 2>&1";
		$data = $this->getPost();

		if (phpversion() < '4.3.0') {

	        $fp = popen($cmdline, "w+");
    	    if(!$fp) {
        	    $this->error = "Can't execute command $cmdline";
           	 	return HTTPS_ERROR;
 		    }
        	fputs($fp, $str = "$this->method $url->path?" . $url->getQueryString() . " HTTP/1.0\r\n");
        	fputs($fp, "Host: $url->host\r\n");
	        fputs($fp, "User-Agent: Mozilla/4.5 [en]\r\n");
	        fputs($fp, "Content-Type: $this->conttype\r\n");
	        fputs($fp, "Content-Length: ".strlen($data)."\r\n");
	        if (!empty($this->referer)) {
	            fputs($fp, "Referer: ".$this->referer."\r\n");
	        }  
	        fputs($fp, "\r\n");
	        if ($this->method == 'POST') {
	            fputs($fp, "$data\r\n");
	        }
	        // retrieve result
	        $get_state = 0;
	        $this->headers = array();
	        $this->response = "";
	        $data = "";
	        while(!feof($fp)) {
	            $data .= fread($fp, 65536);
	        }
	
	        pclose($fp);
		} else {
			$descriptorspec = array(
					0 => array("pipe", "r"),
					1 => array("pipe", "w"),
					2 => array("file", "/tmp/error-output.txt", "a"));
			$fp = proc_open($cmdline,$descriptorspec,$pipes);
			if(!is_resource($fp)) {
				$this->error = "Can't execute command $cmdline";
				return HTTPS_ERROR;
			}
			fputs($pipes[0], "$this->method $url->path?" . $url->getQueryString() . " HTTP/1.0\r\n");
			fputs($pipes[0], "Host: $url->host\r\n");
			fputs($pipes[0], "User-Agent: Mozilla/4.5 [en]\r\n");
			fputs($pipes[0], "Content-Type: $this->conttype\r\n");
			fputs($pipes[0], "Content-Length: ".strlen($data)."\r\n");
			fputs($pipes[0], "\r\n");
			if ($this->method == 'POST') {
				fputs($pipes[0], "$data\r\n");
			}
			fclose($pipes[0]);

			// retrieve result
			$get_state = 0;
			$this->headers = array();
			$this->response = "";
			$data = "";
			while(!feof($pipes[1])) {
				$data .= fread($pipes[1], 65536);
			}
			fclose($pipes[1]);
			proc_close($fp);
		}
        // parse response

        // find end-of-headers
        $pos = strpos($data, "\r\n\r\n");
        if ($pos === false) {
            $pos = strpos($data, "\n\n");
        }
        if ($pos === false )
        {
            $this->error = "openssl error ".$data;
            return HTTPS_ERROR;
        }
        // parse headers
        if (preg_match("/HTTP\/1\.[01] (\d+)/m", $data, $matches)) {
            $this->status = $matches[1];
        }

        $this->headers = array();
        $headers = explode("\n", substr($data, 0, $pos));
        $this->response = trim(substr($data, $pos));

        foreach ($headers as $header) {
            if (($pos = strpos($header, ":")) !== false) {
                $this->headers[strtolower(substr($header, 0, $pos))] = 
                    trim(substr($header, $pos+1));
            }
        }
        // handle redirect
        if (isset($this->headers['location']) && $this->status == '302') {
            $redirect = $this->headers['location'];
            $url->querystring = '';
            $url->anchor = '';
            // Absolute URL
            if (preg_match('/^https?:\/\//i', $redirect)) {
                $url = func_new("Net_URL",$redirect);
            } else if ($redirect{0} == '/') {
                // Absolute path
                $url->path = $redirect;
            // Relative path
            } elseif (substr($redirect, 0, 3) == '../' OR substr($redirect, 0, 2) == './') {
                if (substr($url->path, -1) == '/') {
                    $redirect = $url->path . $redirect;
                } else {
                    $redirect = dirname($url->path) . '/' . $redirect;
                }
                $redirect = Net_URL::resolvePath($redirect);
                $url->path = $redirect;
            // Filename, no path
            } else {
                if (substr($url->path, -1) == '/') {
                    $redirect = $url->path . $redirect;
                } else {
                    $redirect = dirname($url->path) . '/' . $redirect;
                }
                $url->path = $redirect;
            }
            $this->url = $url->getURL();
            $this->method = 'GET';
            return $this->requestOpenSSL();
        }
        return HTTPS_SUCCESS;
    }

    var $curlErrors = array(
'1' =>
'Unsupported protocol "PROTO". This build of curl has no support for this protocol.',
'2' =>
'Failed to initialize.',
'3' =>
'URL "FULLURL" malformat. The syntax was not correct.',
'4' =>
'URL "FULLURL" user malformatted. The user-part of the URL syntax was not correct.',
'5' =>
'Couldn\'t resolve proxy. The given proxy host could not be resolved.',
'6' =>
'Couldn\'t resolve host HOST. The given remote host was not resolved.',
'7' =>
'Failed to connect to host HOST.',
'8' =>
'FTP weird server reply. The server sent data curl couldn\'t parse.',
'9' =>
'FTP access denied. The server denied login.',
'10' =>
'FTP user/password incorrect. Either one or both were not accepted by the server.',
'11' =>
'FTP weird PASS reply. Curl couldn\'t parse the reply sent to the PASS request.',
'12' =>
'FTP weird USER reply. Curl couldn\'t parse the reply sent to the USER request.',
'13' =>
'FTP weird PASV reply, Curl couldn\'t parse the reply sent to the PASV request.',
'14' =>
'FTP weird 227 format. Curl couldn\'t parse the 227-line the server sent.',
'15' =>
'FTP can\'t get host. Couldn\'t resolve the host IP we got in the 227-line.',
'16' =>
'FTP can\'t reconnect. Couldn\'t connect to the host we got in the 227-line.',
'17' =>
'FTP couldn\'t set binary. Couldn\'t change transfer method to binary.',
'18' =>
'Partial file. Only a part of the file was transfered.',
'19' =>
'FTP couldn\'t RETR file. The RETR command failed.',
'20' =>
'FTP write error. The transfer was reported bad by the server.',
'21' =>
'FTP quote error. A quote command returned error from the server.',
'22' =>
'HTTP not found. The requested page FULLURL was not found. This return code only appears if --fail is used.',
'23' =>
'Write error. Curl couldn\'t write data to a local filesystem or similar.',
'24' =>
'Malformat user. User name badly specified.',
'25' =>
'FTP couldn\'t STOR file. The server denied the STOR operation.',
'26' =>
'Read error. Various reading problems.',
'27' =>
'Out of memory. A memory allocation request failed.',
'28' =>
'Operation timeout. The specified time-out period was reached according to the conditions.',
'29' =>
'FTP couldn\'t set ASCII. The server returned an unknown reply.',
'30' =>
'FTP PORT failed. The PORT command failed.',
'31' =>
'FTP couldn\'t use REST. The REST command failed.',
'32' =>
'FTP couldn\'t use SIZE. The SIZE command failed. The command is an extension to the original FTP spec RFC 959.',
'33' =>
'HTTP range error. The range "command" didn\'t work.',
'34' =>
'HTTP post error. Internal post-request generation error.',
'35' =>
'SSL connect error. The SSL handshaking failed.',
'36' =>
'FTP bad download resume. Couldn\'t continue an earlier aborted download.',
'37' =>
'FILE couldn\'t read file. Failed to open the file. Permissions?',
'38' =>
'LDAP cannot bind. LDAP bind operation failed.',
'39' =>
'LDAP search failed.',
'40' =>
'Library not found. The LDAP library was not found.',
'41' =>
'Function not found. A required LDAP function was not found.',
'42' =>
'Aborted by callback. An application told curl to abort the operation.',
'43' =>
'Internal error. A function was called with a bad parameter.',
'44' =>
'Internal error. A function was called in a bad order.',
'45' =>
'Interface error. A specified outgoing interface could not be used.',
'46' =>
'Bad password entered. An error was signaled when the password was entered.',
'47' =>
'Too many redirects. When following redirects, curl hit the maximum amount.',
'48' =>
'Unknown TELNET option specified.',
'49' =>
'Malformed telnet option.',
'51' =>
'The remote peer\'s SSL certificate wasn\'t ok',
'52' =>
'The server didn\'t reply anything, which here is considered an error.',
'53' =>
'SSL crypto engine not found',
'54' =>
'Cannot set SSL crypto engine as default',
'55' =>
'Failed sending network data',
'56' =>
'Failure in receiving network data',
'57' =>
'Share is in use (internal error)',
'58' =>
'Problem with the local certificate',
'59' =>
'Couldn\'t use specified SSL cipher',
'60' =>
'Problem with the CA cert (path? permission?)',
'61' =>
'Unrecognized transfer encoding',
);
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

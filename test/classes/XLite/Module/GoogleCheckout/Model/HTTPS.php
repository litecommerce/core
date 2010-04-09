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

require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Net' . LC_DS . 'URL2.php';

/**
* @package GoogleCheckout
*
* HTTPS allows to make HTTP requests thru ssl secure
* connection.
*
* Properties: 
*
* @param string $headers	additional headers
* @access public

* @version $Id$
*/
class XLite_Module_GoogleCheckout_Model_HTTPS extends XLite_Model_HTTPS
{	
	public $headers = null;

	function getHeaders($slashed=false)
	{
		$headers = array();
		foreach ($this->headers as $k=>$v) {
			if ($slashed) {
				$v = addslashes($v);
			}

			$headers[] = is_integer($k) ? $v : ($k.": ".$v);
		}

		return $headers;
	}
    
    function libCurl_init()
    {
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, $this->method=="POST");
        curl_setopt($c, CURLOPT_POSTFIELDS, $this->getPost());
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($c, CURLOPT_SSL_VERIFYHOST, 0);

		$headers = $this->getHeaders();
		if ($headers) {
			curl_setopt ($c, CURLOPT_HTTPHEADER, $headers);
		}

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

		return $c;
    }

	// LC 2.1.2 compatibility
	function requestLibCurl()
	{
		if ($this->LibCurl_detect() == XLite_Model_HTTPS::HTTPS_ERROR) {
			return XLite_Model_HTTPS::HTTPS_ERROR;
		}
		$c = curl_init($this->url);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_POST, $this->method=="POST");
		curl_setopt($c, CURLOPT_POSTFIELDS, $this->getPost());
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);

		$headers = $this->getHeaders();
		if ($headers) {
			curl_setopt ($c, CURLOPT_HTTPHEADER, $headers);
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
        $execline .= ' -d "'.preg_replace("/\"/","\\\"",$this->getPost()).'"';
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

		$headers = $this->getHeaders(true);
		if ($headers) {
			foreach ($headers as $v) {
				$execline .= " -H \"".$v."\"";
			}
		}

        if ($this->method=="GET") {
            $execline .= " --get";
        }
        exec($cmd="$execline -s $this->url", $this->response, $this->curlErrorCode);
        $this->response = join('', $this->response);
        if ($this->curlErrorCode) {
            if (isset($this->curlErrors[$this->curlErrorCode])) {
                $this->error = "Curl error $this->curlErrorCode: ".$this->curlErrors[$this->curlErrorCode];
                $url = new Net_URL2($this->url);
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

    function requestOpenSSL()
    {
        $opensslBinary = func_find_executable("openssl");
        if (!$opensslBinary) {
            $this->error = "openssl executable is not found";
            return XLite_Model_HTTPS::HTTPS_ERROR;
        }
        $url = new Net_URL2($this->url);
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
           	 	return XLite_Model_HTTPS::HTTPS_ERROR;
 		    }
        	fputs($fp, $str = "$this->method $url->path?" . $url->getQueryString() . " HTTP/1.0\r\n");
        	fputs($fp, "Host: $url->host\r\n");
	        fputs($fp, "User-Agent: Mozilla/4.5 [en]\r\n");
	        fputs($fp, "Content-Type: $this->conttype\r\n");
	        fputs($fp, "Content-Length: ".strlen($data)."\r\n");
	        if (!empty($this->referer)) {
	            fputs($fp, "Referer: ".$this->referer."\r\n");
	        }  

			$headers = $this->getHeaders();
			if ($headers) {
				foreach ($headers as $v) {
					fputs($fp, $v."\r\n");
				}
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
				return XLite_Model_HTTPS::HTTPS_ERROR;
			}
			fputs($pipes[0], "$this->method $url->path?" . $url->getQueryString() . " HTTP/1.0\r\n");
			fputs($pipes[0], "Host: $url->host\r\n");
			fputs($pipes[0], "User-Agent: Mozilla/4.5 [en]\r\n");
			fputs($pipes[0], "Content-Type: $this->conttype\r\n");
			fputs($pipes[0], "Content-Length: ".strlen($data)."\r\n");

			$headers = $this->getHeaders();
			if ($headers) {
				foreach ($headers as $v) {
					fputs($pipes[0], $v."\r\n");
				}
			}

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
            return XLite_Model_HTTPS::HTTPS_ERROR;
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
                $url = new Net_URL2($redirect);
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
                $redirect = Net_URL2::resolvePath($redirect);
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
        return XLite_Model_HTTPS::HTTPS_SUCCESS;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

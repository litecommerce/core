<?php
// +-----------------------------------------------------------------------+
// | Copyright (c) 2002-2003, Richard Heyes                                     |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Richard Heyes <richard@phpguru.org>                           |
// +-----------------------------------------------------------------------+
//
// $Id: Request.php 1609 2010-01-13 22:52:46Z vvs $
//
// HTTP_Request Class
//
// Simple example, (Fetches yahoo.com and displays it):
//
// $a = &new HTTP_Request('http://www.yahoo.com/');
// $a->sendRequest();
// echo $a->getResponseBody();
//

require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Net' . LC_DS . 'Socket.php';
require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Net' . LC_DS . 'URL.php';

define('HTTP_REQUEST_METHOD_GET',     'GET',     true);
define('HTTP_REQUEST_METHOD_HEAD',    'HEAD',    true);
define('HTTP_REQUEST_METHOD_POST',    'POST',    true);
define('HTTP_REQUEST_METHOD_PUT',     'PUT',     true);
define('HTTP_REQUEST_METHOD_DELETE',  'DELETE',  true);
define('HTTP_REQUEST_METHOD_OPTIONS', 'OPTIONS', true);
define('HTTP_REQUEST_METHOD_TRACE',   'TRACE',   true);

define('HTTP_REQUEST_HTTP_VER_1_0', '1.0', true);
define('HTTP_REQUEST_HTTP_VER_1_1', '1.1', true);

class HTTP_Request {

    /**
    * Full url
    * @public string
    */
    public $_url;

    /**
    * Type of request
    * @public string
    */
    public $_method;

    /**
    * HTTP Version
    * @public string
    */
    public $_http;

    /**
    * Request headers
    * @public array
    */
    public $_requestHeaders;

    /**
    * Basic Auth Username
    * @public string
    */
    public $_user;
    
    /**
    * Basic Auth Password
    * @public string
    */
    public $_pass;

    /**
    * Socket object
    * @public object
    */
    public $_sock;
    
    /**
    * Proxy server
    * @public string
    */
    public $_proxy_host;
    
    /**
    * Proxy port
    * @public integer
    */
    public $_proxy_port;
    
    /**
    * Proxy username
    * @public string
    */
    public $_proxy_user;
    
    /**
    * Proxy password
    * @public string
    */
    public $_proxy_pass;

    /**
    * Post data
    * @public mixed
    */
    public $_postData;

    /**
    * Connection timeout.
    * @public integer
    */
    public $_timeout;
    
    /**
    * HTTP_Response object
    * @public object
    */
    public $_response;
    
    /**
    * Whether to allow redirects
    * @public boolean
    */
    public $_allowRedirects;
    
    /**
    * Maximum redirects allowed
    * @public integer
    */
    public $_maxRedirects;
    
    /**
    * Current number of redirects
    * @public integer
    */
    public $_redirects;

    /**
    * Constructor
    *
    * Sets up the object
    * @param $url The url to fetch/access
    * @param $params Associative array of parameters which can be:
    *                  method         - Method to use, GET, POST etc
    *                  http           - HTTP Version to use, 1.0 or 1.1
    *                  user           - Basic Auth username
    *                  pass           - Basic Auth password
    *                  proxy_host     - Proxy server host
    *                  proxy_port     - Proxy server port
    *                  proxy_user     - Proxy auth username
    *                  proxy_pass     - Proxy auth password
    *                  timeout        - Connection timeout in seconds.
    *                  allowRedirects - Whether to follow redirects or not
    *                  maxRedirects   - Max number of redirects to follow
    * @access public
    */
    function HTTP_Request($url, $params = array())
    {
        $this->setURL($url);

        $this->_sock           = new Net_Socket();
        $this->_method         =  HTTP_REQUEST_METHOD_GET;
        $this->_http           =  HTTP_REQUEST_HTTP_VER_1_1;
        $this->_requestHeaders = array();
        $this->_postData       = null;

        $this->_user = null;
        $this->_pass = null;

        $this->_proxy_host = null;
        $this->_proxy_port = null;
        $this->_proxy_user = null;
        $this->_proxy_pass = null;

        $this->_allowRedirects = true;
        $this->_maxRedirects   = 3;
        $this->_redirects      = 0;

        $this->_timeout  = null;
        $this->_response = null;

        foreach ($params as $key => $value) {
            $this->{'_' . $key} = $value;
        }

        // Default useragent
        $this->addHeader('User-Agent', 'PEAR HTTP_Request class ( http://pear.php.net/ )');

        // Default Content-Type
        $this->addHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Make sure keepalives dont knobble us
        $this->addHeader('Connection', 'close');

        // Basic authentication
        if (!empty($this->_user)) {
            $this->_requestHeaders['Authorization'] = 'Basic ' . base64_encode($this->_user . ':' . $this->_pass);
        }

        // Host header
        if (HTTP_REQUEST_HTTP_VER_1_1 == $this->_http) {

            $this->addHeader('Host', $this->_generateHostHeader());

            if (extension_loaded('zlib')) {
                $this->addHeader('Accept-Encoding', 'gzip');
            }
        }
    }
    
    /**
    * Generates a Host header for HTTP/1.1 requests
    *
    * @access private
    */
    function _generateHostHeader()
    {
        if ($this->_url->port != 80 AND strcasecmp($this->_url->protocol, 'http') == 0) {
            $host = $this->_url->host . ':' . $this->_url->port;

        } elseif ($this->_url->port != 443 AND strcasecmp($this->_url->protocol, 'https') == 0) {
            $host = $this->_url->host . ':' . $this->_url->port;

        } elseif ($this->_url->port == 443 AND strcasecmp($this->_url->protocol, 'https') == 0 AND strpos($this->_url->url, ':443') !== false) {
            $host = $this->_url->host . ':' . $this->_url->port;
        
        } else {
            $host = $this->_url->host;
        }

        return $host;
    }
    
    /**
    * Resets the object to its initial state.
    * Takes the same parameters as the constructor.
    *
    * @param  string $url    The url to be requested
    * @param  array  $params Associative array of parameters
    *                        (see constructor for details)
    * @access public
    */
    function reset($url, $params = array())
    {
        $this->HTTP_Request($url, $params);
    }

    /**
    * Sets the URL to be requested
    *
    * @param  string $url The url to be requested
    * @access public
    */
    function setURL($url)
    {
        $this->_url = new Net_URL($url);

        // If port is 80 and protocol is https, assume port 443 is to be used
        // This does mean you can't send an https request to port 80 without
        // some fudge. (mmm...)
        if (strcasecmp($this->_url->protocol, 'https') == 0 AND $this->_url->port == 80) {
            $this->_url->port = 443;
        }

        $this->addHeader('Host', $this->_generateHostHeader());
    }
    
    /**
    * Sets a proxy to be used
    *
    * @param $host Proxy host
    * @param $port Proxy port
    * @param $user Proxy username
    * @param $pass Proxy password
    * @access public
    */
    function setProxy($host, $port = 8080, $user = null, $pass = null)
    {
        $this->_proxy_host = $host;
        $this->_proxy_port = $port;
        $this->_proxy_user = $user;
        $this->_proxy_pass = $pass;

        if (!empty($user)) {
            $this->addHeader('Proxy-Authorization', 'Basic ' . base64_encode($user . ':' . $pass));
        }
    }

    /**
    * Sets basic authentication parameters
    *
    * @param $user Username
    * @param $pass Password
    */
    function setBasicAuth($user, $pass)
    {
        $this->_user = $user;
        $this->_pass = $pass;

        $this->addHeader('Authorization', 'Basic ' . base64_encode($user . ':' . $pass));
    }

    /**
    * Sets the method to be used, GET, POST etc.
    *
    * @param $method Method to use. Use the defined constants for this
    * @access public
    */
    function setMethod($method)
    {
        $this->_method = $method;
    }

    /**
    * Sets the HTTP version to use, 1.0 or 1.1
    *
    * @param $http Version to use. Use the defined constants for this
    * @access public
    */
    function setHttpVer($http)
    {
        $this->_http = $http;
    }

    /**
    * Adds a request header
    *
    * @param $name Header name
    * @param $value Header value
    * @access public
    */
    function addHeader($name, $value)
    {
        $this->_requestHeaders[$name] = $value;
    }

    /**
    * Removes a request header
    *
    * @param $name Header name to remove
    * @access public
    */
    function removeHeader($name)
    {
        if (isset($this->_requestHeaders[$name])) {
            unset($this->_requestHeaders[$name]);
        }
    }

    /**
    * Adds a querystring parameter
    *
    * @param $name Querystring parameter name
    * @param $value Querystring parameter value
    * @param $preencoded Whether the value is already urlencoded or not, default = not
    * @access public
    */
    function addQueryString($name, $value, $preencoded = false)
    {
        $this->_url->addQueryString($name, $value, $preencoded);
    }    
    
    /**
    * Sets the querystring to literally what you supply
    *
    * @param $querystring The querystring data. Should be of the format foo=bar&x=y etc
    * @param $preencoded Whether data is already urlencoded or not, default = already encoded
    * @access public
    */
    function addRawQueryString($querystring, $preencoded = true)
    {
        $this->_url->addRawQueryString($querystring, $preencoded);
    }

    /**
    * Adds postdata items
    *
    * @param $name Post data name
    * @param $value Post data value
    * @param $preencoded Whether data is already urlencoded or not, default = not
    * @access public
    */
    function addPostData($name, $value, $preencoded = false)
    {
        if ($preencoded) {
            $this->_postData[$name] = $value;
        } else {
            $this->_postData[$name] = is_array($value)? array_map('urlencode', $value): urlencode($value);
        }
    }

    /**
    * Adds raw postdata
    *
    * @param $postdata The data
    * @param $preencoded Whether data is preencoded or not, default = already encoded
    * @access public
    */
    function addRawPostData($postdata, $preencoded = true)
    {
        $this->_postData = $preencoded ? $postdata : urlencode($postdata);
    }

    /**
    * Clears any postdata that has been added. Useful for
    * multiple request scenarios.
    *
    * @access public
    */
    function clearPostData()
    {
        $this->_postData = null;
    }

    /**
    * Appends a cookie to "Cookie:" header
    * 
    * @param string $name cookie name
    * @param string $value cookie value
    * @access public
    */
    function addCookie($name, $value)
    {
        $cookies = isset($this->_requestHeaders['Cookie']) ? $this->_requestHeaders['Cookie']. '; ' : '';
        $this->addHeader('Cookie', $cookies . urlencode($name) . '=' . urlencode($value));
    }
    
    /**
    * Clears any cookies that have been added. Useful
    * for multiple request scenarios
    *
    * @access public
    */
    function clearCookies()
    {
        $this->removeHeader('Cookie');
    }

    /**
    * Sends the request
    *
    * @access public
    * @return mixed  PEAR error on error, true otherwise
    */
    function sendRequest()
    {
        $host = isset($this->_proxy_host) ? $this->_proxy_host : $this->_url->host;
        $port = isset($this->_proxy_port) ? $this->_proxy_port : $this->_url->port;

        // 4.3.0 supports SSL connections using OpenSSL. The function test determines
        // we running on at least 4.3.0
        if (strcasecmp($this->_url->protocol, 'https') == 0 AND function_exists('file_get_contents') AND extension_loaded('openssl')) {
            $host = 'ssl://' . $host;
        }

        // If this is a second request, we may get away without
        // re-connecting if they're on the same server
        if (   PEAR::isError($err = $this->_sock->connect($host, $port, null, $this->_timeout))
            OR PEAR::isError($err = $this->_sock->write($this->_buildRequest())) ) {

           return $err;
        }

        // Read the response
        if (PEAR::isError($err = $this->readResponse()) ) {
            return $err;
        }

        // Check for redirection
        if (    $this->_allowRedirects
            AND $this->_redirects <= $this->_maxRedirects
            AND $this->getResponseCode() > 300
            AND $this->getResponseCode() < 399
            AND !empty($this->_response->_headers['Location'])) {

            
            $redirect = $this->_response->_headers['Location'];
			$this->_url->querystring = '';

            // Absolute URL
            if (preg_match('/^https?:\/\//i', $redirect)) {
                $this->_url = new Net_URL($redirect);

            // Absolute path
            } elseif ($redirect{0} == '/') {
                $this->_url->path = $redirect;
            
            // Relative path
            } elseif (substr($redirect, 0, 3) == '../' OR substr($redirect, 0, 2) == './') {
                if (substr($this->_url->path, -1) == '/') {
                    $redirect = $this->_url->path . $redirect;
                } else {
                    $redirect = dirname($this->_url->path) . '/' . $redirect;
                }
                $redirect = Net_URL::resolvePath($redirect);
                $this->_url->path = $redirect;
                
            // Filename, no path
            } else {
                if (substr($this->_url->path, -1) == '/') {
                    $redirect = $this->_url->path . $redirect;
                } else {
                    $redirect = dirname($this->_url->path) . '/' . $redirect;
                }
                $this->_url->path = $redirect;
            }
			$this->_method = HTTP_REQUEST_METHOD_GET;

            $this->_redirects++;
            return $this->sendRequest();

        // Too many redirects
        } elseif ($this->_allowRedirects AND $this->_redirects > $this->_maxRedirects) {
            return PEAR::raiseError('Too many redirects');
        }
        
        return true;
    }

    /**
    * Returns the response code
    *
    * @access public
    */
    function getResponseCode()
    {
        return isset($this->_response->_code) ? $this->_response->_code : false;
    }

    /**
    * Returns either the named header or all if no name given
    *
    * @param $headername The header name to return
    * @access public
    */
    function getResponseHeader($headername = null)
    {
        if (!isset($headername)) {
            return $this->_response->_headers;
        } else {
            return isset($this->_response->_headers[$headername]) ? $this->_response->_headers[$headername] : false;
        }
    }

    /**
    
    * Returns the body of the response
    *
    * @access public
    */
    function getResponseBody()
    {
        return isset($this->_response->_body) ? $this->_response->_body : false;
    }

    /**
    * Returns cookies set in response
    * 
    * @access public
    */
    function getResponseCookies()
    {
        return isset($this->_response->_cookies) ? $this->_response->_cookies : false;
    }

    /**
    * Builds the request string
    *
    * @access private
    * @return string The request string
    */
    function _buildRequest()
    {
        $querystring = ($querystring = $this->_url->getQueryString()) ? '?' . $querystring : '';

        $host = isset($this->_proxy_host) ? $this->_url->protocol . '://' . $this->_url->host : '';
        $port = (isset($this->_proxy_host) AND $this->_url->port != 80) ? ':' . $this->_url->port : '';
        $path = $this->_url->path . $querystring;
        $url  = $host . $port . $path;

        $request = $this->_method . ' ' . $url . ' HTTP/' . $this->_http . "\r\n";

        // Request Headers
        if (!empty($this->_requestHeaders)) {
            foreach ($this->_requestHeaders as $name => $value) {
                $request .= $name . ': ' . $value . "\r\n";
            }
        }

        // Post data if it's an array
        if (!empty($this->_postData) AND is_array($this->_postData)) {
            foreach($this->_postData as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $postdata[] = sprintf('%s[%s]=%s', $name, $k, $v);
                    }
                } else {
                    $postdata[] = $name . '=' . $value;
                }
            }

            $postdata = implode('&', $postdata);
            $request .= 'Content-Length: ' . strlen($postdata) . "\r\n\r\n";
            $request .= $postdata;

        // Post data if it's raw
        } elseif(!empty($this->_postData)) {
            $request .= 'Content-Length: ' . strlen($this->_postData) . "\r\n\r\n";
            $request .= $this->_postData;

        // No post data, so simply add a final CRLF
        } else {
            $request .= "\r\n";
        }
        
        return $request;
    }

    /**
    * Initiates reading of the response
    *
    * @access private
    */
    function readResponse()
    {
        $this->_response = new HTTP_Response($this->_sock);
		$this->_sock->disconnect();
		if ($this->_response->_cookies) {
			foreach ($this->_response->_cookies as $c) {
				$this->addCookie($c["name"], $c["value"]);
			}
		}
    }
}


/**
* Response class to complement the Request class
*/
class HTTP_Response
{
    /**
    * Socket object
    * @public object
    */
    public $_sock;

    /**
    * Protocol
    * @public string
    */
    public $_protocol;
    
    /**
    * Return code
    * @public string
    */
    public $_code;
    
    /**
    * Response headers
    * @public array
    */
    public $_headers;

    /**
    * Cookies set in response  
    * @public array
    */
    public $_cookies;

    /**
    * Response body
    * @public string
    */
    public $_body;

    /**
    * Constructor
    *
    * Reads the entire response, parse out the headers, and checks
    * for chunked encoding.
    *
    * @return mixed PEAR Error on error, true otherwise
    */
    function HTTP_Response(&$sock)
    {
        // Fetch all
        $response = $sock->readAll();

        if (PEAR::isError($response)) {
            return $response;
        }

        // Sort out headers
		$pos = strpos($response, "\r\n\r\n");
		if (substr($response, $pos+4, 8) == 'HTTP/1.1') {
			$response = substr($response, $pos+4);
			$pos = strpos($response, "\r\n\r\n");
		}
        $headers = substr($response, 0, $pos);
        $headers = explode("\r\n", $headers);

        list($this->_protocol, $this->_code) = sscanf($headers[0], '%s %s');
        unset($headers[0]);
        foreach ($headers as $value) {
            $headername   = substr($value, 0, strpos($value, ':'));
            $headername_i = strtolower($headername);
            $headervalue  = ltrim(substr($value, strpos($value, ':') + 1));

            if ('set-cookie' != $headername_i) {
                $this->_headers[$headername] = $headervalue;
                $this->_headers[$headername_i] = $headervalue;
            } else {
                // Parse a SetCookie header to fill _cookies array
                $cookie = array(
                    'expires' => null,
                    'domain'  => null,
                    'path'    => null,
                    'secure'  => false
                );

                // Only a name=value pair
                if (!strpos($headervalue, ';')) {
                    list($cookie['name'], $cookie['value']) = array_map('trim', explode('=', $headervalue));
                    $cookie['name']  = urldecode($cookie['name']);
                    $cookie['value'] = urldecode($cookie['value']);

                // Some optional parameters are supplied
                } else {
                    $elements = explode(';', $headervalue);
                    list($cookie['name'], $cookie['value']) = array_map('trim', explode('=', $elements[0]));
                    $cookie['name']  = urldecode($cookie['name']);
                    $cookie['value'] = urldecode($cookie['value']);

                    for ($i = 1; $i < count($elements);$i++) {
                        list ($elName, $elValue) = array_map('trim', explode('=', $elements[$i]));
                        if ('secure' == $elName) {
                            $cookie['secure'] = true;
                        } elseif ('expires' == $elName) {
                            $cookie['expires'] = str_replace('"', '', $elValue);
                        } elseif ('path' == $elName OR 'domain' == $elName) {
                            $cookie[$elName] = urldecode($elValue);
                        } else {
                            $cookie[$elName] = $elValue;
                        }
                    }
                }
                $this->_cookies[] = $cookie;
            }

        }

        // Store body
        $this->_body = substr($response, strpos($response, "\r\n\r\n") + 4);

        // If response was chunked, parse it out
        if (@$this->_headers['transfer-encoding'] == 'chunked') {
            $body   = $this->_body;
            $chunks = array();
            while (true) {
                $chunksize = 0;
                $line = substr($body, 0, $pos = strpos($body, "\r\n"));
                $body = substr($body, $pos + 2);

                if (preg_match('/^([0-9a-f]+)/i', $line, $matches)) {
                    $chunksize = hexdec($matches[1]);
                    if ($chunksize > 0) {
                        $chunks[] = substr($body, 0, $chunksize);
                        $body = substr($body, $chunksize + 2); // Plus trailing CRLF
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }
            
            // Save chunks to $this->_body
            $this->_body = implode('', $chunks);
        }

        // If response was compressed using gzip, uncompress it
        if (@$this->_headers['content-encoding'] == 'gzip') {
            $body = substr($this->_body, 10);
            $this->_body = gzinflate($body);
        }

        return true;
    }
} // End class HTTP_Response
?>

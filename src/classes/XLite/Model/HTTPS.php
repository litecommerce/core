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

namespace XLite\Model;

/**
 * HTTPS bouncer
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class HTTPS extends \XLite\Base
{
    const HTTPS_ERROR = 1;
    const HTTPS_SUCCESS = 2;

    const CRLF = "\r\n";

    /**
     * Request method 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $method = 'POST';

    /**
     * Request URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $url = '';

    /**
     * Request data 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $data = array();

    /**
     * Request content type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $conttype = 'application/x-www-form-urlencoded';

    /**
     * PEM certificate file
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cert = null;

    /**
     * SSL private key file
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $kcert = null;

    /**
     * Force use SSL 3
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $use_ssl3 = false;

    /**
     * Timeout (seconds)
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $timeout = null;

    /**
     * Request user name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $user = null;

    /**
     * Request password 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $password = null;

    /**
     * Response HTTP code 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $responseCode = null;

    /**
     * Request response 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $response = null;

    /**
     * Response error message
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $error = '';

    /**
     * Raw response headers 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $rawHeaders = null;

    /**
     * Response headers 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $responseHeaders = array();

    /**
     * Path to curl executable file
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $curlBinary = null;

    /**
     * CURL error code 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $curlErrorCode = 0;

    /**
     * Urlencoded data or not
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $urlencoded = false;

    /**
     * Request headers 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $headers = array();

    /**
     * CURL error codes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $curlErrors = array(
        '1' => 'Unsupported protocol "PROTO". This build of curl has no support for this protocol.',
        '2' => 'Failed to initialize.',
        '3' => 'URL "FULLURL" malformat. The syntax was not correct.',
        '4' => 'URL "FULLURL" user malformatted. The user-part of the URL syntax was not correct.',
        '5' => 'Couldn\'t resolve proxy. The given proxy host could not be resolved.',
        '6' => 'Couldn\'t resolve host HOST. The given remote host was not resolved.',
        '7' => 'Failed to connect to host HOST.',
        '8' => 'FTP weird server reply. The server sent data curl couldn\'t parse.',
        '9' => 'FTP access denied. The server denied login.',
        '10' => 'FTP user/password incorrect. Either one or both were not accepted by the server.',
        '11' => 'FTP weird PASS reply. Curl couldn\'t parse the reply sent to the PASS request.',
        '12' => 'FTP weird USER reply. Curl couldn\'t parse the reply sent to the USER request.',
        '13' => 'FTP weird PASV reply, Curl couldn\'t parse the reply sent to the PASV request.',
        '14' => 'FTP weird 227 format. Curl couldn\'t parse the 227-line the server sent.',
        '15' => 'FTP can\'t get host. Couldn\'t resolve the host IP we got in the 227-line.',
        '16' => 'FTP can\'t reconnect. Couldn\'t connect to the host we got in the 227-line.',
        '17' => 'FTP couldn\'t set binary. Couldn\'t change transfer method to binary.',
        '18' => 'Partial file. Only a part of the file was transfered.',
        '19' => 'FTP couldn\'t RETR file. The RETR command failed.',
        '20' => 'FTP write error. The transfer was reported bad by the server.',
        '21' => 'FTP quote error. A quote command returned error from the server.',
        '22' => 'HTTP not found. The requested page FULLURL was not found. This return code only appears if --fail is used.',
        '23' => 'Write error. Curl couldn\'t write data to a local filesystem or similar.',
        '24' => 'Malformat user. User name badly specified.',
        '25' => 'FTP couldn\'t STOR file. The server denied the STOR operation.',
        '26' => 'Read error. Various reading problems.',
        '27' => 'Out of memory. A memory allocation request failed.',
        '28' => 'Operation timeout. The specified time-out period was reached according to the conditions.',
        '29' => 'FTP couldn\'t set ASCII. The server returned an unknown reply.',
        '30' => 'FTP PORT failed. The PORT command failed.',
        '31' => 'FTP couldn\'t use REST. The REST command failed.',
        '32' => 'FTP couldn\'t use SIZE. The SIZE command failed. The command is an extension to the original FTP spec RFC 959.',
        '33' => 'HTTP range error. The range "command" didn\'t work.',
        '34' => 'HTTP post error. Internal post-request generation error.',
        '35' => 'SSL connect error. The SSL handshaking failed.',
        '36' => 'FTP bad download resume. Couldn\'t continue an earlier aborted download.',
        '37' => 'FILE couldn\'t read file. Failed to open the file. Permissions?',
        '38' => 'LDAP cannot bind. LDAP bind operation failed.',
        '39' => 'LDAP search failed.',
        '40' => 'Library not found. The LDAP library was not found.',
        '41' => 'Function not found. A required LDAP function was not found.',
        '42' => 'Aborted by callback. An application told curl to abort the operation.',
        '43' => 'Internal error. A function was called with a bad parameter.',
        '44' => 'Internal error. A function was called in a bad order.',
        '45' => 'Interface error. A specified outgoing interface could not be used.',
        '46' => 'Bad password entered. An error was signaled when the password was entered.',
        '47' => 'Too many redirects. When following redirects, curl hit the maximum amount.',
        '48' => 'Unknown TELNET option specified.',
        '49' => 'Malformed telnet option.',
        '51' => 'The remote peer\'s SSL certificate wasn\'t ok',
        '52' => 'The server didn\'t reply anything, which here is considered an error.',
        '53' => 'SSL crypto engine not found',
        '54' => 'Cannot set SSL crypto engine as default',
        '55' => 'Failed sending network data',
        '56' => 'Failure in receiving network data',
        '57' => 'Share is in use (internal error)',
        '58' => 'Problem with the local certificate',
        '59' => 'Couldn\'t use specified SSL cipher',
        '60' => 'Problem with the CA cert (path? permission?)',
        '61' => 'Unrecognized transfer encoding',
    );

    /**
     * Writable properties names
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $writableProperties = array(
        'url',
        'data',
        'cert',
        'kcert',
        'method',
        'conttype',
        'use_ssl3',
        'timeout',
        'user',
        'password',
        'urlencoded',
    );

    /**
     * Readable properties names
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $readableProperties = array(
        'response', 'error', 'responseHeaders', 'responseCode',
    );

    /**
     * Protected constructor. It's empty now
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct()
    {
        require_once LC_LIB_DIR . 'Net' . LC_DS . 'URL2.php';
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
    public function __set($name, $value)
    {
        if (in_array($name, $this->writableProperties)) {
            $this->$name = $value;
        }
    }

    /**
     * Getter
     *
     * @param string $name property name
     *
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function __get($name)
    {
        $result = null;

        if (in_array($name, $this->readableProperties)) {
            $result = $this->$name;
        } else {
            $result = parent::__get($name);
        }

        return $result;
    }

    /**
     * Add header 
     * 
     * @param string $name  Header name
     * @param string $value Header value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addHeader($name, $value = null)
    {
        if (is_null($value)) {
            $this->headers[] = $name;

        } else {
            $this->headers[$name] = $value;
        }
    }

    /**
     * Get headers 
     * 
     * @param boolean $slashed Slashed flag
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHeaders($slashed = false)
    {
        $headers = array();

        foreach ($this->headers as $k=>$v) {
            if ($slashed) {
                $v = addslashes($v);
            }

            $headers[] = is_integer($k) ? $v : ($k . ': ' . $v);
        }

        return $headers;
    }
    
    /**
     * Do request 
     *
     * @param string $software HTTPS engine name
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function request($software = null)
    {
        $this->rawHeaders = '';
        $this->responseCode = null;
        $this->responseHeaders = array();
        $this->response = null;

        if (is_null($software)) {
            $software = 'autodetect' == $this->config->Security->httpsClient
                ? $this->detectSoftware()
                : $this->config->Security->httpsClient;
        }

        switch ($software) {
            case 'libcurl':
                $result = $this->requestLibCURL();
                break;

            case 'curl':
                $result = $this->requestCURL();
                break;

            case 'openssl':
                $result = $this->requestOpenSSL();
                break;

            default:
                $this->error = 'Can\'t detect client software to use for SSL connections: '
                    . 'nither libcurl extension is installed nor curl/openssl binaries are found in PATH';
                $result = self::HTTPS_ERROR;
        }

        if (self::HTTPS_SUCCESS == $result) {
            $this->error = '';
        }

        return $result;
    }

    /**
     * Get post raw data
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPost()
    {
        $result = false;

        if (is_array($this->data)) {
            $params = array();
            foreach ($this->data as $k => $v) {
                if ($this->urlencoded) {
                    $params[] = $k . '=' . $v;
                } else {
                    $params[] = $k . '=' . urlencode($v);
                }
            }
            $result = join('&', $params);

        } elseif ($this->urlencoded) {
            $result = $this->data;

        } else {
            $result = urlencode($this->data);
        }

        return $result;
    }

    /**
     * Detect software 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function detectSoftware()
    {
        $result = false;

        if ($this->detectLibCURL() == self::HTTPS_SUCCESS) {
            $result = 'libcurl';

        } elseif (!LC_OS_IS_WIN && func_find_executable('openssl')) {
            $result = 'openssl';

        } elseif ($this->detectCURL() == self::HTTPS_SUCCESS) {
            $result = 'curl';
        }

        return $result;
    }
    
    /**
     * Detect libcurl 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function detectLibCURL()
    {
        $result = self::HTTPS_SUCCESS;

        if (!function_exists('curl_init')) {
            $this->error = 'libcurl extension is not found';
            $result = self::HTTPS_ERROR;

        } else {
            $version = curl_version();
            if (
                (is_array($version) && !in_array('https', $version['protocols']))
                || (!is_array($version) && !preg_match('/ssl|tls/Ssi', $version))
            ) {
                $result = self::HTTPS_ERROR;
            }
        }

        return $result;
    }

    /**
     * Initialize libcurl resource
     * 
     * @return resource
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initLibCURL()
    {
        $post = 'POST' == $this->method;

        $url = $this->url;
        if (!$post) {
            $data = $this->getPost();
            if ($data) {
                $url .= (false === strpos($url, '?') ? '?' : '&') . $data;
            }
        }

        $c = curl_init($url);

        $url = new \Net_URL2($this->url);
        if ($url->port != 443 && $url->port != 80) {
            curl_setopt($c, CURLOPT_PORT, $url->port);
        }

        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);

        if ($this->use_ssl3) {
            curl_setopt($c, CURLOPT_SSLVERSION, 3);
        }

        if ($this->timeout) {
            curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        }

        if ($this->config->Security->proxy) {
            curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($c, CURLOPT_PROXY, $this->config->Security->proxy);
        }

        curl_setopt($c, CURLOPT_POST, $post);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        if ($post) {
            if (is_array($this->data)) {
                curl_setopt($c, CURLOPT_POSTFIELDS, $this->data);

            } else {
                curl_setopt($c, CURLOPT_POSTFIELDS, $this->getPost());
            }
        }

        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);

        if ($this->referer) {
            curl_setopt($c, CURLOPT_REFERER, $this->referer);
        }

        $headers = $this->getHeaders();
        if (count($headers) > 0) {
            curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        }

        if ($this->cert) {
            curl_setopt($c, CURLOPT_SSLCERT, $this->cert);
            if ($this->kcert) {
                curl_setopt($c, CURLOPT_SSLKEY, $this->kcert);
            }
        }

        saveCURLHeader($this);
        curl_setopt($c, CURLOPT_HEADERFUNCTION, 'saveCURLHeader');

        if ($this->user) {
            curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

            if ($this->password) {
                curl_setopt($c, CURLOPT_USERPWD, $this->user . ':' . $this->password);

            } else {
                curl_setopt($c, CURLOPT_USERPWD, $this->user);
            }
        }

        return $c;
    }

    /**
     * Append raw response headers
     * 
     * @param string $string Headers string
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setHeadersCallback($string)
    {
        $this->rawHeaders .= $string;

        return strlen($string);
    }

    /**
     * Rrequest with libcurl
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function requestLibCURL()
    {
        $result = self::HTTPS_SUCCESS;

        if ($this->detectLibCURL() == self::HTTPS_ERROR) {
            $result = self::HTTPS_ERROR;

        } else {

            $c = $this->initLibCURL();

            $this->response = curl_exec($c);
            $this->curlErrorCode = curl_errno($c);
            $this->error = curl_error($c);
            curl_close($c);

            if ($this->curlErrorCode) {
                $result = self::HTTPS_ERROR;

            } else {
                $this->parseResponseHeaders($this->rawHeaders);
            }
        }

        return $result;
    }

    /**
     * Detect external curl 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function detectCURL()
    {
        $this->curlBinary = func_find_executable('curl');

        $result = self::HTTPS_SUCCESS;

        if (!$this->curlBinary) {
            $this->error = 'curl executable is not found';
            $result = self::HTTPS_ERROR;

        } else {

            $output = array();
            exec($this->curlBinary . ' --version', $output);
            $version = @$output[0];
            $this->supportsInsecure = false;

            if (preg_match('/curl ([^ $]+)/', $version, $m)) {
                $parts = explode('.', $m[1]);
                $this->supportsInsecure = $parts[0] > 7
                    || ($parts[0] = 7 && $parts[1] >= 10);
            }
        }

        return $result;
    }
    
    /**
     * Request with external CURL 
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function requestCURL()
    {
        $result = self::HTTPS_SUCCESS;

        if ($this->detectCURL() == self::HTTPS_ERROR) {
            $result = self::HTTPS_ERROR;

        } elseif (!$this->cert && !$this->supportsInsecure) {
            $result = self::HTTPS_ERROR;
            $this->error = 'curl version must be > 7.10 or you must use SSL certificates';

        } else {

            list($execline, $fname) = $this->getCURLCommandLine();

            $this->response = array();
            exec($execline, $this->response, $this->curlErrorCode);

            $this->response = implode('', $this->response);
            if (file_exists($fname)) {
                $this->rawHeaders = file_get_contents($fname);
                unlink($fname);
            }

            if ($this->curlErrorCode) {
                $this->error = 'Curl error #' . $this->curlErrorCode;

                if (isset($this->curlErrors[$this->curlErrorCode])) {
                    $this->error .= ': ' . $this->curlErrors[$this->curlErrorCode];

                    $url = new \Net_URL2($this->url);

                    $this->error = str_replace('PROTO', $url->protocol, $this->error);
                    $this->error = str_replace('FULLURL', $this->url, $this->error);
                    $this->error = str_replace('HOST', $url->host, $this->error);
                }

                // get detailed error message
                $erromsg = array();
                exec($execline . ' ' . $this->url . ' 2>&1', $erromsg, $this->curlErrorCode);
                $erromsg = join('', $erromsg);
                $this->error .= ' \'' . $erromsg . '\'';

                $result = self::HTTPS_ERROR;

            } elseif ($this->rawHeaders) {
                $this->parseResponseHeaders($this->rawHeaders);
            }
        }

        return $result;
    }

    /**
     * Get CURL command line and headers file
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCURLCommandLine()
    {

        $execline = $this->curlBinary . ' -k'
            . ' -d "' . preg_replace('/"/', '\"', $this->getPost()) . '"';

        if ($this->cert) {
            $execline .= ' --cert "' . $this->cert . '"';
        }

        if ($this->kcert) {
            $execline .= ' --key "' . $this->kcert . '"';
        }

        if ($this->use_ssl3) {
            $execline .= ' --sslv3';
        }

        if ($this->timeout) {
            $execline .= ' --connect-timeout ' . $this->timeout . ' -m ' . $this->timeout;
        }

        if ($this->config->Security->proxy) {
            $execline .= '  --proxy ' . $this->config->Security->proxy;
        }

        if ($this->user) {
            $execline .= ' --anyauth';

            if ($this->password) {
                $execline .= '  --user ' . $this->user . ':' . $this->password;

            } else {
                $execline .= '  --user ' . $this->user;
            }
        }

        if ($this->conttype != 'application/x-www-form-urlencoded') {
            $execline .= ' -H "Content-Type: ' . $this->conttype . '"';
        }

        if (!empty($this->referer)) {
            $execline .= ' -H "Referer: ' . $this->referer . '"';
        }
        
        $headers = $this->getHeaders(true);
        if (count($headers) > 0) {
            foreach ($headers as $v) {
                $execline .= ' -H "' . $v . '"';
            }
        }

        if ($this->method == 'GET') {
            $execline .= ' --get';
        }

        $fname = $this->getTempFilename('lc_curl_headers');
        $execline .= ' -D ' . $fname;

        array($execline .= ' -s ' . $this->url, $fname);
    }

    /**
     * Detect OpenSSL bouncer availability
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function detectOpenSSL()
    {
        $result = self::HTTPS_SUCCESS;

        if (LC_OS_IS_WIN || !func_find_executable('openssl')) {
            $this->error = 'openssl executable is not found';
            $result = self::HTTPS_ERROR;
        }

        return $result;
    }

    /**
     * Request with OpenSSL 
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function requestOpenSSL()
    {
        $result = self::HTTPS_SUCCESS;

        if (self::HTTPS_ERROR == $this->detectOpenSSL()) {
            $result = self::HTTPS_ERROR;

        } else {

            $cmdline = $this->getOpenSSLCommandLine();
            $data = $this->getPost();

            $errFile = $this->getTempFilename('lc_ossl_errors');

            $descriptorspec = array(
                array('pipe', 'r'),
                array('pipe', 'w'),
                array('file', $errFile, 'a')
            );

            $fp = proc_open($cmdline, $descriptorspec, $pipes);
            if (!is_resource($fp)) {
                $this->error = 'Can\'t execute command ' . $cmdline;
                $result = self::HTTPS_ERROR;

            } elseif (!$this->sendOpenSSLRequest($pipes)) {
                $result = self::HTTPS_ERROR;

            } elseif ($this->processOpenSSLRedirect()) {
                $result = $this->requestOpenSSL();
            }
        }

        return $result;
    }

    /**
     * Get OpenSSL command line 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOpenSSLCommandLine()
    {
        $url = new \Net_URL2($this->url);
        if ($url->port == 80) {
            $url->port = 443;
        }

        $args = array(
            '-connect ' . $url->host . ':' . $url->port,
        );

        if ($this->cert) {
            $args[] = '-cert ' . $this->cert;
        }

        if ($this->kcert) {
            $args[] = '-key ' . $this->kcert;
        }

        if ($this->use_ssl3) {
            $args[] = '-ssl3';
        }

        return func_find_executable('openssl') . ' s_client ' . implode(' ', $args) . ' -quiet 2>&1';
    }

    /**
     * Send OpenSSL request 
     * 
     * @param array $pipes Process pipes array
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendOpenSSLRequest(array $pipes)
    {
        $result = true;

        $data = $this->getPost();

        fputs($pipes[0], $this->method . ' ' . $url->path . '?' . $url->getQueryString() . ' HTTP/1.0' . self::CRLF);
        fputs($pipes[0], 'Host: ' . $url->host . self::CRLF);
        fputs($pipes[0], 'User-Agent: Mozilla/4.5 [en]' . self::CRLF);
        fputs($pipes[0], 'Content-Type: ' . $this->conttype . self::CRLF);
        fputs($pipes[0], 'Content-Length: ' . strlen($data) . self::CRLF);
        
        $headers = $this->getHeaders();
        if (count($headers) > 0) {
            foreach ($headers as $v) {
                fputs($pipes[0], $v . self::CRLF);
            }
        }

        fputs($pipes[0], self::CRLF);

        if ($this->method == 'POST') {
            fputs($pipes[0], $data . self::CRLF);
        }
        fclose($pipes[0]);

        // retrieve result
        $this->headers = array();
        $this->response = '';
        $data = '';
        while (!feof($pipes[1])) {
            $data .= fread($pipes[1], 65536);
        }
        fclose($pipes[1]);
        proc_close($fp);

        // parse response

        // find end-of-headers
        $pos = strpos($data, self::CRLF . self::CRLF);
        if (false === $pos) {
            $pos = strpos($data, "\n\n");
        }

        if (false === $pos) {
            $this->error = 'openssl error ' . $data;
            $result = false;

        } else {

            // parse headers
            $this->parseResponseHeaders(substr($data, 0, $pos));
            $this->response = trim(substr($data, $pos));
        }

        return $result;
    }

    /**
     * Check and preprocess data for redirect request
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processOpenSSLRedirect()
    {
        $result = false;

        if (
            isset($this->headers['location'])
            && $this->headers['location']
            && 302 == $this->responseCode
        ) {
            $this->method  = 'GET';
            $this->data    = '';
            $this->headers = array();
            $this->url     = $url->resolve($this->headers['location'])->getURL();

            $result = true;
        }

        return $result;
    }

    /**
     * Parse response headers 
     * 
     * @param string $rawHeaders Response headers
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponseHeaders($rawHeaders)
    {
        $responseHeaders = array_map('trim', explode("\n", $rawHeaders));
        if (preg_match('/HTTP\/1\.\d (\d+)/', $responseHeaders[0], $match)) {
            $this->responseCode = intval($match[1]);
        }
        unset($responseHeaders[0]);

        foreach ($responseHeaders as $v) {
            $v = explode(':', $v, 2);
            if (2 == count($v)) {
                $this->responseHeaders[strtolower($v[0])] = trim($v[1]);
            }
        }
    }

    /**
     * Get temporary file path
     * 
     * @param string $name File prefix
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTempFilename($name = 'temp')
    {
        if (function_exists('sys_get_temp_dir')) {
            $dir = sys_get_temp_dir();

        } else {
            $dir = LC_VAR_DIR;
        }

        return tempnam($dir, $name);
    }
}

/**
 * cURL header callback
 * 
 * @param mixed  $curl   cURL request resource or \XLite\Model\HTTPS object
 * @param string $header Headers string
 *  
 * @return integer
 * @see    ____func_see____
 * @since  3.0.0
 */
function saveCURLHeader($curl, $header = '')
{
    static $object = null;

    $result = 0;

    if ($curl instanceof \XLite\Model\HTTPS) {
        $object = $curl;
    }

    if ($header) {
        $result = $object->setHeadersCallback($header);
    }

    return $result;
}


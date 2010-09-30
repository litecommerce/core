<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Base class for all LiteCommerce web tests
 *  
 * @category   LiteCommerce_Tests
 * @package    LiteCommerce_Tests
 * @subpackage Main
 * @author     Ruslan R. Fazliev <rrf@x-cart.com> 
 * @copyright  Copyright (c) 2009 Ruslan R. Fazliev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php LiteCommerce license
 * @version    SVN: $Id$
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

abstract class XLite_Tests_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    const SELENIUM_TTL = 60000;

    /**
     * Prefix for all classes with test cases
     */
    const CLASS_PREFIX = 'XLite_Web_';

    static public $cssProcessedFiles = array();

    /**
     * Process W3C HTML validation warnings as errors
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $processW3CHTMLWarnings = true;

    /**
     * Base URL
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $baseURL = null;

    /**
     * Browsers list
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  1.0.0
     */
    public static $browsers = array(
/*
        array(
            'name'    => 'Safari (Windows)',
            'browser' => '*safari',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
        array(
            'name'    => 'Google chrome (Windows)',
            'browser' => '*googlechrome',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
        array(
            'name'    => 'IE 8 (Windows)',
            'browser' => '*iexplore',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
        array(
            'name'    => 'FireFox 3 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox 3\firefox.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
*/
        array(
            'name'    => 'FireFox 3.5 (Windows)',
            'browser' => '*firefox C:\Program Files\Mozilla Firefox 3.5\firefox.exe',
            'host'    => SELENIUM_SERVER,
            'port'    => 4444,
            'timeout' => self::SELENIUM_TTL,
        ),
/*
        array(
            'name'    => 'Opera 9 (Windows)',
            'browser' => '*opera C:\Program Files\Opera9\opera.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
         array(
            'name'    => 'Opera 10 (Windows)',
            'browser' => '*opera C:\Program Files\Opera10\opera.exe',
            'host'    => 'cormorant.crtdev.local',
            'port'    => 4444,
            'timeout' => 10000
        ),
*/
    );

    protected $unknownCSSProperty = array(
        'zoom',
        'border-radius',
        'border-top-left-raius',
        'border-top-right-raius',
        'border-bottom-left-raius',
        'border-bottom-right-raius',
        'box-shadow',
        '-moz-border-radius',
        '-webkit-border-radius',
        '-moz-border-radius-topleft',
        '-moz-border-radius-topright',
        '-moz-border-radius-bottomright',
        '-moz-border-radius-bottomleft',
        '-webkit-border-top-left-radius',
        '-webkit-border-top-right-radius',
        '-webkit-border-bottom-right-radius',
        '-webkit-border-bottom-left-radius',
        '-webkit-box-shadow',
        '-moz-box-shadow',
    );

    /**
     * Constructor
     * 
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($name = NULL, array $data = array(), array $browser = array())
    {
        $this->browserName = $browser['name'];
        $this->coverageScriptUrl = defined('SELENIUM_COVERAGE_URL')
            ? SELENIUM_COVERAGE_URL . '/phpunit_coverage.php'
            : SELENIUM_SOURCE_URL . '/phpunit_coverage.php';
        parent::__construct($name, $data, $browser);
    }

    /**
     * @return array
     * @since  Method available since Release 3.2.0
     */
    protected function getCodeCoverage()
    {
        if (!empty($this->coverageScriptUrl)) {
            $url = sprintf(
              '%s?PHPUNIT_SELENIUM_TEST_ID=%s',
              $this->coverageScriptUrl,
              $this->testId
            );

            $buffer = @file_get_contents($url);

            if ($buffer !== FALSE) {
                $buffer = unserialize($buffer);
                if ($buffer !== FALSE) {
                    return $this->matchLocalAndRemotePaths($buffer);
                }
            }
        }

        return array();
    }

    /**
     * Return message (common method)
     * 
     * @param string $message custom part of message
     * @param string $class   called class name
     * @param string $method  called method name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMessage($message, $class = '', $method = '')
    {
        // Full debag trace for called method
        $trace = debug_backtrace();
        $trace = $trace[1];

        // Retrieve class and method names
        $class = str_replace(self::CLASS_PREFIX, '', empty($class) ? $trace['class'] : $class);
        $method = lcfirst(str_replace('test', '', empty($method) ? $trace['function'] : $method));

        return $class . ' : ' . str_repeat(' ', 30 - strlen($this->browserName)) . $this->browserName . ' [' . $method . ']. ' . $message;
    }

    /**
     * PHPUnit default function.
     * Redefine this method only if you really need to do so.
     * In any other cases redefine the getRequest() one
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        // Print new line between classes
        $currentClass = get_called_class();
        if (empty(XLite_Tests_TestSuite::$currentClass) || $currentClass !== XLite_Tests_TestSuite::$currentClass) {
            echo "\n";
            XLite_Tests_TestSuite::$currentClass = $currentClass;
        }

        $this->baseURL = SELENIUM_SOURCE_URL . '/src/';

        $this->setBrowserUrl($this->baseURL);
    }

    /**
     * PHPUnit default function.
     * It's not recommended to redefine this method
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function tearDown()
    {
        $this->stop();

        $message = $this->getMessage('', get_called_class(), $this->name);
        echo "\n" . sprintf('%\'.-86s', trim($message));
    }

    public function getJSExpression($expression)
    {
        return $this->getEval('selenium.browserbot.getCurrentWindow().' . $expression);
    }

    public function assertJqueryPresent($pattern, $message = null)
    {
        $this->assertTrue(
            0 < intval($this->getJSExpression("$('" . $pattern . "').length")),
            $message
        );
    }

    public function assertJqueryNotPresent($pattern, $message = null)
    {
        $this->assertEquals(
            0,
            intval($this->getJSExpression("$('" . $pattern . "').length")),
            $message
        );
    }

    protected function skipCoverage()
    {
        $this->openAndWait('');
        $this->createCookie('no_xdebug_coverage=1');
    }

    public function __call($command, $arguments)
    {
        try {

            return parent::__call($command, $arguments);

        } catch (RuntimeException $e) {

            if (
                $e->getMessage() == 'Could not connect to the Selenium RC server.'
                || preg_match('/^The response from the Selenium RC server is invalid: Timed out after \d+ms$/Ss', $e->getMessage())
            ) {

                $this->markTestSkipped($e->getMessage());

            } else {

                throw $e;

            }

        }
    }

    /**
     * Validate W3C HTML
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validate()
    {
        // Foribidden validation for Google chrome
        if (preg_match('/^Google chrome/Ss', $this->browserName)) {
            return;
        }

        $html = trim($this->getHTML());

        $this->assertTrue(strlen($html) > 0, 'get pure HTML code');

        $this->validateHTML($html);

        return;

        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        foreach ($xpath->query("//link[@rel='stylesheet']") as $node) {
            $url = $node->getAttribute('href');
            if (preg_match('/^\//Ss', $url)) {
                $parsed = parse_url($this->baseURL);
                $url = $parsed['scheme'] . '://' . $parsed['host'] . $url;

            } elseif (!preg_match('/^https?:\/\//Ss', $url)) {
                $url = $this->baseURL . $url;
            }

            if (!in_array($url, XLite_Tests_SeleniumTestCase::$cssProcessedFiles)) {
                $this->validateCSS(trim(file_get_contents($url)), $url);
                XLite_Tests_SeleniumTestCase::$cssProcessedFiles[] = $url;
            }
        }
    }

    /**
     * Validate HTML 
     * 
     * @param string $html HTML
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function validateHTML($html)
    {
        $post = 'fragment=' . urlencode($html);

        $ch = curl_init();

//        curl_setopt($ch, CURLOPT_URL, 'http://w3c.crtdev.local/check');
        curl_setopt($ch, CURLOPT_URL, 'http://validator.w3.org/check');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($post)));

        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $this->assertTrue(is_string($body), 'page validation return non string data');

        $dom = new DOMDocument();
        $dom->loadHTML(trim($body));
        $xpath = new DOMXPath($dom);

        foreach ($xpath->query("//li[@class='msg_err']") as $node) {
            $em = $xpath->query('em', $node)->item(0)->nodeValue;
            $title = $xpath->query('span[@class=\'msg\']', $node)->item(0)->nodeValue;
            $descr = $xpath->query('pre/code', $node)->item(0)->nodeValue;

            if ($em && $title && $descr && $this->isW3CHTMLError($title)) {
                $this->fail('W3C HTML validation fail: ' . $title . '(' . $em . ')' . " in block\n" . $descr);
            }
        }

        if ($this->processW3CHTMLWarnings) {
            foreach ($xpath->query("//li[@class='msg_warn']") as $node) {
                $em = $xpath->query('em', $node);
                $em = $em->length ? $em->item(0)->nodeValue : false;
    
                $title = $xpath->query('span[@class=\'msg\']', $node);
                if ($title->length == 0) {
                    $title = $xpath->query('p/span[@class=\'msg\']', $node);
                }
                $title = $title->length ? $title->item(0)->nodeValue : false;

                $code = $xpath->query('pre/code', $node);
                $code = $code->length ? $code->item(0)->nodeValue : false;

                $descr = $xpath->query('ul', $node);
                $descr = $descr->length ? $descr->item(0)->nodeValue : false;

                if ($title && ($descr || $code)) {

                    $str = '';
                    if ($em) {
                        $str .= '(' . $em . ') ';
                    }

                    $str .= $title;

                    if ($code) {
                        $str .= " in block\n" . $code;

                    } elseif ($descr) {
                        $str .= "\n" . $descr;
                    }

                    $this->fail('[WARNING] ' . $str);
                }
            }
        }
    }

    private function validateCSS($data, $url)
    {
        $post = array(
            'text' => $data,
            'lang' => 'en',
            'profile' => 'css3',
            'usermedium' => 'all',
            'type' => 'none',
            'warning' => '1'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://jigsaw.w3.org/css-validator/validator');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $this->assertTrue(is_string($body), 'page CSS validation return non string data');

        $dom = new DOMDocument();
        $dom->loadHTML(trim($body));
        $xpath = new DOMXPath($dom);

        $errors = array();

        foreach ($xpath->query('//tr[@class=\'error\']') as $node) {
            $line = $xpath->query('td[position()=1]', $node)->item(0)->nodeValue;
            $context = trim($xpath->query('td[position()=2]', $node)->item(0)->nodeValue);
            $descr = trim($xpath->query('td[position()=3]', $node)->item(0)->nodeValue);

            if ($line && $context && $descr) {
                $descr = preg_replace('/[ ]{2,}/Ss', ' ', $descr);
                $descr = str_replace("\n ", "\n", $descr);
                $descr = str_replace("\n\n", "\n", $descr);
                if (
                    !preg_match('/Property (\S+) doesn\'t exist/Ssi', $descr, $m)
                    || !in_array($m[1], $this->unknownCSSProperty)
                ) {
                    $errors[] = '[' . str_repeat('0', 5 - strlen($line)) . $line . '] ' . $context . ' : ' . $descr;
                }
            }
        }

        if ($errors) {
            $this->fail(
                'W3C CSS validation fail (' . $url. '):' . PHP_EOL
                . "\t" . implode(PHP_EOL . "\t", $errors) . PHP_EOL
            );
        }
    }

    /**
     * Check W3C HTML validation error title - is error or not 
     * 
     * @param string $name Error title
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isW3CHTMLError($name)
    {
        return !in_array(
            $name,
            array(
                'Attribute "autocomplete" is not a valid attribute'
            )
        );
    }

    /**
     * Get pure HTML code from current page
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHTML()
    {
             $js = <<<JS
function getXmlHttp() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e) {
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function getHTMLCode() {
    var xmlhttp = getXmlHttp();
    if (!xmlhttp) {
        return false;
    }
    xmlhttp.open('GET', window.location + '', false);
    xmlhttp.send(null);

    return xmlhttp.responseText;
}
getHTMLCode();
JS;

        return $this->getEval($js);
   }
}


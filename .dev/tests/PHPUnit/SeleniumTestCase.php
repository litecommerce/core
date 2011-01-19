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

/**
 * Selenium test case 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Tests_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    /**
     * Selenioum common TTL 
     */
    const SELENIUM_TTL = 60000;


    /**
     * Prefix for all classes with test cases
     */
    const CLASS_PREFIX = 'XLite_Web_';


    /**
     * Tests range
     *
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public static $testsRange = array();

    /**
     * Processed CSS files 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cssProcessedFiles = array();

    /**
     * Calidated pages URL list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $validatedPages = array();

    /**
     * Validate every page or not
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $validatePage = false;

    /**
     * Validate CSS flag
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $validateCSS = true;

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
     * Temporary skipped flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $temporarySkipped = false;

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

    /**
     * Unknown nut allowed CSS properties list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
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
        $this->browserName = isset($browser['name']) ? $browser['name'] : 'unknown';
        $this->coverageScriptUrl = defined('SELENIUM_COVERAGE_URL')
            ? SELENIUM_COVERAGE_URL . '/phpunit_coverage.php'
            : SELENIUM_SOURCE_URL . '/phpunit_coverage.php';

        if (defined('W3C_VALIDATION')) {
            $this->validatePage = true;
        }

        parent::__construct($name, $data, $browser);
    }

    /**
     * Run test 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function runTest()
    {
        try {

            $shortName = lcfirst(substr($this->name, 4));
            if (self::$testsRange && !in_array($shortName, self::$testsRange)) {
                $this->markTestSkipped();

            } elseif ($this->temporarySkipped) {

                $this->markTestSkipped('Temporary skipped - fix test ASAP!');

            } else {
                parent::runTest();
            }

        } catch (\Exception $exception) {

            try {
                $location = preg_replace('/[\/\\&\?:]/Ss', '-', $this->getLocation());
                file_put_contents(
                    LC_ROOT_DIR . 'var/log/selenium.' . $location . '.' . date('Ymd-His') . '.html',
                    '<!--' . PHP_EOL
                    . 'Exception: ' . $exception->getMessage() . ';' . PHP_EOL
                    . 'Back trace: ' . var_export(\XLite\Core\Operator::getInstance()->getBackTrace(), true) . PHP_EOL
                    . '-->' . PHP_EOL . $this->getHtmlSource()
                );

            } catch (\RuntimeException $e) {
            }

            try {
                $this->stop();

            } catch (\RuntimeException $e) {
            }

            throw $exception;
        }
    }

    /**
     * Get code coverage 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.2.0
     */
    protected function getCodeCoverage()
    {
        $result = array();

        if (!empty($this->coverageScriptUrl)) {
            $url = sprintf(
              '%s?PHPUNIT_SELENIUM_TEST_ID=%s',
              $this->coverageScriptUrl,
              $this->testId
            );

            $buffer = @file_get_contents($url);

            if ($buffer !== false) {
                $buffer = unserialize($buffer);
                if ($buffer !== false) {
                    $result = $this->matchLocalAndRemotePaths($buffer);
                }
            }
        }

        return $result;
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

        return $class
            . ' : '
            . str_repeat(' ', 30 - strlen($this->browserName))
            . $this->browserName
            . ' [' . $method . ']. '
            . $message;
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

        if (!defined('DEPLOYMENT_TEST')) {

            // Clear and restart (if need) entity manager
            \XLite\Core\Database::getEM()->clear();

            \XLite\Core\Session::getInstance()->restart();
        }
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
        echo (PHP_EOL . sprintf('%\'.-86s', trim($message)));
    }

    /**
     * Improved version of 'typeKeys' Selenium command
     * 
     * @param string $locator Locator
     * @param string $value   Type values
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function typeKeys($locator, $value)
    {
        $this->__call('type', array($locator, substr($value, 0, -1)));

        return $this->__call('typeKeys', array($locator, substr($value, -1)));
    }

    /**
     * Toggle checkbox by jQuery 
     * 
     * @param string  $jqueryExpression jQuery locator
     * @param boolean $checked          Status
     *  
     * @return boolean Current status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function toggleByJquery($jqueryExpression, $checked = null)
    {
        if (0 == intval($this->getJSExpression('jQuery("' . $jqueryExpression . '").length'))) {
            $this->fail($jqueryExpression . ' jQuery expression not found');
        }

        $checked = isset($checked) ? $checked : !$this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked');
        $this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked = ' . ($checked ? 'true' : 'false'));
        $this->getJSExpression('jQuery("' . $jqueryExpression . '").change()');

        return (bool)$this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked');
    }

    /**
     * Get JS expression 
     * 
     * @param string $expression javascript expression
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSExpression($expression)
    {
        return $this->getEval('selenium.browserbot.getCurrentWindow().' . $expression);
    }

    /**
     * Assert jQuery locator present 
     * 
     * @param string $pattern jQuery locator
     * @param string $message Fail message
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assertJqueryPresent($pattern, $message = null)
    {
        $this->assertTrue(
            0 < intval($this->getJSExpression('jQuery("' . $pattern . '").length')),
            $message ?: 'jQuery pattern \'' . $pattern . '\' is NOT present'
        );
    }

    /**
     * Assert jQuery locator NOT present
     * 
     * @param string $pattern jQuery locator
     * @param string $message Fail message
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assertJqueryNotPresent($pattern, $message = null)
    {
        $this->assertEquals(
            0,
            intval($this->getJSExpression('jQuery("' . $pattern . '").length')),
            $message ?: 'jQuery pattern \'' . $pattern . '\' is PRESENT'
        );
    }

    /**
     * Wait inline progress mark
     * 
     * @param string $jqueryExpression jQuery input locator
     * @param string $message          Fail message
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function waitInlineProgress($jqueryExpression, $message = null)
    {
        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().jQuery("' . $jqueryExpression . '").parents().eq(0).find(".single-progress-mark").length > 0',
            10000,
            'check inline progress mark for ' . $jqueryExpression . ' (' . $message . ')'
        );
        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().jQuery("' . $jqueryExpression . '").parents().eq(0).find(".single-progress-mark").length == 0',
            20000,
            'check GONE inline progress mark for ' . $jqueryExpression . ' (' . $message . ')'
        );
    }

    /**
     * Assert input error note present 
     * 
     * @param string $jqueryExpression jQuery input locator
     * @param string $message          Fail message
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assertInputErrorPresent($jqueryExpression, $message = null)
    {
        $cnt = intval($this->getJSExpression('jQuery("' . $jqueryExpression . '").parents().eq(0).find(".error").length'));
        $this->assertTrue(
            1 == $cnt,
            ($message ?: 'check error for ' . $jqueryExpression)
        );
    }

    /**
     * Assert input error note NOT present
     *
     * @param string $jqueryExpression jQuery input locator
     * @param string $message          Fail message
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assertInputErrorNotPresent($jqueryExpression, $message = null)
    {
        $cnt = intval($this->getJSExpression('jQuery("' . $jqueryExpression . '").parents().eq(0).find(".error").length'));
        $this->assertTrue(
            0 == $cnt,
            ($message ?: 'check no-error for ' . $jqueryExpression)
        );
    }

    /**
     * Skip coverage for current Selenium session
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function skipCoverage()
    {
        $this->openAndWait('');
        $this->createCookie('no_xdebug_coverage=1');
    }

    /**
     * Drag-n-drop with delay
     * 
     * @param string $locatorFrom   Begin locator
     * @param string $locatorMiddle Middle position locator 
     * @param string $locatorTo     Finish locator
     * @param int    $delay         Delay (seconds)
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function dragAndDropDelay($locatorFrom, $locatorMiddle, $locatorTo, $delay = 1)
    {
        $this->mouseDownAt($locatorFrom, '0,+10');
        $this->mouseMoveAt($locatorMiddle, '0,+10');
        $this->mouseOver($locatorMiddle);
        sleep($delay);

        $this->mouseMoveAt($locatorTo, '0,+10');
        $this->mouseOver($locatorTo);
        $this->mouseUpAt($locatorTo, '0,+10');
    }

    /**
     * Wait for local window-based condition 
     * 
     * @param string  $condition Condition
     * @param integer $ttl       TTL (msec)
     * @param string  $message   Fail message
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function waitForLocalCondition($condition, $ttl = 10000, $message = null)
    {
        if (preg_match('/^jQuery/Ss', $condition)) {
            $this->waitForCondition(
                '"undefined" != typeof(selenium.browserbot.getCurrentWindow().jQuery)'
                . ' && selenium.browserbot.getCurrentWindow().' . $condition,
                isset($ttl) ? $ttl : 10000,
                $message
            );
            
        } else {
            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().' . $condition,
                isset($ttl) ? $ttl : 10000,
                $message
            );
        }
    }

    /**
     * Common command interrupter
     * 
     * @param string $command   Command name
     * @param array  $arguments Arguments array
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($command, array $arguments)
    {
        $result = null;

        try {

            $result = parent::__call($command, $arguments);

            if (
                preg_match('/^open(?:AndWait)?$/Ssi', $command)
                && $this->validatePage
            ) {
                $url = $arguments[0];
                if (!isset(static::$validatedPages[$url])) {
                    static::$validatedPages[$url] = true;
                    $this->validate();
                }
            }

        } catch (RuntimeException $e) {

            $message = $e->getMessage();

            if (
                'Could not connect to the Selenium RC server.' == $message
                || preg_match('/^The response from the Selenium RC server is invalid: Timed out after \d+ms$/Ss', $message)
            ) {
                
                if ($command == 'waitForCondition') {
                    $this->fail(
                        'Timeout failed (' . $arguments[1] . 'ms): '
                        . (isset($arguments[2]) ? $arguments[2] : $arguments[0])
                    );

                } elseif (preg_match('/AndWait$/Ss', $command)) {
                    $this->fail(
                        'Timeout failed for ' . $command . ' command with pattern ' . $arguments[0]
                    );

                } elseif (!defined('DEPLOYMENT_TEST')) {
                    $this->markTestSkipped($e->getMessage());
                
                } else {
                    $this->fail($e->getMessage());
                }

            } else {

                throw $e;

            }
        }

        return $result;
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

        if ($this->validateCSS) {
            $this->validatePageCSS();
        }
    }

    /**
     * Validate page CSS 
     * 
     * @param string $html Page HTML code
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function validatePageCSS($html)
    {
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

            if (!in_array($url, static::$cssProcessedFiles)) {
                $css = @file_get_contents($url);
                static::$cssProcessedFiles[] = $url;
                if ($css) {
                    $this->validateCSS(trim($css), $url);
                }
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

        //curl_setopt($ch, CURLOPT_URL, 'http://w3c.crtdev.local/check');
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
                $this->fail(
                    'W3C HTML validation fail (' . $this->getLocation() . '): '
                    . $title . '(' . $em . ')'
                    . ' in block' . PHP_EOL . $descr
                );
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

                    $this->fail(
                        'W3C HTML validation warning (' . $this->getLocation() . '): '
                        . $str
                    );
                }
            }
        }
    }

    /**
     * Validate CSS 
     * 
     * @param string $data CSS code
     * @param string $url  CSS script URL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function validateCSS($data, $url)
    {
        $post = array(
            'text'       => $data,
            'lang'       => 'en',
            'profile'    => 'css3',
            'usermedium' => 'all',
            'type'       => 'none',
            'warning'    => '1',
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
                'W3C CSS validation fail (' . $url . '):' . PHP_EOL
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
                'Attribute "autocomplete" is not a valid attribute',
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

    protected function getProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);
    }

    protected function getProductBySku($sku)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => $sku));
    }

}


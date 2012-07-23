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
 * @package    Tests
 * @subpackage Portal
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.1.0
 */

namespace Portal;

require_once PATH_TESTS . '/Portal/Autoload.php';

class Selenium
{
    
    /**
     * Browser
     * 
     * @access protected
     * @var    Selenium\Browser
     * @see    ___func_see___
     * @since  1.1.0
     */
    protected $browser = NULL;
    
    /**
     * Selenium client
     * 
     * @access protected
     * @var    Selenium\Client
     * @see    ___func_see___
     * @since  1.1.0
     */
    protected $client = NULL;

    /**
     * Unknown nut allowed CSS properties list
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.1.0
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
     * URL
     * 
     * @access protected
     * @var    string
     * @see    ___func_see___
     * @since  1.1.0
     */    
    protected $url = 'http://localhost/';
    
    /**
     * Constructor
     *
     * @access public
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function __construct()
    {
    }

    /**
     * Start new browser session via selenium if necessary
     * 
     * @access public
     * @see    ___func_see___
     * @since  1.1.0
     */
    public static function start()
    {
        static $isStarted;
        if ($isStarted !== true) {
            static::getBrowser()->start();
            $isStarted = true;
        }
    }
    
    /**
     * Shutdown browser session
     * 
     * @access public
     * @see    ___func_see___
     * @since  1.1.0
     */
    public static function stop()
    {
        static::getBrowser()->stop();
    }
    
    public static function getClient()
    {
        static $selenium_client;// = NULL;
        if (!is_object($selenium_client)) {
            $browser = static::getBrowserInfo();
            $host    = $browser['host'];
            $port    = $browser['port'];
            $timeout = $browser['timeout'] * 1000;
            $selenium_client = new \Selenium\Client($host, $port, $timeout);
        }
        
        return $selenium_client;
    }
    
    /**
     * Get Selenium client 
     * 
     * @access protected
     * @return Browser
     * @see    ___func_see___
     * @since  1.1.0
     */
    public static function getBrowser()
    {
        static $selenium_browser;// = NULL;
        if (!is_object($selenium_browser)) {
            $browser = static::getBrowserInfo();
            $url = 'http://localhost/';//$this->url;
            $selenium_browser = static::getClient()->getBrowser($url, $browser['browser']);
        }
        
        return $selenium_browser;
    }
    
    public static function getBrowserInfo()
    {
        global $availableBrowsersList;
        $browser = $availableBrowsersList[0];
        
        return $browser;
    }

    /**
     * Toggle checkbox by jQuery
     *
     * @param string  $jqueryExpression jQuery locator
     * @param boolean $checked          Status
     *
     * @return boolean Current status
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function toggleByJquery($jqueryExpression, $checked = null)
    {
        if (0 == intval($this->getJSExpression('jQuery("' . $jqueryExpression . '").length'))) {
            $this->fail($jqueryExpression . ' jQuery expression not found');
        }

        $checked = isset($checked) ? $checked : !$this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked');
        $this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked = ' . ($checked ? 'true' : 'false'));
        sleep(1);
        $this->getJSExpression('jQuery("' . $jqueryExpression . '").change()');

        return (bool)$this->getJSExpression('jQuery("' . $jqueryExpression . '").get(0).checked');
    }

    /**
     * Get JS expression
     *
     * @param string $expression javascript expression
     *
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getJSExpression($expression)
    {
        $expression = 'selenium.browserbot.getCurrentWindow().' . $expression;

        if (preg_match('/jQuery/Ss', $expression)) {
            $expression = '("undefined" != typeof(selenium.browserbot.getCurrentWindow) && "undefined" != typeof(selenium.browserbot.getCurrentWindow().jQuery)) ? ' . $expression . ' : null';
        }

        return $this->getEval($expression);
    }

    /**
     * Assert jQuery locator present
     *
     * @param string $pattern jQuery locator
     * @param string $message Fail message
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function assertJqueryPresent($pattern, $message = null)
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
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function assertJqueryNotPresent($pattern, $message = null)
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
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function waitInlineProgress($jqueryExpression, $message = null)
    {
        $this->waitForLocalCondition(
            'jQuery("' . $jqueryExpression . '").parents().eq(0).find(".single-progress-mark").length > 0',
            10000,
            'check inline progress mark for ' . $jqueryExpression . ' (' . $message . ')'
        );
        $this->waitForLocalCondition(
            'jQuery("' . $jqueryExpression . '").parents().eq(0).find(".single-progress-mark").length == 0',
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
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function assertInputErrorPresent($jqueryExpression, $message = null)
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
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function assertInputErrorNotPresent($jqueryExpression, $message = null)
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
     * @since  1.1.0
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
     * @since  1.1.0
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
     * @since  1.1.0
     */
    protected function waitForLocalCondition($condition, $ttl = 10000, $message = null)
    {
        if (!is_array($condition)) {
            $condition = array($condition);
        }

        foreach ($condition as $k => $v) {
            $condition[$k] = 'selenium.browserbot.getCurrentWindow().' . $v;
        }

        if (0 < count(preg_grep('/jQuery/Ss', $condition))) {
            array_unshift($condition, '"undefined" != typeof(selenium.browserbot.getCurrentWindow().jQuery)');
        }

        array_unshift($condition, '"undefined" != typeof(selenium.browserbot.getCurrentWindow)');

        $this->waitForCondition(
            implode(' && ', $condition),
            isset($ttl) ? $ttl : 10000,
            $message
        );
    }


    /**
     * Validate W3C HTML
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.1.0
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
     * @since  1.1.0
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
     * @since  1.1.0
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
     * @since  1.1.0
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
     * @since  1.1.0
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
     * @since  1.1.0
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

   /**
     * Set new value to the 'sleep' property of the browser driver and return previous value
     *
     * @param integer $sleep Seconds to sleep
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function setSleep($sleep)
    {
        $oldSleep = null;

        if (isset($this->drivers[0])) {
            $oldSleep = $this->drivers[0]->getSleep();
            $this->drivers[0]->setSleep($sleep);
        }

        return $oldSleep;
    }

    protected function waitForPopUpDialog($selector = '.ui-dialog.popup'){
        $this->waitForLocalCondition(
            'jQuery("'.$selector.':visible").length > 0',
            10000,
            'Popup dialog is not present'
        );
    }
}
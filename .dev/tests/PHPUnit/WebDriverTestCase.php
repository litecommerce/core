<?php

require_once 'WebDriver/WebDriver.php';
require_once 'WebDriver/WebDriver/Driver.php';
require_once 'WebDriver/WebDriver/MockDriver.php';
require_once 'WebDriver/WebDriver/WebElement.php';
require_once 'WebDriver/WebDriver/MockElement.php';

/**
 * @throws Exception
 * @method void set_implicit_wait()
 * @method unknown load()
 * @method bool is_element_present()
 * @method bool is_element_not_present()
 * @method WebDriver_WebElement get_element()
 * @method string get_alert_text()
 * @method unknown execute_js_sync()
 * @method string get_text()
 * @method WebDriver_WebElement[] get_all_elements()
 * @method string get_url()
 * @method void set_cookie()
 *
 */
class Xlite_WebDriverTestCase extends PHPUnit_Framework_TestCase
{
    protected $browserUrl;
    /**
     * @var string
     */
    protected $browserName;
    /**
     * @var float
     */
    protected $startTime;
    /**
     * Prefix for all classes with test cases
     */
    const CLASS_PREFIX = 'XLite_Web_';
    /**
     * @var WebDriver_Driver
     */
    protected $driver;

    public function setUp()
    {
        $this->driver = WebDriver_Driver::InitAtHost(SELENIUM_SERVER, "4444", "firefox");
        $this->browserName = 'firefox';
        $this->setBrowserUrl(SELENIUM_SOURCE_URL);
        $this->set_implicit_wait(50000);
        $this->startTime = microtime(true);
        echo PHP_EOL . $this->getMessage('...', get_called_class(), $this->getName());
    }

    /**
     * @throws Exception
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->driver, $name)) {
            return call_user_func_array(array($this->driver, $name), $arguments);
        } else {
            throw new Exception("Tried to call nonexistent method $name with arguments:\n" . print_r($arguments, true));
        }
    }

    public function tearDown()
    {
        if ($this->driver) {
            if ($this->hasFailed()) {
                $this->driver->set_sauce_context("passed", false);
            } else {
                $this->driver->set_sauce_context("passed", true);
            }
            $this->driver->quit();
        }
        parent::tearDown();
        $time = microtime(true) - $this->startTime;
        $message = '...' . round($time, 2) . 's...';
        echo (sprintf('%\'.-15s', trim($message)));
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
               . ' [' . $method . '].'
               . $message;
    }

    function setBrowserUrl($url)
    {
        $this->browserUrl = rtrim($url, "/");
    }

    /**
     * Skip coverage for current Selenium session
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function skipCoverage()
    {
        $this->open('');
        $this->set_cookie('no_xdebug_coverage', '1');
    }


    function open($url)
    {
        if (substr($url, 0, 7) == 'http://') {
            $this->load($url);
        }
        else
            $this->load($this->browserUrl . '/' . ltrim($url, "/"));
    }

    public function assert_element_present($element_locator, $message = null)
    {
        PHPUnit_Framework_Assert::assertTrue($this->is_element_present($element_locator), $message ?
                                                                                                : "Element <" . $element_locator . "> doesn't present");
    }

    public function assert_element_text($element_locator, $text, $message)
    {
        $this->assertEquals($text, $this->get_element($element_locator)->get_text(), $message);
    }

    public function assert_element_not_present($element_locator, $message = null)
    {
        $this->assertTrue($this->is_element_not_present($element_locator), $message ?
                                                                                 : "Element <" . $element_locator . "> present");
    }

    public function assert_string_present($expected_string, $message)
    {
        $page_text = $this->get_text();
        PHPUnit_Framework_Assert::assertContains($expected_string, $page_text, $message);
    }

    public function assert_string_not_present($expected_missing_string, $message)
    {
        $page_text = $this->get_text();
        PHPUnit_Framework_Assert::assertNotContains($expected_missing_string, $page_text, $message);
    }

    public function assert_alert_text($expected_text, $message)
    {
        $actual_text = $this->get_alert_text();
        PHPUnit_Framework_Assert::assertEquals($expected_text, $actual_text, $message);
    }

    public function click($locator)
    {
        $this->get_element($locator)->click();
    }

    public function type($locator, $val)
    {
        $element = $this->get_element($locator);
        $element->clear();
        $element->send_keys($val);
    }

    public function select($locator, $value, $selector = '@value')
    {
        $select = $this->get_element($locator);
        $select->click();
        $option = $select->get_next_element('xpath=//option[' . $selector . '="' . $value . '"]');
        $option->click();

    }

    /**
     * Wait inline progress mark
     *
     * @param $locator
     * @param string $message          Fail message
     *
     *
     * @internal param string $jqueryExpression jQuery input locator
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function wait_inline_progress($locator, $message = null)
    {
        //$wait = $this->set_implicit_wait(10000);
        $progress = $this->get_element($locator)->get_parent();
        $progress->assert_contains_element('css=.single-progress-mark', 'check inline progress mark for ' . $locator . ' (' . $message . ')');
        //sleep(3);
        $progress->assert_does_not_contain_element('css=.single-progress-mark', 'check GONE inline progress mark for ' . $locator . ' (' . $message . ')');
        //$this->set_implicit_wait($wait);
    }

    /**
     * Assert input error note present
     *
     * @param $locator
     * @param string $message          Fail message
     *
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function assert_input_error_present($locator, $message = null)
    {
        $this->get_element($locator)->get_parent()->assert_contains_element('css=.error', ($message ?
                    : 'check error for ' . $locator));
    }

    /**
     * Assert input error note NOT present
     *
     * @param $locator
     * @param string $message          Fail message
     *
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function assert_input_error_not_present($locator, $message = null)
    {
        $this->get_element($locator)->get_parent()->assert_does_not_contain_element('css=.error', ($message ?
                    : 'check no-error for ' . $locator));
    }

    public function assert_element_visible($locator, $message = null)
    {
        $this->assert_element_present($locator, 'Element <' . $locator . '> not present (' . $message . ')');
        $this->get_element($locator)->assert_visible($message);
    }

    /**
     * Asserts that element is hidden by CSS, has zero width or height, or not present on the page at all
     *
     * @param $locator
     * @param $message
     * @return void
     */
    public function assert_element_not_visible($locator, $message = null)
    {
        $wait = $this->set_implicit_wait(100);
        if ($this->is_element_not_present($locator))
            return;
        $this->set_implicit_wait($wait);
        $this->get_element($locator)->assert_hidden($message);
    }

    public function assert_url_contains($url_substring, $message = null)
    {
        $this->assertContains($url_substring, $this->get_url(), $message ?: "Failed asserting that URL contains <$url_substring>.");
    }

    /**
     * @param $url_substring
     * @param null $message
     * @return array|null
     */
    public function assert_url_regexp($url_substring, $message = null)
    {
        $this->assertTrue(preg_match($url_substring, $this->get_url(), $m) == 1,$message ?: "Failed asserting that URL matches <$url_substring>.");
        return $m;
    }


    public function toggle($locator, $enable)
    {
        $this->assert_element_present($locator);
        $element = $this->get_element($locator);
        if ($element->is_selected() != $enable)
            $element->click();
    }

    /**
     * formatPrice
     *
     * @param mixed $value ____param_comment____
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function formatPrice($value)
    {
        return '$' . number_format($value, 2, '.', '');
    }

    /**
     * Get payment method id by name
     *
     * @param string $name Payment method name
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPaymentMethodIdByName($name)
    {
        $pmethod = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(array('service_name' => $name));

        if (!$pmethod) {
            $this->fail($name . ' payment method is not found');
        }

        $pid = $pmethod->getMethodId();

        return $pid;
    }
    public static function suite($className){
        return new XLite_Tests_TestSuite($className);
    }
}

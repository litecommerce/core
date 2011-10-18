<?php

require_once '../../lib/WebDriver/WebDriver.php';
require_once '../../lib/WebDriver/WebDriver/Driver.php';
require_once '../../lib/WebDriver/WebDriver/MockDriver.php';
require_once '../../lib/WebDriver/WebDriver/WebElement.php';
require_once '../../lib/WebDriver/WebDriver/MockElement.php';

/**
 * @throws Exception
 * @method void set_implicit_wait()
 * @method unknown load()
 * @method bool is_element_present()
 * @method WebDriver_WebElement get_element()
 * @method string get_alert_text()
 * @method unknown execute_js_sync()
 * @method string get_text()
 * @method WebDriver_WebElement[] get_all_elements()
 * @method string get_url()
 *
 */
class Xlite_WebDriverTestCase extends PHPUnit_Framework_TestCase
{
    protected $browserUrl;

    /**
     * @var WebDriver_Driver
     */
    protected $driver;

    public function setUp()
    {
        $this->driver = WebDriver_Driver::InitAtHost(SELENIUM_SERVER, "4444", "firefox");
        $this->setBrowserUrl(SELENIUM_SOURCE_URL_ADMIN);
        $this->set_implicit_wait(3000);
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
    }

    function setBrowserUrl($url)
    {
        $this->browserUrl = rtrim($url, "/");
    }

    function open($url)
    {
        if (substr($url, 0, 7) == 'http://') {
            $this->load($url);
        }
        else
            $this->load($this->browserUrl . '/' . ltrim($url, "/"));
    }

    public function assert_element_present($element_locator, $message)
    {
        PHPUnit_Framework_Assert::assertTrue($this->is_element_present($element_locator), $message);
    }

    public function assert_element_text($element_locator, $text, $message)
    {
        $this->assertEquals($text, $this->get_element($element_locator)->get_text(), $message);
    }

    public function assert_element_not_present($element_locator, $message)
    {
        $wait = $this->set_implicit_wait(0);
        $present = true;
        for ($i=0;$i<$wait;$i++)
        {
            if (!$this->is_element_present($element_locator)){
                $present = false;
                break;
            }
        }
        $this->set_implicit_wait($wait);
        $this->assertFalse($present);
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
    public function select($locator, $value)
    {
        $select = $this->get_element($locator);
        $select->click();
        $option = $select->get_next_element('xpath=//option[@value="'.$value.'"]');
        $option->click();

    }

}

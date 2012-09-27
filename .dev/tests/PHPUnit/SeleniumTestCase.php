<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Base class for all LiteCommerce web tests
 *
 * @category  LiteCommerce_Tests
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once dirname(__FILE__) . '/SeleniumTestCase/Driver.php';

/**
 * Selenium test case
 *
 * @see     ____class_see____
 * @since   1.0.0
 */
abstract class XLite_Tests_SeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{

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
     * @since  1.0.0
     */
    public static $testsRange = array();

    /**
     * Processed CSS files
     *
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $cssProcessedFiles = array();

    /**
     * Calidated pages URL list
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $validatedPages = array();

    /**
     * Validate every page or not
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * Unknown nut allowed CSS properties list
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
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
     * @var float
     */
    protected $startTime;

    /**
     * Constructor
     *
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '', array $browser = array())
    {
        $this->browserName = isset($browser['name']) ? $browser['name'] : 'unknown';

        /*
        $this->coverageScriptUrl = defined('SELENIUM_COVERAGE_URL')
            ? SELENIUM_COVERAGE_URL . '/phpunit_coverage.php'
            : SELENIUM_SOURCE_URL . '/phpunit_coverage.php';
        */

        if (defined('SELENIUM_SCREENSHOTS_PATH') && defined('SELENIUM_SCREENSHOTS_URL')) {
            $this->captureScreenshotOnFailure = true;
            $this->screenshotPath = SELENIUM_SCREENSHOTS_PATH;
            $this->screenshotUrl = SELENIUM_SCREENSHOTS_URL;
        }

        if (defined('W3C_VALIDATION')) {
            $this->validatePage = true;
        }

        parent::__construct($name, $data, $dataName, $browser);

        $this->testConfig = $this->getTestConfigOptions();
    }

    /**
     * @param  string $className
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite($className)
    {
		$suite = new PHPUnit_Framework_TestSuite;
        $suite->setName($className);

        $class            = new ReflectionClass($className);
        $classGroups      = PHPUnit_Util_Test::getGroups($className);
        $staticProperties = $class->getStaticProperties();

        if (defined('XLITE_TEST_BROWSERS_LIST')) {
            $staticProperties['browsers'] = unserialize(XLITE_TEST_BROWSERS_LIST);
        }

        // Leave only one browser in deployment mode
        if (defined('DEPLOYMENT_TEST') && !empty($staticProperties['browsers'])) {
            $_browsers = array();
            foreach ($staticProperties['browsers'] as $key => $value) {
                $_browsers[$key] = $value;
                break;
            }
            $staticProperties['browsers'] = $_browsers;
        }

        // Create tests from Selenese/HTML files.
        if (isset($staticProperties['seleneseDirectory']) &&
            is_dir($staticProperties['seleneseDirectory'])) {
            $files = array_merge(
              self::getSeleneseFiles($staticProperties['seleneseDirectory'], '.htm'),
              self::getSeleneseFiles($staticProperties['seleneseDirectory'], '.html')
            );

            // Create tests from Selenese/HTML files for multiple browsers.
            if (!empty($staticProperties['browsers'])) {
                foreach ($staticProperties['browsers'] as $browser) {
                    $browserSuite = new PHPUnit_Framework_TestSuite;
                    $browserSuite->setName($className . ': ' . $browser['name']);

                    foreach ($files as $file) {
                        $browserSuite->addTest(
                          new $className($file, array(), '', $browser),
                          $classGroups
                        );
                    }

                    $suite->addTest($browserSuite);
                }
            }

            // Create tests from Selenese/HTML files for single browser.
            else {
                foreach ($files as $file) {
                    $suite->addTest(new $className($file), $classGroups);
                }
            }
        }

        // Create tests from test methods for multiple browsers.
        if (!empty($staticProperties['browsers'])) {
            foreach ($staticProperties['browsers'] as $browser) {
				if (defined('NO_RESTORE'))
                    $browserSuite = new PHPUnit_Framework_TestSuite;
                else
				    $browserSuite = new XLite_Tests_TestSuite;
                $browserSuite->setName($className . ': ' . $browser['name']);

                foreach ($class->getMethods() as $method) {
                    if (PHPUnit_Framework_TestSuite::isPublicTestMethod($method)) {
                        $name   = $method->getName();
                        $data   = PHPUnit_Util_Test::getProvidedData($className, $name);
                        $groups = PHPUnit_Util_Test::getGroups($className, $name);

                        // Test method with @dataProvider.
                        if (is_array($data) || $data instanceof Iterator) {
                            $dataSuite = new PHPUnit_Framework_TestSuite_DataProvider(
                              $className . '::' . $name
                            );

                            foreach ($data as $_dataName => $_data) {
                                $dataSuite->addTest(
                                  new $className($name, $_data, $_dataName, $browser),
                                  $groups
                                );
                            }

                            $browserSuite->addTest($dataSuite);
                        }

                        // Test method with invalid @dataProvider.
                        else if ($data === FALSE) {
                            $browserSuite->addTest(
                              new PHPUnit_Framework_Warning(
                                sprintf(
                                  'The data provider specified for %s::%s is invalid.',
                                  $className,
                                  $name
                                )
                              )
                            );
                        }

                        // Test method without @dataProvider.
                        else {
                            $browserSuite->addTest(
                              new $className($name, array(), '', $browser), $groups
                            );
                        }
                    }
                }

                $suite->addTest($browserSuite);
            }
        }

        // Create tests from test methods for single browser.
        else {
            foreach ($class->getMethods() as $method) {
                if (PHPUnit_Framework_TestSuite::isPublicTestMethod($method)) {
                    $name   = $method->getName();
                    $data   = PHPUnit_Util_Test::getProvidedData($className, $name);
                    $groups = PHPUnit_Util_Test::getGroups($className, $name);

                    // Test method with @dataProvider.
                    if (is_array($data) || $data instanceof Iterator) {
                        $dataSuite = new PHPUnit_Framework_TestSuite_DataProvider(
                          $className . '::' . $name
                        );

                        foreach ($data as $_dataName => $_data) {
                            $dataSuite->addTest(
                              new $className($name, $_data, $_dataName),
                              $groups
                            );
                        }

                        $suite->addTest($dataSuite);
                    }

                    // Test method with invalid @dataProvider.
                    else if ($data === FALSE) {
                        $suite->addTest(
                          new PHPUnit_Framework_Warning(
                            sprintf(
                              'The data provider specified for %s::%s is invalid.',
                              $className,
                              $name
                            )
                          )
                        );
                    }

                    // Test method without @dataProvider.
                    else {
                        $suite->addTest(
                          new $className($name), $groups
                        );
                    }
                }
            }
        }

        return $suite;
    }

    /**
     * Get options from ini-file
     *
     * @return array
     * @since  1.0.0
     */
    protected function getTestConfigOptions()
    {
        $configFile = XLITE_DEV_CONFIG_DIR . LC_DS . 'xlite-test.config.php';

        if (file_exists($configFile) && false !== ($config = parse_ini_file($configFile, true))) {
            return $config;

        } else {
            die('Config file not found: ' . $configFile);
        }
    }

    /**
     * @param  array $browser
     * @return XLite_Extensions_SeleniumTestCase_Driver
     * @since  1.0.0
     */
    protected function getDriver(array $browser)
    {
        if (isset($browser['name'])) {
            if (!is_string($browser['name'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['name'] = '';
        }

        if (isset($browser['browser'])) {
            if (!is_string($browser['browser'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['browser'] = '';
        }

        if (isset($browser['host'])) {
            if (!is_string($browser['host'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['host'] = 'localhost';
        }

        if (isset($browser['port'])) {
            if (!is_int($browser['port'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['port'] = 4444;
        }

        if (isset($browser['timeout'])) {
            if (!is_int($browser['timeout'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['timeout'] = 30000;
        }

        if (isset($browser['sleep'])) {
            if (!is_int($browser['sleep'])) {
                throw new InvalidArgumentException;
            }
        } else {
            $browser['sleep'] = 0;
        }

        $driver = new XLite_Extensions_SeleniumTestCase_Driver;
        $driver->setName($browser['name']);
        $driver->setBrowser($browser['browser']);
        $driver->setHost($browser['host']);
        $driver->setPort($browser['port']);
        $driver->setTimeout($browser['timeout']);
        $driver->setSleep($browser['sleep']);
        $driver->setTestCase($this);
        $driver->setTestId($this->testId);

        $this->drivers[] = $driver;

        return $driver;
    }

    /**
     * Run test
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function runTest()
    {
        try {

            $shortName = lcfirst(substr($this->getName(), 4));
            if (self::$testsRange && !in_array($shortName, self::$testsRange)) {
                $this->markTestSkipped();

            } elseif ($this->temporarySkipped) {

                $this->markTestSkipped('Temporary skipped - fix test ASAP!');

            } else {
                parent::runTest();
            }

        } catch (\Exception $exception) {

            if (isset($this->drivers[0]) && $this->drivers[0]->getSessionId()) {
                try {
                    $location = preg_replace('/[^\w]/Ss', '-', $this->getLocation());
                    $location = preg_replace('/-+/Ss', '-', $location);
                    $html = $this->getHtmlSource();
                    $trace = array();
                    if (!defined('DEPLOYMENT_TEST')) {
                        $trace = \XLite\Core\Operator::getInstance()->getBackTrace();
                    }
                    file_put_contents(
                        TESTS_LOG_DIR . 'selenium.' . $location . '.' . date('Ymd-His') . '.html',
                        '<!--' . PHP_EOL
                        . 'Exception: ' . $exception->getMessage() . ';' . PHP_EOL
                        . ($trace ? 'Back trace: ' . var_export($trace, true) . PHP_EOL : '')
                        . '-->' . PHP_EOL
                        . $html
                    );

                } catch (\RuntimeException $e) {
                }
            }

            $backtrace = array();
            foreach ($exception->getTrace() as $t) {
                $b = null;

                if (isset($t['file'])) {
                    $b = $t['file'] . ' : ' . $t['line'];

                } elseif (isset($t['function'])) {
                    $b = 'function ' . $t['function'] . '()';
                    if (isset($t['line'])) {
                        $b .= ' : ' . $t['line'];
                    }
                }

                if ($b) {
                    $backtrace[] = $b;
                }
            }

            file_put_contents(
                TESTS_LOG_DIR . 'selenium.' . date('Ymd-His') . '.backtrace',
                'Exception: ' . $exception->getMessage() . ';' . PHP_EOL
                . PHP_EOL
                . 'Backtrace: ' . PHP_EOL
                . implode(PHP_EOL, $backtrace) . PHP_EOL
            );

            throw $exception;
        }
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
        set_time_limit(0);

        // Delay before each test
        sleep(3);

        $this->setCustomerURL();

        if (!defined('DEPLOYMENT_TEST')) {

            // Clear and restart (if need) entity manager
            \XLite\Core\Database::getEM()->clear();

            \XLite\Core\Session::getInstance()->restart();
        }
        $this->startTime = microtime(true);
    }

    /**
     * Set main URL to admin area
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setAdminURL()
    {
        $this->baseURL = rtrim(SELENIUM_SOURCE_URL_ADMIN, '/') . '/';

        $this->setBrowserURL($this->baseURL);
    }

    /**
     * Set main URL to customer area
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setCustomerURL()
    {
        $this->baseURL = rtrim(SELENIUM_SOURCE_URL, '/') . '/';

        $this->setBrowserUrl($this->baseURL);
    }

    /**
     * Open the customer area
     *
     * @param type $shortURL
     * @return type
     */
    protected function openShortCustomerAndWait($shortURL)
    {
        return $this->openAndWait(rtrim(SELENIUM_SOURCE_URL, '/') . '/' . $shortURL);
    }

    /**
     * Open the admin area
     *
     * @param type $shortURL
     * @return type
     */
    protected function openShortAdminAndWait($shortURL)
    {
        return $this->openAndWait(rtrim(SELENIUM_SOURCE_URL_ADMIN, '/') . '/' . $shortURL);
    }

    /**
     * Login procedure to the admin area
     *
     * @param string $user     user name
     * @param string $password user password
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logInAdmin($user = 'rnd_tester@cdev.ru', $password = 'master')
    {
        $this->openShortAdminAndWait('admin.php');

        if ($this->isLoggedIn()) {
            return;
            //$this->logOut(true);
        }

        $this->type("//input[@name='login' and @type='text']", $user);
        $this->type("//input[@name='password' and @type='password']", $password);

        $this->click("//button[@class='main-button' and @type='submit']");

        $this->waitForPageToLoad(30000);
    }

    /**
     * Log in to the customer area
     *
     * @param string $username ____param_comment____
     * @param string $password ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function logInCustomer($username = 'master', $password = 'master')
    {
        $this->openShortCustomerAndWait('user');

        if ($this->isLoggedIn()) {
            $this->logOut(true);
        }

        $this->type('id=edit-name', $username);
        $this->type('id=edit-pass', $password);

        $this->submitAndWait('id=user-login');

        $this->assertTrue($this->isLoggedIn(), 'Check that user is logged in successfully');
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
        $time = microtime(true) - $this->startTime;
        $message = $this->getMessage('...' . round($time, 2) . 's...', get_called_class(), $this->getName());
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
     * @since  1.0.0
     */
    public function typeKeys($locator, $value)
    {
        if (!empty($value)){
            $type = substr($value, 0, -1);
            $typeKeys = substr($value, -1);
        }
        else{
            $type = '';
            $typeKeys = '';
        }
        $this->__call('type', array($locator, ''));
        $this->__call('type', array($locator, $value));

//        if (!empty($type))
//            $this->__call('type', array($locator, $type));
        $this->focus($locator);
        return $this->__call('typeKeys', array($locator, $typeKeys));
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
     * @since  1.0.0
     */
    public function toggleByJquery($jqueryExpression, $checked = null)
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
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSExpression($expression)
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
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function waitInlineProgress($jqueryExpression, $message = null)
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
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * Common command interrupter
     *
     * @param string $command   Command name
     * @param array  $arguments Arguments array
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __call($command, $arguments)
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

            if (isset($arguments) && !empty($arguments)) {
                $_arguments = $arguments;
                $param = (string)array_shift($_arguments);

            } else {
                $param = '';
            }

            echo "\n$command('$param') [ERROR] $message\n";

            /*
            if (
                preg_match('/Could not connect to the Selenium RC server/', $message)
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
             */

//            } elseif (preg_match('/this\.getCurrentWindow is not a function/', $message)) {
//                $this->markTestSkipped('Browser down: ' . $e->getMessage());

//            } else {

                throw $e;

//            }
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
     * @since  1.0.0
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
     * @since  1.0.0
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

    /**
     * Set new value to the 'sleep' property of the browser driver and return previous value
     *
     * @param integer $sleep Seconds to sleep
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
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

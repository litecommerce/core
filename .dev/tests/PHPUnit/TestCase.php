<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Base class for all LiteCommerce tests
 *
 * @category  LiteCommerce_Tests
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

abstract class XLite_Tests_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Prefix for all classes with test cases
     */
    const CLASS_PREFIX = 'XLite_Tests_';


    /**
     * List of tests (names w/o 'test' prefix) that should be runned
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    public static $testsRange = array();

    /**
     * Message length
     *
     * @var    integer
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $messageLength = 70;

    /**
     * IMAP mailbox resource
     *
     * @var    resource
     * @see    ____var_see____
     * @since  1.0.0
     */
    private $mailBox = null;

    /**
     * last message counter in IMAP mailbox
     *
     * @var    float
     * @see    ____var_see____
     * @since  1.0.0
     */
    private $lastMessage = 0;

    /**
     * Parameters registering when test starts
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $start = array('time' => 0, 'memory' => 0);

    /**
     * Parameters registering when test ends
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $end = array('time' => 0, 'memory' => 0);

    /**
     * Array of testing options
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $testConfig = null;

    /**
     * Flag: generate database backup on test failure
     *
     * @var    boolean
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $makeSqlBackupOnFailure = false;


    // {{{ Methods that are redefine the methods of a base class


    /**
     * Constructs a test case with the given name.
     *
     * @param  string $name
     * @param  array  $data
     * @param  string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // Initialize array of testing options
        $this->testConfig = $this->getTestConfigOptions();
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        set_time_limit(0);

        $request = $this->getRequest();

        // This data will be parsed by Reqest/Router transports
        $GLOBALS['_SERVER']['REQUEST_METHOD'] = $request['method'];

        if (!empty($request['data'])) {
            $GLOBALS['_' . $request['method']] = $request['data'];
        }

        if (!empty($request['cookies'])) {
            $GLOBALS['_COOKIE'] = $request['cookies'];
        }

        // Instantiate singltons
        if ($this->needAppInit($request)) {
            $this->app = \XLite::getInstance()->run($request['controller']);
        }

        // Set customer skin
        \XLite\Core\Layout::getInstance()->setCustomerSkin();

        // Clear and restart (if need) entity manager
        \XLite\Core\Database::getEM()->clear();

        $this->query('SET autocommit = 1');

        try {
            \XLite\Core\Database::getEM()->flush();

        } catch (\Doctrine\ORM\ORMException $e) {

            if ('The EntityManager is closed.' == $e->getMessage()) {

                \XLite\Core\Database::getInstance()->startEntityManager();
                xlite(true);

            } else {
                throw $e;
            }
        }

        \XLite\Core\Session::getInstance()->restart();

        // Memory usage
        $this->start['memory'] = memory_get_usage();
        $this->end['memory'] = 0;

        // Timing
        $this->start['time'] = microtime(true);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function tearDown()
    {
        // Timing
        $this->end['time'] = microtime(true);

        //        if ($this->needAppInit()) {
        //            \XLite\Core\Converter::getInstance()->__destruct();
        //            \XLite\Core\Database::getInstance()->__destruct();
        //            $this->app->__destruct();
        //        }

        // Memory usage
        $this->end['memory'] += memory_get_usage();

        echo PHP_EOL . $this->getExecTime();
        echo $this->getMemoryUsage();

        $this->writeMetricLog();
    }

    /**
     * Run test
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function runTest()
    {
        $result = null;

        $shortName = lcfirst(substr($this->getName(), 4));

        if (self::$testsRange && !in_array($shortName, self::$testsRange)) {
            $this->markTestSkipped();

        } else {

            try {

                $result = parent::runTest();

            } catch (PHPUnit_Framework_AssertionFailedError $exception) {

                if ($this->makeSqlBackupOnFailure) {

                    $path = LC_DIR_ROOT . 'var/log/unit-' . date('Ymd-His') . '-' . $this->getName() . '.sql';

                    try {
                        $this->doMakeBackup($path);

                    } catch (\Exception $e) {
                    }
                }

                throw $exception;
            }
        }

        return $result;
    }

    // }}}


    // {{{ Service methods

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
     * Return test execution time
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function getExecTime()
    {
        $time = number_format($this->end['time'] - $this->start['time'], 4);
        $message = trim($this->getMessage('', get_called_class(), $this->getName()));

        if (strlen($message) > self::$messageLength) {
            self::$messageLength = strlen($message) + 1;
        }

        return sprintf('%\'.-' . self::$messageLength . 's', trim($message))
            . ' ' . sprintf('%8s', $time) . ' sec .....';
    }

    /**
     * Return memory used by test
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function getMemoryUsage()
    {
        $memory = $this->end['memory'] - $this->start['memory'];
        if ($memory < 0) {
            $memory = 0;
        }

        return sprintf('%8s', number_format($memory / 1024, 2)) . ' Kb .....';
    }

    /**
     * Check if we need to construct/destruct application singleton
     *
     * @param array $request request info
     *
     * @return bool
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function needAppInit(array $request = array())
    {
        if (empty($request)) {
            $request = $this->getRequest();
        }

        return $request['init_app'];
    }

    /**
     * Return message (common method)
     *
     * @param string $message custom part of message
     * @param string $class   called class name
     * @param string $method  called method name
     *
     * @return string
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

        return $class . ' ' . '[' . $method . ']. ' . $message;
    }

    /**
     * Return data needed to start application.
     * Derived class can redefine this method.
     * It's possible to detect current test using the $this->name variable
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequest()
    {
        // Default request
        $request = array(
            'init_app' => true,
            'method' => 'GET',
            'controller' => true, // true - admin, false - customer
            'data' => array(
                'target' => \XLite::TARGET_DEFAULT,
                'action' => '',
            ),
            'cookies' => array(),
        );

        return $request;
    }

    /**
     * Write metric log
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function writeMetricLog()
    {
        $trace = debug_backtrace();
        $trace = $trace[0];

        $class = get_called_class();
        $method = $this->getName();

        $class = str_replace(self::CLASS_PREFIX, '', empty($class) ? $trace['class'] : $class);
        $method = lcfirst(str_replace('test', '', empty($method) ? $trace['function'] : $method));

        $time = intval(round($this->end['time'] - $this->start['time'], 6) * 1000000);
        $memory = max($this->end['memory'] - $this->start['memory'], 0);

        XLite_Tests_MetricWriter::write($class, $method, $time, $memory);
    }

    // }}}


    // {{{ Exceptions checking method

    /**
     * Check exception code
     * TODO: Review if this can be achived by native PHPUnit asserttions
     * http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.exceptions
     *
     * @param function $func    Function
     * @param string   $class   Exception class name
     * @param string   $message Exception message
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkException($func, $message, $class = '\Exception')
    {
        try {

            $func();
            $this->fail('Exception "' . $message . '" was not thrown');

        } catch (\Exception $exception) {

            $this->assertEquals($message, $exception->getMessage(), 'Check exception : "' . $message . '" message not found');
            $this->assertTrue($exception instanceof $class, 'Check exception : "' . $class . '" exception class not equal');

        }
    }

    // }}}


    // {{{ E-mail box operations

    /**
     * Check IMAP extension
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkIMAP()
    {
        if (!function_exists('imap_open')) {
            $this->markTestSkipped('IMAP extension is not loaded');
        }
    }

    /**
     * Set start of emails counter
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function startCheckingMail()
    {
        $this->initMailBox();

        $mc = imap_check($this->mailBox);

        $this->lastMessage = $mc->Nmsgs;

        $this->closeMailBox();
    }

    /**
     * Init mailbox
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function initMailBox()
    {
        $this->checkIMAP();

        if (
            is_null($this->mailBox)
            || false === $this->mailBox
        ) {

            $this->mailBox = imap_open(
                $this->testConfig['imap']['imap_box'],
                $this->testConfig['imap']['imap_user'],
                $this->testConfig['imap']['imap_pass']
            );

        }
    }

    /**
     * close IMAP mailbox
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function closeMailBox()
    {
        imap_close($this->mailBox);

        $this->mailBox = null;
    }

    /**
     * check if there are new emails and fetch them
     *
     * @return array array of emails
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function finishCheckingMail()
    {
        $this->initMailBox();

        $mc = imap_check($this->mailBox);

        $emails = array();

        if ($mc->Nmsgs > $this->lastMessage) {

            for ($i = $this->lastMessage + 1; $i <= $mc->Nmsgs; $i++) {

                $header = @imap_fetchbody($this->mailBox, $i, '0');
                $body = @imap_fetchbody($this->mailBox, $i, '1');

                $emails[] = array(
                    'header' => $header,
                    'body' => $body,
                );
            }

            $this->lastMessage = $mc->Nmsgs;
        }

        $this->closeMailBox();

        return $emails;
    }

    // }}}

    // {{{ Database operations

    /**
     * Make backup
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function doMakeBackup($path)
    {
        ob_start();
        xlite_make_sql_backup($path);
        ob_end_clean();

        \Includes\Utils\FileManager::unlinkRecursive(LC_DIR . '/../.dev/tests/images');
        \Includes\Utils\FileManager::mkdirRecursive(LC_DIR . '/../.dev/tests/images');
        \Includes\Utils\FileManager::copyRecursive(LC_DIR_IMAGES, LC_DIR . '/../.dev/tests/images/');
    }

    /**
     * Restore database from common backup
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doRestoreDb($path = null, $drop = true)
    {
        $message = '';
        $this->assertTrue(xlite_restore_sql_from_backup($path, false, $drop, $message), $message);
        \Includes\Utils\FileManager::copyRecursive(LC_DIR . '/.dev/tests/images', LC_DIR_IMAGES);
    }

    // }}}

    // {{{ XLite-specific methods

    /**
     * Do SQL query
     *
     * @param sql $sql SQL query
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function query($sql)
    {
        \XLite\Core\Database::getEM()->getConnection()->executeQuery($sql, array());
    }

    /**
     * getProduct
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);
    }

    /**
     * getProductBySku
     *
     * @param string $sku Product SKU
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductBySku($sku)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => $sku));
    }

    protected function clearEntity($entity)
    {
        if ($entity) {
            $em = \XLite\Core\Database::getEM();
            $entityState = $em->getUnitOfWork()->getEntityState($entity, \Doctrine\ORM\UnitOfWork::STATE_NEW);
            switch ($entityState) {
                case \Doctrine\ORM\UnitOfWork::STATE_DETACHED:
                    echo "detached";
                    $entity = $em->merge($entity);
                case \Doctrine\ORM\UnitOfWork::STATE_MANAGED:
//                    $id = $em->getClassMetadata(get_class($entity))->getIdentifierValues($entity);
//                    $entity = Xlite\Core\Database::getRepo(get_class($entity))->find($id);
//                    if ($entity)
//                        $em->remove($entity);
                    $em->refresh($entity);
                    $em->remove($entity);
                    break;
                case \Doctrine\ORM\UnitOfWork::STATE_NEW:
                case \Doctrine\ORM\UnitOfWork::STATE_REMOVED:
                default:
                    $entity = null;
                    break;
            }
        }
    }
    // }}}

    /**
     * Trace test execution time
     * @param $msg
     */
    protected function traceTime($msg){
        $time = microtime(true) - $this->start['time'];
        print PHP_EOL . 'TRACE: ' . $msg . '... ' . $time . ' sec';
    }

}

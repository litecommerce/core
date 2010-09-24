<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Base class for all LiteCommerce tests
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

abstract class XLite_Tests_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Prefix for all classes with test cases
     */
    const CLASS_PREFIX = 'XLite_Tests_';

    const IMAP_BOX  = '{mail.crtdev.local:143/imap/tls/novalidate-cert}INBOX';
    const IMAP_USER = 'rnd_tester';
    const IMAP_PASS = '7qnDjKzVoc6Qcb7b';

    protected static $messageLength = 70;

    /**
     * IMAP mailbox resource
     * 
     * @var    resource
     * @access private
     * @see    ____var_see____
     * @since  1.0.0
     */
    private $mailBox = null;

    /**
     * last message counter in IMAP mailbox
     * 
     * @var    float
     * @access private
     * @see    ____var_see____
     * @since  1.0.0
     */
    private $lastMessage = 0;

    private $app = null;

    /**
     * Parameters registering when test starts
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $start = array('time' => 0, 'memory' => 0);

    /**
     * Parameters registering when test ends
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $end = array('time' => 0, 'memory' => 0);

    /**
     * Return test execution time  
     * 
     * @return string
     * @access private
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function getExecTime()
    {
        $time    = number_format($this->end['time'] - $this->start['time'], 4);
        $message = trim($this->getMessage('', get_called_class(), $this->name));

        if (strlen($message) > self::$messageLength) {
            self::$messageLength = strlen($message) + 1;
        }

        return sprintf('%\'.-' . self::$messageLength. 's', trim($message))
            . ' ' . sprintf('%8s', $time) . ' sec .....';
    }

    /**
     * Return memory used by test
     * 
     * @return string
     * @access private
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
     * @access private
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

        return $class . ' ' . '[' . $method . ']. ' . $message;
    }

    /**
     * Return data needed to start application.
     * Derived class can redefine this method.
     * It's possible to detect current test using the $this->name variable
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequest()
    {
        // Default request
        $request = array(
            'init_app'   => true,
            'method'     => 'GET',
            'controller' => true, // true - admin, false - customer
            'data'       => array(
                'target' => \XLite::TARGET_DEFAULT,
                'action' => '',
            ),
            'cookies'    => array(),
        );

        return $request;
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

        // Clear and restart (if need) entity manager
        \XLite\Core\Database::getEM()->clear();
        try {
            \XLite\Core\Database::getEM()->flush();
        } catch (\Doctrine\ORM\ORMException $e) {
            if ('The EntityManager is closed.' == $e->getMessage()) {
                \XLite\Core\Database::getInstance()->startEntityManager();

            } else {
                throw $e;
            }
        }

        // Memory usage
        $this->start['memory'] = memory_get_usage();
        $this->end['memory']   = 0;

        // Print new line between classes
        $currentClass = get_called_class();
        if (empty(XLite_Tests_TestSuite::$currentClass) || $currentClass !== XLite_Tests_TestSuite::$currentClass) {
            echo PHP_EOL;
            XLite_Tests_TestSuite::$currentClass = $currentClass;
        }

        // Timing
        $this->start['time'] = microtime(true);
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
        // Timing
        $this->end['time'] = microtime(true);
        
//        if ($this->needAppInit()) {
//            \XLite\Core\Converter::getInstance()->__destruct();
//            \XLite\Core\Session::getInstance()->__destruct();
//            \XLite\Core\Database::getInstance()->__destruct();
//            $this->app->__destruct();
//        }

        // Memory usage
        $this->end['memory'] += memory_get_usage();

        echo PHP_EOL . $this->getExecTime();
        echo $this->getMemoryUsage();

        $this->writeMetricLog();
    }

    private function writeMetricLog()
    {
        $trace = debug_backtrace();
        $trace = $trace[0];

        $class = get_called_class();
        $method = $this->name;
        $class = str_replace(self::CLASS_PREFIX, '', empty($class) ? $trace['class'] : $class);
        $method = lcfirst(str_replace('test', '', empty($method) ? $trace['function'] : $method));

        $time = intval(round($this->end['time'] - $this->start['time'], 6) * 1000000);
        $memory = max($this->end['memory'] - $this->start['memory'], 0);

        XLite_Tests_MetricWriter::write($class, $method, $time, $memory);
    }

    /**
     * Check exception code 
     * 
     * @param function $func      Function
     * @param string   $errorCode Error code
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkException($func, $errorCode)
    {
        if ($this->isFunction($func)) {
            $func = array($func);

        } elseif (is_array($func)) {
            foreach ($func as $k => $v) {
                if (!$this->isFunction($v)) {
                    unset($func[$k]);
                }
            }
        }

        if (!is_array($func) || count($func) == 0) {
            $this->fail($this->getMessage('Argument $func is not valid'));
        }

        foreach ($func as $i => $f) {

            try {

                $f();

            } catch (\XLite\Core\Exception $exception) {

                $message = $this->getMessage('Check for the ' . $errorCode . ' exception:');
                $this->assertEquals($errorCode, $exception->getName(), $message);
                continue;
            } 

            $this->fail(
                $this->getMessage(
                    'The ' . $errorCode . ' exception was not thrown'
                    . (count($func) > 1 ? (' (function #' . ($i + 1) .')') : '')
                )
            );
        }
    }

    protected function checkWarning($func, $errorCode)
    {
        if ($this->isFunction($func)) {
            $func = array($func);

        } elseif (is_array($func)) {
            foreach ($func as $k => $v) {
                if (!$this->isFunction($v)) {
                    unset($func[$k]);
                }
            }
        }

        if (!is_array($func) || count($func) == 0) {
            $this->fail($this->getMessage('Argument $func is not valid'));
        }

        foreach ($func as $i => $f) {

            try {

                $f();

            } catch (PHPUnit_Framework_Error_Warning $exception) {

                $message = $this->getMessage('Check for the "' . $errorCode . '" exception:');
                $this->assertEquals($errorCode, substr($exception->getMessage(), 0, strlen($errorCode)), $message);
                continue;
            } 

            $this->fail(
                $this->getMessage(
                    'The "' . $errorCode . '" exception was not thrown'
                    . (count($func) > 1 ? (' (function #' . ($i + 1) .')') : '')
                )
            );
        }

    }

    protected function isFunction($func) {
        return is_object($func) && get_class($func) == 'Closure';
    }

    /**
     * Set start of emails counter
     * 
     * @return void
     * @access public
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
     * @access private
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function initMailBox()
    {
        if (
            is_null($this->mailBox) 
            || false === $this->mailBox
        ) {

            $this->mailBox = imap_open(self::IMAP_BOX, self::IMAP_USER, self::IMAP_PASS);

        }
    }

    /**
     * close IMAP mailbox
     * 
     * @return void
     * @access private
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
     * @access public
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
                $body   = @imap_fetchbody($this->mailBox, $i, '1');

                $emails[] = array(
                    'header' => $header,
                    'body'   => $body,
                );
            }

            $this->lastMessage = $mc->Nmsgs;
        }

        $this->closeMailBox();

        return $emails;
    }

    /**
     * Do SQL query 
     * 
     * @param sql $sql SQL query
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function query($sql)
    {
        \XLite\Core\Database::getEM()->getConnection()->executeQuery($sql, array());
    }
}


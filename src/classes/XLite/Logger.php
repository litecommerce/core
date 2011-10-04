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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite;

/**
 * Logger
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Logger extends \XLite\Base\Singleton
{
    /**
     * Log file name regexp pattern 
     */
    const LOG_FILE_NAME_PATTERN = '/^[a-zA-Z_]+\.log\.\d{4}-\d{2}-\d{2}\.php$/Ss';

    /**
     * Security file header
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $securityHeader = '<?php die(1); ?>';

    /**
     * Hash errors
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $hashErrors = array();

    /**
     * Errors translate table (PHP -> PEAR)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorsTranslate = null;

    /**
     * PHP error names
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorTypes = null;

    /**
     * Options
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $options = array(
        'type'  => null,
        'name'  => '/dev/null',
        'level' => LOG_WARNING,
        'ident' => 'X-Lite',
    );

    /**
     * Runtime id 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.11
     */
    protected $runtimeId;

    /**
     * Mark templates flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $markTemplates = false;


    /**
     * Check - display debug templates info or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isMarkTemplates()
    {
        return self::$markTemplates;
    }

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct()
    {
        include_once LC_DIR_LIB . 'Log.php';

        $this->options = array_merge(
            $this->options,
            \XLite::getInstance()->getOptions('log_details')
        );

        set_error_handler(array($this, 'registerPHPError'));
        // set_exception_handler(array($this, 'registerException'));

        // Default log path
        $path = $this->getErrorLogPath();
        ini_set('error_log', $path);
        $this->checkLogSecurityHeader($path);

        if (isset($this->options['suppress_errors']) && $this->options['suppress_errors']) {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);

        } else {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }

        if (isset($this->options['suppress_log_errors']) && $this->options['suppress_log_errors']) {
            ini_set('log_errors', 0);

        } else {
            ini_set('log_errors', 1);
        }

        self::$markTemplates = (bool)\XLite::getInstance()->getOptions(array('debug', 'mark_templates'));

        $logger = \Log::singleton(
            $this->getType(),
            $this->getName(),
            $this->getIdent()
        );

        if (isset($this->options['level'])) {
            $level = $this->options['level'];
            if (defined($level)) {
                $level = constant($level);
            }
            $level = min(7, intval($level));
            $mask = 0;
            for ($i = 0; $i <= $level; $i++) {
                $mask += 1 << $i;
            }

            $logger->setMask($mask);
        }
    }

    /**
     * Add log record
     *
     * @param string $message Message
     * @param string $level   Level code OPTIONAL
     * @param array  $trace   Back trace OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function log($message, $level = LOG_DEBUG, array $trace = array())
    {
        $dir = getcwd();
        chdir(LC_DIR);

        $logger = \Log::singleton(
            $this->getType(),
            $this->getName(),
            $this->getIdent()
        );

        // Add additional info
        $parts = array(
            'Server API: ' . PHP_SAPI,
        );

        if (isset($_SERVER)) {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                $parts[] = 'Request method: ' . $_SERVER['REQUEST_METHOD'];
            }

            if (isset($_SERVER['REQUEST_URI'])) {
                $parts[] = 'URI: ' . $_SERVER['REQUEST_URI'];
            }
        }

        $message .= PHP_EOL . implode(';' . PHP_EOL, $parts) . ';';

        // Add debug backtrace
        if (PEAR_LOG_ERR >= $level) {
            $backTrace = $trace ? $this->prepareBackTrace($trace) : $this->getBackTrace();
            $message .= PHP_EOL . 'Backtrace:' . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $backTrace);
        }

        $logger->log(trim($message) . PHP_EOL, $level);

        chdir($dir);
    }

    /**
     * Register PHP error
     *
     * @param integer $errno   Error code
     * @param string  $errstr  Error message
     * @param string  $errfile File path
     * @param integer $errline Line number
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerPHPError($errno, $errstr, $errfile, $errline)
    {
        $hash = $errno . ':' . $errfile . ':' . $errline;

        if (
            ini_get('error_reporting') & $errno
            && (0 != ini_get('display_errors') || 0 != ini_get('log_errors'))
            && 0 != error_reporting()
            && (1 != ini_get('ignore_repeated_errors') || !isset(self::$hashErrors[$hash]))
        ) {

            $errortype = $this->getPHPErrorName($errno);

            $message = $errortype . ': ' . $errstr . ' in ' . $errfile . ' on line ' . $errline;

            // Display error
            if (0 != ini_get('display_errors')) {
                $displayMessage = $message;

                if (isset($_SERVER['REQUEST_METHOD'])) {
                    $displayMessage = '<strong>' . $errortype . '</strong>: ' . $errstr
                        . ' in <strong>' . $errfile . '</strong> on line <strong>' . $errline . '</strong><br />';
                }

                echo ($displayMessage . PHP_EOL);
            }

            // Save to log
            if (0 != ini_get('log_errors')) {
                $this->log($message, $this->convertPHPErrorToLogError($errno));
            }

            // Save to cache
            if (1 == ini_get('ignore_repeated_errors')) {
                self::$hashErrors[$hash] = true;
            }
        }

        return true;
    }

    /**
     * Register non-catched exception
     *
     * @param \Exception $exception Exception
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerException(\Exception $exception)
    {
        if (
            ini_get('error_reporting') & E_ERROR
            && (0 != ini_get('display_errors') || 0 != ini_get('log_errors'))
            && 0 != error_reporting()
        ) {

            $message = 'Exception: ' . $exception->getMessage()
                . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine();

            // Display error
            if (0 != ini_get('display_errors')) {

                if (isset($_SERVER['REQUEST_METHOD'])) {
                    $displayMessage = '<strong>Exception</strong>: ' . $exception->getMessage()
                        . ' in <strong>' . $exception->getFile() . '</strong>'
                        . ' on line <strong>' . $exception->getLine() . '</strong><br />';
                } else {
                    $displayMessage = $message;
                }

                echo ($displayMessage . PHP_EOL);
            }

            // Save to log
            if (0 != ini_get('log_errors')) {
                $this->log($message, PEAR_LOG_ERR, $exception->getTrace());
            }
        }
    }

    /**
     * Log custom message
     * 
     * @param string  $type         Message type
     * @param string  $message      Message
     * @param boolean $useBackTrace User backtrace flag OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function logCustom($type, $message, $useBackTrace = false)
    {
        $type = preg_replace('/[^a-zA-Z0-9_-]/Ss', '', strval($type));
        $path = $this->getCustomLogPath($type);
        $header = $this->getLogFileHeader();
        if (!file_exists($path) || strlen($header) > filesize($path)) {
            @file_put_contents($path, $header);
        }

        $message = trim('[' . @date('H:i:s') . '] ' . $message) . PHP_EOL
            . 'Runtime id: ' . $this->getRuntimeId() . PHP_EOL
            . 'SAPI: ' . PHP_SAPI . '; '
            . 'IP: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'n/a') . PHP_EOL
            . PHP_EOL;

        @file_put_contents($path, $message, FILE_APPEND);

        return $path;
    }

    /**
     * Get custom log URL 
     * 
     * @param string $type Type
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getCustomLogURL($type)
    {
        return \XLite\Core\Converter::buildURL('log', '', array('log' => $this->getCustomLogFileName($type)));
    }

    /**
     * Get custom log file path 
     * 
     * @param string $type Type
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getCustomLogPath($type)
    {
        return LC_DIR_VAR . 'log' . LC_DS . $this->getCustomLogFileName($type);
    }

    /**
     * Get custom log file name 
     * 
     * @param string $type Type
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getCustomLogFileName($type)
    {
        return $type . '.log.' . date('Y-m-d') . '.php';
    }

    /**
     * Get log file header 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getLogFileHeader()
    {
        return '<' . '?php die(); ?' . '>' . PHP_EOL;
    }

    /**
     * Get runtime id
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRuntimeId()
    {
        if (!isset($this->runtimeId)) {
            $this->runtimeId = hash('md4', uniqid('runtime', true), false);
        }

        return $this->runtimeId;
    }

    /**
     * Get log type
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getType()
    {
        return $this->options['type'];
    }

    /**
     * Get logger name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getName()
    {
        $result = $this->options['name'];

        if ('file' == $this->getType()) {
            $dir = dirname(LC_DIR . LC_DS . ltrim($result, LC_DS));
            $file = basename($result);
            $parts = explode('.', $file);
            array_splice($parts, count($parts) - 1, 0, date('Y-m-d'));
            $result = $dir . LC_DS . implode('.', $parts);
            if (!preg_match('/\.php$/Ss', $result)) {
                $result .= '.php';
            }

            $this->checkLogSecurityHeader($result);
        }

        return $result;
    }

    /**
     * Get logger identtificator
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIdent()
    {
        return $this->options['ident'];
    }

    /**
     * Get back trace list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBackTrace()
    {
        return \XLite\Core\Operator::getInstance()->getBackTrace(2);
    }

    /**
     * Prepare back trace
     *
     * @param array $trace Back trace raw data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareBackTrace(array $trace)
    {
        return \XLite\Core\Operator::getInstance()->prepareBackTrace($trace);
    }

    /**
     * Detect class name by object
     *
     * @param object $obj Object
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function detectClassName($obj)
    {
        return is_object($obj) ? get_class($obj) : strval($obj);
    }

    /**
     * Convert PHP error code to PEAR error code
     *
     * @param integer $errno PHP error code
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function convertPHPErrorToLogError($errno)
    {
        if (!isset($this->errorsTranslate)) {

            $this->errorsTranslate = array(
                E_ERROR             => PEAR_LOG_ERR,
                E_WARNING           => PEAR_LOG_WARNING,
                E_PARSE             => PEAR_LOG_CRIT,
                E_NOTICE            => PEAR_LOG_NOTICE,
                E_CORE_ERROR        => PEAR_LOG_ERR,
                E_CORE_WARNING      => PEAR_LOG_WARNING,
                E_COMPILE_ERROR     => PEAR_LOG_ERR,
                E_COMPILE_WARNING   => PEAR_LOG_WARNING,
                E_USER_ERROR        => PEAR_LOG_ERR,
                E_USER_WARNING      => PEAR_LOG_WARNING,
                E_USER_NOTICE       => PEAR_LOG_NOTICE,
                E_STRICT            => PEAR_LOG_NOTICE,
                E_RECOVERABLE_ERROR => PEAR_LOG_ERR,
            );

            if (defined('E_DEPRECATED')) {
                $this->errorsTranslate[E_DEPRECATED] = PEAR_LOG_WARNING;
                $this->errorsTranslate[E_USER_DEPRECATED] = PEAR_LOG_WARNING;
            }
        }

        return isset($this->errorsTranslate[$errno]) ? $this->errorsTranslate[$errno] : PEAR_LOG_INFO;
    }

    /**
     * Get PHP error name
     *
     * @param integer $errno PHP error code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPHPErrorName($errno)
    {
        if (!isset($this->errorTypes)) {
            $this->errorTypes = array(
                E_ERROR             => 'Error',
                E_WARNING           => 'Warning',
                E_PARSE             => 'Parsing Error',
                E_NOTICE            => 'Notice',
                E_CORE_ERROR        => 'Error',
                E_CORE_WARNING      => 'Warning',
                E_COMPILE_ERROR     => 'Error',
                E_COMPILE_WARNING   => 'Warning',
                E_USER_ERROR        => 'Error',
                E_USER_WARNING      => 'Warning',
                E_USER_NOTICE       => 'Notice',
                E_STRICT            => 'Runtime Notice',
                E_RECOVERABLE_ERROR => 'Catchable fatal error',
            );

            if (defined('E_DEPRECATED')) {
                $this->errorTypes[E_DEPRECATED] = 'Warning (deprecated)';
                $this->errorTypes[E_USER_DEPRECATED] = 'Warning (deprecated)';
            }
        }

        return isset($this->errorTypes[$errno]) ? $this->errorTypes[$errno] : 'Unknown Error';
    }

    /**
     * Get rrror log path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getErrorLogPath()
    {
        return LC_DIR_VAR . 'log' . LC_DS . 'php_errors.log.' . date('Y-m-d') . '.php';
    }

    /**
     * Check security header for specified file
     *
     * @param string $path File path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkLogSecurityHeader($path)
    {
        if (!file_exists(dirname($path))) {
            \Includes\Utils\FileManager::mkdirRecursive(dirname($path));
        }

        if (!file_exists($path) || $this->securityHeader > filesize($path)) {
            file_put_contents($path, $this->securityHeader . "\n");
        }
    }
}

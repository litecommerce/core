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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite;

/**
 * Logger 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Logger extends \XLite\Base\Singleton
{
    /**
     * Security file header 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $securityHeader = '<?php die(1); ?>';

    /**
     * Hash errors 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $hashErrors = array();

    /**
     * Errors translate table (PHP -> PEAR)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $errorsTranslate = null;

    /**
     * PHP error names 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $errorTypes = null;

    /**
     * Options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = array(
        'type'  => null,
        'name'  => '/dev/null',
        'level' => LOG_WARNING,
        'ident' => 'X-Lite',
    );

    /**
     * Mark tempaltes flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $markTempaltes = false;

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        require_once LC_LIB_DIR . 'Log.php';

        $this->options = array_merge(
            $this->options,
            \XLite::getInstance()->getOptions('log_details')
        );

        set_error_handler(array($this, 'registerPHPError'));

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

        self::$markTempaltes = (bool)\XLite::getInstance()->getOptions(array('debug', 'mark_templates'));

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
     * @param string $level   Level code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function log($message, $level = LOG_DEBUG)
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
            $message .= PHP_EOL . 'Backtrace:' . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $this->getBackTrace());
        }

        $logger->log(trim($message) . PHP_EOL, $level);

        chdir($dir);
    }

    /**
     * Get log type 
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getType()
    {
        return $this->options['type'];
    }

    /**
     * Get logger name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getName()
    {
        $result = $this->options['name'];

        if ('file' == $this->getType()) {
            $dir = dirname($result);
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIdent()
    {
        return $this->options['ident'];
    }

    /**
     * Get back trace list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBackTrace()
    {
        return \XLite\Core\Operator::getInstance()->getBackTrace(3);
    }

    /**
     * Detect class name by object
     * 
     * @param object $obj Object
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function detectClassName($obj)
    {
        return is_object($obj) ? get_class($obj) : strval($obj);
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function registerPHPError($errno, $errstr, $errfile, $errline)
    {
        $hash = $errno . ':' . $errfile . ':' . $errline;

        if (
            ini_get('error_reporting') & $errno
            && 0 != ini_get('display_errors')
            && 0 != ini_get('log_errors')
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

                echo ($displayMessage . "\n");
            }

            // Save to log
            if (1 == ini_get('log_errors')) {
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
     * Convert PHP error code to PEAR error code
     * 
     * @param integer $errno PHP error code
     *  
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getErrorLogPath()
    {
        return LC_VAR_DIR . 'log' . LC_DS . 'php_errors.log.' . date('Y-m-d') . '.php';
    }

    /**
     * Check security header for specified file
     * 
     * @param string $path File path
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

    /**
     * Check - display debug templates info or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isMarkTemplates()
    {
        return self::$markTempaltes;
    }
}

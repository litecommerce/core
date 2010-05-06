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

/**
 * Logger 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Logger extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Logger defaults 
     */
    const LOGGER_DEFAULT_TYPE = null;
    const LOGGER_DEFAULT_NAME = '/dev/null';
    const LOGGER_DEFAULT_LEVEL = LOG_DEBUG;
    const LOGGER_DEFAULT_IDENT = 'X-Lite';


    /**
     * Files repositories paths
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $filesRepositories = array(
        LC_COMPILE_DIR => 'compiled classes repository',
        LC_ROOT_DIR    => 'lc root',
    );

    /**
     * Options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = array(
        'type'  => self::LOGGER_DEFAULT_TYPE,
        'name'  => self::LOGGER_DEFAULT_NAME,
        'level' => self::LOGGER_DEFAULT_LEVEL,
        'ident' => self::LOGGER_DEFAULT_IDENT
    );

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
        require_once LC_EXT_LIB_DIR . 'Log.php';

        $this->options = array_merge(
            $this->options,
            XLite::getInstance()->getOptions('log_details')
        );
    }
    
    /**
     * Get class instance 
     * 
     * @return XLite_Logger
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
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
    public function log($message, $level = null)
    {
        $dir = getcwd();
        chdir(LC_DIR);

        $logger = Log::singleton(
            $this->getType(),
            $this->getName(),
            $this->getIdent()
        );

        if (is_null($level)) {
            $defaultLevel = $this->options['level'];
            if (defined($defaultLevel)) {
                $level = constant($defaultLevel);
            }
        }

        if (is_null($level)) {
            $level = PEAR_LOG_DEBUG;
        }

        // Add additional info
        $parts = array(
            'Server API: ' . php_sapi_name(),
        );

        if (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD'])) {
            $parts[] = 'Request method: ' . $_SERVER['REQUEST_METHOD'];
            $parts[] = 'URI: ' . $_SERVER['REQUEST_URI'];
        }

        $message .= "\n" . implode('; ', $parts) . ';';

        // Add debug backtrace
        if (PEAR_LOG_ERR >= $level) {
            $message .= "\n" . 'Backtrace:' . "\n\t" . implode("\n\t", $this->getBackTrace());
        }

        $logger->log(trim($message) . "\n", $level);

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
        $patterns = array_keys($this->filesRepositories);
        $placeholders = preg_replace('/^(.+)$/Ss', '<\1>/', array_values($this->filesRepositories));

        $trace = array();
        foreach (debug_backtrace(false) as $l) {
            $parts = array();
            if (isset($l['file'])) {

                $parts[] = 'file ' . str_replace($patterns, $placeholders, $l['file']);

            } elseif (isset($l['class']) && isset($l['function'])) {

                $parts[] = 'method ' . $l['class'] . '::' . $l['function'] . $this->getBackTraceArgs($l);

            } elseif (isset($l['function'])) {

                $parts[] = 'function ' . $l['function'] . $this->getBackTraceArgs($l);

            }

            if (isset($l['line'])) {
                $parts[] = $l['line'];
            }

            if ($parts) {
                $trace[] = implode(' : ', $parts);
            }
        }

        return array_slice($trace, 3);
    }

    /**
     * Get back trace function or method arguments 
     * 
     * @param array $l Back trace record
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBackTraceArgs(array $l)
    {
        $args = array();
        if (isset($l['args'])) {
            foreach ($l['args'] as $arg) {
                switch (gettype($arg)) {
                    case 'boolean':
                        $args[] = $arg ? 'true' : 'false';
                        break;

                    case 'integer':
                    case 'double':
                        $args[] = $arg;
                        break;

                    case 'string':
                        if (is_callable($arg)) {
                            $args[] = 'lambda function';

                        } else {
                            $args[] = '\'' . addslashes($arg) . '\'';
                        }
                        break;

                    case 'unicode':
                        $args[] = '\'' . addslashes($arg) . '\' (unicode)';
                        break;

                    case 'resource':
                        $args[] = strval($arg);
                        break;

                    case 'array':
                        if (is_callable($arg)) {
                            $args[] = 'callback ' . $this->detectClassName($arg[0]) . '::' . $arg[1];

                        } else {
                            $args[] = 'array{' . count($arg) . '}'; 
                        }
                        break;

                    case 'object':
                        if (
                            is_callable($arg)
                            && class_exists('Closure')
                            && $arg instanceof Closure
                        ) {
                            $args[] = 'anonymous function';

                        } else {
                            $args[] = 'object of ' . $this->detectClassName($arg);
                        }
                        break;

                    case 'NULL';
                    case 'null';
                        $args[] = 'null';
                        break;

                    default:
                        $args[] = 'variable of ' . gettype($arg);
                }
            }
        }

        return '(' . implode(', ', $args) . ')';
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
        return function_exists('get_called_class') ? get_called_class($obj) : get_class($obj);
    }
}

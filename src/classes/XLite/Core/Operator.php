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

namespace XLite\Core;

/**
 * Common operations repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Operator extends \XLite\Base\Singleton
{
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
     * Check if we need to perform a redirect or not 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected static function checkRedirectStatus()
    {
        return !\XLite\Core\CMSConnector::isCMSStarted() 
            || !\XLite\Core\Request::getInstance()->__get(\XLite\Core\CMSConnector::NO_REDIRECT);
    }

    /**
     * setHeaderLocation
     * 
     * @param string  $location URL
     * @param integer $code     Operation code OPTIONAL
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function setHeaderLocation($location, $code = 302)
    {
        if (headers_sent()) {

            // HTML meta tags-based redirect
            echo (
                '<script type="text/javascript">' . "\n"
                . '<!--' . "\n"
                . 'self.location=\'' . $location . '\';' . "\n"
                . '-->' . "\n"
                . '</script>' . "\n"
                . '<noscript><a href="' . $location . '">Click here to redirect</a></noscript><br /><br />'
            );

        } elseif (\XLite\Core\Request::getInstance()->isAJAX() && 200 == $code) {

            // AJAX-based redirct
            header('AJAX-Location: ' . $location, true, $code);

        } else {

            // HTTP-based redirect
            header('Location: ' . $location, true, $code);
        }
    }

    /**
     * finish 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function finish()
    {
        exit (0);
    }


    /**
     * Redirect 
     * 
     * @param string  $location URL
     * @param boolean $force    Check or not redirect conditions OPTIONAL
     * @param integer $code     Operation code OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function redirect($location, $force = false, $code = 302)
    {
        if (static::checkRedirectStatus() || $force) {
            static::setHeaderLocation($location, $code);
            static::finish();
        }
    }

    /**
     * Check if class exists 
     * 
     * @param string $name Name of class to check
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isClassExists($name)
    {
        return class_exists($name, false)
            || file_exists(LC_CLASSES_CACHE_DIR . str_replace('\\', LC_DS, $name) . '.php');
    }

    /**
     * Get URL content 
     * 
     * @param string $url URL
     *  
     * @return string|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getURLContent($url)
    {
        $result = null;

        if (ini_get('allow_url_fopen')) {
            $result = file_get_contents($url);

        } else {
            $bouncer = new \XLite\Model\HTTPS();
            $bouncer->url = $url;
            $bouncer->method = 'GET';
            if (\XLite\Model\HTTPS::HTTPS_SUCCESS == $bouncer->request()) {
                $result = $bouncer->response;
            }
        }

        return $result;
    }

    /**
     * Calculate pagination info
     * 
     * @param integer $count Items count
     * @param integer $page  Current page index OPTIONAL
     * @param integer $limit Page length limit OPTIONAL
     *  
     * @return array (pages count + current page number)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function calculatePagination($count, $page = 1, $limit = 20)
    {
        $count = max(0, intval($count));
        $limit = max(0, intval($limit));

        if (0 == $limit && $count) {
            $pages = 1;

        } else {
            $pages = 0 == $count ? 0 : ceil($count / $limit);
        }

        $page = min($pages, max(1, intval($page)));

        return array($pages, $page);
    }

    /**
     * Display 404 page
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function display404()
    {
        if (!headers_sent()) {
            header('HTTP/1.0 404 Not Found');
        }
        exit(1);
    }

    /**
     * Get back trace list
     * FIXME: to revise
     *
     * @param integer $slice Trace slice count OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBackTrace($slice = 0)
    {
        $patterns = array_keys($this->filesRepositories);
        $placeholders = preg_replace('/^(.+)$/Ss', '<\1>/', array_values($this->filesRepositories));

        $slice = max(0, $slice) + 1;

        $trace = array();
        foreach (debug_backtrace(false) as $l) {
            if (0 < $slice) {
                $slice--;
                continue;
            }

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

        return $trace;
    }

    /**
     * Get back trace function or method arguments 
     * FIXME: to revise
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
        if (!isset($l['args'])) {
            $l['args'] = array();
        }

        foreach ($l['args'] as $arg) {

            if (is_bool($arg)) {
                $args[] = $arg ? 'true' : 'false';

            } elseif (is_int($arg) || is_float($arg)) {
                $args[] = $arg;

            } elseif (is_string($arg)) {
                if (is_callable($arg)) {
                    $args[] = 'lambda function';

                } else {
                    $args[] = '\'' . $arg . '\'';
                }

            } elseif (is_resource($arg)) {

                $args[] = strval($arg);

            } elseif (is_array($arg)) {
                if (is_callable($arg)) {
                    $args[] = 'callback ' . $this->detectClassName($arg[0]) . '::' . $arg[1];

                } else {
                    $args[] = 'array(' . count($arg) . ')';
                }

            } elseif (is_object($arg)) {
                if (
                    is_callable($arg)
                    && class_exists('Closure')
                    && $arg instanceof Closure
                ) {
                    $args[] = 'anonymous function';

                } else {
                    $args[] = 'object of ' . $this->detectClassName($arg);
                }

            } elseif (!isset($arg)) {
                $args[] = 'null';

            } else {
                $args[] = 'variable of ' . gettype($arg);
            }
        }

        return '(' . implode(', ', $args) . ')';
    }

    /**
     * detectClassName 
     * FIXME: unknown functionality
     * 
     * @param mixed $class ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function detectClassName($class)
    {
        return get_class($class);
    }
}


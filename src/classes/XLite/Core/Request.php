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

namespace XLite\Core;

/**
 * Request
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Request extends \XLite\Base\Singleton
{
    const METHOD_CLI = 'cli';

    /**
     * Cureent request method
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $requestMethod = null;

    /**
     * Request data
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $data = array();


    /**
     * Map request data
     *
     * @param array $data Custom data OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function mapRequest(array $data = array())
    {
        if (empty($data)) {
            if ($this->isCLI()) {
                for ($i = 1; count($_SERVER['argv']) > $i; $i++) {
                    $pair = explode('=', $_SERVER['argv'][$i], 2);
                    $data[preg_replace('/^-+/Ss', '', $pair[0])] = isset($pair[1]) ? trim($pair[1]) : true;
                }

            } else {
                $data = array_merge($_GET, $_POST, $_COOKIE);
            }
        }

        $this->data = array_replace_recursive($this->data, $this->prepare($data));
    }

    /**
     * Return all data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return current request method
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Set request method
     *
     * @param string $method New request method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
    }

    /**
     * Check if current request method is "GET"
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isGet()
    {
        return 'GET' === $this->requestMethod;
    }

    /**
     * Check if current request method is "POST"
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isPost()
    {
        return 'POST' === $this->requestMethod;
    }

    /**
     * Check if current request method is "HEAD"
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isHead()
    {
        return 'HEAD' === $this->requestMethod;
    }

    /**
     * Check - is AJAX request or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAJAX()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * Check - is secure connection or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isHTTPS()
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1'))
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
            || (
                isset($_SERVER['REMOTE_ADDR'])
                && \XLite::getInstance()->getOptions(array('host_details', 'remote_addr')) == $_SERVER['REMOTE_ADDR']
            );
    }

    /**
     * Check - is command line interface or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCLI()
    {
        return 'cli' == PHP_SAPI;
    }

    /**
     * Getter
     *
     * @param string $name Property name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Setter
     *
     * @param string $name  Property name
     * @param mixed  $value Property value
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $this->prepare($value);
    }

    /**
     * Check property accessability
     *
     * @param string $name Property name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }


    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        $this->requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::METHOD_CLI;
        $this->mapRequest();
    }

    /**
     * Unescape single value
     *
     * @param string $value Value to sanitize
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doUnescapeSingle($value)
    {
        return stripslashes($value);
    }

    /**
     * Remove automatically added escaping
     *
     * @param mixed $data Data to sanitize
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doUnescape($data)
    {
        return is_array($data)
            ? array_map(array($this, __FUNCTION__), $data)
            : $this->doUnescapeSingle($data);
    }

    /**
     * Normalize request data
     *
     * @param mixed $request Request data
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function normalizeRequestData($request)
    {
        if (ini_get('magic_quotes_gpc')) {
            $request = $this->doUnescape($request);
        }

        return $request;
    }

    /**
     * Wrapper for sanitize()
     *
     * @param mixed $data Data to sanitize
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepare($data)
    {
        if (is_array($data)) {
            if (isset($data['target']) && !$this->checkControlArgument($data['target'], 'Target')) {
                $data['target'] = \XLite::TARGET_404;
                $data['action'] = null;
            }

            if (isset($data['action']) && !$this->checkControlArgument($data['action'], 'Action')) {
                unset($data['action']);
            }
        }

        return $this->normalizeRequestData($data);
    }

    /**
     * Check control argument (like target)
     *
     * @param mixed  $value Argument value
     * @param string $name  Argument name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkControlArgument($value, $name)
    {
        $result = true;

        if (!is_string($value)) {
            \XLite\Logger::getInstance()->log($name . ' has a wrong type');
            $result = false;

        } elseif (!preg_match('/^[a-z0-9_]*$/Ssi', $value)) {
            \XLite\Logger::getInstance()->log($name . ' has a wrong format');
            $result = false;
        }

        return $result;
    }
}

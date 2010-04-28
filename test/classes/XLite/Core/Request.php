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
 * Request 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_Request extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Cureent request method 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $requestMethod = null;

    /**
     * Request data 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $data = array();


    /**
     * Strip possible SQL injections
     * TODO - improve or remove (if the PDO will be used) this function
     * 
     * @param string $value value to check
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function stripSQLInjection($value)
    {
        // (UNION SELECT) case
        if (false !== strpos(strtolower($value), 'union')) {
            $value = preg_replace(
                '/union([\s\(\)]|((?:\/\*).*(?:\*\/))|(?:union|select|all|distinct))+select/i',
                ' ',
                $value
            );
        }

        // (BENCHMARK) case
        if (false !== strpos(strtolower($value), 'benchmark(')) {
            $value = preg_replace('/benchmark\(/i', ' ', $value);
        }

        return $value;
    }

    /**
     * Sanitize single value
     * 
     * @param string $value value to sanitize
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function sanitizeSingle($value)
    {
        return strip_tags($this->stripSQLInjection($value));
    }

    /**
     * Sanitize passed data 
     * 
     * @param mixed $data data to sanitize
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function sanitize($data)
    {
        return is_array($data) ? array_map(array($this, __FUNCTION__), $data) : $this->sanitizeSingle($data);
    }

    /**
     * Wrapper for sanitize()
     *
     * @param mixed $data data to sanitize
     *
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function prepare($data)
    {
        return XLite::isAdminZone() ? $data : $this->sanitize($data);
    }

    /**
     * Constructor
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->mapRequest();
    }

    /**
     * Method to access the singleton 
     * 
     * @return XLite_Core_CMSConnector
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Map request data
     * 
     * @param array $data custom data (optional)
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function mapRequest(array $data = array())
    {
        if (empty($data)) {
            $data = $_REQUEST;
        }

        $this->data = array_merge($this->data, $this->prepare($data));
    }

    /**
     * Return all data 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return current request method
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Check if current request method is "GET" 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isGet()
    {
        return 'GET' === $this->requestMethod;
    }

    /**
     * Check if current request method is "POST"
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isPost()
    {
        return 'POST' === $this->requestMethod;
    }

    /**
     * Check - is AJAX request or not
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAJAX()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    /**
     * Check - is secure connection or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isHTTPS()
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1'))
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
            || (
                isset($_SERVER['REMOTE_ADDR'])
                && XLite::getInstance()->getOptions(array('host_details', 'remote_addr')) == $_SERVER['REMOTE_ADDR']
            );
    }

    /**
     * Check - is command line interface or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCLI()
    {
        return 'cli' == php_sapi_name();
    }

    /**
     * Getter
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    /**
     * Setter 
     * 
     * @param string $name  property name
     * @param mixed  $value property value
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $this->prepare($value);
    }

    /**
     * Check property accessability
     *
     * @param string $name property name
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
}

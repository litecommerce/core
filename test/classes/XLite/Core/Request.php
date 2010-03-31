<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Request
 *  
 * @category  Litecommerce
 * @package   Core
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Request
 *                         
 * @package    Core
 * @since      3.0                   
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
    protected function stripSQLinjection($value)
    {
        // (UNION SELECT) case
        if (false !== strpos(strtolower($value), 'union')) {
            $value = preg_replace('/union([\s\(\)]|((?:\/\*).*(?:\*\/))|(?:union|select|all|distinct))+select/i', ' ', $value);
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
        return strip_tags($this->stripSQLinjection($value));
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

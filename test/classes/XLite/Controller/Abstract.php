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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract controller
 * 
 * @package    XLite
 * @subpackage Controller
 * @since      3.0.0
 */
abstract class XLite_Controller_Abstract extends XLite_Core_Handler
{
	/**
	 * Current page template 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $template = 'main.tpl';

    /**
     * Breadcrumbs 
     * 
     * @var    XLite_Model_LocationPath
     * @access protected
     * @since  3.0.0 EE
     */
    protected $locationPath = null;

    /**
     * Pages array for tabber
     * FIXME - must be protected
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $pages = array();


	/**
	 * Check if current page is accessible
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function checkAccess()
	{
		return XLite_Model_Auth::getInstance()->isAuthorized($this);
	}

	/**
	 * Perform redirect 
	 * 
	 * @param string $url redirect URL
	 *  
	 * @return void
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function redirect($url = null)
    {
		$location = $this->getReturnUrl();

		if (is_null($location)) {
			$location = is_null($url) ? $this->getUrl() : $url;
		}

        // filter xlite_form_id from redirect url
        $action = $this->get('action');
        if (empty($action)) {
            $location = $this->filterXliteFormID($location);
		}

        XLite_Model_Profiler::getInstance()->enabled = false;

		if ($this->returnUrlAbsolute) {
			$location = $this->getShopUrl($location, $this->get('secure'));
		}

		$code = 302;
    	if (XLite_Core_Request::getInstance()->isAJAX()) {
			$code = 278;
    	}

        header('Location: ' . $location, true, $code);
    }

	/**
	 * Add the base part of the location path
	 * 
	 * @return void
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function addBaseLocation()
	{
		$this->locationPath->addNode(new XLite_Model_Location('Home', $this->buildURL()));
	}

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {
        return null;
    }

    /**
     * getCMSTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getCMSTemplate()
    {
        return 'center_top.tpl';
    }

    /**
     * Get the full URL of the page
     * Example: getShopUrl("cart.php") = "http://domain/dir/cart.php 
     * 
     * @param string $url    relative URL  
     * @param bool   $secure flag to use HTTPS
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getShopUrl($url, $secure = false)
    {
        return XLite::getInstance()->getShopUrl($url, $secure);
    }


	/**
	 * Return current location path 
	 * 
	 * @return XLite_Model_LocationPath
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getLocationPath()
	{
        if (!isset($this->locationPath)) {

            $this->locationPath = new XLite_Model_LocationPath();
            $this->addBaseLocation();

            if ($this->getLocation()) {
                $this->locationPath->addNode(new XLite_Model_Location($this->getLocation()));
            }
        }

        return $this->locationPath;
	}

    /**
     * Handles the request. Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function handleRequest()
    {
		if (!$this->checkAccess()) {

            $this->params = array('target', 'mode');
            $this->set('target', XLite::TARGET_DEFAULT);
            $this->set('mode', 'access_denied');

        } elseif (!empty(XLite_Core_Request::getInstance()->action) && $this->isValid()) {

            $action = 'action_' . XLite_Core_Request::getInstance()->action;
            $this->$action();
        }

        if (XLite_Core_Request::getInstance()->isPost() && $this->isValid() && !$this->silent) {
            $this->redirect();
        }
	}

	/**
	 * Return Viewer object
	 * 
	 * @return XLite_View_Controller
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getViewer($isExported = false)
	{
        $template = $this->template;
        $params   = array();

        foreach (array('silent', 'dumpStarted') as $name) {
            $params[$name] = $this->get($name);
        }

        if ($isExported) {
            $params[XLite_View_Abstract::PARAM_IS_EXPORTED] = true;
            $template = $this->getCMSTemplate();
        }

		return new XLite_View_Controller($template, $params);
    }

	/**
	 * This function called after template output
	 * FIXME - may be there is a better way to handle this?
	 * 
	 * @return void
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function postprocess()
	{
	}

	/**
	 * Get controlelr parameters
	 * 
	 * @param string $exeptions Parameter keys string
	 *  
	 * @return array
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getAllParams($exeptions = null)
    {
        $result = array();
		$exeptions = isset($exeptions) ? explode(",", $exeptions) : false;

        foreach ($this->get('params') as $name) {
			$value = $this->get($name);
            if (isset($value) && (!$exeptions || in_array($name, $exeptions))) {
                $result[$name] = $value;
            }
        }

		return $result;
	}

	/**
	 * Return current (or default) category object
	 * 
	 * @return XLite_Model_Category
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getCategory()
    {
		return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Category', array(XLite_Core_Request::getInstance()->category_id));
    }

	/**
	 * Return current (or default) product object
	 * 
	 * @return XLite_Model_Product
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getProduct()
    {
		return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Product', array(XLite_Core_Request::getInstance()->product_id));
    }

	/**
	 * Return current page title
	 * 
	 * @return string
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getTitle()
	{
		return null;
	}


	// TODO - all of the above should be revised


    protected $params = array('target');	
    public $dumpStarted = false; // startDump was called	

	protected $silent = false;

	protected $pageTemplates = array();

	protected $returnUrlAbsolute = false;

	protected $product = null;

	protected $category = null;

    /**
     * Validity flag
     * TODO - check where it's really needed
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected $valid = true;

	/**
	 * Page type parameters
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $pageTypeParams = null;

    /**
     * Set properties
     * FIXME - backward compatibility
     *
     * @param array $attrs params to set
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function setAttributes(array $attrs)
    {
        foreach ($attrs as $name => $value) {
            // FIXME - mapping
            $this->$name = $value;
        }   
    }   
    
    /**
     * Check if handler is valid 
     * TODO - check where it's really needed
     * 
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function isValid()
    {
        return $this->valid;
    } 

    /**
     * Initialize controller
     *
     * @return void
     * @access public
     * @since  3.0.0
     */ 
    public function init()
    {
        // FIXME - backward compatibility; to delete
        $this->setAttributes(XLite_Core_Request::getInstance()->getData());
        $this->fillForm();
    }

    /**
     * FIXME - backward compatibility; to delete
     * 
     * @param mixed $request ____param_comment____
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function mapRequest($request = null)
    {   
    }   
        
    /** 
     * FIXME - backward compatibility; to delete 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function fillForm()
    {
    }

    /**
     * getReturnUrl 
     * 
     * @return mixed
     * @access public
     * @since  3.0.0
     */
	public function getReturnUrl()
	{
		return $this->returnUrl;
	}

	function getTemplate()
    {
		return $this->template;
	}

	function _clear_xsid_data()
	{
		unset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]);
		$this->xlite->session->destroy();
		$this->xlite->session->setID(SESSION_DEFAULT_ID);
		$this->xlite->session->_initialize();
		$this->xlite->session->_data = array();
	}

	function _pure_url_path($str)
	{
		$pos = strpos($str, "?");
		if ($pos !== false) {
			$str = substr($str, 0, $pos);
		}

		$last = strlen($str) - 1;
		if ($last > 0 && $str{$last} == "/") {
			$str = substr($str, 0, $last);
		}

		return $str;
	}

    function isHTTPS()
    {
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1')) ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ||
            (isset($_SERVER['REMOTE_ADDR']) && $this->xlite->getOptions(array('host_details', 'remote_addr')) == $_SERVER['REMOTE_ADDR']))
        {
            return true;
        }
        return false;
    }

    /*function startPage()
    {
        // send no-cache headers
        $error_reporting = error_reporting(0); // suppress warning messages
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: text/html");
        error_reporting($error_reporting);
    }*/
    
    function startDownload($filename, $contentType = "application/force-download")
    {
        @set_time_limit(0);
        header("Content-type: $contentType");
        header("Content-disposition: attachment; filename=$filename");
    }

    function startImage()
    {
        header("Content-type: image/gif");
        $this->set("silent", true);
    }

    function startDump()
    {
        @set_time_limit(0);
        $this->set("silent", true);
        if (!isset($_REQUEST["mode"]) || $_REQUEST["mode"]!="cp") {
            func_refresh_start();
            $this->dumpStarted = true;
        }
    }

    /**
    * Provides access to accessdenied function.
    */
    function accessDenied()
    {
    }

    /**
    * Returns the access level value required to access this dialog.
    *
    * @access public
    * @return integer The access level value
    */
    function getAccessLevel()
    {
        return $this->getComplex('auth.customerAccessLevel');
    }

    function getProperties()
    {
        $result = array();
        foreach ($_REQUEST as $name => $value)
        {
            $result[$name] = $this->get($name);
        }
        return $result;
    }

    function getUrl(array $params = array())
    {
        $params = array_merge($this->getAllParams(), $params);

		$target = isset($params['target']) ? $params['target'] : '';
		unset($params['target']);

		return $this->buildURL($target, '', $params);
    }

    function getPageTemplate()
    {
        if (isset($this->pageTemplates[$this->get("page")])) {
            return $this->pageTemplates[$this->get("page")];
        }
        return null;
    }

	/**
	 * Return the array of pages for tabber
     * FIXME - move to the Controller/Admin/Abstract.php:
     *  tabber is not used in customer area
	 * 
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getTabPages()
	{
		return $this->pages;
	}


    function getUploadedFile()
    {
        $file = null;

        if (is_uploaded_file($_FILES["userfile"]['tmp_name'])) {
            $file = $_FILES["userfile"]['tmp_name'];
        } elseif (is_readable($_POST["localfile"])) {
            $file = $_POST["localfile"];
        } else {
            $this->_die("FAILED: data file unspecified");
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            $this->_die("ACCESS DENIED");
        }
        return $file;
    }

    function checkUploadedFile()
    {
        $check = true;

        if (is_uploaded_file($_FILES["userfile"]['tmp_name'])) {
            $file = $_FILES["userfile"]['tmp_name'];
        } elseif (is_readable($_POST["localfile"])) {
            $file = $_POST["localfile"];
        } else {
            return false;
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            return false;
        }

        return $check;
    }

	function getCharset()
	{
		$charset = $this->getComplex('cart.profile.billingCountry.charset');
		if ($charset)
			return $charset;

		if ($this->auth->isLogged()) {
			$profile = $this->auth->get("profile");
			return $profile->getComplex('billingCountry.charset');
		} else {
			$country = $this->config->getComplex('General.default_country');
			$obj = new XLite_Model_Country($country);
			return ($obj->get("charset")) ? $obj->get("charset") : "iso-8859-1";
		}
	}

	function getEmailValidatorRegExp()
	{
		$values = array();
		$domains = split(",| |;|\||\/", $this->config->getComplex('Email.valid_email_domains'));
		foreach ((array)$domains as $key=>$val) {
			if (!trim($val))
				continue;

			$values[$key] = "(\.".trim($val).")";
		}

		if (count($values) <= 0) {
			$values[] = "(\..{2,3})";
		}

		return "/\b(^(\S+@).+(".implode("|", $values).")$)\b/gi";
	}

    function isSecure()
    {
        return false;
    }
    
    function strftime($format)
    {
        return strftime($format);
    }

    function rand()
    {
        return rand();
    }

	function filterXliteFormID($url)
	{
		if (preg_match("/(\?|&)(xlite_form_id=[a-zA-Z0-9]+)(&.+)?$/", $url, $matches)) {
			if ($matches[1] == '&') $param = $matches[1].$matches[2];
			elseif (empty($matches[3])) $param = $matches[1].$matches[2];
			else $param = $matches[2]."&";
			$url = str_replace($param, "", $url);
		}
		return $url;
	}

    function checkHtaccess()
    {
        if($this->getComplex('config.Security.htaccess_protection') == "Y"){
            $htaccess = new XLite_Model_Htaccess();
            $htaccess->checkFiles();
        }
    }

    /**
     * Define page type parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function definePageTypeParams()
    {
        $this->pageTypeParams = array();
    }

    /**
     * Return page type parameters list
     *
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getPageTypeParams()
    {
        if (is_null($this->pageTypeParams)) {
            $this->definePageTypeParams();
        }

        return $this->pageTypeParams;
    }

    /**
     * Check passed attributes
     *
     * @param array $attributes attributes to check
     *
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validatePageTypeAttributes(array $attrs)
    {
        $messages = array();

        foreach ($this->getPageTypeParams() as $name => $param) {

            if (isset($attrs[$name])) {
                list($result, $widgetErrors) = $param->validate($attrs[$name]);

                if (false === $result) {
                    $messages[] = $param->label . ': ' . implode('<br />' . $param->label . ': ', $widgetErrors);
                }

            } else {
                $messages[] = $param->label . ': is not set';
            }
        }

        return $messages;
    }

    /**
     * Check - page instance visible or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPageInstanceVisible()
    {
        return false;
    }

	/**
	 * Get page instance data (name and URL)
	 * 
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getPageInstanceData()
	{
		return array($this->getPageTypeName(), $this->getUrl());
	}

    /**
     * Get page type name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeName()
    {
        return null;
    }

}


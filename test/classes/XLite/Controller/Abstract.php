<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class Dialog_
*
* @package Base
* @access public
* @version $Id$
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
	 * Check if current page is accessible
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function checkAccess()
	{
		return $this->auth->isAuthorized($this);
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
			$location = $this->shopURL($location, $this->get('secure'));
		}

		$code = 302;
    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$code = 278;
    	}

        header('Location: ' . $location, true, $code);
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
	public function getViewer()
	{
		$attrs = array();

		foreach (array('silent', 'dumpStarted') as $attr) {
			$attrs[$attr] = $this->is($attr);
		}

		return new XLite_View_Controller($attrs, $this->template);
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
	 * getAllParams 
	 * 
	 * @param mixed $exeptions ____param_comment____
	 *  
	 * @return array
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getAllParams($exeptions = null)
    {
        $result = array();
		$exeptions = isset($exeptions) ? explode(",", $exeptions) : null;

        foreach ($this->get('params') as $name) {
			$value = $this->get($name);
            if (isset($value) && !(isset($exeptions) && in_array($name, $exeptions))) {
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
		return XLite_Model_CachingFactory::getObject('XLite_Model_Category', $this->get('category_id'));
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
		return XLite_Model_CachingFactory::getObject('XLite_Model_Product', $this->get('product_id'));
    }



    public $params = array('target');	
    public $dumpStarted = false; // startDump was called	
    public $locationPath = array(); // path for dialog location

	protected $silent = false;

	protected $pageTemplates = array();

	protected $returnUrlAbsolute = false;

	protected $product = null;

	protected $category = null;

	public $cart = null;

	/**
	 * Page type parameters
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $pageTypeParams = null;

	protected function getReturnUrl()
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

    /*function output()
    {
        $this->xlite->profiler->log("request_time");
        if (!$this->silent) {
            ob_start();
            $this->startPage();
            $this->display();
            ob_end_flush();
        }
        if ($this->dumpStarted) {
            func_refresh_end();
        }
    }*/
    
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

    /*function redirect($url = null)
	{
		$location = is_null($returnUrl = $this->getReturnUrl()) ? (is_null($url) ? $this->getUrl() : $url) : $returnUrl; 

		// filter xlite_form_id from redirect url
		$action = $this->get('action');
		if (empty($action))
		    $location = $this->filterXliteFormID($location);

        XLite_Model_Profiler::getInstance()->enabled = false;

		header('Location: ' . ($this->returnUrlAbsolute ? $this->shopURL($location, $this->get('secure')) : $location));
    }*/

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
		if (empty($params)) {
            $params = $this->getAllParams();
        }

		$target = isset($params['target']) ? $params['target'] : '';
		unset($params['target']);

		return $this->buildURL($target, '', $params);
    }

    public function getLoginURL()
	{
		return $this->shopUrl($this->getComplex('xlite.script'));
	}

    function getPageTemplate()
    {
        if (isset($this->pageTemplates[$this->get("page")])) {
            return $this->pageTemplates[$this->get("page")];
        }
        return null;
    }
    
    /**
    * Recalculates the shopping cart.
    */
    function updateCart()
    {
		if (!is_null($this->cart)) {
	        $items = $this->cart->get("items");
			$this->set("absence_of_product", null);
    	    foreach ($items as $key => $i) {
        	    if(!$i->isValid()) {
            		$this->set("absence_of_product", true);
            		$this->redirect($this->buildURL('cart'));
	        		return;
    	        }
	        }
        	if ($this->cart->isPersistent) {
    	        $this->cart->calcTotals();
        	    $this->cart->update();
	        }
		}
    }

    function recalcCart()
    {
        if (!$this->cart->get("empty")) {
        	$this->cart->recalcItems();
            $this->cart->calcTotal();
            $this->cart->update();

    		$this->set("absence_of_product", null);
        	$items = $this->cart->get("items");
            foreach ($items as $key => $i) {
                if(!$i->isValid()) {
                	$this->set("absence_of_product", true);
                	$this->redirect($this->buildURL('cart'));
                	return;
                }
            }
        }
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
    * Get the full URL of the page.
    * Example: shopURL("cart.php") = "http://domain/dir/cart.php
    */
    function shopURL($url, $secure = false, $pure_url = false)
    {
        return XLite::getInstance()->shopURL($url, $secure);
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


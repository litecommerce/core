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
     * Controller main params
     */

    const PARAM_TARGET = 'target';
    const PARAM_ACTION = 'action';

    /**
     * Controller params
     * FIXME - must be moved to the low-level controllers
     */

    const PARAM_CATEGORY_ID = 'category_id';
    const PARAM_PRODUCT_ID  = 'product_id';


    /**
     * Breadcrumbs 
     * 
     * @var    XLite_Model_LocationPath
     * @access protected
     * @since  3.0.0
     */
    protected $locationPath = null;

    /**
     * Internal redirect flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $internalRedirect = false;

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
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
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
     * @since  3.0.0
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
            $location = $this->getShopUrl($location, $this->getSsecure());
        }

        $code = 302;
        if (XLite_Core_Request::getInstance()->isAJAX()) {
            $code = $this->internalRedirect ? 279 : 278;
        }

        header('Location: ' . $location, true, $code);
    }

    /**
     * Get secure controller status
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSecure()
    {
        return false;
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return null;
    }

    /**
     * getRegularTemplate 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        return 'main.tpl';
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
     * getViewerTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getViewerTemplate()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED) ? $this->getCMSTemplate() : $this->getRegularTemplate();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TARGET => new XLite_Model_WidgetParam_String('Target', null),
            self::PARAM_ACTION => new XLite_Model_WidgetParam_String('Action', null),
        );

        $this->widgetParams += array(
            self::PARAM_CATEGORY_ID => new XLite_Model_WidgetParam_ObjectId_Category('Category Id', 0),
            self::PARAM_PRODUCT_ID  => new XLite_Model_WidgetParam_ObjectId_Product('Product Id', 0),
        );
    }

    /**
     * getTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTarget()
    {
        return $this->getParam(self::PARAM_TARGET);
    }

    /**
     * getAction 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getAction()
    {
        return $this->getParam(self::PARAM_ACTION);
    }

    /**
     * getCategoryId
     * FIXME - must be moved to the low-level controllers
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        return $this->getParam(self::PARAM_CATEGORY_ID);
    }

    /**
     * getProductId
     * FIXME - must be moved to the low-level controllers
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getProductId()
    {
        return $this->getParam(self::PARAM_PRODUCT_ID);
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
     * @since  3.0.0
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
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if (!$this->checkAccess()) {

            $this->markAsAccessDenied();

        } elseif (!empty(XLite_Core_Request::getInstance()->action) && $this->isValid()) {

            $oldMethodName = 'action_' . XLite_Core_Request::getInstance()->action;
            $newMethodName = 'doAction' . preg_replace('/_([a-z])/Sse', 'strtoupper("\1")', '_' . XLite_Core_Request::getInstance()->action);
            if (method_exists($this, $oldMethodName)) {
                $this->$oldMethodName();

            } elseif (method_exists($this, $newMethodName)) {
                $this->$newMethodName();
            }
        }

        if (XLite_Core_Request::getInstance()->isPost() && $this->isValid() && !$this->silent) {
            $this->redirect();
        }
    }

    /**
     * Mark controller run thread as access denied
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function markAsAccessDenied()
    {
        $this->params = array('target');
        $this->set('target', 'access_denied');
        XLite_Core_Request::getInstance()->target = 'access_denied';
    }

    /**
     * Return Viewer object
     * 
     * @return XLite_View_Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer()
    {
        $params = array();

        foreach (array(self::PARAM_SILENT, self::PARAM_DUMP_STARTED) as $name) {
            $params[$name] = $this->get($name);
        }

        return new XLite_View_Controller($params, $this->getViewerTemplate());
    }

    /**
     * This function called after template output
     * FIXME - may be there is a better way to handle this?
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function postprocess()
    {
    }

	/**
	 * Get controlelr parameters
     * TODO - check this method
     * FIXME - backward compatibility
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
     * FIXME - must be moved to the low-level controllers
	 * 
	 * @return XLite_Model_Category
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getCategory()
    {
		return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Category', array($this->getCategoryId()));
    }

	/**
	 * Return current (or default) product object
     * FIXME - must be moved to the low-level controllers
	 * 
	 * @return XLite_Model_Product
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getProduct()
    {
		return XLite_Model_CachingFactory::getObject(__METHOD__, 'XLite_Model_Product', array($this->getProductId()));
    }

    /**
     * Return current page title
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return null;
    }


    // TODO - all of the above should be revised


    protected $params = array('target');    

    protected $pageTemplates = array();

    protected $returnUrlAbsolute = false;

    /**
     * Validity flag
     * TODO - check where it's really needed
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $valid = true;

    /**
     * Check if handler is valid 
     * TODO - check where it's really needed
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isValid()
    {
        return $this->valid;
    } 

    /**
     * Initialize controller
     * FIXME - backward compatibility; to delete
     *
     * @return void
     * @access public
     * @since  3.0.0
     */ 
    public function init()
    {
        parent::init();

        $this->fillForm();
    }

    /**
     * FIXME - backward compatibility; to delete
     * 
     * @param mixed $request ____param_comment____
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function mapRequest($request = null)
    {   
    }   
        
    /** 
     * FIXME - backward compatibility; to delete 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function fillForm()
    {
    }

    /**
     * Get return URL
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
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
     * tabber is not used in customer area
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
            $this->doDie("FAILED: data file unspecified");
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            $this->doDie("ACCESS DENIED");
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

    /**
     * Get controller charset 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCharset()
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
}


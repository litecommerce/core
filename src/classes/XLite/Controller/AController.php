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

namespace XLite\Controller;

define('EMAIL_REGEXP', '(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])');

/**
 * Abstract controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AController extends \XLite\Core\Handler
{
    /**
     * Controller main params
     */

    const PARAM_TARGET = 'target';
    const PARAM_ACTION = 'action';

    const PARAM_REDIRECT_CODE = 'redirectCode';

    /**
     * Request param to pass URLs to return
     */
    const RETURN_URL = 'returnURL';


    /**
     * Object to keep action status
     * 
     * @var    \XLite\Model\ActionError\Abstract
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $actionStatus;

    /**
     * Breadcrumbs 
     * 
     * @var    \XLite\View\Location
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $locationPath;

    /**
     * returnUrl 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $returnUrl;


    /**
     * Check if current page is accessible
     *
     * @return boolean 
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return \XLite\Core\Auth::getInstance()->isAuthorized($this);
    }

    /**
     * Return default redirect code 
     * 
     * @return integer 
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultRedirectCode()
    {
        return \XLite\Core\Request::getInstance()->isAJAX() ? 200 : 302;
    }

    /**
     * Default URL to redirect
     * 
     * @return string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function getDefaultReturnURL()
    {
        return null;
    }

    /**
     * Perform redirect 
     * 
     * @param string $url Redirect URL OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function redirect($url = null)
    {
        $location = $this->getReturnUrl();

        if (!isset($location)) {
            $location = isset($url) ? $url : $this->getUrl();
        }

        // filter xlite_form_id from redirect url
        // FIXME - check if it's really needed
        $action = $this->get('action');
        if (empty($action)) {
            $location = $this->filterXliteFormID($location);
        }

        \XLite\Core\Event::getInstance()->display();
        \XLite\Core\Event::getInstance()->clear();

        \XLite\Core\Operator::redirect(
            $location,
            $this->getRedirectMode(),
            $this->getParam(self::PARAM_REDIRECT_CODE)
        );
    }

    /**
     * Get redirect mode - force redirect or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRedirectMode()
    {
        return false;
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
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return null;
    }

    /**
     * Add part to the location nodes list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
    }

    /**
     * Add node to the location line
     * 
     * @param string $name     Node title
     * @param string $link     Node link OPTIONAL
     * @param array  $subnodes Node subnodes
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addLocationNode($name, $link = null, array $subnodes = null)
    {
        $this->locationPath[] = \XLite\View\Location\Node::create($name, $link, $subnodes);
    }

    /**
     * Method to create the location line
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLocationPath()
    {
        $this->locationPath = array();

        // Common element for all location lines
        $this->addLocationNode('Home', $this->buildURL());

        // Ability to add part to the line
        $this->addBaseLocation();

        // Ability to define last element in path via short function
        $params = (array) $this->getLocation();

        if ($params) {
            call_user_func_array(array('static', 'addLocationNode'), $params);
        }
    }

    /**
     * Select template to use
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewerTemplate()
    {
        return 'main.tpl';
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
            self::PARAM_REDIRECT_CODE => new \XLite\Model\WidgetParam\Int('Redirect code', $this->getDefaultRedirectCode()),
        );
    }

    /**
     * Class name for the \XLite\View\Model\ form (optional)
     * 
     * @return string|void
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return null;
    }

    /**
     * Return model form object
     * 
     * @param array $params Form constructor params
     *  
     * @return \XLite\View\Model\AModel|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelForm(array $params = array())
    {
        $result = null;
        $class  = $this->getModelFormClass();

        if (isset($class)) {
            $result = \XLite\Model\CachingFactory::getObject(
                __METHOD__ . $class . (empty($params) ? '' : md5(serialize($params))),
                $class,
                $params
            );
        }

        return $result;
    }

    /**
     * Perform some actions before redirect
     *
     * @param string|null $action Performed action
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocess($action)
    {
        if (isset($action)) {
            $method = __FUNCTION__ . \XLite\Core\Converter::convertToCamelCase($action);
            if (method_exists($this, $method)) {
                // Call action method
                $this->$method();
            }
        }
    }


    /**
     * isRedirectNeeded
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function isRedirectNeeded()
    {
        return (\XLite\Core\Request::getInstance()->isPost() || $this->getReturnUrl()) && !$this->silent;
    }

    /**
     * Get target
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTarget()
    {
        return \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Get action
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getAction()
    {
        return \XLite\Core\Request::getInstance()->action;
    }

    /**
     * Get the full URL of the page
     * Example: getShopUrl('cart.php') = "http://domain/dir/cart.php 
     * 
     * @param string $url    Relative URL  
     * @param boolean   $secure Flag to use HTTPS OPTIONAL
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getShopUrl($url, $secure = false)
    {
        return \XLite::getInstance()->getShopUrl($url, $secure);
    }

    /**
     * Return current location path
     *
     * @return \XLite\View\Location
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLocationPath()
    {
        if (!isset($this->locationPath)) {
            $this->defineLocationPath();
        }

        return $this->locationPath;
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
        if (!isset($this->returnUrl)) {
            $this->returnUrl = \XLite\Core\Request::getInstance()->{self::RETURN_URL};
        }

        return $this->returnUrl;
    }

    /**
     * Set return URL
     *
     * @param string $url URL to set
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setReturnUrl($url)
    {
        $this->returnUrl = $url;
    }

    /**
     * Get current URL with additional params
     * 
     * @param array $params Query params to use
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function setReturnUrlParams(array $params)
    {
        return $this->setReturnUrl($this->buildURL($this->getTarget(), '', $params));
    }

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function 
     * FIXME - simplify
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if (!$this->checkAccess()) {

            $this->markAsAccessDenied();

        } elseif (!empty(\XLite\Core\Request::getInstance()->action) && $this->isValid()) {

            $action = \XLite\Core\Request::getInstance()->action;

            $oldMethodName = 'action_' . $action;
            $newMethodName = 'doAction' . \XLite\Core\Converter::convertToCamelCase($action);

            if (method_exists($this, $oldMethodName)) {
                $this->$oldMethodName();

            } elseif (method_exists($this, $newMethodName)) {
                $this->$newMethodName();
            }

            $this->actionPostprocess($action);

        } else {
            $this->doNoAction();
        }

        if ($this->isRedirectNeeded()) {
            if (\XLite\Core\Request::getInstance()->isAJAX()) {

                foreach (\XLite\Core\TopMessage::getInstance()->getMessages() as $message) {
                    $encodedMessage = json_encode(
                        array(
                            'type'    => $message[\XLite\Core\TopMessage::FIELD_TYPE],
                            'message' => $message[\XLite\Core\TopMessage::FIELD_TEXT],
                        )
                    );
                    header('event-message: ' . $encodedMessage);
                }
                \XLite\Core\TopMessage::getInstance()->clear();

                if (!$this->isValid()) {

                    // AXAX-based - cancel redirect
                    header('ajax-response-status: 0');
                    header('not-valid: 1');

                } elseif ($this->internalRedirect) {

                    // Popup internal redirect
                    header('ajax-response-status: 279');

                } elseif ($this->silenceClose) {

                    // Popup silence close
                    header('ajax-response-status: 277');

                } else {
                    header('ajax-response-status: 270');
                }
            }

            $this->redirect();
        }
    }

    /**
     * Preprocessor for no-action ren
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doNoAction()
    {
    }

    /**
     * Set internal popup redirect 
     * 
     * @param boolean $flag Internal redirect status OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setInternalRedirect($flag = true)
    {
        if (\XLite\Core\Request::getInstance()->isAJAX()) {
            $this->internalRedirect = (bool)$flag;
        }
    }

    /**
     * Set silence close popup
     * 
     * @param boolean $flag Silence close status OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setSilenceClose($flag = true)
    {
        if (\XLite\Core\Request::getInstance()->isAJAX()) {
            $this->silenceClose = (bool)$flag;
        }
    }

    /**
     * Return Viewer object
     * 
     * @return \XLite\View\Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer()
    {
        $params = array();

        foreach (array(self::PARAM_SILENT, self::PARAM_DUMP_STARTED) as $name) {
            $params[$name] = $this->get($name);
        }

        if (
            \XLite\Core\Request::getInstance()->isAJAX()
            && \XLite\Core\Request::getInstance()->widget
        ) {

            $viewer = $this->getAJAXViewer();

        } else {        
            $viewer = new \XLite\View\Controller($params, $this->getViewerTemplate());
        }

        return $viewer;
    }

    /**
     * Get AJAX-called viewer 
     * 
     * @return \XLite\View\AView
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAJAXViewer()
    {
        $params = array();

        foreach (array(self::PARAM_SILENT, self::PARAM_DUMP_STARTED) as $name) {
            $params[$name] = $this->get($name);
        }

        $class = \XLite\Core\Request::getInstance()->widget;

        return new $class($params, $this->getViewerTemplate());
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
     * Return the current page title (for the content area)
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return null;
    }

    /**
     * Check whether the title is to be displayed in the content area 
     * 
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function isTitleVisible()
    {
        return true;
    }

    /**
     * Return the page title (for the <title> tag)
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getPageTitle()
    {
        return $this->getTitle();
    }

    /**
     * Check if an error occured
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function isActionError()
    {
        return isset($this->actionStatus) && $this->actionStatus->isError();
    }

    /**
     * setActionStatus 
     * 
     * @param integer    $status  Error/success
     * @param string $message Status info OPTIONAL
     * @param integer    $code    Status code OPTIONAL
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setActionStatus($status, $message = '', $code = 0)
    {
        $this->actionStatus = new \XLite\Model\ActionStatus($status, $message, $code);
    }

    /**
     * setActionError 
     * 
     * @param string $message Status info  OPTIONAL
     * @param integer    $code    Status code OPTIONAL
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setActionError($message = '', $code = 0)
    {
        $this->setActionStatus(\XLite\Model\ActionStatus::STATUS_ERROR, $message, $code);
    }

    /**
     * setActionSuccess
     *
     * @param string $message Status info OPTIONAL
     * @param integer    $code    Status code OPTIONAL
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setActionSuccess($message = '', $code = 0)
    {
        $this->setActionStatus(\XLite\Model\ActionStatus::STATUS_SUCCESS, $message, $code);
    }



    // TODO - should be revised

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


    protected $params = array('target');

    protected $pageTemplates = array();

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
     * Get controlelr parameters
     * TODO - check this method
     * FIXME - backward compatibility
     *
     * @param string $exeptions Parameter keys string OPTIONAL
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
        \XLite\Core\Request::getInstance()->target = 'access_denied';
    }

    /**
     * Check if handler is valid 
     * TODO - check where it's really needed
     * 
     * @return boolean 
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
     * @param mixed $request ____param_comment____ OPTIONAL
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

    function _clear_xsid_data()
    {
        unset($_REQUEST[\XLite\Model\Session::SESSION_DEFAULT_NAME]);
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
        return \XLite\Core\Request::getInstance()->isHTTPS();
    }

    function startDownload($filename, $contentType = 'application/force-download')
    {
        @set_time_limit(0);
        header('Content-type: ' . $contentType);
        header('Content-disposition: attachment; filename=' . $filename);
    }

    function startImage()
    {
        header('Content-type: image/gif');
        $this->set('silent', true);
    }

    function startDump()
    {
        @set_time_limit(0);
        $this->set('silent', true);
        if (!isset($_REQUEST['mode']) || $_REQUEST['mode']!="cp") {
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
     * Get access level 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAccessLevel()
    {
        return $this->auth->getCustomerAccessLevel();
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
        if (isset($this->pageTemplates[$this->get('page')])) {
            return $this->pageTemplates[$this->get('page')];
        }
        return null;
    }

    /**
     * Return the array(pages) for tabber
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

        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $file = $_FILES['userfile']['tmp_name'];
        } elseif (is_readable($_POST['localfile'])) {
            $file = $_POST['localfile'];
        } else {
            $this->doDie("FAILED: data file unspecified");
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            $this->doDie('ACCESS DENIED');
        }
        return $file;
    }

    function checkUploadedFile()
    {
        $check = true;

        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $file = $_FILES['userfile']['tmp_name'];
        } elseif (is_readable($_POST['localfile'])) {
            $file = $_POST['localfile'];
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
        return 'UTF-8';
    }

    function getEmailValidatorRegExp()
    {
        $values = array();
        $domains = split(",| |;|\||\/", $this->config->Email->valid_email_domains);
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
        if ('Y' == $this->config->Security->htaccess_protection) {
            $htaccess = new \XLite\Model\Htaccess();
            $htaccess->checkFiles();
        }
    }


    /**
     * Common prefix for editable elements in lists
     *
     * NOTE: this method is requered for the GetWidget and AAdmin classes
     * TODO: after the multiple inheritance should be moved to the AAdmin class
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrefixPostedData()
    {
        return 'postedData';
    }

    /**
     * Common prefix for the "delete" checkboxes in lists
     *
     * NOTE: this method is requered for the GetWidget and AAdmin classes
     * TODO: after the multiple inheritance should be moved to the AAdmin class
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrefixToDelete()
    {
        return 'toDelete';
    }

    /**
     * Get current currency 
     * TODO - rework
     * 
     * @return \XLite\Model\Currency
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentCurrency()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Currency')
            ->find(840);
    }

    /**
     * Return the reserved ID of root category
     *
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRootCategoryId()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getRootCategoryId();
    }

    /**
     * Return current category Id
     *
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryId()
    {
        return intval(\XLite\Core\Request::getInstance()->category_id) ?: $this->getRootCategoryId();
    }
}

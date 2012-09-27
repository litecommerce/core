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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller;

/**
 * Abstract controller
 *
 */
abstract class AController extends \XLite\Core\Handler
{
    /**
     * Name of temporary variable to store time
     * of last request to marketplace
     */
    const MARKETPLACE_LAST_REQUEST_TIME = 'marketplaceLastRequestTime';

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
     * @var \XLite\Model\ActionError\Abstract
     */
    protected $actionStatus;

    /**
     * returnURL
     *
     * @var string
     */
    protected $returnURL;

    /**
     * params
     *
     * @var string
     */
    protected $params = array('target');

    /**
     * Validity flag
     * TODO - check where it's really needed
     *
     * @var boolean
     */
    protected $valid = true;

    /**
     * Hard (main page redict) redirect in AJAX request
     *
     * @var boolean
     */
    protected $hardRedirect = false;

    /**
     * Internal (into popup ) redirect in AJAX request
     *
     * @var boolean
     */
    protected $internalRedirect = false;

    /**
     * Popup silence close in AJAX request
     *
     * @var boolean
     */
    protected $silenceClose = false;

    /**
     * Pure action flag in AJAX request
     * Set to true if the client does not require any action
     *
     * @var boolean
     */
    protected $pureAction = false;

    /**
     * Get target by controller class name
     *
     * @return string
     */
    protected static function getTargetByClassName()
    {
        $parts = explode('\\', get_called_class());

        return \Includes\Utils\Converter::convertFromCamelCase(lcfirst(array_pop($parts)));
    }

    // {{{ Pages

    /**
     * Get current page
     * FIXME: to revise
     *
     * @return string
     */
    public function getPage()
    {
        $page  = $this->page;
        $pages = $this->getPages();

        return $page && isset($pages[$page]) ? $page : key($pages);
    }

    /**
     * getPages
     *
     * @return void
     */
    public function getPages()
    {
        return array();
    }

    /**
     * Return list of page templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        return array();
    }

    // }}}

    /**
     * Get controlelr parameters
     * TODO - check this method
     * FIXME - backward compatibility
     *
     * @param string $exeptions Parameter keys string OPTIONAL
     *
     * @return array
     */
    public function getAllParams($exeptions = null)
    {
        $result = array();

        $exeptions = isset($exeptions) ? explode(',', $exeptions) : false;

        foreach ($this->get('params') as $name) {
            $value = $this->get($name);
            if (isset($value) && (!$exeptions || in_array($name, $exeptions))) {
                $result[$name] = $value;
            }
        }

        return $result;
    }

    /**
     * isRedirectNeeded
     *
     * @return boolean
     */
    public function isRedirectNeeded()
    {
        return (\XLite\Core\Request::getInstance()->isPost() || $this->getReturnURL()) && !$this->silent;
    }

    /**
     * Get target
     *
     * @return string
     */
    public function getTarget()
    {
        return \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return \XLite\Core\Request::getInstance()->action;
    }

    /**
     * Get the full URL of the page
     * Example: getShopURL('cart.php') = "http://domain/dir/cart.php
     *
     * @param string  $url    Relative URL OPTIONAL
     * @param boolean $secure Flag to use HTTPS OPTIONAL
     * @param array   $params Optional URL params OPTIONAL
     *
     * @return string
     */
    public function getShopURL($url = '', $secure = null, array $params = array())
    {
        return \XLite::getInstance()->getShopURL($url, $secure, $params);
    }

    /**
     * Get return URL
     *
     * @return string
     */
    public function getReturnURL()
    {
        if (!isset($this->returnURL)) {
            $this->returnURL = \XLite\Core\Request::getInstance()->{static::RETURN_URL};
        }

        return $this->returnURL;
    }

    /**
     * Set return URL
     *
     * @param string $url URL to set
     *
     * @return void
     */
    public function setReturnURL($url)
    {
        $this->returnURL = $url;
    }

    /**
     * Get current URL with additional params
     *
     * @param array $params Query params to use
     *
     * @return string
     */
    public function setReturnURLParams(array $params)
    {
        return $this->setReturnURL($this->buildURL($this->getTarget(), '', $params));
    }

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function
     *
     * @return void
     */
    public function handleRequest()
    {
        if (!$this->checkAccess()) {
            $this->markAsAccessDenied();

        } elseif (!$this->isVisible()) {
            $this->display404();

        } elseif ($this->needSecure()) {
            $this->redirectToSecure();

        } else {
            $this->run();
        }

        if ($this->isRedirectNeeded()) {
            $this->doRedirect();
        }
    }

    /**
     * Alias: check for an AJAX request
     *
     * @return void
     */
    public function isAJAX()
    {
        return \XLite\Core\Request::getInstance()->isAJAX();
    }

    /**
     * Return Viewer object
     *
     * @return \XLite\View\Controller
     */
    public function getViewer()
    {
        $class = $this->getViewerClass();

        return new $class($this->getViewerParams(), $this->getViewerTemplate());
    }

    /**
     * Process request
     *
     * @return void
     */
    public function processRequest()
    {
        $viewer = $this->getViewer();
        $viewer->init();

        if ($this->isAJAX()) {
            $this->printAJAXOuput($viewer->getContent());

        } else {
            $viewer->display();
        }
    }


    /**
     * This function called after template output
     * FIXME - may be there is a better way to handle this?
     *
     * @return void
     */
    public function postprocess()
    {
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return null;
    }

    /**
     * Check whether the title is to be displayed in the content area
     *
     * @return boolean
     */
    public function isTitleVisible()
    {
        return true;
    }

    /**
     * Return the page title (for the <title> tag)
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->getTitle();
    }

    /**
     * Check if an error occured
     *
     * @return boolean
     */
    public function isActionError()
    {
        return isset($this->actionStatus) && $this->actionStatus->isError();
    }

    /**
     * setActionStatus
     *
     * @param integer $status  Error/success
     * @param string  $message Status info OPTIONAL
     * @param integer $code    Status code OPTIONAL
     *
     * @return void
     */
    public function setActionStatus($status, $message = '', $code = 0)
    {
        $this->actionStatus = new \XLite\Model\ActionStatus($status, $message, $code);
    }

    /**
     * setActionError
     *
     * @param string  $message Status info  OPTIONAL
     * @param integer $code    Status code OPTIONAL
     *
     * @return void
     */
    public function setActionError($message = '', $code = 0)
    {
        $this->setActionStatus(\XLite\Model\ActionStatus::STATUS_ERROR, $message, $code);
    }

    /**
     * setActionSuccess
     *
     * @param string  $message Status info OPTIONAL
     * @param integer $code    Status code OPTIONAL
     *
     * @return void
     */
    public function setActionSuccess($message = '', $code = 0)
    {
        $this->setActionStatus(\XLite\Model\ActionStatus::STATUS_SUCCESS, $message, $code);
    }

    /**
     * Check if handler is valid
     * TODO - check where it's really needed
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Initialize controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Check - is secure connection or not
     *
     * @return boolean
     */
    public function isHTTPS()
    {
        return \XLite\Core\Request::getInstance()->isHTTPS();
    }

    /**
     * Get access level
     *
     * @return integer
     */
    public function getAccessLevel()
    {
        return \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();
    }

    /**
     * getProperties
     *
     * @return void
     */
    public function getProperties()
    {
        $result = array();

        foreach ($_REQUEST as $name => $value) {
            $result[$name] = $this->get($name);
        }

        return $result;
    }

    /**
     * getURL
     *
     * @param array $params URL parameters OPTIONAL
     *
     * @return void
     */
    public function getURL(array $params = array())
    {
        $params = array_merge($this->getAllParams(), $params);
        $target = isset($params['target']) ? $params['target'] : '';
        unset($params['target']);

        return $this->buildURL($target, '', $params);
    }

    /**
     * getPageTemplate
     *
     * @return void
     */
    public function getPageTemplate()
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getPageTemplates(), $this->getPage());
    }

    /**
     * Return the array(pages) for tabber
     * FIXME - move to the Controller/Admin/Abstract.php:
     * tabber is not used in customer area
     *
     * @return array
     */
    public function getTabPages()
    {
        return $this->getPages();
    }

    /**
     * getUploadedFile
     *
     * @return void
     */
    public function getUploadedFile()
    {
        $file = null;

        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $file = $_FILES['userfile']['tmp_name'];

        } elseif (is_readable($_POST['localfile'])) {
            $file = $_POST['localfile'];

        } else {
            $this->doDie('FAILED: data file unspecified');
        }

        // security check
        $name = $_FILES['userfile']['name'];

        if (strstr($name, '../') || strstr($name, '..\\')) {
            $this->doDie('ACCESS DENIED');
        }

        return $file;
    }

    /**
     * checkUploadedFile
     *
     * @return void
     */
    public function checkUploadedFile()
    {
        $check = true;

        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $file = $_FILES['userfile']['tmp_name'];

        } elseif (is_readable($_POST['localfile'])) {
            $file = $_POST['localfile'];

        } else {
            $check = false;
        }

        if ($check) {

            // security check
            $name = $_FILES['userfile']['name'];

            if (strstr($name, '../') || strstr($name, '..\\')) {
                $check = false;
            }
        }

        return $check;
    }

    /**
     * Get controller charset
     *
     * @return string
     */
    public function getCharset()
    {
        return 'utf-8';
    }

    /**
     * isSecure
     *
     * @return boolean
     */
    public function isSecure()
    {
        return false;
    }

    /**
     * Return the reserved ID of root category
     *
     * @return integer
     */
    public function getRootCategoryId()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getRootCategoryId();
    }

    /**
     * Return current category Id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return \XLite\Core\Request::getInstance()->category_id;
    }

    /**
     * Get meta description
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return null;
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return null;
    }

    /**
     * Return model form object
     *
     * @param array $params Form constructor params OPTIONAL
     *
     * @return \XLite\View\Model\AModel|void
     */
    public function getModelForm(array $params = array())
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
     * Check if current page is accessible
     *
     * @return boolean
     */
    protected function checkAccess()
    {
        return \XLite\Core\Auth::getInstance()->isAuthorized($this);
    }

    /**
     * Return default redirect code
     *
     * @return integer
     */
    protected function getDefaultRedirectCode()
    {
        return $this->isAJAX() ? 200 : 302;
    }

    /**
     * Default URL to redirect
     *
     * @return string
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
     */
    protected function redirect($url = null)
    {
        $location = $this->getReturnURL();

        if (!isset($location)) {
            $location = isset($url) ? $url : $this->getURL();
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
            $this->getParam(static::PARAM_REDIRECT_CODE)
        );
    }

    /**
     * Get redirect mode - force redirect or not
     *
     * @return boolean
     */
    protected function getRedirectMode()
    {
        return false;
    }

    /**
     * Select template to use
     *
     * @return string
     */
    protected function getViewerTemplate()
    {
        return 'main.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_REDIRECT_CODE => new \XLite\Model\WidgetParam\Int('Redirect code', $this->getDefaultRedirectCode()),
        );
    }

    /**
     * Class name for the \XLite\View\Model\ form (optional)
     *
     * @return string|void
     */
    protected function getModelFormClass()
    {
        return null;
    }

    /**
     * Perform some actions before redirect
     *
     * @param string $action Performed action
     *
     * @return void
     */
    protected function actionPostprocess($action)
    {
        if (method_exists($this, $method = __FUNCTION__ . \Includes\Utils\Converter::convertToPascalCase($action))) {

            // Call action method
            $this->$method();
        }
    }

    /**
     * Call controller action
     *
     * @return void
     */
    protected function callAction()
    {
        $action = \XLite\Core\Request::getInstance()->action;
        $method = 'doAction' . \Includes\Utils\Converter::convertToPascalCase($action);

        if (method_exists($this, $method)) {
            // Call method doAction<action-name-in-camel-case>
            $this->$method();

        } else {
            \XLite\Logger::getInstance()->log(
                'Handler for the action "' . $action . '" is not defined for the "' . get_class($this) . '" class'
            );
        }

        $this->actionPostprocess($action);
    }

    /**
     * Run controller
     *
     * @return void
     */
    protected function run()
    {
        if (!empty(\XLite\Core\Request::getInstance()->action) && $this->isValid()) {
            $this->callAction();

        } else {
            $this->doNoAction();
        }
    }

    /**
     * Do redirect
     *
     * @return void
     */
    protected function doRedirect()
    {
        if ($this->isAJAX()) {
            $this->translateTopMessagesToHTTPHeaders();
            $this->assignAJAXResponseStatus();
        }

        $this->redirect();
    }

    /**
     * Translate top messages to HTTP headers (AJAX)
     *
     * @return void
     */
    protected function translateTopMessagesToHTTPHeaders()
    {
        foreach (\XLite\Core\TopMessage::getInstance()->getAJAXMessages() as $message) {
            $encodedMessage = json_encode(
                array(
                    'type'    => $message[\XLite\Core\TopMessage::FIELD_TYPE],
                    'message' => $message[\XLite\Core\TopMessage::FIELD_TEXT],
                )
            );
            header('event-message: ' . $encodedMessage);
        }

        \XLite\Core\TopMessage::getInstance()->clearAJAX();
    }

    /**
     * Assign AJAX response status to HTTP header(s)
     *
     * @return void
     */
    protected function assignAJAXResponseStatus()
    {
        if (!$this->isValid()) {

            // AXAX-based - cancel redirect
            header('ajax-response-status: 0');
            header('not-valid: 1');

        } elseif ($this->hardRedirect) {

            // Main page redirect
            header('ajax-response-status: 278');

        } elseif ($this->internalRedirect) {

            // Popup internal redirect
            header('ajax-response-status: 279');

        } elseif ($this->silenceClose) {

            // Popup silence close
            header('ajax-response-status: 277');

        } elseif ($this->pureAction) {

            // Pure action
            header('ajax-response-status: 276');

        } else {
            header('ajax-response-status: 270');
        }
    }

    /**
     * Preprocessor for no-action run
     *
     * @return void
     */
    protected function doNoAction()
    {
    }

    /**
     * Check controller visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return true;
    }

    /**
     * Display 404 page
     *
     * @return void
     */
    protected function display404()
    {
        \XLite\Core\Request::getInstance()->target = \XLite::TARGET_404;
        \XLite\Core\Request::getInstance()->action = '';
    }

    /**
     * Set internal popup redirect
     *
     * @param boolean $flag Internal redirect status OPTIONAL
     *
     * @return void
     */
    protected function setInternalRedirect($flag = true)
    {
        if ($this->isAJAX()) {
            $this->internalRedirect = (bool) $flag;
        }
    }

    /**
     * Set hard (main page redirect) redirect
     *
     * @param boolean $flag Internal redirect status OPTIONAL
     *
     * @return void
     */
    protected function setHardRedirect($flag = true)
    {
        if ($this->isAJAX()) {
            $this->hardRedirect = (bool) $flag;
        }
    }

    /**
     * Set silence close popup
     *
     * @param boolean $flag Silence close status OPTIONAL
     *
     * @return void
     */
    protected function setSilenceClose($flag = true)
    {
        if ($this->isAJAX()) {
            $this->silenceClose = (bool) $flag;
        }
    }

    /**
     * Set pure action flag
     *
     * @param boolean $flag Flag
     *
     * @return void
     */
    protected function setPureAction($flag = false)
    {
        if ($this->isAJAX()) {
            $this->pureAction = (bool) $flag;
        }
    }

    /**
     * Check if current viewer is for an AJAX request
     *
     * @return boolean
     */
    protected function isAJAXViewer()
    {
        return $this->isAJAX() && \XLite\Core\Request::getInstance()->widget;
    }

    /**
     * Return class of current viewer
     *
     * @return string
     */
    protected function getViewerClass()
    {
        return $this->isAJAXViewer()
            ? \XLite\Core\Request::getInstance()->widget
            : '\XLite\View\Controller';
    }

    /**
     * Print AJAX request output
     *
     * @param string $output Output
     *
     * @return void
     */
    protected function printAJAXOuput($output)
    {
        echo ('<h2 class="ajax-title-loadable">' . static::t($this->getTitle()) . '</h2>');
        echo ('<div class="ajax-container-loadable">' . $output . '</div>');
    }

    /**
     * Mark controller run thread as access denied
     *
     * @return void
     */
    protected function markAsAccessDenied()
    {
        $this->params = array('target');
        $this->set('target', 'access_denied');
        \XLite\Core\Request::getInstance()->target = 'access_denied';
    }

    /**
     * startDownload
     *
     * @param string $filename    File name
     * @param string $contentType Content type OPTIONAL
     *
     * @return void
     */
    protected function startDownload($filename, $contentType = 'application/force-download')
    {
        @set_time_limit(0);
        header('Content-type: ' . $contentType);
        header('Content-disposition: attachment; filename=' . $filename);
    }

    /**
     * startImage
     *
     * @return void
     */
    protected function startImage()
    {
        header('Content-type: image/gif');
        $this->set('silent', true);
    }

    /**
     * startDump
     *
     * @return void
     */
    protected function startDump()
    {
        @set_time_limit(0);

        $this->set('silent', true);

        if (!isset(\XLite\Core\Request::getInstance()->mode) || 'cp' != \XLite\Core\Request::getInstance()->mode) {

            func_refresh_start();
            $this->dumpStarted = true;
        }
    }

    /**
     * filterXliteFormID
     *
     * @param mixed $url ____param_comment____
     *
     * @return void
     */
    protected function filterXliteFormID($url)
    {
        if (preg_match('/(\?|&)(xlite_form_id=[a-zA-Z0-9]+)(&.+)?$/', $url, $matches)) {

            if ($matches[1] == '&') {
                $param = $matches[1] . $matches[2];

            } elseif (empty($matches[3])) {
                $param = $matches[1] . $matches[2];

            } else {
                $param = $matches[2] . '&';
            }

            $url = str_replace($param, '', $url);
        }

        return $url;
    }

    /**
     * Get viewer parameters
     *
     * @return array
     */
    protected function getViewerParams()
    {
        $params = array();

        // FIXME: is it really needed?
        foreach (array(static::PARAM_SILENT, static::PARAM_DUMP_STARTED) as $name) {
            $params[$name] = $this->get($name);
        }

        if ($this->isAJAXViewer()) {
            $data = \XLite\Core\Request::getInstance()->getData();

            unset($data['target']);
            unset($data['action']);

            $params += $data;
        }

        return $params;
    }

    /**
     * Get current logged user profile
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfile()
    {
        return \XLite\Core\Auth::getInstance()->getProfile();
    }

    /**
     * Check - need use secure protocol or not
     *
     * @return boolean
     */
    protected function needSecure()
    {
        return $this->isSecure()
            && !\XLite\Core\Request::getInstance()->isHTTPS()
            && !\XLite\Core\Request::getInstance()->isCLI()
            && \XLite\Core\Request::getInstance()->isGet();
    }

    /**
     * Redirect to secure protocol
     *
     * @return void
     */
    protected function redirectToSecure()
    {
        $request = \XLite\Core\Request::getInstance();

        return $this->redirect($this->getShopURL($this->getURL(), true));
    }

    // {{{ Language-related routines

    /**
     * Get current language code
     *
     * @return string
     */
    public function getCurrentLanguage()
    {
        return \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }

    /**
     * Change current language
     *
     * @return void
     */
    protected function doActionChangeLanguage()
    {
        $code = \XLite\Core\Request::getInstance()->language;

        if (!empty($code)) {
            $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode($code);

            if (isset($language) && $language->getEnabled()) {
                \XLite\Core\Session::getInstance()->setLanguage($language->getCode());
            }
        }

        $this->setReturnURL($this->getURL());
    }

    // }}}
}

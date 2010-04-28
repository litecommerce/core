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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_View_Abstract extends XLite_Core_Handler
{
    /**
     * Resource types
     */

    const RESOURCE_JS  = 'js';
    const RESOURCE_CSS = 'css';

    /**
     * Common widget parameter names
     */

    const PARAM_TEMPLATE     = 'template';
    const PARAM_VISIBLE      = 'visible';
    const PARAM_MODE         = 'mode';
    const PARAM_SESSION_CELL = 'sessionCell';


    /**
     * isCloned 
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $isCloned = false;

    /**
     * Widgets resources collector
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected static $resources = array(
        self::RESOURCE_JS  => array(),
        self::RESOURCE_CSS => array(),
    );


    /**
     * "Named" widgets cache
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $namedWidgets = array();

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array();

    /**
     * List of so called "request" params - which take values from request (if passed)
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $requestParams = array(self::PARAM_SESSION_CELL);

    /**
     * Request param values saved in session
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $savedRequestParams = null;

    /**
     * Flag; determines if passed name of session cell is match for the current widget
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected $sessionCellStatus = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultTemplate();


    /**
     * Return full URL by the skindir-related one
     * 
     * @param string $url relative URL
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected static function getSkinURL($url)
    {
        return XLite_Model_Layout::getInstance()->getSkinURL($url);
    }

    /**
     * Prepare resources list
     * 
     * @param mixed $data data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function prepareResources($data)
    {
        return is_array($data) ? array_map(array('self', __FUNCTION__), $data) : self::getSkinURL($data);
    }


    /**
     * Return current template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return $this->getParam(self::PARAM_TEMPLATE);
    }

    /**
     * Return full template file name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplateFile()
    {
        return XLite_Model_Layout::getInstance()->getLayout($this->getTemplate());
    }

    /**
     * Return compiled template file name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDisplayFile()
    {
        return LC_COMPILE_DIR . $this->getTemplateFile() . '.php';
    }

    /**
     * Check if template is up-to-date
     * 
     * @param string $original original template
     * @param string $compiled compiled one
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkTemplateStatus($original, $compiled)
    {
        return file_exists($compiled) && (filemtime($compiled) === filemtime($original));
    }

    /**
     * Return instance of the child widget 
     * 
     * @param string $class child widget class
     *  
     * @return XLite_View_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getChildWidget($class = null)
    {
        return isset($class) ? new $class() : clone $this;
    }

    /**
     * getSessionCell
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getSessionCell()
    {
        return get_class($this);
    }

    /**
     * checkSessionCell 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkSessionCell()
    {
        if (!isset($this->sessionCellStatus)) {

            $paramName  = self::PARAM_SESSION_CELL;
            $paramValue = $this->getWidgetParams(self::PARAM_SESSION_CELL)->value;

            $this->sessionCellStatus = XLite_Core_Request::getInstance()->$paramName == $paramValue;
        }

        return $this->sessionCellStatus;
    }

    /**
     * Return list of the modes allowed by default
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModes()
    {
        return array();
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
            self::PARAM_TEMPLATE     => new XLite_Model_WidgetParam_File('Template', $this->getDefaultTemplate()),
            self::PARAM_VISIBLE      => new XLite_Model_WidgetParam_Bool('Visible', true),
            self::PARAM_MODE         => new XLite_Model_WidgetParam_Array('Modes', $this->getDefaultModes()),
            self::PARAM_SESSION_CELL => new XLite_Model_WidgetParam_String('Session cell', $this->getSessionCell()),
        );
    }

    /**
     * Check if we should try to take widget param value from request
     * 
     * @param string $param param name
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isRequestParam($param)
    {
        return in_array($param, $this->requestParams);
    }

    /**
     * Return widget param value 
     * 
     * @param string $param param to fetch
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getParam($param)
    {
        // Trying to get param value from request (if it's the so called "request"-param)
        $value = $this->isRequestParam($param) ? $this->getRequestParamValue($param) : null;

        return isset($value) ? $value : parent::getParam($param);
    }

    /**
     * Fetch param value from current session
     *
     * @param string $param parameter name
     *
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getSavedRequestParam($param)
    {
        if (!isset($this->savedRequestParams)) {

            // Cache the session cell (variable) associatd with the current widget
            $this->savedRequestParams = XLite_Model_Session::getInstance()->get(
                $this->getWidgetParams(self::PARAM_SESSION_CELL)->value
            );

            // ... To avoid repeated initializations
            if (!$this->savedRequestParams) {
                $this->savedRequestParams = array();
            }
        }

        return isset($this->savedRequestParams[$param]) ? $this->savedRequestParams[$param] : null;
    }

    /**
     * Get the value of the so called "request" params
     * 
     * @param string $param parameter name
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getRequestParamValue($param)
    {
        // Get value from session only if it's not passed in the request, or f it's associated for another widget.
        // For this, we check if "session cell" param is not passed or is not equal to the current one
        $value = $this->checkSessionCell() ? null : $this->getSavedRequestParam($param);

        return isset($value) ? $value : XLite_Core_Request::getInstance()->$param;
    }

    /**
     * Common layout for the widget resources 
     * 
     * @param array $jsResources  list of JS resources
     * @param array $cssResources list of CSS resources
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function getResourcesSchema(array $jsResources = array(), array $cssResources = array())
    {
        return XLite_Core_Converter::getArraySchema(array_keys(self::$resources), array($jsResources, $cssResources));
    }

    /**
     * Register resources of certain type 
     * 
     * @param string $type      resources type
     * @param array  $resources resources to register
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function registerResourcesType($type, array $resources)
    {
        self::$resources[$type] = array_merge(self::$resources[$type], array_diff($resources, self::$resources[$type]));
    }

    /**
     * Register widget resources
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function registerResources()
    {
        foreach ($this->getResources() as $type => $list) {
            $this->registerResourcesType($type, $list);
        }
    }

    /**
     * Check visibility according to the current target
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkTarget()
    {
        return empty($this->allowedTargets)
            || in_array(XLite_Core_Request::getInstance()->target, $this->allowedTargets);
    }

    /**
     * Check if current mode is allowable 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkMode()
    {
        $allowedModes = $this->getParam(self::PARAM_MODE);

        return empty($allowedModes)
            || in_array(XLite_Core_Request::getInstance()->mode, $allowedModes);
    }

    /**
     * Called before the includeCompiledFile()
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function initView()
    {
        // Add widget resources to the static array
        $this->registerResources();

        // Save all "request" parameters in session
        if ($this->checkSessionCell()) {
            XLite_Model_Session::getInstance()->set(
                $this->getParam(self::PARAM_SESSION_CELL),
                $this->getRequestParams()
            );
        }
    }

    /**
     * Called after the includeCompiledFile()
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function closeView()
    {
    }

    /**
     * Compile and display a template
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function includeCompiledFile()
    {
        // Template files: source and compiled
        $original = LC_ROOT_DIR . $this->getTemplateFile();
        $compiled = $this->getDisplayFile();

        // Only compile if some criteria is match
        if (!$this->checkTemplateStatus($original, $compiled)) {

            // Create directory for compiled template (if not exists)
            $dir = dirname($compiled);
            if (!file_exists($dir)) {
                mkdirRecursive($dir, 0755);
            }

            // Save compiled data and checng file access time to prevent repeated compiling
            file_put_contents($compiled, XLite_Core_FlexyCompiler::getInstance()->parse($original));
            touch($compiled, filemtime($original));
        }

        // Execute PHP code from compiled template
        include $compiled;
    }

    /**
     * getRequestParams 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getRequestParams()
    {
        return $this->getParamsHash($this->requestParams);
    }

    /**
     * Return list of widget resources 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function getResources()
    {
        return self::getResourcesSchema(
            $this->prepareResources($this->getJSFiles()),
            $this->prepareResources($this->getCSSFiles())
        );
    }

    /**
     * Return widget object
     *
     * @param array  $params widget params
     * @param string $class  widget class
     * @param string $name   widget class
     *
     * @return XLite_View_Abstract
     * @access public
     * @since  3.0.0
     */
    public function getWidget(array $params = array(), $class = null, $name = null)
    {
        if (isset($name)) {
            // Save object reference in cache if it's not already saved
            if (!isset($this->namedWidgets[$name])) {
                $this->namedWidgets[$name] = $this->getChildWidget($class);
            }
            // Get cached object
            $widget = $this->namedWidgets[$name];

        } else {
            // Create/clone current widget
            $widget = $this->getChildWidget($class);
        }

        // Set param values
        $widget->setWidgetParams($params);

        // Initialize
        $widget->init();

        return $widget;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return $this->getParam(self::PARAM_VISIBLE) && $this->checkTarget() && $this->checkMode(); 
    }

    /**
     * Attempts to display widget using its template 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function display()
    {
        if ($this->isVisible()) {
            if ($this->isCloned) {
                $this->includeCompiledFile();
            } else {
                $this->initView();
                $this->includeCompiledFile();
                $this->closeView();
            }
        }
    }

    /**
     * Return viewer output
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getContent()
    {
        ob_start();
        $this->display();
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }


    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        return array();
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        return array(
            'js/common.js',
            'js/jquery.mousewheel.js',
        );
    }

    /**
     * Return list of all registered resources 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function getRegisteredResources($type = null)
    {
        return isset($type) ? self::$resources[$type] : self::$resources;
    }

    /**
     * Cleanup resources 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function cleanUpResources()
    {
        self::$resources = self::getResourcesSchema();
    }

    /**
     * Check for current target 
     * FIXME - backward compatibility; use the isVisible() instead
     * 
     * @param array $target list of allowed targets
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isDisplayRequired(array $target)
    {
        return in_array(XLite_Core_Request::getInstance()->target, $target);
    }

    /**
     * FIXME - backward compatibility
     *
     * @param string $name property name
     *
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function get($name)
    {
        $value = parent::get($name);

        return isset($value) ? $value : XLite::getController()->get($name);
    }

    /**
     * Use current controller context
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function __get($name)
    {
        $value = parent::__get($name);

        return isset($value) ? $value : XLite::getController()->$name;
    }

    /**
     * Use current controller context
     * 
     * @param string $method method name
     * @param array  $args   call arguments
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array(array(XLite::getController(), $method), $args);
    }

    /**
     * Copy widget params 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __clone()
    {
        foreach ($this->getWidgetParams() as $name => $param) {
            $this->widgetParams[$name] = clone $param;
        }

        $this->isCloned = true;
    }

   

    // ------------------> Routines for templates


    /**
     * Compares two values 
     * 
     * @param mixed $val1 value 1
     * @param mixed $val2 value 2
     * @param mixed $val3 value 3
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSelected($val1, $val2, $val3 = null)
    {
        return isset($val3) ? $val1->get($val2) == $val3 : $val1 == $val2;
    }

    /**
     * Truncates the baseObject property value to specified length 
     * 
     * @param mixed  $baseObject string or object instance to get field value from
     * @param mixed  $field      string length or field to get value
     * @param int    $length     field length to truncate to
     * @param string $etc        string to add to truncated field value
     * @param mixed  $breakWords word wrap flag
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function truncate($baseObject, $field, $length = 0, $etc = '...', $breakWords = false)
    {
        if (is_scalar($baseObject)) {
            $string = $baseObject;
            $length = $field;

        } else {
            $string = $baseObject->get($field);
        }

        if (0 == $length) {

            $string = '';

        } elseif (strlen($string) > $length) {

            $length -= strlen($etc);
            if (!$breakWords) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            }

            $string = substr($string, 0, $length) . $etc;
        }

        return $string;
    }

    /**
     * Format date
     * 
     * @param mixed  $base   string or object instance to get field value from
     * @param string $field  field to get value
     * @param string $format date format
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function date_format($base, $field = null, $format = null)
    {
        if (!isset($format)) {
            $format = $this->config->General->date_format;
        }

        return strftime($format, is_scalar($base) ? $base : $base->get($field));
    }

    /**
     * Format timestamp
     *
     * @param mixed  $base   string or object instance to get field value from
     * @param string $field  field to get value
     * @param string $format date format
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function time_format($base, $field = null, $format = null)
    {
        return $this->date_format(
            $base,
            $field,
            $this->config->General->date_format . ' ' . $this->config->General->time_format
        );
    }

    /**
     * Format price 
     * 
     * @param mixed  $base          string or object instance to get field value from
     * @param string $field         field to get value
     * @param mixed  $thousandDelim thousands separator
     * @param mixed  $decimalDelim  separator for the decimal point
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function price_format($base, $field = '', $thousandDelim = null, $decimalDelim = null)
    {
        if (!isset($thousandDelim)) {
            $thousandDelim = $this->config->General->thousand_delim;
        }

        if (!isset($decimalDelim)) {
            $decimalDelim = $this->config->General->decimal_delim;
        }

        $result = null;

        if (!is_null($base)) {
            $result = sprintf(
                $this->config->General->price_format,
                number_format(is_scalar($base) ? $base : $base->get($field), 2, $decimalDelim, $thousandDelim)
            );
        }

        return $result;
    }

    /**
     * Add slashes 
     * 
     * @param mixed  $base  string or object instance to get field value from
     * @param string $field field to get value
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addSlashes($base, $field = null)
    {
        return addslashes(is_scalar($base) ? $base : $base->get($field));
    }

    /**
     * Check if data is empty 
     * 
     * @param mixed $data data to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isEmpty($data)
    {
        return empty($data);
    }

    /**
     * Split an array into chunks
     * 
     * @param array $array array to split
     * @param int   $count chunks count
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function split(array $array, $count)
    {
        $result = array_chunk($array, $count);

        $lastKey   = count($result) - 1;
        $lastValue = $result[$lastKey];

        $count -= count($lastValue);

        if (0 < $count) {
            $result[$lastKey] = array_merge($lastValue, array_fill(0, $count, null));
        }

        return $result;
    }

    /**
     * Increment
     * 
     * @param int $value value to increment
     * @param int $inc   increment
     *  
     * @return integer
     * @access protected
     * @since  3.0.0
     */
    protected function inc($value, $inc = 1)
    {
        return $value + $inc;
    }

    /**
     * Get random number
     * TODO - rarely used function; probably, should be removed 
     * 
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function rand()
    {
        return rand();
    }

    /**
     * For the "zebra" tables
     * 
     * @param int    $row          row index
     * @param string $oddCSSClass  first CSS class
     * @param string $evenCSSClass second CSS class
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getRowClass($row, $oddCSSClass, $evenCSSClass = null)
    {
        return 0 == ($row % 2) ? $oddCSSClass : $evenCSSClass;
    }

    /**
     * Check if captcha required on the current page
     * 
     * @param string $widgetId page identifier
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isActiveCaptchaPage($widgetId)
    {
        $result = 'Y' == $this->config->Security->captcha_protection_system;

        if ($result) {
            $pages = $this->config->Captcha->active_captcha_pages;
            $result = isset($pages[$widgetId]);
        }

        return $result;
    }
}


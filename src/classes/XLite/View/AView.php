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

namespace XLite\View;

/**
 * Abstract widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AView extends \XLite\Core\Handler
{
    /**
     * Resource types
     */

    const RESOURCE_JS  = 'js';
    const RESOURCE_CSS = 'css';

    /**
     * Common widget parameter names
     */

    const PARAM_TEMPLATE = 'template';
    const PARAM_MODE     = 'mode';


    /**
     *  View list insertation position
     */
    const INSERT_BEFORE = 'before';
    const INSERT_AFTER  = 'after';
    const REPLACE       = 'replace';


    /**
     * Object instance cache
     * FIXME[SINGLETONS] - to remove
     * 
     * @var    \XLite\Core\FlexyCompiler
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $flexy;

    /**
     * Object instance cache
     * FIXME[SINGLETONS] - to remove
     * 
     * @var    \XLite\Model\Layout
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $layout;

    /**
     * Deep count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $countDeep = 0;

    /**
     * Level count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $countLevel = 0;

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
     * View lists (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $viewLists = array();

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getDefaultTemplate();

    /**
     * Prepare resources list
     * 
     * @param array   $data     Data to prepare
     * @param boolean $isCommon Flag to determine how to prepare URL OPTIONAL
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function prepareResources(array $data, $isCommon = false)
    {
        foreach ($data as &$file) {
            $file = static::$layout->{'get' . ($isCommon ? 'Common' : '') . 'SkinURL'}($file);
        }

        return $data;
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
     * @param string $template Template file name (optional) OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTemplateFile($template = null)
    {
        return static::$layout->getLayout($template ?: $this->getTemplate());
    }

    /**
     * Return instance of the child widget 
     * 
     * @param string $class Child widget class OPTIONAL
     *  
     * @return \XLite\View\AView
     * @access protected
     * @since  3.0.0
     */
    protected function getChildWidget($class = null)
    {
        return isset($class) ? new $class() : clone $this;
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
            self::PARAM_TEMPLATE => new \XLite\Model\WidgetParam\File('Template', $this->getDefaultTemplate()),
            self::PARAM_MODE     => new \XLite\Model\WidgetParam\Collection('Modes', $this->getDefaultModes()),
        );
    }

    /**
     * Common layout for the widget resources 
     * 
     * @param array $jsResources  List of JS resources
     * @param array $cssResources List of CSS resources
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function getResourcesSchema(array $jsResources = array(), array $cssResources = array())
    {
        return \XLite\Core\Converter::getArraySchema(array_keys(self::$resources), array($jsResources, $cssResources));
    }

    /**
     * Register resources of certain type 
     * 
     * @param string $type      Resources type
     * @param array  $resources Resources to register
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
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkTarget()
    {
        $targets = static::getAllowedTargets();

        return empty($targets) || $this->isDisplayRequired($targets);
    }

    /**
     * Check if current mode is allowable 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkMode()
    {
        $modes = $this->getParam(self::PARAM_MODE);

        return empty($modes) || $this->isDisplayRequiredForMode($modes);
    }

    /**
     * Called before the includeCompiledFile()
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initView()
    {
        // Add widget resources to the static array
        $this->registerResources();
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
     * Check if widget is visible
     *
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return $this->checkTarget() && $this->checkMode();
    }

    /**
     * Compile and display a template
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function includeCompiledFile($original = null)
    {
        $compiled = static::$flexy->prepare($this->getTemplateFile($original));

        // Execute PHP code from compiled template
        $cnt = \XLite\View\AView::$countDeep++;
        $cntLevel = \XLite\View\AView::$countLevel++;
        $markTemplates = \XLite\Logger::isMarkTemplates();
        $profilerEnabled = \XLite\Core\Profiler::isTemplatesProfilingEnabled();

        if ($profilerEnabled) {
            $timePoint = str_repeat('+', $cntLevel) . '[TPL ' . str_repeat('0', 4 - strlen((string)$cnt)) . $cnt . '] '
                . get_called_class() . ' :: ' . substr($original, strlen(LC_SKINS_DIR));
            \XLite\Core\Profiler::getInstance()->log($timePoint);
        }

        if ($markTemplates) {
            $original = substr($original, strlen(LC_SKINS_DIR));
            $markTplText = get_called_class() . ' : ' . $original . ' (' . $cnt . ')'
                . ($this->viewListName ? ' [\'' . $this->viewListName . '\' list child]' : '');

            echo ('<!-- ' . $markTplText . ' {{{ -->');
        }

        ob_start();
        include $compiled;
        $content = ob_get_contents();
        ob_end_clean();

        echo ($this->postprocessContent($content));

        if ($markTemplates) {
            echo ('<!-- }}} ' . $markTplText . ' -->');
        }

        if ($profilerEnabled) {
            \XLite\Core\Profiler::getInstance()->log($timePoint);
        }

        \XLite\View\AView::$countLevel--;
    }

    /**
     * FIXME - must be removed
     * 
     * @param string $name Param name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequestParamValue($name)
    {
        return \XLite\Core\Request::getInstance()->$name;
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
            array_merge(
                static::prepareResources($this->getCommonFiles(), true), // prepare common JS files
                static::prepareResources($this->getJSFiles()) // prepare JS files specific for the skin + widget
            ),
            static::prepareResources($this->getCSSFiles())
        );
    }

    /**
     * Return widget object
     *
     * @param array  $params Widget params
     * @param string $class  Widget class OPTIONAL
     * @param string $name   Widget class OPTIONAL
     *
     * @return \XLite\View\AView
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
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkVisibility()
    {
        return $this->isCloned || $this->isVisible();
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
        if ($this->checkVisibility()) {
            $this->isCloned ?: $this->initView();
            $this->includeCompiledFile();
            $this->isCloned ?: $this->closeView();
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
        $list = array(
            'ajax.css',
            'css/popup.css',
        );

        if (\XLite\Logger::isMarkTemplates()) {
            $list[] = 'template_debuger.css';
        }

        return $list;
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
        $list = array(
            'js/php.js',
            'js/jquery.mousewheel.js',
        );

        if (\XLite\Logger::isMarkTemplates()) {
            $list[] = 'js/template_debuger.js';
        }

        return $list;
    }

    /**  
     * Register files from common repository
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCommonFiles()
    {    
        $list = array(
            'js/common.js',
            'js/core.js',
            'js/core.controller.js',
            'js/core.loadable.js',
            'js/core.popup.js',
            'js/core.form.js',
        );

        return $list;
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
     * Return list of allowed targets
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        return array();
    }

    /**
     * Check for current target
     * 
     * @param array $targets List of allowed targets
     *  
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayRequired(array $targets)
    {
        return in_array(\XLite\Core\Request::getInstance()->target, $targets);
    }

    /**
     * Check for current mode
     *
     * @param array $modes List of allowed modes
     *
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayRequiredForMode(array $modes)
    {
        return in_array(\XLite\Core\Request::getInstance()->mode, $modes);
    }

    /**
     * Get current language 
     * 
     * @return \XLite\Model\Language
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentLanguage()
    {
        return \XLite\Core\Session::getInstance()->getLanguage();
    }

    /**
     * FIXME - backward compatibility
     *
     * @param string $name Property name
     *
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function get($name)
    {
        $value = parent::get($name);

        return isset($value) ? $value : \XLite::getController()->get($name);
    }

    /**
     * Use current controller context
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function __get($name)
    {
        $value = 'mm' == $name
            ? \XLite\Core\Database::getRepo('\XLite\Model\Module')
            : parent::__get($name);

        return isset($value)
            ? $value
            : \XLite::getController()->$name;
    }

    /**
     * Use current controller context
     * 
     * @param string $method Method name
     * @param array  $args   Call arguments
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array(array(\XLite::getController(), $method), $args);
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
     * concat 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function concat()
    {
        return implode('', func_get_args());
    }

    /**
     * Compares two values 
     * 
     * @param mixed $val1 Value 1
     * @param mixed $val2 Value 2
     * @param mixed $val3 Value 3 OPTIONAL
     *  
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isSelected($val1, $val2, $val3 = null)
    {
        if (isset($val1) && isset($val3)) {

            $method = 'get';

            if ($val1 instanceof \XLite\Model\AEntity) {

                $method .= \XLite\Core\Converter::convertToCamelCase($val2);

            }

            // Get value with get() method and compare it with third value
            $result = $val1->$method() == $val3;

        } else {

            $result = $val1 == $val2;

        }

        return $result;
    }

    /**
     * Truncates the baseObject property value to specified length 
     * 
     * @param mixed  $base       String or object instance to get field value from
     * @param mixed  $field      String length or field to get value
     * @param integer    $length     Field length to truncate to OPTIONAL
     * @param string $etc        String to add to truncated field value OPTIONAL
     * @param mixed  $breakWords Word wrap flag OPTIONAL
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function truncate($base, $field, $length = 0, $etc = '...', $breakWords = false)
    {
        if (is_scalar($base)) {
            $string = $base;
            $length = $field;

        } else {
            if ($base instanceof \XLite\Model\AEntity) {
                $string = $base->{'get' . \XLite\Core\Converter::convertToCamelCase($field)}();
            } else {
                $string = $base->get($field);
            }
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
     * @param mixed  $base   String or object instance to get field value from
     * @param string $field  Field to get value OPTIONAL
     * @param string $format Date format OPTIONAL
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

        if (is_object($base)) {
            $base = $base instanceof \XLite\Model\AEntity
                ? $base->$field
                : $base->get($field);
        }

        return strftime($format, $base);
    }

    /**
     * Format timestamp
     *
     * @param mixed  $base   String or object instance to get field value from
     * @param string $field  Field to get value OPTIONAL
     * @param string $format Date format OPTIONAL
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
            $this->config->General->date_format . ', ' . $this->config->General->time_format
        );
    }

    /**
     * Format price 
     * FIXME - to revise
     * 
     * @param mixed  $base          String or object instance to get field value from
     * @param string $field         Field to get value OPTIONAL
     * @param mixed  $thousandDelim Thousands separator OPTIONAL
     * @param mixed  $decimalDelim  Separator for the decimal point OPTIONAL
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
            if (is_object($base)) {
                if ($base instanceof \XLite\Model\AEntity) {
                    $base = $base->{'get' . \XLite\Core\Converter::convertToCamelCase($field)}();
                } else {
                    $base = $base->get($field);
                }
            }

            $result = sprintf(
                $this->config->General->price_format,
                number_format($base, 2, $decimalDelim, $thousandDelim)
            );
        }

        return $result;
    }

    /**
     * Format price 
     * 
     * @param float                 $value    Price
     * @param \XLite\Model\Currency $currency Currency
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function formatPrice($value, \XLite\Model\Currency $currency = null)
    {
        if (!isset($currency)) {
            $currency = \XLite::getInstance()->getCurrency();
        }

        $symbol = $currency->getSymbol() ?: (strtoupper($currency->getCode()) . ' ');

        return $symbol . $currency->formatValue($value);
    }

    /**
     * Add slashes 
     * 
     * @param mixed  $base  String or object instance to get field value from
     * @param string $field Field to get value OPTIONAL
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
     * @param mixed $data Data to check
     *  
     * @return boolean 
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
     * @param array $array Array to split
     * @param integer   $count Chunks count
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
     * @param integer $value Value to increment
     * @param integer $inc   Increment OPTIONAL
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
     * @return integer 
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
     * @param integer    $row          Row index
     * @param string $oddCSSClass  First CSS class
     * @param string $evenCSSClass Second CSS class OPTIONAL
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
     * @param string $widgetId Page identifier
     *  
     * @return boolean 
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

    /**
     * Get view list 
     * 
     * @param string $list      List name
     * @param array  $arguments List common arguments
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewList($list, array $arguments = array())
    {
        if (!isset($this->viewLists[$list])) {
            $this->viewLists[$list] = $this->defineViewList($list);
        }

        if ($arguments) {
            foreach ($this->viewLists[$list] as $widget) {
                $widget->setWidgetParams($arguments);
            }
        }

        $result = array();
        foreach ($this->viewLists[$list] as $widget) {
            if ($widget->checkVisibility()) {
                $result[] = $widget;
            }
        }

        return $result;
    }

    /**
     * getViewListChildren
     * 
     * @param string $list List name
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewListChildren($list)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ViewList')->findClassList(
            $this->getViewListClass(),
            $list,
            \XLite::isAdminZone() ? \XLite\Model\ViewList::INTERFACE_ADMIN : \XLite\Model\ViewList::INTERFACE_CUSTOMER
        );
    }

    /**
     * addViewListChild 
     * 
     * @param array &$list      list to modify
     * @param array $properties Node properties
     * @param integer   $weight     Node position OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addViewListChild(array &$list, array $properties, $weight = 0) 
    {
        // Search node to insert after
        while ((list($key, $node) = each($list)) && ($node->getWeight() <= $weight));

        // Prepare properties
        $properties['tpl']    = static::$layout->getShortPath() . $properties['tpl'];
        $properties['weight'] = $weight;
        $properties['list']   = $node->getList();

        // Add element to the array
        array_splice($list, $key, 0, array(new \XLite\Model\ViewList($properties)));
    }

    /**
     * Define view list 
     * 
     * @param string $list List name
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineViewList($list)
    {
        $widgets    = array();
        $pathLength = strlen(static::$layout->getShortPath());
        $hash       = array();

        foreach ($this->getViewListChildren($list) as $widget) {

            if ($widget->tpl && isset($hash[$widget->tpl])) {
                continue;
            }

            $w = false;

            if ($widget->child) {

                // List child is widget
                $w = $this->getWidget(
                    array(
                        'viewListClass' => $this->getViewListClass(),
                        'viewListName'  => $list,
                    ),
                    $widget->child
                );

            } elseif ($widget->tpl && 0 === strncmp(static::$layout->getShortPath(), $widget->tpl, $pathLength)) {

                // List child is template
                $w = $this->getWidget(
                    array(
                        'viewListClass' => $this->getViewListClass(),
                        'viewListName'  => $list,
                        'template'      => substr($widget->tpl, $pathLength),
                    )
                );
            }

            if ($w) {
                $widgets[] = $w;
                if ($widget->tpl) {
                    $hash[$widget->tpl] = true;
                }
            }
        }

        return $widgets;
    }

    /**
     * Check - view list is visible or not
     * 
     * @param string $list      List name
     * @param array  $arguments List common arguments
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isViewListVisible($list, array $arguments = array())
    {
        return 0 < count($this->getViewList($list, $arguments));
    }

    /**
     * Get view list class name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewListClass()
    {
        return get_called_class();
    }

    /**
     * Content postprocessing
     * 
     * @param string $content Content
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessContent($content)
    {
        return $content;
    }

    /**
     * Get XPath by content 
     * 
     * @param string $content Content
     *  
     * @return DOMXPath
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getXpathByContent($content)
    {
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        
        return @$dom->loadHTML($content) ? new DOMXPath($dom) : null;
    }

    /**
     * Get view list content 
     * 
     * @param string $list      List name
     * @param array  $arguments List common arguments
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewListContent($list, array $arguments = array())
    {
        ob_start();
        foreach ($this->getViewList($list, $arguments) as $widget) {
            $widget->display();
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Get view list content as nodes list
     * 
     * @param string $list List name
     *  
     * @return DOMNamedNodeMap|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewListContentAsNodes($list)
    {
        $d = new \DOMDocument();
        $content = $this->getViewListContent($list);
        $result = null;
        if ($content && @$d->loadHTML($content)) {
            $result = $d->documentElement->childNodes->item(0)->childNodes;
        }

        return $result;
    }

    /**
     * Insert view list by XPath query
     * 
     * @param string $content        Content
     * @param string $query          XPath query
     * @param string $list           List name
     * @param string $insertPosition Insert position code OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function insertViewListByXpath($content, $query, $list, $insertPosition = self::INSERT_BEFORE)
    {
        $xpath = $this->getXpathByContent($content);
        if ($xpath) {
            $places = $xpath->query($query);
            $patches = $this->getViewListContentAsNodes($list);
            if (0 < $places->length && $patches && 0 < $patches->length) {
                $this->applyXpathPatches($places, $patches, $insertPosition);
                $content = $xpath->document->saveHTML();
            }
        }

        return $content;
    }

    /**
     * Apply XPath-based patches 
     * 
     * @param DOMNamedNodeMap $places         Patch placeholders
     * @param DOMNamedNodeMap $patches        Patches
     * @param string          $baseInsertType Patch insert type
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function applyXpathPatches(\DOMNamedNodeMap $places, \DOMNamedNodeMap $patches, $baseInsertType)
    {
        foreach ($places as $place) {

            $insertType = $baseInsertType;
            foreach ($patches as $node) {
                $node = $node->cloneNode(true);

                if (self::INSERT_BEFORE == $insertType) {

                    // Insert patch node before XPath result node 
                    $place->parentNode->insertBefore($node, $place);

                } elseif (self::INSERT_AFTER == $insertType) {

                    // Insert patch node after XPath result node
                    if ($place->nextSibling) {
                        $place->parentNode->insertBefore($node, $place->nextSibling);
                        $insertType = self::INSERT_BEFORE;
                        $place = $place->nextSibling;

                    } else {
                        $place->parentNode->appendChild($node);
                    }

                } elseif (self::REPLACE == $insertType) {

                    // Replace XPath result node to patch node
                    $place->parentNode->replaceChild($node, $place);

                    if ($node->nextSibling) {
                        $place = $node->nextSibling;
                        $insertType = self::INSERT_BEFORE;

                    } else {
                        $place = $node;
                        $insertType = self::INSERT_AFTER;
                    }
                }
            }
        }
    }

    /**
     * Insert view list by regular expression pattern 
     * 
     * @param string $content Content
     * @param string $pattern Pattern (PCRE)
     * @param string $list    List name
     * @param string $replace Replace pattern OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function insertViewListByPattern($content, $pattern, $list, $replace = '%s')
    {
        return preg_replace(
            $pattern,
            sprintf($replace, $this->getViewListContent($list)),
            $content
        );
    }

    /**
     * Return current list name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return null;
    }

    /**
     * Combines the nested list name from the parent list name and a suffix
     * 
     * @param string $part Suffix to be added to the parent list name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNestedListName($part)
    {
        return (is_null($this->viewListName) ? $this->getListName() : $this->viewListName) . '.' . $part;
    }

    /**
     * Display a nested view list
     * 
     * @param string $part   Suffix that should be appended to the name of a parent list (will be delimited with a dot)
     * @param array  $params Widget params
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function displayListPart($part, array $params = array())
    {
        $this->displayViewListContent($this->getNestedListName($part), $params);
    }

    /**
     * Get a nested view list 
     * 
     * @param string $part      Suffix of the nested list name
     * @param array  $arguments List common arguments
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNestedViewList($part, array $arguments = array())
    {
        return $this->getViewList($this->getNestedListName($part), $arguments);
    }

    /**
     * Display view list content 
     * 
     * @param string $list      List name
     * @param array  $arguments List common arguments
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function displayViewListContent($list, array $arguments = array())
    {
        echo ($this->getViewListContent($list, $arguments));
    }


    /**
     * getNamePostedData 
     * 
     * @param string $field Field name
     * @param integer    $id    Model object ID OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNamePostedData($field, $id = null)
    {
        return $this->getPrefixPostedData() . (isset($id) ? '[' . $id . ']' : '') . '[' . $field . ']';
    }

    /**
     * getNameToDelete
     *
     * @param integer $id Model object ID
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNameToDelete($id)
    {
        return $this->getPrefixToDelete() . '[' . $id . ']';
    }


    /**
     * So called "static constructor".
     *
     * NOTE: do not call the "parent::__constructStatic()" explicitly: it will be called automatically
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __constructStatic()
    {
        static::$flexy  = \XLite\Core\FlexyCompiler::getInstance();
        static::$layout = \XLite\Model\Layout::getInstance();
    }

    /**
     * Display plain array as JS array
     * 
     * @param array $data Plain array
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function displayCommentedData(array $data)
    {
        echo ('<!--' . "\r\n");

        $result = array();

        foreach ($data as $key => $value) {

            $result[] = $key . '-' . $value . ';';

        }

        echo (implode("\r\n", $result));
        echo ("\r\n" . '-->' . "\r\n");
    }
}


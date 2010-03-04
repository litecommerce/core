<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_View_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_View_Abstract extends XLite_Core_Handler
{
    /**
     * Indexes in the "resources" array
     */

    const RESOURCE_JS  = 'js';
    const RESOURCE_CSS = 'css';

    /**
     * Attribute to determines if widget is exported by CMS handler
     */
    const IS_EXPORTED = '____is_exported____';

    /**
     * Internal widget name (sometimes used in templates)
     */
    const WIDGET_NAME = '____widget_name____';


    /**
     * Widgets resources collector
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $resources = array(self::RESOURCE_JS => array(), self::RESOURCE_CSS => array());

    /**
     * Widget template filename
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = null;

	/**
	 * By default, widget is visible
	 * 
	 * @var    bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $visible = true;

	/**
	 * List of named widgets; FIXME - backward compatibility
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $widgets = array();

	/**
	 * Widget params (for exported widgets)
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $widgetParams = null;

    /**
     * Attributes passed to widget
     * 
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $attributes = array(
        self::IS_EXPORTED => false,
        self::WIDGET_NAME => '',
    );

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array();


    /**
     * Return current template 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTemplate()
    {
        return isset($this->attributes['template']) ? $this->attributes['template'] : $this->template;
    }

    /**
     * Return template file base name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTemplateFile()
    {
        return XLite_Model_Layout::getInstance()->getLayout($this->getTemplate());
    }

    /**
     * Return template file full name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
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
	 * @since  3.0.0 EE
	 */
	protected function checkTemplateStatus($original, $compiled)
	{
		return file_exists($compiled) && (filemtime($compiled) == filemtime($original));
	}

	/**
     * Compile and display a template
     *
     * @param string $includeFile template to display
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function includeCompiledFile($includeFile)
    {
        if (is_null($this->getTemplate())) {
            $this->_die("template is not set");
        }

        $templateFile = LC_ROOT_DIR . $this->getTemplateFile();

        if (!$this->checkTemplateStatus($templateFile, $includeFile)) {

            $fc = new XLite_Core_FlexyCompiler();
            $fc->set('source', file_get_contents($templateFile));
            $fc->set('url_rewrite', array('images' => XLite::getInstance()->shopURL(self::getSkinURL('images'))));
            $fc->set('file', $templateFile);

            $file = $this->getDisplayFile();
            $dir  = dirname($file);

            if (!file_exists($dir)) {
                mkdirRecursive($dir, 0755);
            }

            file_put_contents($file, trim($fc->parse()) . "\n");
            touch($file, filemtime($templateFile));
        }

        include $includeFile;
    }

	/**
     * Return widget object; FIXME - backward compatibility
     *
     * @param array  $attrs widget attributes
     * @param string $class widget class
     * @param string $name  widget class
     *
     * @return XLite_View_Abstract
     * @access protected
     * @since  3.0.0 EE
     */
    protected function _getWidget(array $attrs = array(), $class = null, $name = null)
    {
        // Name is the primary for widget selection
        if (isset($name)) {

            // Widget not exists - create and save it in cache
            if (!isset($this->widgets[$name])) {
                $this->$name = $this->widgets[$name] = isset($class) ? new $class() : clone $this;
            }

            // Fetch widget object from cache and set its name
            $widget = $this->widgets[$name];
            $attrs[self::WIDGET_NAME] = $name;

        } else {

            // Do not cache unnamed widgets
            $widget = isset($class) ? new $class() : clone $this;
        }

        // Initialization
		$widget->init($attrs);

        return $widget;
    }

	/**
     * Called before the display()
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function initView()
    {
        $this->setInitialAttributes();
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
        $this->widgetParams = array();
    }

    /**
     * Set properties
     *
     * @param array $attributes params to set
     *  
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */     
    protected function setAttributes(array $attributes)
    {       
        parent::setAttributes($attributes);
    
        foreach ($attributes as $name => $value) {
            if (isset($this->attributes[$name])) {
                $this->attributes[$name] = $value;
            }
        }
    }

    /**
     * Check visibility according to the current target
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkTarget()
    {
        return empty($this->allowedTargets) || in_array(XLite_Core_Request::getInstance()->target, $this->allowedTargets);
    }

    /**
     * Register widget resources
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerResources()
    {
        foreach ($this->getResources() as $type => $list) {
            if (!empty($list)) {
                self::$resources[$type] = array_merge(self::$resources[$type], array_diff($list, self::$resources[$type]));
            }
        }
    }

    /**
     * Return full URL by the skindir-related one
     * 
     * @param string $url relative URL
     *  
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected static function getSkinURL($url)
    {
        return XLite_Model_Layout::getInstance()->getPath() . $url;
    }

    /**
     * Prepare resources list
     * 
     * @param mixed $data data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected static function prepareResources($data)
    {
        return is_array($data) ? array_map(array('self', __FUNCTION__), $data) : self::getSkinURL($data);
    }


	/**
     * FIXME - backward compatibility
     *
     * @param string $name property name
     *
     * @return mixed
     * @access public
     * @since  3.0.0 EE
     */
	public function get($name)
    {
		$value = parent::get($name);

        return isset($value) ? $value : XLite::$controller->get($name);
    }

	/**
	 * Use current controoler context
	 * 
	 * @param string $name property name
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function __get($name)
	{
		$value = parent::__get($name);

		return isset($value) ? $value : XLite::$controller->$name;
	}

	/**
	 * Use current controoler context
	 * 
	 * @param string $method method name
	 * @param array  $args   call arguments
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function __call($method, array $args = array())
    {
		return call_user_func_array(array(XLite::$controller, $method), $args);
    }

    /**
     * Initialize widget
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init(array $attributes = array())
    {
        parent::init();

        foreach ($this->getWidgetParams() as $name => $param) {
            $this->attributes[$name] = $param->value;
        }
        
        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }

        // FIXME - must be removed
        if (isset($attributes['template'])) {
            $this->template = $attributes['template'];
        }
    }

    /**
     * Return certain attribute value (or all attributes)
     * 
     * @param string $name attribute name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0 EE
     */
    public function getAttributes($name = null)
    {
        // FIXME - must return NULL instead of the $this->get('name')
        return isset($name) ? (isset($this->attributes[$name]) ? $this->attributes[$name] : $this->get('name')) : $this->attributes;
    }

    /**
     * Check if widget is visible
     * FIXME - this function must be completely revised
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        $result = $this->visible && $this->checkTarget();

        if ($result && !empty($this->mode) && isset(XLite::$controller)) {
            $result = in_array(XLite::$controller->mode, explode(',', $this->mode));
        }

        return ($result && isset($this->parentWidget)) ? $this->parentWidget->isVisible() : $result;
    }

    /**
     * Attempts to display widget using its template 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function display()
    {
		$this->initView();

        if ($this->isVisible()) {
            $this->registerResources();
            $this->includeCompiledFile($this->getDisplayFile());
        }
    }

    /**
     * Return viewer output
     *
     * @return string
     * @access public
     * @since  3.0.0 EE
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
     * Return widget parameters list
     *
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getWidgetParams()
    {
        if (!isset($this->widgetParams)) {
            $this->defineWidgetParams();
        }

        return $this->widgetParams;
    }

    /**
     * Check passed attributes
     *
     * @param array $attrs attributes to check
     *
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $messages = array();

        foreach ($this->getWidgetParams() as $name => $param) {

            if (isset($attrs[$name])) {
                list($result, $widgetErrors) = $param->validate($attrs[$name]);

                if (false === $result) {
                    $messages[] = $param->label . ': ' . implode('<br />' . $param->label . ': ', $widgetErrors);
                }

            } else {
                $messages[] = $param->label . ': is not set';
            }
        }

        return parent::validateAttributes($attrs) + $messages;
    }

    /**
     * Check for current target 
     * FIXME - this function must be used instead of the isVisible() one
     * 
     * @param array $target list of allowed targets
     *  
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function isDisplayRequired(array $target)
    {
        return in_array(XLite_Core_Request::getInstance()->target, $target);
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
        return array();
    }

    /**
     * getResources 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function getResources()
    {
        return array(
            self::RESOURCE_JS  => $this->prepareResources($this->getJSFiles()),
            self::RESOURCE_CSS => $this->prepareResources($this->getCSSFiles()),
        );
    }

    /**
     * Return list of all registered resources 
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
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
    public static function cleanupResources()
    {
        self::$resources = array(self::RESOURCE_JS => array(), self::RESOURCE_CSS => array());
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
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected function truncate($baseObject, $field, $length = 0, $etc = '...', $breakWords = false)
    {
        
        if (is_scalar($baseObject)) {
            $string = $baseObject;
            $length = $field;
        } else {
        	$string = $baseObject->get($field);
        }

        if ($length == 0) {

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
     * @param mixed  $base  string or object instance to get field value from
     * @param string $field field to get value
     * @param string $format date format
     *  
     * @return string
     * @access protected
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected function time_format($base, $field = null, $format = null)
    {
        return $this->date_format($base, $field, $this->config->General->date_format . ' ' . $this->config->General->time_format);
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
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected function split(array $array, $count)
    {
        $result = array_chunk($array, $count);

        $lastKey   = count($result)-1;
        $lastValue = $result[$lastKey];

        if (0 < ($count = $count - count($lastValue))) {
            $result[$lastKey] = array_merge($lastValue, array_fill(0, $count, null));
        }

        return $result;
    }

    /**
     * Increment
     * 
     * @param int $value value to increment
     * @param int $inc increment
     *  
     * @return void
     * @access protected
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected function rand()
    {
        return rand();
    }

    /**
     * For the "zebra" tables
     * 
     * @param int    $row            row index
     * @param string $odd_css_class  first CSS class
     * @param string $even_css_class second CSS class
     *  
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
	protected function getRowClass($row, $odd_css_class, $even_css_class = null)
	{
		return (0 == ($row % 2)) ? $odd_css_class : $even_css_class;
	}

    /**
     * Check if captcha required on the current page
     * 
     * @param string $widgetId page identifier
     *  
     * @return bool
     * @access protected
     * @since  3.0.0 EE
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
     * Initial set widget attributes
     * TODO - check if it's really needed
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setInitialAttributes()
    {
    }
}


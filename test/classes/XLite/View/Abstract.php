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
     * Attribute to determines if widget is exported by CMS handler
     */
    const IS_EXPORTED = 'is_exported';


    /**
     * Widgets resources collector
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public static $resources = array();

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
    protected $attributes = array(self::IS_EXPORTED => false);


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
	 * Return URL of the skin images folder
	 * 
	 * @return string
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getImagesURL()
	{
		return XLite::getInstance()->shopURL(XLite_Model_Layout::getInstance()->getPath() . 'images');
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
        if (is_null($this->get('template'))) {
            $this->_die("template is not set");
        }

        $templateFile = LC_ROOT_DIR . $this->getTemplateFile();

        if (!$this->checkTemplateStatus($templateFile, $includeFile)) {

            $fc = new XLite_Core_FlexyCompiler();
            $fc->set('source', file_get_contents($templateFile));
            $fc->set('url_rewrite', array('images' => $this->getImagesURL()));
            $fc->set('file', $templateFile);

            $file = $this->getDisplayFile();
            $dir  = dirname($file);

            if (!file_exists($dir)) {
                mkdirRecursive($dir, 0755);
            }

            file_put_contents($file, $fc->parse());
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
        if (isset($name)) {
            if (!isset($this->widgets[$name])) {
                $this->$name = $this->widgets[$name] = isset($class) ? new $class() : clone $this;
            }
            $widget = $this->widgets[$name];
        } else {
            $widget = isset($class) ? new $class() : clone $this;
        }

        if (!empty($attrs)) {
            $widget->setAttributes($attrs);
        }

		$this->init();

        return $widget;
    }

	/**
	 * FIXME - backward compatibility 
	 * 
	 * @return XLite_View_Abstract
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getWidget()
    {
        return $this;
    }

	/**
	 * FIXME - backward compatibility
	 * 
	 * @return XLite_View_Abstract
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getDialog()
    {
        return isset(XLite::$controller) ? XLite::$controller : $this;
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
     * Set widget attributes 
     * 
     * @param array $attributes widget params
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }
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
	 * FIXME - backward compatibility
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
	 * FIXME - backward compatibility 
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
     * Set properties
     *
     * @param array $attributes params to set
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function setAttributes(array $attributes)
    {
        parent::setAttributes($attributes);

        foreach ($attributes as $name => $value) {
            if (isset($this->attributes[$name])) {
                $this->attributes[$name] = $value;
            }
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

        return isset($name) ? 
            (isset($this->attributes[$name]) ? $this->attributes[$name] : $this->get('name')) : $this->attributes;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        $result = $this->visible;

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
     * Return widget parameters list
     *
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getWidgetParams()
    {
        if (is_null($this->widgetParams)) {
            $this->defineWidgetParams();
        }

        return $this->widgetParams;
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
    public function validateAttributes(array $attributes)
    {
        return array();
    }

    /**
     * Check for current target 
     * 
     * @param array $target list of allowed targets
     *  
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function isDisplayRequired(array $target)
    {
        return in_array($this->target, $target);
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
     * Register widget resources 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerResources()
    {
        $local = array(
            'js'  => $this->getJSFiles(),
            'css' => $this->getCSSFiles(),
        );

        foreach ($local as $k => $v) {
            if ($v) {
                if (!isset(self::$resources[$k])) {
                    self::$resources[$k] = array();
                }

                self::$resources[$k] = array_merge(self::$resources[$k], $v);
            }
        }
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


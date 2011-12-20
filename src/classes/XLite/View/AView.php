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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Abstract widget
 *
 * @see   ____class_see____
 * @since 1.0.0
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
    const PARAM_MODES    = 'modes';

    /**
     *  View list insertation position
     */
    const INSERT_BEFORE = 'before';
    const INSERT_AFTER  = 'after';
    const REPLACE       = 'replace';

    /**
     * Deep count
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $countDeep = 0;

    /**
     * Level count
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $countLevel = 0;

    /**
     * Widgets resources collector
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $resources = array();

    /**
     * Templates tail
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $tail = array();

    /**
     * isCloned
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $isCloned = false;

    /**
     * "Named" widgets cache
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $namedWidgets = array();

    /**
     * View lists (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $viewLists = array();

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getDefaultTemplate();


    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        return array();
    }

    /**
     * Get templates tail
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getTail()
    {
        return \XLite\View\AView::$tail;
    }

    /**
     * Use current controller context
     *
     * @param string $name Property name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array(array(\XLite::getController(), $method), $args);
    }

    /**
     * Copy widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __clone()
    {
        foreach ($this->getWidgetParams() as $name => $param) {
            $this->widgetParams[$name] = clone $param;
        }

        $this->isCloned = true;
    }

    /**
     * Return widget object
     *
     * @param array  $params Widget params OPTIONAL
     * @param string $class  Widget class OPTIONAL
     * @param string $name   Widget class OPTIONAL
     *
     * @return \XLite\View\AView
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkVisibility()
    {
        return $this->isCloned || $this->isVisible();
    }

    /**
     * Attempts to display widget using its template
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Check for current target
     *
     * @param array $targets List of allowed targets
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDisplayRequiredForMode(array $modes)
    {
        return in_array(\XLite\Core\Request::getInstance()->mode, $modes);
    }

    /**
     * Get current language
     *
     * @return \XLite\Model\Language
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function get($name)
    {
        $value = parent::get($name);

        return isset($value) ? $value : \XLite::getController()->get($name);
    }


    /**
     * Return current template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTemplate()
    {
        return $this->getParam(self::PARAM_TEMPLATE);
    }

    /**
     * Return full template file name
     *
     * @param string $template         Template file name OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTemplateFile($template = null)
    {
        return \XLite\Singletons::$handler->layout->getTemplateFullPath($template ?: $this->getTemplate());
    }

    /**
     * Return instance of the child widget
     *
     * @param string $class Child widget class OPTIONAL
     *
     * @return \XLite\View\AView
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getChildWidget($class = null)
    {
        return isset($class) ? new $class() : clone $this;
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModes()
    {
        return array();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TEMPLATE => new \XLite\Model\WidgetParam\File('Template', $this->getDefaultTemplate()),
            self::PARAM_MODES    => new \XLite\Model\WidgetParam\Collection('Modes', $this->getDefaultModes()),
        );
    }

    /**
     * Check visibility according to the current target
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkMode()
    {
        $modes = $this->getParam(self::PARAM_MODES);

        return empty($modes) || $this->isDisplayRequiredForMode($modes);
    }

    /**
     * Called before the includeCompiledFile()
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function initView()
    {
        // Add widget resources to the static array
        $this->registerResourcesForCurrentWidget();
    }

    /**
     * Called after the includeCompiledFile()
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function closeView()
    {
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return $this->checkTarget() && $this->checkMode();
    }

    /**
     * Compile and display a template
     *
     * @param string $original Template file name OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function includeCompiledFile($original = null)
    {
        $normalized = $this->getTemplateFile($original);
        $compiled = \XLite\Singletons::$handler->flexy->prepare($normalized);

        // Execute PHP code from compiled template
        $cnt = \XLite\View\AView::$countDeep++;
        $cntLevel = \XLite\View\AView::$countLevel++;
        $markTemplates = \XLite\Logger::isMarkTemplates();
        $profilerEnabled = \XLite\Core\Profiler::isTemplatesProfilingEnabled();

        if ($profilerEnabled) {
            $timePoint = str_repeat('+', $cntLevel) . '[TPL ' . str_repeat('0', 4 - strlen((string)$cnt)) . $cnt . '] '
                . get_called_class() . ' :: ' . substr($original, strlen(LC_DIR_SKINS));
            \XLite\Core\Profiler::getInstance()->log($timePoint);
        }

        if ($markTemplates) {
            $original = substr($original, strlen(LC_DIR_SKINS));
            $markTplText = get_called_class() . ' : ' . $original . ' (' . $cnt . ')'
                . ($this->viewListName ? ' [\'' . $this->viewListName . '\' list child]' : '');

            echo ('<!-- ' . $markTplText . ' {' . '{{ -->');
        }

        \XLite\View\AView::$tail[] = $normalized;

        ob_start();
        include $compiled;
        $content = ob_get_contents();
        ob_end_clean();

        array_pop(\XLite\View\AView::$tail);

        echo ($this->postprocessContent($content));

        if ($markTemplates) {
            echo ('<!-- }}' . '} ' . $markTplText . ' -->');
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestParamValue($name)
    {
        return \XLite\Core\Request::getInstance()->$name;
    }

    // {{{ Resources (CSS and JS)

    /**
     * Return list of all registered resources
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getRegisteredResources($type = null)
    {
        ksort(static::$resources, SORT_NUMERIC);

        return \Includes\Utils\ArrayManager::getIndex(
            call_user_func_array('array_merge_recursive', static::$resources),
            $type
        );
    }

    /**
     * Get list of methods, priorities and interfaces for the resources
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected static function getResourcesSchema()
    {
        return array(
            array('getCommonFiles', 200, \XLite::COMMON_INTERFACE),
            array('getResources', 300, null),
            array('getThemeFiles', \XLite::isAdminZone() ? 100 : 400, null),
        );
    }

    /**
     * Get common schema for an element in the resources list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected static function getResourcesTypeSchema()
    {
        return array(
            static::RESOURCE_JS  => array(),
            static::RESOURCE_CSS => array(),
        );
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        return array();
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        return array();
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonFiles()
    {
        $list = array(
            static::RESOURCE_JS => array(
                'js/jquery.min.js',
                'js/jquery-ui.min.js',
                'js/common.js',
                'js/core.js',
                'js/core.controller.js',
                'js/core.loadable.js',
                'js/core.popup.js',
                'js/core.form.js',
                'js/php.js',
                'js/jquery.mousewheel.js',
                'js/jquery.validationEngine-' . $this->getCurrentLanguage()->getCode() . '.js',
                'js/jquery.validationEngine.js',
            ),
            static::RESOURCE_CSS => array(
                'ui/jquery-ui.css',
                'css/jquery.mousewheel.css',
                'css/validationEngine.jquery.css',
            ),
        );

        if (\XLite\Logger::isMarkTemplates()) {
            $list[static::RESOURCE_JS][]  = 'js/template_debuger.js';
            $list[static::RESOURCE_CSS][] = 'css/template_debuger.css';
        }

        return $list;
    }

    /**
     * Return theme common files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getThemeFiles()
    {
        return array(
            static::RESOURCE_CSS => array(
                'css/style.css',
                'css/ajax.css',
                array('file' => 'css/print.css', 'media' => 'print'),
            ),
        );
    }

    /**
     * Return list of widget resources
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getResources()
    {
        return array(
            static::RESOURCE_JS  => $this->getJSFiles(),
            static::RESOURCE_CSS => $this->getCSSFiles(),
        );
    }

    /**
     * Register widget resources
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function registerResourcesForCurrentWidget()
    {
        foreach ($this->getResourcesSchema() as $data) {
            list($method, $index, $interface) = $data;

            $this->registerResources($this->$method(), $index, $interface);
        }
    }

    /**
     * Common method to register resources
     *
     * @param array    $resources List of resources to register
     * @param initeger $index     Position in list
     * @param string   $interface Interface OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function registerResources(array $resources, $index, $interface = null)
    {
        foreach ($resources as $type => $files) {
            foreach ($files as $data) {

                if (is_string($data)) {
                    $data = array('file' => $data);
                }

                if (!isset(static::$resources[$index][$type][$data['file']])) {
                    static::$resources[$index][$type][$data['file']] = $this->prepareResource($data, $interface);
                }
            }
        }
    }

    /**
     * Common method to register resources
     *
     * @param array  $data      Resource description
     * @param string $interface Interface OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function prepareResource(array $data, $interface = null)
    {
        $shortURL = str_replace(LC_DS, '/', $data['file']);
        $fullURL  = \XLite\Singletons::$handler->layout->getResourceWebPath(
            $shortURL,
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
            $interface
        );

        $data += array(
            'media' => 'all',
            'url'   => $fullURL,
        );

        if (isset($fullURL)) {
            $data['file'] = \XLite\Singletons::$handler->layout->getResourceFullPath($shortURL, $interface, false);
        }

        return $data;
    }

    // }}}
    
    // {{{ Routines for templates

    /**
     * So called "static constructor".
     * NOTE: do not call the "parent::__constructStatic()" explicitly: it will be called automatically
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function __constructStatic()
    {
        foreach (static::getResourcesSchema() as $data) {
            list(, $index, ) = $data;
            static::$resources[$index] = static::getResourcesTypeSchema();
        }
    }

    /**
     * Display view list content
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function displayViewListContent($list, array $arguments = array())
    {
        echo ($this->getViewListContent($list, $arguments));
    }

    /**
     * Display plain array as JS array
     *
     * @param array $data Plain array
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function displayCommentedData(array $data)
    {
        echo ('<!--' . "\r\n" . 'DATACELL' . "\r\n" . json_encode($data) . "\r\n" . '-->' . "\r\n");
    }

    /**
     * Format price
     *
     * @param float                 $value    Price
     * @param \XLite\Model\Currency $currency Currency OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function formatPrice($value, \XLite\Model\Currency $currency = null)
    {
        if (!isset($currency)) {
            $currency = \XLite::getInstance()->getCurrency();
        }

        $symbol = $currency->getSymbol() ?: (strtoupper($currency->getCode()) . ' ');
        $sign   = 0 <= $value ? '' : '&minus;&#8197';

        return $sign . $symbol . $currency->formatValue(abs($value));
    }

    /**
     * Format file size 
     * 
     * @param integer $size Size in bytes
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function formatSize($size)
    {
        if (1024 > $size) {
            $result = $this->t('X bytes', array('value' => $size));

        } elseif (1048576 > $size) {
            $result = $this->t('X kB', array('value' => round($size / 1024, 1)));

        } elseif (1073741824 > $size) {
            $result = $this->t('X MB', array('value' => round($size / 1048576, 1)));

        } else {
            $result = $this->t('X GB', array('value' => round($size / 1073741824, 1)));

        }

        return $result;
    }

    /**
     * Check - view list is visible or not
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isViewListVisible($list, array $arguments = array())
    {
        return 0 < count($this->getViewList($list, $arguments));
    }

    /**
     * Build list item class
     *
     * @param string $listName List name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function buildListItemClass($listName)
    {
        $indexName = $listName . 'ArrayPointer';
        $countName = $listName . 'ArraySize';

        $class = array();

        if (1 == $this->$indexName) {
            $class[] = 'first';
        }

        if ($this->$countName == $this->$indexName) {
            $class[] = 'last';
        }

        return implode(' ', $class);
    }

    /**
     * Prepare human-readable output for file size
     *
     * @param integer $size Size in bytes
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function formatFileSize($size)
    {
        return \XLite\Core\Converter::formatFileSize($size);
    }

    /**
     * concat
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSelected($val1, $val2, $val3 = null)
    {
        if (isset($val1) && isset($val3)) {

            $method = 'get';

            if ($val1 instanceof \XLite\Model\AEntity) {
                $method .= \Includes\Utils\Converter::convertToPascalCase($val2);
            }

            // Get value with get() method and compare it with third value
            $result = $val1->$method() == $val3;

        } else {

            $result = $val1 == $val2;
        }

        return $result;
    }

    /**
     * Helper to get array field values
     *
     * @param array  $array Array to get field value
     * @param string $field Field name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.6
     */
    protected function getArrayField(array $array, $field)
    {
        return \Includes\Utils\ArrayManager::getIndex($array, $field, true);
    }

    /**
     * Helper to get object field values
     *
     * @param object  $object   Object to get field value
     * @param string  $field    Field name
     * @param boolean $isGetter Flag OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getObjectField($object, $field, $isGetter = true)
    {
        return \Includes\Utils\ArrayManager::getObjectField($object, $field, $isGetter);
    }

    /**
     * Truncates the baseObject property value to specified length
     *
     * @param mixed   $base       String or object instance to get field value from
     * @param mixed   $field      String length or field to get value
     * @param integer $length     Field length to truncate to OPTIONAL
     * @param string  $etc        String to add to truncated field value OPTIONAL
     * @param mixed   $breakWords Word wrap flag OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function formatDate($base, $field = null, $format = null)
    {
        if (is_object($base)) {
            $base = $base instanceof \XLite\Model\AEntity
                ? $base->$field
                : $base->get($field);
        }

        return \XLite\Core\Converter::formatDate($base, $format);
    }

    /**
     * Format timestamp
     *
     * @param mixed  $base   String or object instance to get field value from
     * @param string $field  Field to get value OPTIONAL
     * @param string $format Date format OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function formatTime($base, $field = null, $format = null)
    {
        if (is_object($base)) {
            $base = $base instanceof \XLite\Model\AEntity
                ? $base->$field
                : $base->get($field);
        }

        return \XLite\Core\Converter::formatTime($base, $format);
    }

    /**
     * Add slashes
     *
     * @param mixed  $base  String or object instance to get field value from
     * @param string $field Field to get value OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isEmpty($data)
    {
        return empty($data);
    }

    /**
     * Split an array into chunks
     *
     * @param array   $array Array to split
     * @param integer $count Chunks count
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function rand()
    {
        return rand();
    }

    /**
     * For the "zebra" tables
     *
     * @param integer $row          Row index
     * @param string  $oddCSSClass  First CSS class
     * @param string  $evenCSSClass Second CSS class OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRowClass($row, $oddCSSClass, $evenCSSClass = null)
    {
        return 0 == ($row % 2) ? $oddCSSClass : $evenCSSClass;
    }

    /**
     * Get view list
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getViewListChildren($list)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\ViewList')->findClassList(
            $list,
            $this->detectCurrentViewZone()
        );
    }

    /**
     * Detect current view zone
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function detectCurrentViewZone()
    {
        if (\XLite\View\Mailer::isComposeRunned()) {
            $zone = \XLite\Model\ViewList::INTERFACE_MAIL;

        } elseif (\XLite\Core\Request::getInstance()->isCLI()) {
            $zone = \XLite\Model\ViewList::INTERFACE_CONSOLE;

        } elseif (\XLite::isAdminZone()) {
            $zone = \XLite\Model\ViewList::INTERFACE_ADMIN;

        } else {
            $zone = \XLite\Model\ViewList::INTERFACE_CUSTOMER;
        }

        return $zone;
    }

    /**
     * addViewListChild
     *
     * @param array   &$list      List to modify
     * @param array   $properties Node properties
     * @param integer $weight     Node position OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addViewListChild(array &$list, array $properties, $weight = 0)
    {
        // Search node to insert after
        foreach ($list as $key => $node) {
            if ($node->getWeight() > $weight) {
                break;
            }
        }

        // Prepare properties
        $properties['tpl']    = substr(
            \XLite\Singletons::$handler->layout->getResourceFullPath($properties['tpl']),
            strlen(LC_DIR_SKINS)
        );
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineViewList($list)
    {
        $widgets = array();

        foreach ($this->getViewListChildren($list) as $widget) {

            if ($widget->getChild()) {

                // List child is widget
                $widgets[] = $this->getWidget(
                    array(
                        'viewListClass' => $this->getViewListClass(),
                        'viewListName'  => $list,
                    ),
                    $widget->getChild()
                );

            } elseif ($widget->getTpl()) {

                // List child is template
                $widgets[] = $this->getWidget(
                    array(
                        'viewListClass' => $this->getViewListClass(),
                        'viewListName'  => $list,
                        'template'      => $widget->getTpl(),
                    )
                );
            }
        }

        return $widgets;
    }

    /**
     * Get view list class name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return \DOMXPath
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getXpathByContent($content)
    {
        $dom = new \DOMDocument();
        $dom->formatOutput = true;

        return @$dom->loadHTML($content) ? new \DOMXPath($dom) : null;
    }

    /**
     * Get view list content
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return \DOMNamedNodeMap|void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \DOMNamedNodeMap $places         Patch placeholders
     * @param \DOMNamedNodeMap $patches        Patches
     * @param string           $baseInsertType Patch insert type
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Combines the nested list name from the parent list name and a suffix
     *
     * @param string $part Suffix to be added to the parent list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getNestedListName($part)
    {
        return $this->viewListName ? $this->viewListName . '.' . $part : $part;
    }

    /**
     * Display a nested view list
     *
     * @param string $part   Suffix that should be appended to the name of a parent list (will be delimited with a dot)
     * @param array  $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function displayNestedViewListContent($part, array $params = array())
    {
        $this->displayViewListContent($this->getNestedListName($part), $params);
    }

    /**
     * Get a nested view list
     *
     * @param string $part      Suffix of the nested list name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getNestedViewList($part, array $arguments = array())
    {
        return $this->getViewList($this->getNestedListName($part), $arguments);
    }

    /**
     * Return internal list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return null;
    }

    /**
     * Combines the inherited list name from the parent list name and a suffix
     *
     * @param string $part Suffix to be added to the inherited list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInheritedListName($part)
    {
        return $this->getListName() ? $this->getListName() . '.' . $part : $part;
    }

    /**
     * Display a inherited view list
     *
     * @param string $part   Suffix that should be appended to the name of a inherited list (will be delimited with a dot)
     * @param array  $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function displayInheritedViewListContent($part, array $params = array())
    {
        $this->displayViewListContent($this->getInheritedListName($part), $params);
    }

    /**
     * Get a inherited view list
     *
     * @param string $part      Suffix of the inherited list name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInheritedViewList($part, array $arguments = array())
    {
        return $this->getViewList($this->getInheritedListName($part), $arguments);
    }

    /**
     * getNamePostedData
     *
     * @param string  $field Field name
     * @param integer $id    Model object ID OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getNamePostedData($field, $id = null)
    {
        $args = func_get_args();

        $field = $args[0];

        $tail = '';

        if (2 <= count($args)) {

            $id = $args[1];
        }

        if (2 < count($args)) {

            $tail = '[' . implode('][', array_slice($args, 2)) . ']';
        }

        return $this->getPrefixPostedData() . (isset($id) ? '[' . $id . ']' : '') . '[' . $field . ']' . $tail;
    }

    /**
     * getNameToDelete
     *
     * @param integer $id Model object ID
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getNameToDelete($id)
    {
        return $this->getPrefixToDelete() . '[' . $id . ']';
    }

    /**
     * Checks if specific developer mode is defined
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDeveloperMode()
    {
        return LC_DEVELOPER_MODE;
    }

    /**
     * Return currency symbol
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrencySymbol()
    {
        return \XLite::getInstance()->getCurrency()->getSymbol();
    }

    // }}}
}

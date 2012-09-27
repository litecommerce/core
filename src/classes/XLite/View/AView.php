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

namespace XLite\View;

/**
 * Abstract widget
 *
 */
abstract class AView extends \XLite\Core\Handler
{
    /**
     * Resource types
     */
    const RESOURCE_JS   = 'js';
    const RESOURCE_CSS  = 'css';

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
     * Favicon resource short path
     */
    const FAVICON = 'favicon.ico';

    /**
     * Widgets resources collector
     *
     * @var array
     */
    protected static $resources = array();

    /**
     * Widgets meta collector
     *
     * @var array
     */
    protected static $metas = array();

    /**
     * HTML namespaces
     *
     * @var array
     */
    protected static $namespaces = array();

    /**
     * Profiler data
     *
     * @var array
     */
    protected static $profilerInfo;

    /**
     * isCloned
     *
     * @var boolean
     */
    protected $isCloned = false;

    /**
     * "Named" widgets cache
     *
     * @var array
     */
    protected $namedWidgets = array();

    /**
     * View lists (cache)
     *
     * @var array
     */
    protected $viewLists = array();

    /**
     * Return widget default template
     *
     * @return string
     */
    abstract protected function getDefaultTemplate();

    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        return array();
    }

    /**
     * Get templates tail
     *
     * @return array
     */
    public static function getTail()
    {
        return static::$profilerInfo['tail'];
    }

    /**
     * Use current controller context
     *
     * @param string $name Property name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $value = parent::__get($name);

        return isset($value) ? $value : \XLite::getController()->$name;
    }

    /**
     * Use current controller context
     *
     * @param string $method Method name
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     */
    public function __call($method, array $args = array())
    {
        return call_user_func_array(array(\XLite::getController(), $method), $args);
    }

    /**
     * Copy widget params
     *
     * @return void
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
     */
    public function checkVisibility()
    {
        return $this->isCloned || $this->isVisible();
    }

    /**
     * Attempts to display widget using its template
     *
     * @param string $template Template file name OPTIONAL
     *
     * @return void
     */
    public function display($template = null)
    {
        $flag = isset($template);

        if ($flag || $this->checkVisibility()) {
            if (!$this->isCloned && !$flag) {
                $this->initView();
            }

            // Body of the old includeCompiledFile() method
            $normalized = $this->getTemplateFile($template);
            $compiled = \XLite\Singletons::$handler->flexy->prepare($normalized);

            $cnt = static::$profilerInfo['countDeep']++;
            $cntLevel = static::$profilerInfo['countLevel']++;

            if (static::$profilerInfo['isEnabled']) {
                $timePoint = str_repeat('+', $cntLevel) . '[TPL ' . str_repeat('0', 4 - strlen((string)$cnt)) . $cnt . '] '
                    . get_class($this) . ' :: ' . substr($template, strlen(LC_DIR_SKINS));
                \XLite\Core\Profiler::getInstance()->log($timePoint);
            }

            if (static::$profilerInfo['markTemplates']) {
                $template = substr($template, strlen(LC_DIR_SKINS));
                $markTplText = get_class($this) . ' : ' . $template . ' (' . $cnt . ')'
                    . ($this->viewListName ? ' [\'' . $this->viewListName . '\' list child]' : '');

                echo ('<!-- ' . $markTplText . ' {' . '{{ -->');
            }

            static::$profilerInfo['tail'][] = $normalized;

            include $compiled;

            array_pop(static::$profilerInfo['tail']);

            if (static::$profilerInfo['markTemplates']) {
                echo ('<!-- }}' . '} ' . $markTplText . ' -->');
            }

            if (static::$profilerInfo['isEnabled']) {
                \XLite\Core\Profiler::getInstance()->log($timePoint);
            }

            if (!$this->isCloned && !$flag) {
                $this->closeView();
            }

            static::$profilerInfo['countLevel']--;
        }
    }

    /**
     * Return viewer output
     *
     * @return string
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
     */
    public function isDisplayRequiredForMode(array $modes)
    {
        return in_array(\XLite\Core\Request::getInstance()->mode, $modes);
    }

    /**
     * Get current language
     *
     * @return \XLite\Model\Language
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
     */
    protected function getChildWidget($class = null)
    {
        return isset($class) ? new $class() : clone $this;
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     */
    protected function getDefaultModes()
    {
        return array();
    }

    /**
     * Return favicon resource path
     *
     * @return string
     */
    protected function getFavicon()
    {
        return \XLite\Singletons::$handler->layout->getResourceWebPath(
            static::FAVICON,
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
        );
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
            self::PARAM_TEMPLATE => new \XLite\Model\WidgetParam\File('Template', $this->getDefaultTemplate()),
            self::PARAM_MODES    => new \XLite\Model\WidgetParam\Collection('Modes', $this->getDefaultModes()),
        );
    }

    /**
     * Check visibility according to the current target
     *
     * @return boolean
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
     */
    protected function closeView()
    {
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return $this->checkTarget() && $this->checkMode() && $this->checkACL();
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    protected function checkACL()
    {
        return true;
    }

    /**
     * FIXME - must be removed
     *
     * @param string $name Param name
     *
     * @return void
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
     * Return list of all registered meta tags
     *
     * @return array
     */
    public static function getRegisteredMetas()
    {
        return static::$metas;
    }

    /**
     * Return list of all registered namespaces
     *
     * @return array
     */
    public static function getRegisteredNamespaces()
    {
        return static::$namespaces;
    }

    /**
     * Get list of methods, priorities and interfaces for the resources
     *
     * @return array
     */
    protected static function getResourcesSchema()
    {
        return array(
            array('getCommonFiles', 100, \XLite::COMMON_INTERFACE),
            array('getResources', 300, null),
            array('getThemeFiles', 400, null),
        );
    }

    /**
     * Get common schema for an element in the resources list
     *
     * @return array
     */
    protected static function getResourcesTypeSchema()
    {
        return array(
            static::RESOURCE_JS   => array(),
            static::RESOURCE_CSS  => array(),
        );
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        return array();
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        return array();
    }

    /**
     * Register Meta tags
     *
     * @return array
     */
    public function getMetaTags()
    {
        return array();
    }

    /**
     * Register Meta tags
     *
     * @return array
     */
    public function getNamespaces()
    {
        return array();
    }

    /**
     * Register files from common repository
     *
     * @return array
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
                $this->getValidationEngineLanguageResource(),
                'js/validationEngine/jquery.validationEngine.js',
            ),
            static::RESOURCE_CSS => array(
                'ui/jquery-ui.css',
                'css/jquery.mousewheel.css',
                'css/validationEngine.jquery.css',
            ),
        );

        if (static::$profilerInfo['markTemplates']) {
            $list[static::RESOURCE_JS][]  = 'js/template_debuger.js';
            $list[static::RESOURCE_CSS][] = 'css/template_debuger.css';
        }

        return $list;
    }

    /**
     * Return theme common files
     *
     * @return array
     */
    protected function getThemeFiles()
    {
        return \XLite::isAdminZone()
            ? array(
                static::RESOURCE_CSS => array(
                    'css/style.css',
                    'css/ajax.css',
                    array('file' => 'css/print.css', 'media' => 'print'),
                ),
            ) : array(
                static::RESOURCE_CSS => array(
                    'css/common.css',
                    'css/layout.css',
                    'css/theme.css',
                    'css/lc.css',
                    'css/ajax.css',
                    array('file' => 'css/print.css', 'media' => 'print'),
                ),
            );
    }

    /**
     * Return list of widget resources
     *
     * @return void
     */
    protected function getResources()
    {
        return array(
            static::RESOURCE_JS   => $this->getJSFiles(),
            static::RESOURCE_CSS  => $this->getCSSFiles(),
        );
    }

    /**
     * Return resource structure for validation engine language file.
     * By default there are several ready-to-use language files from validationEngine project.
     * The translation module is able to use its own language validation file. It should decorate this method for this case.
     *
     * @return array
     */
    protected function getValidationEngineLanguageResource()
    {
        return array(
            'file' => 'js/validationEngine/languages/jquery.validationEngine-LANGUAGE_CODE.js',
            'filelist' => array(
                $this->getValidationEngineLanguageFile(),
                'js/validationEngine/languages/jquery.validationEngine-en.js',
            ),
        );
    }

    /**
     * Return validation engine language file path.
     * By default there are several ready-to-use language files from validationEngine project.
     * The translation module is able to use its own language validation file. It should decorate this method for this case.
     *
     * @return string
     */
    protected function getValidationEngineLanguageFile()
    {
        return 'js/validationEngine/languages/jquery.validationEngine-' . $this->getCurrentLanguage()->getCode() . '.js';
    }

    /**
     * Register widget resources
     *
     * @return void
     */
    protected function registerResourcesForCurrentWidget()
    {
        foreach ($this->getResourcesSchema() as $data) {
            list($method, $index, $interface) = $data;

            $this->registerResources($this->$method(), $index, $interface);
        }

        $this->registerMetas();
        $this->registerNamespaces();
    }

    /**
     * Common method to register resources
     *
     * @param array    $resources List of resources to register
     * @param initeger $index     Position in list
     * @param string   $interface Interface OPTIONAL
     *
     * @return void
     */
    protected function registerResources(array $resources, $index, $interface = null)
    {
        foreach ($resources as $type => $files) {
            foreach ($files as $data) {
                if (is_string($data)) {

                    $data = array(
                        'file' => $data,
                        'filelist' => array($data),
                    );
                }

                if (!isset($data['filelist'])) {
                    $data['filelist'] = array($data['file']);
                }

                if (!isset(static::$resources[$index][$type][$data['file']])) {
                    static::$resources[$index][$type][$data['file']] = $this->prepareResource($data, $interface);
                }
            }
        }
    }

    /**
     * Register meta data
     *
     * @return void
     */
    protected function registerMetas()
    {
        $meta = $this->getMetaTags();

        if ($meta) {
            static::$metas = array_merge(static::$metas, $meta);
        }
    }

    /**
     * Register meta data
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $data = $this->getNamespaces();

        if ($data) {
            static::$namespaces = array_merge(static::$namespaces, $data);
        }
    }

    /**
     * Common method to register resources
     *
     * @param array  $data      Resource description
     * @param string $interface Interface OPTIONAL
     *
     * @return array
     */
    protected function prepareResource(array $data, $interface = null)
    {
        foreach ($data['filelist'] as $file) {

            $shortURL = str_replace(LC_DS, '/', $file);

            $fullURL  = \XLite\Singletons::$handler->layout->getResourceWebPath(
                $shortURL,
                \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
                $interface
            );

            if (isset($fullURL)) {

                $data['file'] = \XLite\Singletons::$handler
                    ->layout
                    ->getResourceFullPath($shortURL, $interface, false);

                $data += array(
                    'media' => 'all',
                    'url'   => $fullURL,
                );

                break;
            }
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
     */
    public static function __constructStatic()
    {
        foreach (static::getResourcesSchema() as $data) {
            list(, $index, ) = $data;
            static::$resources[$index] = static::getResourcesTypeSchema();
        }

        static::$profilerInfo = array(
            'isEnabled'     => \XLite\Core\Profiler::isTemplatesProfilingEnabled(),
            'markTemplates' => (bool) \XLite::getInstance()->getOptions(array('debug', 'mark_templates')),
            'countDeep'     => 0,
            'countLevel'    => 0,
            'tail'          => array(),
        );
    }

    // {{{ View lists

    /**
     * Display view list content
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return void
     */
    protected function displayViewListContent($list, array $arguments = array())
    {
        foreach ($this->getViewList($list, $arguments) as $widget) {
            $widget->display();
        }
    }

    /**
     * Display a nested view list
     *
     * @param string $part   Suffix that should be appended to the name of a parent list (will be delimited with a dot)
     * @param array  $params Widget params OPTIONAL
     *
     * @return void
     */
    protected function displayNestedViewListContent($part, array $params = array())
    {
        $this->displayViewListContent($this->getNestedListName($part), $params);
    }

    /**
     * Display a inherited view list
     *
     * @param string $part   Suffix that should be appended to the name of a inherited list (will be delimited with a dot)
     * @param array  $params Widget params OPTIONAL
     *
     * @return void
     */
    protected function displayInheritedViewListContent($part, array $params = array())
    {
        $this->displayViewListContent($this->getInheritedListName($part), $params);
    }

    /**
     * Combines the nested list name from the parent list name and a suffix
     *
     * @param string $part Suffix to be added to the parent list name
     *
     * @return string
     */
    protected function getNestedListName($part)
    {
        return $this->viewListName ? $this->viewListName . '.' . $part : $part;
    }

    /**
     * Get a nested view list
     *
     * @param string $part      Suffix of the nested list name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return array
     */
    protected function getNestedViewList($part, array $arguments = array())
    {
        return $this->getViewList($this->getNestedListName($part), $arguments);
    }

    /**
     * Combines the inherited list name from the parent list name and a suffix
     *
     * @param string $part Suffix to be added to the inherited list name
     *
     * @return string
     */
    protected function getInheritedListName($part)
    {
        return $this->getListName() ? $this->getListName() . '.' . $part : $part;
    }

    /**
     * Get a inherited view list
     *
     * @param string $part      Suffix of the inherited list name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return array
     */
    protected function getInheritedViewList($part, array $arguments = array())
    {
        return $this->getViewList($this->getInheritedListName($part), $arguments);
    }

    // }}}

    /**
     * Display plain array as JS array
     *
     * @param array $data Plain array
     *
     * @return void
     */
    public function displayCommentedData(array $data)
    {
        echo ('<!--' . "\r\n" . 'DATACELL' . "\r\n" . json_encode($data) . "\r\n" . '-->' . "\r\n");
    }

    /**
     * Format price
     *
     * @param float                 $value        Price
     * @param \XLite\Model\Currency $currency     Currency OPTIONAL
     * @param boolean               $strictFormat Flag if the price format is strict (trailing zeroes and so on options)
     *
     * @return string
     */
    public function formatPrice($value, \XLite\Model\Currency $currency = null, $strictFormat = false)
    {
        if (!isset($currency)) {
            $currency = \XLite::getInstance()->getCurrency();
        }

        $parts = $currency->formatParts($value);

        if (isset($parts['sign']) && '-' == $parts['sign']) {
            $parts['sign'] = '&minus;&#8197';
        }

        if ($strictFormat) {

            $parts = $this->formatPartsStrictly($parts);
        }

        return implode('', $parts);
    }

    /**
     * Format price as HTML block
     *
     * @param float                 $value    Value
     * @param \XLite\Model\Currency $currency Currency OPTIONAL
     *
     * @return string
     */
    public function formatPriceHTML($value, \XLite\Model\Currency $currency = null)
    {
        if (!isset($currency)) {
            $currency = \XLite::getInstance()->getCurrency();
        }

        $parts = $currency->formatParts($value);

        if (isset($parts['sign']) && '-' == $parts['sign']) {
            $parts['sign'] = '&minus;&#8197;';
        }

        foreach ($parts as $name => $value) {
            $class = 'part-' . $name;
            $parts[$name] = '<span class="' . $class . '">' . func_htmlspecialchars($value) . '</span>';
        }

        return implode('', $parts);
    }

    /**
     * Check - view list is visible or not
     *
     * @param string $list      List name
     * @param array  $arguments List common arguments OPTIONAL
     *
     * @return boolean
     */
    public function isViewListVisible($list, array $arguments = array())
    {
        return 0 < count($this->getViewList($list, $arguments));
    }

    /**
     * Format file size
     *
     * @param integer $size Size in bytes
     *
     * @return string
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
     * Return specific CSS class for dialog wrapper
     *
     * @return string
     */
    protected function getDialogCSSClass()
    {
        return 'dialog-content';
    }

    /**
     * Change parts of format price if it is necessary
     *
     * @param array $parts
     *
     * @return array
     */
    protected function formatPartsStrictly($parts)
    {
        if (
            1 == \XLite\Core\Config::getInstance()->General->trailing_zeroes
            && '00' == $parts['decimal']
        ) {

            unset($parts['decimal']);
            unset($parts['decimalDelimiter']);
        }

        return $parts;
    }

    /**
     * Build list item class
     *
     * @param string $listName List name
     *
     * @return string
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
     */
    protected function formatFileSize($size)
    {
        return \XLite\Core\Converter::formatFileSize($size);
    }

    /**
     * concat
     *
     * @return string
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
     * Format timestamp as day time
     *
     * @param mixed  $base   String or object instance to get field value from
     * @param string $field  Field to get value OPTIONAL
     * @param string $format Time format OPTIONAL
     *
     * @return string
     */
    protected function formatDayTime($base, $field = null, $format = null)
    {
        if (is_object($base)) {
            $base = $base instanceof \XLite\Model\AEntity
                ? $base->$field
                : $base->get($field);
        }

        return \XLite\Core\Converter::formatDayTime($base, $format);
    }

    /**
     * Call for Flexy modifier from AView class
     *
     * @param string $callMethod Name of method to call
     * @param string $expr       Exression to modify
     *
     * @return string
     */
    protected function flexyModifierCall($callMethod, $expr)
    {
        $callMethod = 'flexyModifier' . ucfirst($callMethod);

        return method_exists($this, $callMethod) ? $this->$callMethod($expr) : '';
    }

    /**
     * nl2br modifier
     *
     * @param string $expr
     *
     * @return string
     */
    protected function flexyModifierNl2br($expr)
    {
        return nl2br($expr);
    }

    /**
     * Trim modifier
     *
     * @param string $expr
     *
     * @return string
     */
    protected function flexyModifierTrim($expr)
    {
        return trim($expr);
    }

    /**
     * LTrim modifier
     *
     * @param string $expr
     *
     * @return string
     */
    protected function flexyModifierLtrim($expr)
    {
        return ltrim($expr);
    }

    /**
     * RTrim modifier
     *
     * @param string $expr
     *
     * @return string
     */
    protected function flexyModifierRtrim($expr)
    {
        return rtrim($expr);
    }

    /**
     * Add slashes
     *
     * @param mixed  $base  String or object instance to get field value from
     * @param string $field Field to get value OPTIONAL
     *
     * @return void
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
     */
    protected function getViewList($list, array $arguments = array())
    {
        if (!isset($this->viewLists[$list])) {
            $this->viewLists[$list] = $this->defineViewList($list);
        }

        if (!empty($arguments)) {
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
     */
    protected function getViewListChildren($list)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\ViewList')->findClassList(
            $list,
            static::detectCurrentViewZone()
        );
    }

    /**
     * Detect current view zone
     *
     * @return string
     */
    protected static function detectCurrentViewZone()
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
     */
    protected function getViewListClass()
    {
        return get_class($this);
    }

    /**
     * Get XPath by content
     *
     * @param string $content Content
     *
     * @return \DOMXPath
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
     */
    protected function getViewListContent($list, array $arguments = array())
    {
        ob_start();
        $this->displayViewListContent($list, $arguments);
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
     * Return internal list name
     *
     * @return string
     */
    protected function getListName()
    {
        return null;
    }

    /**
     * getNamePostedData
     *
     * @param string  $field Field name
     * @param integer $id    Model object ID OPTIONAL
     *
     * @return string
     */
    protected function getNamePostedData($field, $id = null)
    {
        $args  = func_get_args();
        $field = $args[0];
        $tail  = '';

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
     */
    protected function getNameToDelete($id)
    {
        return $this->getPrefixSelected() . '[' . $id . ']';
    }

    /**
     * Checks if specific developer mode is defined
     *
     * @return boolean
     */
    protected function isDeveloperMode()
    {
        return LC_DEVELOPER_MODE;
    }

    // }}}

    // {{{ Remove class/template from list

    /**
     * Remove widget from list
     *
     * @param string  $name       Class/template name
     * @param boolean $isTemplate Flag OPTIONAL
     * @param string  $list       List name OPTIONAL
     * @param string  $zone       Zone name OPTIONAL
     *
     * @return void
     */
    public static function removeWidgetFromList($name, $isTemplate = true, $list = null, $zone = null)
    {
        $data = array();
        $data[$isTemplate ? 'tpl' : 'child'] = $name;

        if (!empty($list)) {
            $data['list'] = $list;
        }

        if (empty($zone)) {
            $data['zone'] = static::detectCurrentViewZone();
        }

        $repo = \XLite\Core\Database::getRepo('\XLite\Model\ViewList');
        $repo->deleteInBatch($repo->findBy($data));
    }

    // }}}

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath(
			'images/logo.png',
			\XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
			\XLite::CUSTOMER_INTERFACE
		);
    }

    /**
     * Get invoice logo
     *
     * @return string
     */
    public function getInvoiceLogo()
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath(
            'images/invoice_logo.png',
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
            \XLite::CUSTOMER_INTERFACE
        );
    }

}

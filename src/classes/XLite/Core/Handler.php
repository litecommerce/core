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

namespace XLite\Core;

/**
 * Abstract handler (common parent for viewer and controller)
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Handler extends \XLite\Base
{
    /**
     * Common handler params
     */
    const PARAM_IS_EXPORTED = 'isExported';

    /**
     * Controller-specific params
     */
    const PARAM_SILENT       = 'silent';
    const PARAM_DUMP_STARTED = 'dumpStarted';

    /**
     * AJAX-specific parameters
     */
    const PARAM_AJAX_TARGET = 'target';
    const PARAM_AJAX_WIDGET = 'widget';

    /**
     * Widget params
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $widgetParams;

    /**
     * Define and set handler attributes; initialize handler
     *
     * @param array $params Handler params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array())
    {
        parent::__construct();

        $this->setWidgetParams($params);
    }

    /**
     * Initialize handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setWidgetParams(array $params)
    {
        $listParams = $this->getWidgetParams();

        foreach ($listParams as $name => $paramObject) {
            if (0 < strlen($name) && isset($params[$name])) {
                $paramObject->setValue($params[$name]);
            }
            // FIXME - for mapping only
            // FIXME - uncomment (at first), remove after check
            // unset($params[$name]);
        }

        // FIXME - backward compatibility - mapping; to remove
        foreach ($params as $name => $value) {
            if (0 < strlen($name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * Return widget parameters list (or a single object)
     *
     * @param string $param Param name OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWidgetParams($param = null)
    {
        if (!isset($this->widgetParams)) {
            $this->defineWidgetParams();
        }

        return isset($param)
            ? (isset($this->widgetParams[$param]) ? $this->widgetParams[$param] : null)
            : $this->widgetParams;
    }

    /**
     * getWidgetSettings
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWidgetSettings()
    {
        return array_filter(
            $this->getWidgetParams(),
            array($this, 'getWidgetSettingsFilter')
        );
    }

    /**
     * Filter for getWidgetSettings() method
     *
     * @param \XLite\Model\WidgetParam\AWidgetParam $param Widget parameter
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWidgetSettingsFilter(\XLite\Model\WidgetParam\AWidgetParam $param)
    {
        return $param->isSetting;
    }

    /**
     * Check passed attributes
     *
     * @param array $attrs Attributes to check
     *
     * @return array Errors list
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $messages = array();

        foreach ($this->getWidgetSettings() as $name => $param) {

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
     * Compose URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function buildURL($target = '', $action = '', array $params = array())
    {
        return \XLite\Core\Converter::buildURL($target, $action, $params);
    }

    /**
     * Compose complete URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function buildFullURL($target = '', $action = '', array $params = array())
    {
        return \XLite\Core\Converter::buildFullURL($target, $action, $params);
    }

    /**
     * Compose URL path from target, action and additional params
     * FIXME - this method must be removed
     *
     * @param string $target Page identifier
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function buildURLPath($target, $action = '', array $params = array())
    {
        $url = $this->buildURL($target, $action, $params);
        $parts = parse_url($url);

        return (!isset($parts['path']) || strlen($parts['path'])) ? './' : $parts['path'];
    }

    /**
     * Compose URL query arguments from target, action and additional params
     * FIXME - this method must be removed
     *
     * @param string $target Page identifier
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function buildURLArguments($target, $action = '', array $params = array())
    {
        $url = $this->buildURL($target, $action, $params);
        $parts = parse_url($url);

        $args = array();
        if (isset($parts['query'])) {
            parse_str($parts['query'], $args);
        }

        return $args;
    }

    /**
     * Common prefix for editable elements in lists
     *
     * NOTE: this method is requered for the GetWidget and AAdmin classes
     * TODO: after the multiple inheritance should be moved to the AAdmin class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPrefixPostedData()
    {
        return 'postedData';
    }

    /**
     * Common prefix for the selected checkboxes in lists
     *
     * NOTE: this method is requered for the GetWidget and AAdmin classes
     * TODO: after the multiple inheritance should be moved to the AAdmin class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPrefixSelected()
    {
        return 'select';
    }

    // {{{ Methods to work with the received data

    /**
     * getRequestDataByPrefix
     *
     * @param string $prefix Index in the request array
     * @param string $field  Name of the field to retrieve OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestDataByPrefix($prefix, $field = null)
    {
        return (array) \Includes\Utils\ArrayManager::getIndex(
            (array) \XLite\Core\Request::getInstance()->$prefix,
            $field,
            false
        );
    }

    /**
     * getPostedData
     *
     * @param string $field Name of the field to retrieve OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPostedData($field = null)
    {
        return $this->getRequestDataByPrefix($this->getPrefixPostedData(), $field);
    }

    /**
     * Return selected index array
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSelected()
    {
        return $this->getRequestDataByPrefix($this->getPrefixSelected());
    }

    // }}}

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        $this->widgetParams = array(
            self::PARAM_IS_EXPORTED => new \XLite\Model\WidgetParam\Bool('Is exported', \XLite\Core\CMSConnector::isCMSStarted()),
        );
    }

    /**
     * Return widget param value
     *
     * @param string $param Param to fetch
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getParam($param)
    {
        $param = $this->getWidgetParams($param);

        return $param ? $param->value : $param;
    }

    /**
     * isExported
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isExported()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED);
    }

    /**
     * getParamsHash
     *
     * @param array $params List of params to use
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getParamsHash(array $params)
    {
        $result = array();

        foreach ($params as $param) {
            $result[$param] = $this->getParam($param);
        }

        return $result;
    }
}

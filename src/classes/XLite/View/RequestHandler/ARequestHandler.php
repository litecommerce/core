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

namespace XLite\View\RequestHandler;

/**
 * Abstract base which can populate its params from request and session
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ARequestHandler extends \XLite\View\AView
{
    /**
     * The "session cell" param name
     */
    const PARAM_SESSION_CELL = 'sessionCell';

    /**
     * Keys of JS array to send
     */
    const W_CLASS  = 'widget_class';
    const W_TARGET = 'widget_target';


    /**
     * List of so called "request" params - which take values from request (if passed)
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $requestParams;

    /**
     * Request param values saved in session
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $savedRequestParams;


    /**
     * Return name of the session cell identifier
     *
     * @return string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function getSessionCell()
    {
        return \XLite\Core\Converter::getPlainClassName($this);
    }

    /**
     * Return name of this class
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getWidgetClass()
    {
        return get_class($this);
    }

    /**
     * Return data to send to JS
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getJSData()
    {
        return array(
            self::W_CLASS  => $this->getWidgetClass(),
            self::W_TARGET => $this->getWidgetTarget(),
        );
    }

    /**
     * Check if passed request data are correspond to the current widget
     *
     * There are two cases:
     *
     * 1. Name of the session cell is not presented in request.
     * In this case the target widget is undefined, and the request data are used for ALL widgets
     *
     * 2. Name of the session cell is passed in the request.
     * Then only the corresponded widget will use it
     *
     * @return boolean 
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function checkSessionCell()
    {
        $cell = \XLite\Core\Request::getInstance()->{self::PARAM_SESSION_CELL};

        return empty($cell) || $this->getSessionCell() === $cell;
    }

    /**
     * Check if we need to manage request params 
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkRequestParams()
    {
        return $this->getRequestParams() && $this->checkSessionCell();
    }

    /**
     * Called before the includeCompiledFile()
     *
     * Here we save all passed request params into the session.
     * It allows us to refresh pages without restore their default view
     *
     * @return void
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function initView()
    {
        parent::initView();

        if ($this->checkRequestParams()) {
            \XLite\Core\Session::getInstance()->set($this->getSessionCell(), $this->getRequestParamsHash());
        }
    }

    /**
     * Define the "request" parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineRequestParams()
    {
        $this->requestParams = array();
    }

    /**
     * Fetch param value from current session
     *
     * @param string $param Parameter name
     *
     * @return mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function getSavedRequestParam($param)
    {
        if (!isset($this->savedRequestParams)) {

            // Cache the session cell (variable) associatd with the current widget
            $this->savedRequestParams = \XLite\Core\Session::getInstance()->get(
                $this->getSessionCell()
            );

            // ... To avoid repeated initializations
            if (!isset($this->savedRequestParams)) {
                $this->savedRequestParams = array();
            }
        }

        return isset($this->savedRequestParams[$param]) ? $this->savedRequestParams[$param] : null;
    }

    /**
     * Set param values using the request or session
     *
     * @param array &$params param values to modify
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setWidgetRequestParamValues(array &$params)
    {
        foreach ($this->getRequestParams() as $name) {
            // First, check the request
            $value = \XLite\Core\Request::getInstance()->$name;

            if (isset($value)) {
                // If the param is passed in the request - use it
                $params[$name] = $value;

            } else {
                // Else trying to fetch the param from session
                $value = $this->getSavedRequestParam($name);

                // If the value is found - use it
                if (isset($value)) {
                    $params[$name] = $value;
                }
            }
        }
    }


    /**
     * Return target to retrive this widget from AJAX
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getWidgetTarget()
    {
        return '';
    }


    /**
     * Return list of the "request" parameter names
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRequestParams()
    {
        if (!isset($this->requestParams)) {
            $this->defineRequestParams();
        }

        return $this->requestParams;
    }

    /**
     * Return the associative array mapped by the "request" parameter names/values
     *
     * @return array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public function getRequestParamsHash()
    {
        return $this->getParamsHash($this->getRequestParams());
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public function setWidgetParams(array $params)
    {
        if ($this->checkRequestParams() && !$this->isCloned) {
            $this->setWidgetRequestParamValues($params);
        }

        parent::setWidgetParams($params);
    }
}

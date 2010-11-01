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

namespace XLite\View\Form;

/**
 * Abstract form
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AForm extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_START = 'start';
    const PARAM_END   = 'end';

    const PARAM_FORM_TARGET = 'formTarget';
    const PARAM_FORM_ACTION = 'formAction';
    const PARAM_FORM_NAME   = 'formName';
    const PARAM_FORM_PARAMS = 'formParams';
    const PARAM_FORM_METHOD = 'formMethod';
    const PARAM_CLASS_NAME  = 'className';


    /**
     * Form arguments plain list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $plainList = null;


    /**
     * Each form must define its own name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getFormName();


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'form/start.tpl';
    }

    /**
     * Open and close form tags
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return $this->getParam(self::PARAM_END) ? 'form/end.tpl' : parent::getTemplate();
    }

    /**
     * Required form parameters
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCommonFormParams()
    {
        return array(
            'target' => $this->getParam(self::PARAM_FORM_TARGET),
            'action' => $this->getParam(self::PARAM_FORM_ACTION),
        );
    }

    /**
     * Return value for the <form action="..." ...> attribute
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormAction()
    {
        return $this->buildURL($this->getParam(self::PARAM_FORM_TARGET));
    }

    /**
     * Return list of additional params 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParams()
    {
        $params = array_merge($this->getCommonFormParams(), $this->getParam(self::PARAM_FORM_PARAMS));

        if ('post' === $this->getParam(self::PARAM_FORM_METHOD)) {
            $this->setReturnURLParam($params);
        }

        return $params;
    }

    /**
     * Check and (if needed) set the return URL parameter
     * 
     * @param array &$params form params
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setReturnURLParam(array &$params)
    {
        $index = \XLite\Controller\AController::RETURN_URL;

        if (!isset($params[$index])) {
            $params[$index] = \Includes\Utils\URLManager::getSelfURL();
        }
    }

    /**
     * JavaScript: this value will be returned on form submit
     * NOTE - this function designed for AJAX easy switch on/off  
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getOnSubmitResult()
    {
        return 'true';
    }

    /**
     * JavaScript: default action performed on form submit
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSOnSubmitCode()
    {
        return 'return ' . $this->getOnSubmitResult() . ';';
    }

    /**
     * Return default value for the "target" parameter
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return '';
    }

    /**
     * Return default value for the "action" parameter
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return '';
    }

    /**
     * Return list of the form default parameters
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultParams()
    {
        return array();
    }

    /**
     * getDefaultFormMethod 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultFormMethod()
    {
        return 'post';
    }

    /**
     * getDefaultClassName 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultClassName()
    {
        return null;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_START => new \XLite\Model\WidgetParam\Bool('Is start', true),
            self::PARAM_END   => new \XLite\Model\WidgetParam\Bool('Is end', false),

            self::PARAM_FORM_TARGET => new \XLite\Model\WidgetParam\String(
                'Target', $this->getDefaultTarget()
            ),
            self::PARAM_FORM_ACTION => new \XLite\Model\WidgetParam\String(
                'Action', $this->getDefaultAction()
            ),
            self::PARAM_FORM_NAME => new \XLite\Model\WidgetParam\String(
                'Name', ''
            ),
            self::PARAM_FORM_PARAMS => new \XLite\Model\WidgetParam\Collection(
                'Params', $this->getDefaultParams()
            ),
            self::PARAM_FORM_METHOD => new \XLite\Model\WidgetParam\Set(
                'Request method', $this->getDefaultFormMethod(), array('post', 'get')
            ),
            self::PARAM_CLASS_NAME => new \XLite\Model\WidgetParam\String(
                'Class name', $this->getDefaultClassName()
            ),
        );
    }

    /**
     * Ability to add the 'enctype="multipart/form-data"' form attribute
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isMultipart()
    {
        return false;
    }

    /**
     * Get class name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassName()
    {
        return $this->getParam(self::PARAM_CLASS_NAME);
    }

    /**
     * Get validator 
     * 
     * @return \XLite\Core\Validator\HashArray
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getValidator()
    {
        return new \XLite\Core\Validator\HashArray();
    }

    /**
     * Get request data 
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRequestData()
    {
        $data = null;
        $validator = $this->getValidator();

        try {
            $validator->validate(\XLite\Core\Request::getInstance()->getData());
            $data = $validator->sanitize(\XLite\Core\Request::getInstance()->getData());

        } catch (\XLite\Core\Validator\Exception $exception) {
            $message = $this->t($exception->getMessage(), $exception->getLabelArguments());

            if ($exception->isInternal()) {
                \XLite\Logger::getInstance()->log($message, LOG_ERR);

            } else {
                \XLite\Core\Event::invalidElement(
                    $exception->getPath(),
                    $message
                );
            }

            
        }

        return $data;
    }

    /**
     * Return current form reference
     * 
     * @return \XLite\View\Model\AModel
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getCurrentForm()
    {
        return \XLite\View\Model\AModel::getCurrentForm();
    }
}


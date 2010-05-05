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
 * Abstract form widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Module_DrupalConnector_View_Form_Abstract extends XLite_View_Form_Abstract
implements XLite_Base_IDecorator
{
    /**
     * Chech if widget is exported into Drupal and current form has its method = "GET"
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isDrupalGetForm()
    {
        return XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS() 
               && 'get' == strtolower($this->getParam(self::PARAM_FORM_METHOD));
    }

    /**
     * This JavaScript code will be performed when form submits
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSOnSubmitCode()
    {
        return ($this->isDrupalGetForm() ? 'drupalOnSubmitGetForm(this); ' : '')
            . parent::getJSOnSubmitCode();
    }

    /**
     * JavaScript: compose the "{'a':<a>,'b':<b>,...}" string (JS array) by the params array
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParamsAsJSArray()
    {
        return '[\'' . implode('\',\'', array_keys($this->getFormParams())) . '\']';
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

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(array('q' => ''));
    }

    /**
     * Return list of additional params 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParamsAsPlainList()
    {
        if (is_null($this->plainList)) {

            if ('post' == $this->getParam(self::PARAM_FORM_METHOD)) {

                $this->plainList = parent::getFormParamsAsPlainList();

            } elseif ($this->isDrupalGetForm()) {

                $params = $this->getFormParams();

                $this->plainList = array();

                $url = $this->buildUrl(
                    $this->getParam(self::PARAM_FORM_TARGET),
                    $this->getParam(self::PARAM_FORM_ACTION)
                );
                $parsedUrl = parse_url($url);
                if (isset($parsedUrl['query'])) {
                    $query = array();
                    parse_str($parsedUrl['query'], $this->plainList);
                }

            }

        }

        return $this->plainList;
    }
}

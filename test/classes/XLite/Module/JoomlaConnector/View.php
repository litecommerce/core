<?php

/* $Id$ */

/**
 * Viewer base class
 * 
 * @package    Lite Commerce
 * @subpackage Module JoomlaConnector
 * @since      3.0
 */
class XLite_Module_JoomlaConnector_View extends XLite_View implements XLite_Base_IDecorator
{
	/**
     * Compose URL from target, action and additional params
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    protected function getJoomlaURL($target, $action = '', array $params = array())
    {
        return parent::buildURL($target, $action, $params);
    }


    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public function buildURL($target, $action = '', array $params = array())
    {
        return $this->checkCurrentCMS(XLite_Module_JoomlaConnector_Handler::getInstance()->getCMSName()) ?
            $this->getJoomlaURL($target, $action, $params) : parent::buildURL($target, $action, $params);
    }
}


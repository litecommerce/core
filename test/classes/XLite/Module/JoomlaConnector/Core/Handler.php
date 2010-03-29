<?php

/* $Id$ */

/**
 * Handler base class
 * 
 * @package    Lite Commerce
 * @subpackage Module JoomlaConnector
 * @since      3.0
 */
abstract class XLite_Module_JoomlaConnector_Core_Handler extends XLite_Core_Handler implements XLite_Base_IDecorator
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
		// TODO - this function must not be called!
        return parent::buildURL($target, $action, $params) . '&============';
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
    public function buildURL($target = '', $action = '', array $params = array())
    {
        return XLite_Module_JoomlaConnector_Handler::getInstance()->checkCurrentCMS()
			? self::getJoomlaURL($target, $action, $params) 
			: parent::buildURL($target, $action, $params);
    }
}


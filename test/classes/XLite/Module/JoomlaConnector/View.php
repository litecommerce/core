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
     * This field determines if Joomla-specific URLs intsead of the default ones
     */
    const JOOMLA_REWRITE_URLS = '____JOOMLA_REWRITE_URLS____';

	/**
	 * It's the the root part of Joomla nodes which are the imported LiteCommerce widgets
	 */
	const JOOMLA_ROOT_NODE = 'lc_widget';


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
		return '?q=' . implode('/', array(self::JOOMLA_ROOT_NODE, $target, $action)) . '/' . XLite_Core_Converter::buildQuery($params, ':', ',');
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
		return $this->checkWidgetFlag(self::JOOMLA_REWRITE_URLS) ? $this->getJoomlaURL($target, $action, $params) : parent::buildURL($target, $action, $params);
    }
}


<?php

/* $Id$ */

/**
 * Viewer base class
 * 
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @since      3.0
 */
class XLite_Module_DrupalConnector_View extends XLite_View implements XLite_Base_IDecorator
{
	/**
	 * It's the the root part of Drupal nodes which are the imported LiteCommerce widgets
	 */
	const DRUPAL_ROOT_NODE = 'store';


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
	protected function getDrupalURL($target, $action = '', array $params = array())
	{
		return '?q='
			. implode('/', array(self::DRUPAL_ROOT_NODE, $target, $action)) 
			. '/'
			. XLite_Core_Converter::buildQuery($params, '-', '/');
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
		return $this->checkCurrentCMS(XLite_Module_DrupalConnector_Handler::getInstance()->getCMSName())
			? $this->getDrupalURL($target, $action, $params)
			: parent::buildURL($target, $action, $params);
    }
}


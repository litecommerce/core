<?php

/* $Id$ */

/**
 * Drupal-specific routines
 * 
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @since      3.0
 */
class XLite_Module_DrupalConnector_Core_CMSConnector extends XLite_Core_CMSConnector implements XLite_Base_IDecorator
{
	/**
     * Return HTML code of a widget
     *
     * @param string $name   widget name
     * @param array  $params array of XLite_Model_WidgetParam objects
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public function getWidgetHTML($name, array $params = array())
    {
		return parent::getWidgetHTML($name, array(new XLite_Model_WidgetParam(true, XLite_Module_DrupalConnector_View::DRUPAL_REWRITE_URLS)) + $params);
	}
}


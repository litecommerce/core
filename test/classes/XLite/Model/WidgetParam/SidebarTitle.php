<?php

/* $Id$ */

/**
 * Left/right side menu title
 *                         
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0                   
 */
class XLite_Model_WidgetParam_SidebarTitle extends XLite_Model_WidgetParam
{
	/**
     * Save passed values in object properties
     *
     * @param mixed $value param value
     * @param mixed $name  param name
     * @param mixed $label param label
     *
     * @return void
     * @access public
     * @since  3.0
     */
	public function __construct($value = null, $name = null, $label = null)
    {
		parent::__construct($value, 'head', 'Title');
	}
}


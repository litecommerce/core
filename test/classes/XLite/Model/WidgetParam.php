<?php

/* $Id$ */

/**
 * Params for exported widget
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
class XLite_Model_WidgetParam extends XLite_Model_WidgetParam_Abstract
{
	/**
	 * Common constructor 
	 * 
	 * @param string $name  param name
	 * @param string $value param value
	 * @param string $label param text label
	 *  
	 * @return void
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct($name = null, $value = null, $label = null)
	{
		$this->setCommonData($name, $value, $label);
	}
}


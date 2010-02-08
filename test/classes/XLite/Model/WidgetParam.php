<?php

/* $Id$ */

/**
 * Params for exported widget
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
class XLite_Model_WidgetParam extends XLite_Base
{
	/**
	 * Param name 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $name = null;

	/**
	 * Param value 
	 * 
	 * @var    mixed
	 * @access protected
	 * @since  3.0
	 */
	protected $value = null;

	/**
	 * Param label 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $label = null;

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
		$this->name  = $name;
		$this->value = $value;
		$this->label = $label;
	}

	/**
	 * Return protected property 
	 * 
	 * @param string $name property name
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0
	 */
	public function __get($name)
	{
		return isset($this->$name) ? $this->$name : null;
	}
}


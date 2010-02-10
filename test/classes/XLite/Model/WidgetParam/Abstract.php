<?php

/* $Id$ */

/**
 * Params for exported widget
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
abstract class XLite_Model_WidgetParam_Abstract extends XLite_Base
{
	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = null;

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
	 * Save passed data in object properties 
	 * 
	 * @param string $name  param name_
	 * @param string $value param value
	 * @param string $label param label
	 *  
	 * @return void
	 * @access protected
	 * @since  1.0.0
	 */
	protected function setCommonData($name, $value, $label)
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


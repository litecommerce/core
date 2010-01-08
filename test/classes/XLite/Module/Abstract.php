<?php

/**
 * XLite_Module_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0
 */
abstract class XLite_Module_Abstract extends XLite_Module_Abstract
{
	/**
	 * Module dependencies
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $dependencies = array();

	/**
	 * Module version 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $version = '1.0';

	/**
	 * Module description 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $description = 'wdwdw';

	/**
	 * Determines if module switched on/off
	 * 
	 * @var    mixed
	 * @access protected
	 * @since  3.0
	 */
	protected $enabled = true;


	/**
     * Method to initialize concrete module instance
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init()
    {
    }

	/**
	 * Return module dependencies 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0
	 */
	public function getDependencies()
	{
		return $this->dependencies;
	}

	/**
	 * Return module version
	 * 
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Return module description 
	 * 
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Return module status 
	 * 
	 * @return bool
	 * @access public
	 * @since  3.0
	 */
	public function isEnabled()
	{
		return (true === $this->enabled); 
	}
}


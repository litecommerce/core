<?php

abstract class XLite_Module_Abstract extends XLite_Model_Module
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
	protected $description = '';

	/**
	 * Determines if module is switched on/off
	 * 
	 * @var    bool
	 * @access protected
	 * @since  3.0
	 */
	protected $enabled = true;


	/**
	 * Return module name by class name
	 * 
	 * @return string
	 * @access protected
	 * @since  3.0
	 */
	protected function getName()
	{
		return preg_match('/XLite_Module_(\w+)_Main/', get_class($this), $matches) ?
			$matches[1] : $this->_die('Module class name is invalid - "' . get_class($this) . '"');
	}

	/**
	 * Easy way to register payment method
	 * 
	 * @param string $name payment method name
	 *  
	 * @return void
	 * @access protected
	 * @since  3.0
	 */
	protected function registerPaymentMethod($name)
	{
		$method = new XLite_Model_PaymentMethod();
		$class  = 'Module_' . $this->getName() . '_Model_PaymentMethod_' . XLite_Core_Converter::convertToCamelCase($name);

		return $method->registerMethod($name, $class);
	}

	/**
	 * Easy way to register shipping module 
	 * 
	 * @param string $name shipping module name
	 *  
	 * @return void
	 * @access protected
	 * @since  3.0
	 */
	protected function registerShippingModule($name)
	{
		$shipping = new XLite_Model_Shipping();
		$class  = 'Module_' . $this->getName() . '_Model_Shipping_' . XLite_Core_Converter::convertToCamelCase($name);

		return $shipping->registerShippingModule($name, $class);
	}


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

	/**
	 * Clean up cache
	 * 
	 * @return void
	 * @access public
	 * @since  3.0
	 */
	public function uninstall()
    {
        func_cleanup_cache('classes');
        func_cleanup_cache('skins');

        parent::uninstall();
    }
}


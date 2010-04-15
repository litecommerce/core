<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Shipping extends XLite_Model_Abstract
{
	/**
     * Db table fields
     *
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $fields = array(
        'shipping_id' => '',
        'class'       => '',
        'destination' => 'L',
        'name'        => '',
        'order_by'    => 0,
        'enabled'     => 1,
	);

	/**
     * Db table name
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $alias = 'shipping';

	/**
     * Db table primary key
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $autoIncrement = 'shipping_id';

	/**
     * Filed to use in ORDERBY clause
     *
     * @var    string
     * @access public
     * @since  3.0
     */
    public $defaultOrder = "order_by, name";

	/**
	 * List of registered shipping modules
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected static $registeredShippingModules = array(
		'Offline' => 'Model_Shipping_Offline',
	);

	/**
	 * Normalize service name 
	 * 
	 * @param string $name string to normalize_
	 *  
	 * @return string
	 * @access protected
	 * @since  3.0
	 */
	protected function _normalizeName($name)
    {
        return trim(preg_replace('/\s+/', ' ', $name));
    }

	/**
	 * Return shipping zone 
	 * 
	 * @param XLite_Model_Order $order order object
	 *  
	 * @return int
	 * @access protected
	 * @since  3.0
	 */
	protected function getZone(XLite_Model_Order $order)
    {
        if (!($zone = $order->getComplex('profile.shippingState.shipping_zone'))) {
			if (!($zone = $order->getComplex('profile.shippingCountry.shipping_zone'))) {
				$defaultCountry = new XLite_Model_Country($this->config->getComplex('General.default_country'));
				if (!($zone = $defaultCountry->get('shipping_zone'))) {
					$zone = 0;
				}
			}
		}

		return $zone;
    }


	/**
     * Return only enabled services from the $methods list 
     * 
     * @param array $methods methods list
     *  
     * @return array
     * @access public
     * @since  3.0
     */
    public function filterEnabled(array $methods)
    {
        $filtered = array();

        foreach ($methods as $id => $rate) {
            if ($rate->shipping->is('enabled')) {
                $filtered[$id] = $rate;
            }
        }

        return $filtered;
    }

	/**
	 * Check module class 
	 * 
	 * @param string $id module identifier
	 *  
	 * @return void
	 * @access public
	 * @since  3.0
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		// unset the class, if it is not registerred within active shipping modules
		if ($id && ($class = $this->get("class")) && !isset(self::$registeredShippingModules[$class])) {
			$this->set("class", null);
		}
	}

    /**
     * Return module name (stub)
     * 
     * @return void
     * @access public
     * @since  3.0
     */
    public function getModuleName()
    {
		$this->doDie("getModuleName is not implemented for abstract class Shipping");
    }

	/**
     * Register new shipping module.
     *
     * @param string $name  module name
     * @param string $class module class
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function registerShippingModule($name, $class)
    {
		if (!isset(self::$registeredShippingModules[$name]) || !(self::$registeredShippingModules[$name] instanceof self)) {
			$class = 'XLite_' . $class;
			if (class_exists($class)) {
				self::$registeredShippingModules[$name] = new $class();
				self::$registeredShippingModules[$name]->set('class', $name);
			} else {
				// TODO - add exception throwing
			}
		}
    }

    /**
     * Retrieves all shipping methods relevant to $this shipping module 
     * 
     * @return XLite_Model_Shipping
     * @access public
     * @since  3.0
     */
    public function getShippingMethods()
    {
        return $this->findAll('class = \'' . $this->get('class') . '\'');
    }
    
    /**
     * Return shipping rates (stub)
     * 
     * @param XLite_Model_Order $order order object
     *  
     * @return void
     * @since  3.0
     */
    public function getRates(XLite_Model_Order $order)
    {
		$this->doDie("getRates(): Not implemented in abstract class Shipping");
    }
    
    /**
     * calculate 
     * 
     * @param XLite_Model_Order $order order object
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function calculate(XLite_Model_Order $order)
    {
		$rates = $order->get('shippingRates');
		$result = false;

		if (is_array($rates)) { 
			$shippingId = $order->get("shipping_id");
			if (isset($rates[$shippingId])) {
				$result = $rates[$shippingId]->rate;
			}
		}

		return $result;
    }

    /**
     * Used by real-time shipping methods to collect shipping services in
     * the xlite_shipping tables. It will create a shipping $name of class
     * $class and destination $destination (L/I) if there is no such
     * method and return an existing or a newly created one 
     * 
     * @param string $class       module class
     * @param string $name        module name
     * @param string $destination shipping destination
     *  
     * @return XLite_Model_Shipping
     * @access public
     * @since  3.0
     */
    public function getService($class, $name, $destination) 
    {
        $name = $this->_normalizeName($name);

        // search for the shipping method specified by ($class, $name)
        $shipping = new self();
        if (!$shipping->find('class = \'' . $class . '\' AND name = \'' . addslashes($name) . '\' AND destination = \'' . $destination . '\'')) {
            // create a new service, disabled
            $shipping->set('class', $class);
            $shipping->set('name', $name);
            $shipping->set('destination', $destination);
            $shipping->set('enabled', 0);
            $shipping->create();
        }

		return $shipping;
    }

	/**
     * Return list of all available shipping modules
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function getModules()
    {
        return self::$registeredShippingModules;
    }

	/**
	 * Register predefined modules 
	 * 
	 * @return void
	 * @access public
	 * @since  3.0
	 */
	public static function registerDefaultModules()
	{
		foreach (self::$registeredShippingModules as $name => $class) {
			self::registerShippingModule($name, $class);
		}
	}
}

// Instantiate classes
XLite_Model_Shipping::registerDefaultModules();


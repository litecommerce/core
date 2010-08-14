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

namespace XLite\Model;

/**
 * Common shipping method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Shipping extends \XLite\Model\AModel
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
        'Offline' => 'Model\Shipping\Offline',
    );

    /**
     * Default shipping methods is registered (or not)
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $defaultShippingMethodsIsRegistered = false;

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
     * @param \XLite\Model\Order $order order object
     *  
     * @return int
     * @access protected
     * @since  3.0
     */
    protected function getZone(\XLite\Model\Order $order)
    {
        if (!($zone = $order->getComplex('profile.shippingState.shipping_zone'))) {
            if (!($zone = $order->getComplex('profile.shippingCountry.shipping_zone'))) {
                $defaultCountry = \XLite\Core\Database::getEM()->find('XLite\Model\Country', $this->config->General->default_country);
                if (!($zone = $defaultCountry->shipping_zone)) {
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
        if (!self::$defaultShippingMethodsIsRegistered) {
            self::registerDefaultModules();
        }

        parent::__construct($id);

        // unset the class, if it is not registerred within active shipping modules
        if ($id && ($class = $this->get('class')) && !isset(self::$registeredShippingModules[$class])) {
            $this->set('class', null);
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
        $this->doDie('getModuleName is not implemented for abstract class Shipping');
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
    public static function registerShippingModule($name, $class, $rawRegister = false)
    {
        if (!$rawRegister && !self::$defaultShippingMethodsIsRegistered) {
            self::registerDefaultModules();
        }

        if (
            !isset(self::$registeredShippingModules[$name])
            || !(self::$registeredShippingModules[$name] instanceof self)
        ) {
            $class = 'XLite\\' . $class;
            if (\XLite\Core\Operator::isClassExists($class)) {
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
     * @return \XLite\Model\Shipping
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
     * @param \XLite\Model\Order $order order object
     *  
     * @return void
     * @since  3.0
     */
    public function getRates(\XLite\Model\Order $order)
    {
        $this->doDie("getRates(): Not implemented in abstract class Shipping");
    }
    
    /**
     * calculate 
     * 
     * @param \XLite\Model\Order $order order object
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function calculate(\XLite\Model\Order $order)
    {
        $rates = $order->get('shippingRates');
        $result = false;

        if (is_array($rates)) {
            $shippingId = $order->get('shipping_id');
            if (isset($rates[$shippingId])) {
                $result = $rates[$shippingId]->rate;
            }
        }

        return $result;
    }

    /**
     * Used by real-time shipping methods to collect shipping services in
     * the shipping tables. It will create a shipping $name of class
     * $class and destination $destination (L/I) if there is no such
     * method and return an existing or a newly created one 
     * 
     * @param string $class       module class
     * @param string $name        module name
     * @param string $destination shipping destination
     *  
     * @return \XLite\Model\Shipping
     * @access public
     * @since  3.0
     */
    public function getService($class, $name, $destination) 
    {
        $name = $this->_normalizeName($name);

        $shipping = self::getInstanceByName($class);

        // search for the shipping method specified by ($class, $name)
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
        static::registerDefaultModules();

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
        if (!self::$defaultShippingMethodsIsRegistered) {
            foreach (self::$registeredShippingModules as $name => $class) {
                unset(self::$registeredShippingModules[$name]);
                self::registerShippingModule($name, $class, true);
            }

            self::$defaultShippingMethodsIsRegistered = true;
        }
    }

    /**
     * Get instance by name 
     * 
     * @param string  $name       Shipping module name
     * @param integer $shippingId Shipping method id
     *  
     * @return \XLite\Model\Shipping
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstanceByName($name, $shippingId = null)
    {
        $className = isset(self::$registeredShippingModules[$name])
            ? get_class(self::$registeredShippingModules[$name])
            : '\XLite\Model\Shipping';

        return $shippingId ? new $className($shippingId) : new $className();
    }
}

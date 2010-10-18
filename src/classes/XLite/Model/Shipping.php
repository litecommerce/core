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
class Shipping extends \XLite\Base\Singleton
{
    /**
     * List of registered shipping processors
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected static $registeredProcessors = array();

    /**
     * __constructor 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        self::registerProcessor('\XLite\Model\Shipping\Processor\Offline');
    }

    /**
     * Register new shipping processor. All processors classes must be
     * derived from \XLite\Model\Shipping\Processor\AProcessor class
     *
     * @param string $processorClass Processor class
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function registerProcessor($processorClass)
    {
        if (!isset(self::$registeredProcessors[$processorClass])) {

            if (\XLite\Core\Operator::isClassExists($processorClass)) {
                self::$registeredProcessors[$processorClass] = new $processorClass();
                uasort(self::$registeredProcessors, array(\XLite\Model\Shipping::getInstance(), 'compareProcessors'));

            }
        }
    }

    /**
     * Sort function for sorting processors by class 
     * 
     * @param \XLite\Model\Shipping\Processor\AProcessor $a First processor
     * @param \XLite\Model\Shipping\Processor\AProcessor $b Second processor
     *  
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function compareProcessors($a, $b)
    {
        $result = 0;

        $bottomProcessorId = 'offline';

        $a1 = $a->getProcessorId();
        $b1 = $b->getProcessorId();

        if ($a1 == $bottomProcessorId) {
            $result = 1;

        } elseif ($b1 == $bottomProcessorId) {
            $result = -1;
        
        } else {
            $result = strcasecmp($a1, $b1);
        }

        return $result;
    }

    /**
     * Unregister shipping processor.
     *
     * @param string $processorClass Processor class
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function unregisterProcessor($processorClass)
    {
        if (isset(self::$registeredProcessors[$processorClass])) {
            unset(self::$registeredProcessors[$processorClass]);
        }
    }

    /**
     * Returns the list of registered shipping processors 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getProcessors()
    {
        return self::$registeredProcessors;
    }

    /**
     * Retrieves shipping methods: all or by specified processor
     * 
     * @return array
     * @access public
     * @since  3.0
     */
    public function getShippingMethods($processorClass = null)
    {
        $methods = array();

        if (isset($processorClass) && isset(self::$registeredProcessors[$processorClass])) {
            $methods = self::$registeredProcessors[$processorClass]->getShippingMethods();

        } else {

            foreach (self::$registeredProcessors as $processor) {
                $methods = array_merge($processor->getShippingMethods());
            }
        }

        return $methods;
    }
    
    /**
     * Return shipping rates
     * 
     * @param \XLite\Model\Order $order order object
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function getRates(\XLite\Model\Order $order)
    {
        $rates = array();

        foreach (self::$registeredProcessors as $processor) {
            // Get rates from processors
            $rates = array_merge($rates, $processor->getRates($order));
        }

        if (!empty($rates)) {

            $markups = array();

            // Calculate markups
            foreach ($rates as $id => $rate) {

                // If markup has already been calculated for rate then continue iteration
                if (null !== $rate->getMarkup()) {
                    continue;
                }

                $processor = $rate->getMethod()->getProcessor();

                if (!isset($markups[$processor])) {
                    $markups[$processor] = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')
                        ->findMarkupsByProcessor($processor, $order);
                }

                // Set markup to the rate
                if (isset($markups[$processor])) {

                    foreach ($markups[$processor] as $method) {

                        if ($method->getMethodId() == $rate->getMethodId()) {
                            $rate->setMarkup($markup);
                            $rate->setMarkupRate($markup->getMarkupValue());
                            $rates[$id] = $rate;
                        }
                    }
                }
            }
        }

        return $rates;
    }

    /**
     * Get destination address from the order
     * 
     * @param \XLite\Model\Order $order Order instance
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDestinationAddress(\XLite\Model\Order $order)
    {
        $address = null;
        
        if (is_object($order->getProfile())) {

            // Profile is exists

            $addressObj = $order->getProfile()->getShippingAddress();

            if ($addressObj instanceof \XLite\Model\Address) {
                $address = array(
                    'address' => $addressObj->getStreet(),
                    'city'    => $addressObj->getCity(),
                    'state'   => $addressObj->getStateId(),
                    'zipcode' => $addressObj->getZipcode(),
                    'country' => $addressObj->getCountryCode()
                );
            }

        }

        if (!isset($address)) {

            if ($order->getDetail('shipping_estimate_country') && $order->getDetail('shipping_estimate_zipcode')) {

                // Estimated shipping requested

                $address = array(
                    'address' => '',
                    'city'    => '',
                    'state'   => '',
                    'zipcode' => $order->getDetail('shipping_estimate_zipcode')->getValue(),
                    'country' => $order->getDetail('shipping_estimate_country')->getValue()
                );

            } else {

                // Anonymous address
                $config = \XLite\Base::getInstance()->config->Shipping;

                if ($config->def_calc_shippings_taxes) {
                    $address = array(
                        'address' => $config->anonymous_address,
                        'city'    => $config->anonymous_city,
                        'state'   => $config->anonymous_state,
                        'zipcode' => $config->anonymous_zipcode,
                        'country' => $config->anonymous_country
                    );
                }
            }
        }

        return $address;
    }

}

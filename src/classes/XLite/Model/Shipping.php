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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model;

/**
 * Common shipping method
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Shipping extends \XLite\Base\Singleton
{
    /**
     * List of registered shipping processors
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $registeredProcessors = array();


    /**
     * Register new shipping processor. All processors classes must be
     * derived from \XLite\Model\Shipping\Processor\AProcessor class
     *
     * @param string $processorClass Processor class
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * Unregister shipping processor.
     *
     * @param string $processorClass Processor class
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getProcessors()
    {
        return self::$registeredProcessors;
    }


    /**
     * __constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct()
    {
        self::registerProcessor('\XLite\Model\Shipping\Processor\Offline');
    }

    /**
     * Retrieves shipping methods: all or by specified processor
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier Shipping order modifier
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRates(\XLite\Logic\Order\Modifier\Shipping $modifier)
    {
        $rates = array();

        foreach (self::$registeredProcessors as $processor) {
            // Get rates from processors
            $rates = array_merge($rates, $processor->getRates($modifier));
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
                        ->findMarkupsByProcessor($processor, $modifier);
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
     * Get destination address
     *
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier Shipping order modifier
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDestinationAddress(\XLite\Logic\Order\Modifier\Shipping $modifier)
    {
        $address = null;

        if ($modifier->getOrder()->getProfile() && $modifier->getOrder()->getProfile()->getShippingAddress()) {

            // Profile is exists
            $addressObj = $modifier->getOrder()->getProfile()->getShippingAddress();
            $address = array(
                'address' => $addressObj->getStreet(),
                'city'    => $addressObj->getCity(),
                'state'   => $addressObj->getState()->getStateId(),
                'zipcode' => $addressObj->getZipcode(),
                'country' => $addressObj->getCountry() ? $addressObj->getCountry()->getCode() : '',
            );
        }

        if (!isset($address)) {

            // Anonymous address
            $config = \XLite\Core\Config::getInstance()->Shipping;
            $address = array(
                'address' => $config->anonymous_address,
                'city'    => $config->anonymous_city,
                'state'   => $config->anonymous_state,
                'zipcode' => $config->anonymous_zipcode,
                'country' => $config->anonymous_country,
            );
        }

        return $address;
    }


    /**
     * Sort function for sorting processors by class
     *
     * @param \XLite\Model\Shipping\Processor\AProcessor $a First processor
     * @param \XLite\Model\Shipping\Processor\AProcessor $b Second processor
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
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
}

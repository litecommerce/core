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

namespace XLite\Controller\Customer;

/**
 * Shipping estimator
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShippingEstimate extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Modifier (cache)
     *
     * @var   \XLite\Model\Order\Modifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $modifier;

    /**
     * Get page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return static::t('Estimate shipping cost');
    }

    /**
     * Get address
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAddress()
    {
        return \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getModifier()->getModifier());
    }

    /**
     * Get modifier
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifier()
    {
        if (!isset($this->modifier)) {
            $this->modifier = $this->getCart()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->modifier;
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Set estimate destination
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSetDestination()
    {
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')
            ->find(\XLite\Core\Request::getInstance()->country);

        if (\XLite\Core\Request::getInstance()->is_custom_state) {
            $state = new \XLite\Model\State;
            $state->setState(\XLite\Core\Request::getInstance()->state);

        } else {
            $state = \XLite\Core\Database::getRepo('XLite\Model\State')
                ->find(\XLite\Core\Request::getInstance()->state);

        }

        if (
            $country
            && $country->getEnabled()
            && $state
            && $state->getState()
            && \XLite\Core\Request::getInstance()->zipcode
        ) {

            $address = $this->getCartProfile()->getShippingAddress();
            if (!$address) {
                $profile = $this->getCartProfile();
                $address = new \XLite\Model\Address;
                $address->setProfile($profile);
                $address->setIsShipping(true);
                $profile->addAddresses($address);
                \XLite\Core\Database::getEM()->persist($address);
            }

            $address->setCountry($country);
            $address->setState($state->getStateId() ? $state : $state->getState());
            $address->setZipcode(\XLite\Core\Request::getInstance()->zipcode);
            $address->update();

            $this->updateCart();

            $modifier = $this->getCart()->getModifier('shipping', 'SHIPPING');

            if ($modifier) {
                \XLite\Core\Event::updateCart(
                    array(
                        'items'            => array(),
                        'shipping_address' => \XLite\Model\Shipping::getInstance()->getDestinationAddress($modifier->getModifier()),
                    )
                );
            }

            $this->valid = true;

            $this->setInternalRedirect();

        } else {
            \XLite\Core\TopMessage::addError('Shipping address is invalid');

            $this->valid = false;

        }
    }

    /**
     * Change shipping method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionChangeMethod()
    {
        if (
            \XLite\Core\Request::getInstance()->methodId
            && $this->getCart()->getShippingId() != \XLite\Core\Request::getInstance()->methodId
        ) {
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->methodId);

            $address = $this->getCartProfile()->getShippingAddress();
            if (!$address) {

                // Default address
                $profile = $this->getCartProfile();
                $address = new \XLite\Model\Address;

                $addr = $this->getAddress();

                // Country
                $c = 'US';

                if ($addr && isset($addr['country'])) {
                    $c = $addr['country'];

                } elseif (\XLite\Core\Config::getInstance()->General->default_country) {
                    $c = \XLite\Core\Config::getInstance()->General->default_country;
                }

                $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($c);
                if ($country) {
                    $address->setCountry($country);
                }

                // State
                $state = null;
                if ($addr && isset($addr['state']) && $addr['state']) {
                    $state = \XLite\Core\Database::getRepo('XLite\Model\State')->find($addr['state']);

                } elseif (
                    !$addr
                    && \XLite\Core\Config::getInstance()->Shipping->anonymous_custom_state
                ) {

                    $state = new \XLite\Model\State();
                    $state->setState(\XLite\Core\Config::getInstance()->Shipping->anonymous_custom_state);

                }

                if ($state) {
                    $address->setState($state);
                }

                // Zip code
                $address->setZipcode(\XLite\Core\Config::getInstance()->General->default_zipcode);

                $address->setProfile($profile);
                $address->setIsShipping(true);
                $profile->addAddresses($address);
                \XLite\Core\Database::getEM()->persist($address);
            }

            $this->updateCart();

            \XLite\Core\Event::updateCart(
                array(
                    'items'    => array(),
                    'shipping' => $this->getCart()->getShippingId(),
                )
            );
        }

        $this->valid = true;
        $this->setSilenceClose();
    }
}

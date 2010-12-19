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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Customer;

/**
 * Shipping estimator
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingEstimate extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Get page title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('Estimate shipping cost');
    }

    /**
     * Set estimate destination 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

            \XLite\Core\Event::updateCart(
                array(
                    'items'            => array(),
                    'shipping_address' => \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart()),
                )
            );

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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionChangeMethod()
    {
        if (
            \XLite\Core\Request::getInstance()->methodId
            && $this->getCart()->getShippingId() != \XLite\Core\Request::getInstance()->methodId
        ) {
            $this->getCart()->setShippingId(\XLite\Core\Request::getInstance()->methodId);
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


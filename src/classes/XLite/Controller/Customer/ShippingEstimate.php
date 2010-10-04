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
        $profile = $this->getCart()->getProfile();

        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')
            ->find(\XLite\Core\Request::getInstance()->country);

        if ($country && $country->getEnabled() && \XLite\Core\Request::getInstance()->zipcode) {

            $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());

            if (
                !$address
                || $address['country'] != $country->getCode()
                || $address['zipcode'] != \XLite\Core\Request::getInstance()->zipcode
            ) {

                if (!$profile) {
                    $this->getCart()->setDetail('shipping_estimate_country', $country->getCode());
                    $this->getCart()->setDetail('shipping_estimate_zipcode', \XLite\Core\Request::getInstance()->zipcode);

                } else {
                    $profile->set('shipping_country', $country->getCode());
                    $profile->set('shipping_zipcode', \XLite\Core\Request::getInstance()->zipcode);
                    $profile->update();
                }

                $this->updateCart();

                \XLite\Core\Event::updateCart(
                    array(
                        'items'            => array(),
                        'shipping_address' => \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart()),
                    )
                );

            }

            $this->valid = true;

            $this->setInternalRedirect();

        } else {

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


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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Checkout\Step;

/**
 * Shipping step
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Shipping extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Get step name
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStepName()
    {
        return 'shipping';
    }

    /**
     * Get step title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('Shipping info');
    }

    /**
     * Check - step is complete or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCompleted()
    {
        return $this->getCart()->getProfile()
            && $this->getCart()->getProfile()->getShippingAddress()
            && $this->getCart()->getProfile()->getShippingAddress()->isCompleted(\XLite\Model\Address::SHIPPING)
            && $this->getCart()->getShippingMethod();
    }

    /**
     * Check - shipping address is completed or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAddressCompleted()
    {
        $profile = $this->getCart()->getProfile();

        return $profile
            && $profile->getShippingAddress()
            && $profile->getShippingAddress()
                ->isCompleted(\XLite\Model\Address::SHIPPING);
    }

    /**
     * Check - shipping system is enabled or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingEnabled()
    {
        return $this->getCart()->isShippingVisible();
    }

    /**
     * Check - shipping rates is available or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingAvailable()
    {
        return $this->getCart()->isShippingAvailable();
    }

    /**
     * Get rate markup 
     * 
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *  
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarkup(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getMarkup()->getMarkupValue();
    }

}

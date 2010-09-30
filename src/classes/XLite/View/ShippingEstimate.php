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
 * @subpackage Cart
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Shipping estimator
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="center")
 */
class ShippingEstimate extends \XLite\View\AView
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'shopping_cart/shipping_estimator/body.tpl';
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'shipping_estimate';
    
        return $result;
    }

    /**
     * Get countries list
     * 
     * @return array of \XLite\Model\Country
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountries()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Country')
            ->findByEnabled(true);
    }

    /**
     * Check - specified country selected or not
     * 
     * @param \XLite\Model\Country $country Country
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCountrySelected(\XLite\Model\Country $country)
    {
        $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());

        $c = 'US';

        if ($address && isset($address['country'])) {
            $c = $address['country'];

        } elseif (\XLite\Core\Config::getInstance()->General->default_country) {
            $c = \XLite\Core\Config::getInstance()->General->default_country;
        }

        return $country->getCode() == $c;
    }

    /**
     * Get ZIP code 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZipcode()
    {
        $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());

        return ($address && isset($address['zipcode']))
            ? $address['zipcode']
            : '';
    }

    /**
     * Get shipping rates 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingRates()
    {
        return $this->getCart()->getShippingRates();
    }

    /**
     * Check - specified rate is selected or not
     * 
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRateSelected(\XLite\Model\Shipping\Rate $rate)
    {
        return $this->getCart()->getSelectedRate() == $rate;
    }

    /**
     * Get rate method id 
     * 
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodId(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getMethod()->getMethodId();
    }

    /**
     * Get rate method name 
     * 
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodName(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getMethod()->getName();
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

    /**
     * Check - shipping is estimate or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isEstimate()
    {
        return (bool)\XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());
    }

}


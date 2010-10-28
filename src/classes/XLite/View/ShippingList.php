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
 * Shipping rates list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingList extends \XLite\View\AView
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
        return 'shipping_list.tpl';
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
}

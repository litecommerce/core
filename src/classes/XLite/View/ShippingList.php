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

namespace XLite\View;

/**
 * Shipping rates list
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShippingList extends \XLite\View\AView
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
     * Get shipping rates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRates()
    {
        return $this->getModifier()->getRates();
    }

    /**
     * Check - specified rate is selected or not
     *
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isRateSelected(\XLite\Model\Shipping\Rate $rate)
    {
        return $this->getModifier()->getSelectedRate() == $rate;
    }

    /**
     * Get rate method id
     *
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMarkup(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getMarkup()->getMarkupValue();
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'shipping_list.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getModifier();
    }

    /**
     * Get modifier
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModifier()
    {
        if (!isset($this->modifier)) {
            $this->modifier = $this->getCart()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->modifier;
    }
}

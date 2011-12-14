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

namespace XLite\View\Checkout\Step;

/**
 * Review checkout step
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Review extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Get step name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getStepName()
    {
        return 'review';
    }

    /**
     * Get step title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return static::t('Order review');
    }

    /**
     * Check - step is complete or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCompleted()
    {
        return false;
    }

    /**
     * Get Terms and Conditions page URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTermsURL()
    {
        return $this->buildURL('terms');
    }

    /**
     * Get Place button title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPlaceTitle()
    {
        return static::t(
            'Place order X',
            array(
                'total' => $this->formatPrice($this->getCart()->getTotal(), $this->getCart()->getCurrency()),
            )
        );
    }

    /**
     * Get payment processor
     *
     * @return \XLite\Model\Payment\Base\Processor
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProcessor()
    {
        return $this->getCart()->getPaymentMethod()
            ? $this->getCart()->getPaymentMethod()->getProcessor()
            : null;
    }

    /**
     * Get payment template
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPaymentTemplate()
    {
        $processor = $this->getProcessor();

        return $processor ? $processor->getInputTemplate() : null;
    }
}

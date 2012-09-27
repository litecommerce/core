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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model\Shipping\Processor;

/**
 * Shipping processor model
 *
 */
class Offline extends \XLite\Model\Shipping\Processor\AProcessor
{
    /*
     * Default base rate
     */
    const PROCESSOR_DEFAULT_BASE_RATE = 0;

    /**
     * Unique processor Id
     *
     * @var string
     */
    protected $processorId = 'offline';

    /**
     * getProcessorName
     *
     * @return string
     */
    public function getProcessorName()
    {
        return 'Manually defined shipping methods';
    }

    /**
     * Enable admin to remove offline shipping methods
     *
     * @return boolean
     */
    public function isMethodDeleteEnabled()
    {
        return true;
    }

    /**
     * Returns offline shipping rates
     *
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier    Shipping order modifier
     * @param boolean                              $ignoreCache Flag: if true then do not get rates from cache (not used in offline processor) OPTIONAL
     *
     * @return array
     */
    public function getRates($modifier, $ignoreCache = false)
    {
        $rates = array();

        if ($modifier instanceOf \XLite\Logic\Order\Modifier\Shipping) {

            // Find markups for all enabled offline shipping methods
            $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')
                ->findMarkupsByProcessor($this->getProcessorId(), $modifier);

            if (!empty($markups)) {

                // Create shipping rates list
                foreach ($markups as $markup) {
                    $rate = new \XLite\Model\Shipping\Rate();
                    $rate->setMethod($markup->getShippingMethod());
                    $rate->setBaseRate(self::PROCESSOR_DEFAULT_BASE_RATE);
                    $rate->setMarkup($markup);
                    $rate->setMarkupRate($markup->getMarkupValue());
                    $rates[] = $rate;
                }
            }
        }

        // Return shipping rates list
        return $rates;
    }

    /**
     * Returns true if shipping methods named may be modified by admin
     *
     * @return boolean
     */
    public function isMethodNamesAdjustable()
    {
        return true;
    }

}

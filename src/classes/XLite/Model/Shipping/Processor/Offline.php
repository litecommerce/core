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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Shipping\Processor;

/**
 * Shipping processor model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class Offline extends AProcessor
{
    /*
     * Default base rate
     */
    const PROCESSOR_DEFAULT_BASE_RATE = 0;    

    /**
     * Unique processor Id
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $processorId = 'offline';

    /**
     * getProcessorName 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProcessorName()
    {
        return 'Manually defined shipping methods';
    }

    /**
     * Returns offline shipping rates 
     * 
     * @param \XLite\Model\Order $order Order object
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates($order)
    {
        $rates = array();

        // Find markups for all enabled offline shipping methods
        $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')
            ->findMarkupsByProcessor($this->getProcessorId(), $order);

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

        // Return shipping rates list
        return $rates;
    }

    /**
     * Returns true if shipping methods named may be modified by admin
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isMethodNamesAdjustable()
    {
        return true;
    }

}

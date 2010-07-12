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

namespace XLite\Module\Affiliate\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerSummary extends \XLite\Module\Affiliate\Controller\Partner
{
    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */ 
    protected function getLocation()
    {
        return 'Summary statistics';
    }


    function getSales()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->sales)) {
            $this->sales = array(
                    "total" => 0,
                    "queued" => 0,
                    "pending" => 0.00,
                    "approved" => 0.00,
                    "paid" => 0.00,
                    );
            $pp = new \XLite\Module\Affiliate\Model\PartnerPayment();
            foreach ((array)$pp->findAll("partner_id=".$this->getComplex('auth.profile.profile_id')) as $payment) {
                if ($payment->get('affiliate') == 0) {
                    $this->sales['total']++;
                }
                if ($payment->get('affiliate') == 0 && !$payment->isComplex('order.processed')) {
                    $this->sales['queued']++;
                }
                if (!$payment->isComplex('order.processed')) {
                    $this->sales['pending'] += $payment->get('commissions');
                }
                if ($payment->isComplex('order.processed') && !$payment->get('paid')) {
                    $this->sales['approved'] += $payment->get('commissions');
                }
                if ($payment->get('paid')) {
                    $this->sales['paid'] += $payment->get('commissions');
                }
            }
        }
        return $this->sales;
    }
}

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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_CheckoutStep_Regular_PaymentMethod 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_CheckoutStep_Regular_PaymentMethod extends XLite_View_CheckoutStep_Regular_ARegular
{
    /**
     * Return step templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getStepDir()
    {
        return 'paymentMethod';
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Select payment method';
    }

    /**
     * Check - specified payment method is selected or not
     *
     * @param XLite_Model_PaymentMethod $paymentMethod Payment method
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isPaymentSelected(XLite_Model_PaymentMethod $paymentMethod)
    {
        return $this->getCart()->get('paymentMethod')
            && $this->getCart()->get('paymentMethod')->get('payment_method') == $paymentMethod->get('payment_method');
    }
}

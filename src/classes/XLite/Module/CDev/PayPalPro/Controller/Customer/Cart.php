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

namespace XLite\Module\CDev\PayPalPro\Controller\Customer;

/**
 * Cart
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Controller\Customer\Cart
implements \XLite\Base\IDecorator
{
    /**
     * Call 'ExpressCheckout' action
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callActionExpressCheckout()
    {
        $this->doActionExpressCheckout();
    }

    /**
     * Redirect to PayPal Express Checkout 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionExpressCheckout()
    {
        $pm = \XLite\Model\PaymentMethod::factory('paypalpro_express');

        if (!$pm->startExpressCheckout($this->getCart())) {

            // TODO - add top message
            $this->set('returnUrl', $this->buildUrl('cart'));
        }
    }
}

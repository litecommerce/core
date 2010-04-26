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

/**
 * Checkout
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout
implements XLite_Base_IDecorator
{
    /**
     * Define and set handler attributes; initialize handler 
     * 
     * @param array $params handler params
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        if (
            isset(XLite_Core_Request::getInstance()->paypal_result)
            && 'cancel' == XLite_Core_Request::getInstance()->paypal_result
            && 'Q' == $this->getCart()->get('status')
        ) {

            // revert back to I if the payment is cancelled at the PayPal side

            $this->getCart()->set('status', 'I');
            $this->updateCart();
        }
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
        $pm = XLite_Model_PaymentMethod::factory('paypalpro_express');

        if (!$pm->startExpressCheckout($this->getCart())) {

            // TODO - add top message
            $this->set('returnUrl', $this->buildUrl('checkout'));
        }
    }

    /**
     * Redirect to paypal
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRedirect()
    {
        $itemsBeforeUpdate = $this->getCart()->get('itemsFingerprint');

        $this->getCart()->set('status', 'Q');
        $this->updateCart();

        $itemsAfterUpdate = $this->getCart()->get('itemsFingerprint');

        if (
            $this->get('absence_of_product')
            || $this->getCart()->isEmpty()
            || $itemsAfterUpdate != $itemsBeforeUpdate
        ) {
            $this->getCart()->set('status', 'I');
            $this->updateCart();
            $this->session->set('order_id', $this->getCart()->get('order_id'));
            $this->set('absence_of_product', true);
            $this->set('returnUrl', $this->buildUrl('cart'));

            $this->redirect();

            return;
        }

        $pm = $this->getCart()->get('PaymentMethod');
        $profile = $this->getCart()->getProfile();

        $returnUrl = $this->getShopUrl(
            $this->buildUrl('checkout', 'return', array('order_id' => $this->getCart()->get('order_id'))),
            $this->config->Security->customer_security
        );

        $cancelUrl = $this->getShopUrl(
            $this->buildUrl('checkout', '', array('paypal_result' => 'cancel')),
            $this->config->Security->customer_security
        );

        $nodifyUrl = $this->getShopUrl(
            $this->buildUrl('callback', 'callback', array('order_id' => $this->getCart()->get('order_id')))
        );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body onload="javascript: document.paypal_form.submit();">
<form name="paypal_form" action="<?php echo $pm->get('standardUrl'); ?>" method="POST">
<input type="hidden" name="cmd" value="_ext-enter" />
<input type="hidden" name="invoice" value="<?php echo $pm->getComplex('params.standard.prefix'); ?><?php echo $this->getCart()->get("order_id"); ?>" />
<input type="hidden" name="redirect_cmd" value="_xclick" />
<input type="hidden" name="mrb" value="R-2JR83330TB370181P" />
<input type="hidden" name="pal" value="RDGQCFJTT6Y6A" />
<input type="hidden" name="rm" value="2" />
<input type="hidden" name="email" value="<?php echo $profile->get('login'); ?>" />
<input type="hidden" name="first_name" value="<?php echo $profile->get('billing_firstname'); ?>" />
<input type="hidden" name="last_name" value="<?php echo $profile->get('billing_lastname'); ?>" />
<input type="hidden" name="address1" value="<?php echo $profile->get('billing_address'); ?>" />
<input type="hidden" name="city" value="<?php echo $profile->get('billing_city'); ?>" />
<input type="hidden" name="state" value="<?php echo $pm->getBillingState($this->getCart()); ?>" />
<input type="hidden" name="country" value="<?php echo $profile->get('billing_country'); ?>" />
<input type="hidden" name="zip" value="<?php echo $profile->get('billing_zipcode'); ?>" />
<input type="hidden" name="day_phone_a" value="<?php echo $pm->getPhone($this->getCart(), 'a'); ?>" />
<input type="hidden" name="day_phone_b" value="<?php echo $pm->getPhone($this->getCart(), 'b'); ?>" />
<input type="hidden" name="day_phone_c" value="<?php echo $pm->getPhone($this->getCart(), 'c'); ?>" />
<input type="hidden" name="night_phone_a" value="<?php echo $pm->getPhone($this->getCart(), 'a'); ?>" /> 
<input type="hidden" name="night_phone_b" value="<?php echo $pm->getPhone($this->getCart(), 'b'); ?>" />
<input type="hidden" name="night_phone_c" value="<?php echo $pm->getPhone($this->getCart(), 'c'); ?>" />
<input type="hidden" name="business" value="<?php echo $pm->getComplex('params.standard.login'); ?>" />
<input type="hidden" name="item_name" value="<?php echo $pm->getItemName($this->getCart()); ?>" />
<input type="hidden" name="amount" value="<?php echo $this->getCart()->get('total'); ?>" />
<input type="hidden" name="currency_code" value="<?php echo $pm->getComplex('params.standard.currency'); ?>" />
<input type="hidden" name="bn" value="x-cart" />
<input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
<input type="hidden" name="cancel_return" value="<?php echo $cancelUrl; ?>" />
<input type="hidden" name="notify_url" value="<?php echo $nodifyUrl; ?>" />
<input type="hidden" name="image_url" value="<?php echo $pm->getComplex('params.standard.logo'); ?>" />
<div style="text-align: center;">Please wait while connecting to <b>PayPal</b> payment gateway...</center>
</form>
</body>
</html>
<?php
        exit;        
    }

    /**
     * Check - controller must work in secure zone or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSecure()
    {
        return $this->config->Security->customer_security;
    }

}

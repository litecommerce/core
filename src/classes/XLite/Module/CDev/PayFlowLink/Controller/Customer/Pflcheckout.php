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

namespace XLite\Module\CDev\PayFlowLink\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Pflcheckout extends \XLite\Controller\Customer\Checkout
{
    public $registerForm = null;

    function init()
    {
    	if ($_REQUEST['target'] == "pflcheckout") {
    		$this->registerForm = new \XLite\Base();
    	}

    	parent::init();

        // go to cart view if cart is empty
        if ($this->target == "pflcheckout" && $this->cart->is('empty')) {
            $this->redirect("cart.php?target=cart");
            return;
        }

        $this->redirect_PayFlowLink();
    }

    function redirect_PayFlowLink()
    {
        $this->cart->set('status', "Q");
        $this->cart->update();

        $pm = $this->cart->get('PaymentMethod');

?>
<HTML>
<BODY onload="javascript: document.frm.submit();">
<FORM name="frm" action="<?php echo $this->cart->getComplex('paymentMethod.params.gateway_url'); ?>" method="POST">
<INPUT type=hidden name="login" value="<?php echo $this->cart->getComplex('paymentMethod.params.login'); ?>">
<INPUT type=hidden name="partner" value="<?php echo $this->cart->getComplex('paymentMethod.params.partner'); ?>">
<INPUT type=hidden name="amount" value="<?php echo $this->cart->get('total'); ?>">
<INPUT type=hidden name=type value=S>
<INPUT type=hidden name=orderform value=true>
<INPUT type=hidden name=method value=cc>
<INPUT type=hidden name=echodata value=true>
<INPUT type=hidden name=showconfirm value=false>
<INPUT type=hidden name=name value="<?php echo $this->cart->getComplex('profile.billing_firstname'); ?> <?php echo $this->cart->getComplex('profile.billing_lastname'); ?>">
<INPUT type=hidden name=nametoship value="<?php echo $this->cart->getComplex('profile.shipping_firstname'); ?> <?php echo $this->cart->getComplex('profile.shipping_lastname'); ?>">
<INPUT type=hidden name=email value="<?php echo $this->cart->getComplex('profile.login'); ?>">
<INPUT type=hidden name=phone value="<?php echo $this->cart->getComplex('profile.billing_phone'); ?>">
<INPUT type=hidden name=emailtoship value="<?php echo $this->cart->getComplex('profile.login'); ?>">
<INPUT type=hidden name=phonetoship value="<?php echo $this->cart->getComplex('profile.shipping_phone'); ?>">
<INPUT type=hidden name="invoice" value="<?php echo $pm->getOrderId($this->cart); ?>">
<INPUT type=hidden name=address value="<?php echo $this->cart->getComplex('profile.billing_address'); ?>">
<INPUT type=hidden name=addresstoship value="<?php echo $this->cart->getComplex('profile.shipping_address'); ?>">
<INPUT type=hidden name=city value="<?php echo $this->cart->getComplex('profile.billing_city'); ?>">
<INPUT type=hidden name=citytoship value="<?php echo $this->cart->getComplex('profile.shipping_city'); ?>">
<INPUT type=hidden name=country value="<?php echo $this->cart->getComplex('profile.billingCountry.code'); ?>">
<INPUT type=hidden name=countrytoship value="<?php echo $this->cart->getComplex('profile.shippingCountry.code'); ?>">
<INPUT type=hidden name=state value="<?php echo $this->cart->getComplex('profile.billingState.code'); ?>">
<INPUT type=hidden name=statetoship value="<?php echo $this->cart->getComplex('profile.shippingState.code'); ?>">
<INPUT type=hidden name=zip value="<?php echo $this->cart->getComplex('profile.billing_zipcode'); ?>">
<INPUT type=hidden name=ziptoship value="<?php echo $this->cart->getComplex('profile.shipping_zipcode'); ?>">
<CENTER>Please wait while connecting to <b>PayFlow Link</b> payment gateway...</CENTER>
</FORM>
</BODY>
</HTML>
<?php
        $this->success();
        exit;
    }
}

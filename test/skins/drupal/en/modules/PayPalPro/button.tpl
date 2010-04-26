{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * PayPal Express checkout button
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="alt-checkout-button paypal-checkout-button">
  <p>- or use -</p>
  <div>
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" class="paypal-express" title="Save time. Check out securely. Click the PayPal button to use the shipping and billing information you have stored with PayPal." onclick="javascript: self.location = '{buildUrl(#cart#,#express_checkout#)}';" />
  </div>
</div>

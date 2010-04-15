{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="{cart.paymentMethod.params.url}" method="POST" name="securetrading_form">
<input type="hidden" name="merchant" value="{cart.paymentMethod.params.merchant}">
<input type="hidden" name="amount" value="{cart.paymentMethod.getTotalCost(cart)}">
<input type="hidden" name="currency" value="{cart.paymentMethod.params.currency}">
<input type="hidden" name="name" value="{cart.profile.get(#billing_firstname#)} {cart.profile.get(#billing_lastname#)}">
<input type="hidden" name="address" value="{cart.profile.get(#billing_address#)}">
<input type="hidden" name="town" value="{cart.profile.get(#billing_city#)}">
<input type="hidden" name="county" value="{cart.paymentMethod.getBillingState(cart)}">
<input type="hidden" name="country" value="{cart.paymentMethod.getCountry(cart)}">
<input type="hidden" name="postcode" value="{cart.profile.get(#billing_zipcode#)}">
<input type="hidden" name="telephone" value="{cart.profile.get(#billing_phone#)}">
<input type="hidden" name="fax" value="{cart.profile.get(#billing_fax#)}">
<input type="hidden" name="email" value="{cart.profile.get(#login#)}">
<input type="hidden" name="requiredfields" value="name,email">
<input type="hidden" name="orderref" value="{cart.paymentMethod.params.order_prefix}{cart.get(#order_id#)}">
<input type="hidden" name="orderinfo" value="{cart.get(#order_id#)}">
<input type="hidden" name="callbackurl" value="1">
<input type="hidden" name="failureurl" value="1">
<input type="hidden" name="merchantemail" value="{cart.paymentMethod.getMerchantEmail()}">
<input type="hidden" name="customeremail" value="{cart.get(#login#)}">
<input type=hidden name="url" value="{cart.paymentMethod.getReturnURL(cart)}">
<input type=hidden name="customeremail" value="1">
<input type=hidden name="settlementday" value="1">
<p>By clicking "SUBMIT" you are agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>

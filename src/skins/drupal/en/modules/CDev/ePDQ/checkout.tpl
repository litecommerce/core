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
<form action="https://{xlite.options.host_details.https_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/submit.php" method="POST" name="ePDQ_form">
{cart.paymentMethod.getePDQdata(cart):h}
<input type=hidden name=merchantdisplayname value="{cart.paymentMethod.params.param01}">
<input type=hidden name=submit_url value="{cart.paymentMethod.params.param09}">
<input type=hidden name=email value="{cart.profile.get(#login#):r}">
<input type=hidden name=baddr1 value="{cart.profile.get(#billing_address#):r}">
<input type=hidden name=bcity value="{cart.profile.get(#billing_city#)}">
<input type=hidden name=bcountry value="{cart.profile.get(#billing_country#)}">
<input type=hidden name=bpostalcode value="{cart.profile.get(#billing_zipcode#)}">
{if:IsSelected(cart.profile,#billing_country#,#US#)}
<input type=hidden name=bstate value="{cart.profile.get(#billingState.code#)}">
<input type=hidden name=sstate value="{cart.profile.get(#shippingState.code#)}">
{end:}
{if:IsSelected(cart.profile,#billing_country#,#GB#)}
<input type="hidden" name="bcountyprovince" value="{cart.profile.get(#billingState.code#)}">
<input type="hidden" name="scountyprovince" value="{cart.profile.get(#shippingState.code#)}">
{end:} 
<input type=hidden name=saddr1 value="{cart.profile.get(#shipping_address#)}">
<input type=hidden name=scity value="{cart.profile.get(#shipping_city#)}">
<input type=hidden name=spostalcode value="{cart.profile.get(#shipping_zipcode#)}">
<input type=hidden name=scountry value="{cart.profile.get(#shipping_country#)}">
<input type=hidden name=returnurl value="https://{xlite.options.host_details.https_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/return.php">
<INPUT type=hidden name=cpi_logo value="{cart.paymentMethod.params.param06}">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>

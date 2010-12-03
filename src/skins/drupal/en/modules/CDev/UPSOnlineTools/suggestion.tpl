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
The UPS OnLine&reg; Tools Address Validation checked your shipping address. There seems to be a problem. Please make sure the contents of the shipping address fields is correct.

<div IF="avError">
<p>
<font class="ErrorMessage">{avErrorMessage:h}</font>
</div>

<form action="cart.php" method="post" name="suggest_form">
<input type="hidden" name="action_type" value="3">
<input type="hidden" FOREACH="session.ups_av_profile,name,val" name="{name}" value="{val}"/>

<table cellpadding="0" cellspacing="10" border="0">
<tr>
    <td><b>You entered:</b></td>
    <td>{upsUsed.shipping_city},{if:upsUsed.shippingState.code} {upsUsed.shippingState.code} ({upsUsed.shippingState.state}){else:} {upsUsed.shippingState.state}{end:} {upsUsed.shipping_zipcode:r}</td>
</tr>
<tr IF="suggestionExists">
    <td><b>We suggest:</b></td>
    <td>
        <select name="suggest">
        <option FOREACH="session.ups_av_result,key,av" value="{key}">{av.city}, {av.state} {av.zipcode}</option>
        </select>
    </td>
</tr>
</table>
</form>
<table cellpadding="0" cellspacing="15" border="0">
<tr>
    <td colspan="2">
        <widget class="\XLite\View\Button" label="Re-enter address" href="javascript:document.suggest_form.submit();" font="FormButton">
    </td>
{if:suggestionExists}
</tr>
<tr>
    <td>
        <widget class="\XLite\View\Button" label="Use suggestion" href="javascript:document.suggest_form.action_type.value=1;document.suggest_form.submit();" font="FormButton">
    </td>
{end:}
    <td>
        <widget class="\XLite\View\Button" label="Keep current address" href="javascript:document.suggest_form.action_type.value=2;document.suggest_form.submit();" font="FormButton">
    </td>
</tr>
</table>
<hr>
<widget template="modules/CDev/UPSOnlineTools/bottom.tpl">

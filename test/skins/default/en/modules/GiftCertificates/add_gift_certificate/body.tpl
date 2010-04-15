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
<widget class="XLite_Module_GiftCertificates_View_AddStatesInfo" />

{* Add gift certificate page *}
<script language="JavaScript1.2" type="text/javascript">
function checkEmailAddress(field) {
	var goodEmail = field.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.biz)|(\...))$)\b/gi);
    
	if (goodEmail) {
    	return true;
	} else {
        alert("E-mail address is invalid! Please correct");
        field.focus();
        field.select();
        return false;
    }
}

	function formSubmit()
	{
	 goodAmount=document.gccreate.amount.value.search(/^[0-9]+(\.[0-9][0-9]?)?$/);
	 if (document.gccreate.recipient.value == "")
	 {
		document.gccreate.recipient.focus();
		alert ("Recipient is invalid! Please correct.");
		return;
	 }
	if (goodAmount==-1 || document.gccreate.amount.value<{config.GiftCertificates.minAmount} || document.gccreate.amount.value>{config.GiftCertificates.maxAmount})
	 {
		document.gccreate.amount.focus();
	    alert ("Amount is invalid! Please correct.");
		return;
	 }
{if:config.GiftCertificates.enablePostGC}
     if ((document.gccreate.send_via[0].checked) && (!checkEmailAddress(document.gccreate.recipient_email)))
     {
        document.gccreate.recipient_email.focus();
        return;
     }
     if (document.gccreate.send_via[1].checked && (document.gccreate.recipient_firstname.value == "" || document.gccreate.recipient_lastname.value == "" || document.gccreate.recipient_address.value == "" || document.gccreate.recipient_city.value == "" || document.gccreate.recipient_zipcode.value == "" || document.gccreate.recipient_state.value == 0 || (document.gccreate.recipient_state.value == -1 && document.gccreate.recipient_custom_state.value == "")))
     {
        document.gccreate.recipient_firstname.focus();
        alert ("Please fill in all the required fields of the recipient's mail address.");
        return;
     }
{else:}
     if (!checkEmailAddress(document.gccreate.recipient_email))
     {
        document.gccreate.recipient_email.focus();
        return;
     }
{end:}
	 document.gccreate.submit();
	}
function borderChanged()
{
    var border_img = document.getElementById("border_img")
    border_img.src = '{gc.bordersDir}' + document.gccreate.border.options[document.gccreate.border.selectedIndex].text + '.gif'
}
</script>

<table cellpadding=5>
<tr>
<td><img src="images/modules/GiftCertificates/gift_certificate.gif" alt=""></td>
<td>
Gift certificates are the perfect solution when you just can't  find the right gift or you're short of time. Gift Certificates make the perfect present for friends, family, and business associates.
</td></tr>
</table>
<form name="gccreate" action="cart.php" method="POST">
<input type="hidden" name="target" value="add_gift_certificate">
<input type="hidden" name="action" value="add">
<input type="hidden" name="gcid" value="{gcid:r}">
<table border="0" width="100%" cellpadding="0">
<tr><td colspan="4"><b><font class="GiftCertificateTitle">1. Who are you sending this to?<br>
</font></b>
The gift certificate will include the sender's name, the recipient's name and a message.<BR><BR>
</td></tr>

<tr>
<td align="right">From</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="purchaser" size="30" value="{purchaser}"></td>
<td>&nbsp;<widget class="XLite_Validator_RequiredValidator" field="purchaser"></td>
</tr>

<tr>
<td align="right">To</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient" size="30" value="{recipient:r}"> </td>
<td>&nbsp;<widget class="XLite_Validator_RequiredValidator" field="recipient"></td>
</tr>

<tr>
<td colspan="4"><b><font class="GiftCertificateTitle"><br>2. Add a message<br></font></b>
</td>
</tr>

<tr>
<td align="right">Message:</td>
<td>&nbsp;</td>
<td align="left" colspan=2><textarea name="message" rows="4" cols="50">{message}</textarea></td>
</tr>

<tr>
<td colspan="4"><b><font class="GiftCertificateTitle"><br>3. Choose an amount<br></font></b>
Specify the amount in currency.<br><br>
</td>
</tr>

<tr>
<td align="right">Amount</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="amount" size="6" value="{amount}">
{price_format(config.GiftCertificates.minAmount):h} - {price_format(config.GiftCertificates.maxAmount):h}
</td>
<td>&nbsp;<widget class="XLite_Validator_RangeValidator" field="amount" min="{config.GiftCertificates.minAmount}" max="{config.GiftCertificates.maxAmount}"></td>
</tr>

<tr valign="middle" IF="!gc.gcid">
<td colspan="3"><br>
<widget class="XLite_Module_GiftCertificates_View_SpambotArrest" id="on_add_giftcert{gc.gcid}">
</td>    
</tr>

<tr><td colspan="4">&nbsp;</td></tr>

<tr><td colspan="4"><b><font class="GiftCertificateTitle"><br>4. Choose a delivery method<br><br></font></b></td>
</tr>

<tr><td colspan="4">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td align="right" IF="config.GiftCertificates.enablePostGC"><INPUT type="radio" name="send_via" value="E" checked="{send_via=#E#}"></td>
<span IF="!config.GiftCertificates.enablePostGC"><INPUT type="hidden" name="send_via" value="E"></span>
<td><b>Send via E-mail</b></td>
</tr></table></td>
</tr>

<tr><td colspan="4">Enter the e-mail who you send a Gift Certificate to.<br><br></td></tr>

<tr>
<td nowrap align="right">E-mail</td>
<td><font class="Star">*</font></td>
<td align="left" colspan=2><input type="text" name="recipient_email" size="30" value="{recipient_email:r}">
<br>&nbsp;
</td>
</tr>
<tr IF="gc.hasECards()">
<td nowrap align="right">E-Card</td>
<td>&nbsp;</td>
<td align="left" colspan=2>
<table border=0 cellpadding=3 cellspacing=0>
<tr IF="gc.ecard_id">
<td>    
    <img src="{gc.eCard.thumbnail.url}">
</td>
</tr>
<tr IF="gc.ecard_id">
<td>
    <widget class="XLite_View_Button" href="javascript: document.gccreate.action.value='delete_ecard';document.gccreate.submit()" label="Delete e-Card">
</td>
</tr>
<tr>
<td>
    <widget class="XLite_View_Button" href="javascript: document.gccreate.action.value='select_ecard';document.gccreate.submit()" label="Select e-Card">
</td>
</tr>
</table>
</td>
</tr>
<tbody IF="gc.ecard_id">
<tr valign="top">
<td nowrap align="right">E-Card greetings</td>
<td>&nbsp;</td>
<td align="left">
    <input type="text" name="greetings" value="{greetings}">
    <br><i>examples: 'Hi,' 'Dear'</i>
    <br>&nbsp;
</td>
</tr>
<tr valign="top">
<td nowrap align="right">E-Card farewell</td>
<td>&nbsp;</td>
<td align="left">
    <input type="text" name="farewell" value="{farewell}">
    <br><i>examples: 'From ', 'Lovely, '</i>
    <br>&nbsp;
</td>
</tr>
<tr IF="gc.ecard.needBorder">
<td nowrap align="right">E-Card border</td>
<td>&nbsp;</td>
<td align="left">
    <select name="border" onChange="borderChanged()">
    <option FOREACH="gc.ecard.allBorders,_border" selected="border=_border">{_border}</option>
    </select>&nbsp;&nbsp;&nbsp;<img ID="border_img" src="{gc.bordersDir}{border}.gif">
    <br>&nbsp;
</td>
</tr>

<tr valign="top">
<td nowrap align="right">&nbsp;</td>
<td>&nbsp;</td>
<td align="left">
<widget class="XLite_View_Button" href="javascript: document.gccreate.action.value='preview_ecard';document.gccreate.submit()" label="Preview e-Card">
    <br>&nbsp;
</td>
</tr>
</tbody>

<tbody IF="config.GiftCertificates.enablePostGC">

<tr><td colspan=4>&nbsp;</td></tr>

<tr><td colspan=4>&nbsp;</td></tr>

<tr><td colspan=4><table border=0 cellspacing=0 cellpadding=0><tr>
<td align=right><INPUT type=radio name="send_via" value="P" checked="{send_via=#P#}"></td>
<td><b>Send via Postal Mail</b></td>
</tr></table></td>
</tr>

<tr><td colspan=4>Enter the postal address who you're sending a Gift Certificate to.<br><br></td></tr>

<tr>
<td nowrap align=right>First name</td>
<td><font class="Star">*</font></td>
<td align=left colspan=2><input type=text name=recipient_firstname size=30 value="{recipient_firstname:r}"></td>
</tr>

<tr>
<td nowrap align=right>Last name</td>
<td><font class="Star">*</font></td>
<td align=left colspan=2><input type=text name=recipient_lastname size=30 value="{recipient_lastname:r}"></td>
</tr>

<tr>
<td nowrap align=right>Address</td>
<td><font class="Star">*</font></td>
<td align=left colspan=2><input type=text name=recipient_address size=40 value="{recipient_address:r}"></td>
</tr>

<tr>
<td nowrap align=right>City</td>
<td><font class="Star">*</font></td>
<td align=left colspan=2><input type=text name=recipient_city size=30 value="{recipient_city:r}"></td>
</tr>

<tr>
<td nowrap align=right>ZIP code</td>
<td><font class="Star">*</font></td>
<td align=left colspan=2><input type=text name=recipient_zipcode size=30 value="{recipient_zipcode:r}"></td>
</tr>

<tr>
<td nowrap align=right>State</td>
<td><font class="Star">*</font></td>
{if:versionUpper2_1}
    <td>
        <widget class="XLite_View_StateSelect" field="recipient_state" onChange="javascript: changeState(this, 'recipient');" fieldId="recipient_state_select">
    </td>
    <td>
        <widget class="XLite_Validator_StateValidator" field="recipient_state" countryField="recipient_country">
    </td>
{else:}
    <td>
        <widget class="XLite_View_StateSelect" field="recipient_state" fieldId="recipient_state_select">
    </td>
{end:}
</tr>

{if:versionUpper2_1}
<tr id="recipient_custom_state_body">
	<td align=right>Other state</td>
	<td>&nbsp;</td>
	<td><input type="text" name="recipient_custom_state" value="{recipient_custom_state:r}" size="32" maxlength="64">
	</td>
	<td>
		&nbsp;
	</td>
</tr>
{end:}

<tr>
<td nowrap align=right>Country</td>
<td><font class="Star">*</font></td>
{if:versionUpper2_1}
<td>
    <widget class="XLite_View_CountrySelect" field="recipient_country" onChange="javascript: populateStates(this,'recipient');" fieldId="recipient_country_select">
</td>
<td>
    <widget class="XLite_Validator_RequiredValidator" field="recipient_country">
</td>
{else:}
<td>
    <widget class="XLite_View_CountrySelect" field="recipient_country"  fieldId="recipient_country_select">
</td>
{end:}
</tr>

<tr>
<td nowrap align=right>Phone</td>
<td>&nbsp;</td>
<td align=left colspan=2><input type=text name=recipient_phone size=30 value="{recipient_phone:r}"></td>
</tr>

</tbody>

</table>
</form>
<p>
<center>

<widget IF="isGCAdded()" class="XLite_View_Button" href="javascript: formSubmit()" label="Update"/>
<widget IF="!isGCAdded()" class="XLite_View_Button" href="javascript: formSubmit()" label="Add to cart"/>
</center>

<script lang="text/javascript">
function initStates() {        
	var elm = document.getElementById('recipient_state_select');
	if (elm)
	{
		changeState(elm, "recipient");
	}
}

var elm = document.getElementById("recipient_country_select");
if (elm) {
    populateStates(elm,"recipient",true);
}
initStates();
</script>

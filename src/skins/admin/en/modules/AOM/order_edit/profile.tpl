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
<script>
    function showUsersForm()
    {
		var user_window;
        if (user_window) user_window.close();
		user_window = window.open("admin.php?target={target}&mode=show_users&order_id={order_id}","select_users","width=500,height=500,scrollbars=yes"); 
	}
	function copyBillingInfo()
	{
		var fields = new Array("title","firstname","lastname","phone","fax","company","address","city","zipcode");
		for (var i = 0; i < fields.length; i++) {
			var b_element = document.getElementById("billing_" + fields[i]);
			var s_element = document.getElementById("shipping_" + fields[i]);
			s_element.value = b_element.value;
		}	
		document.update_profile_form.elements["cloned_profile[shipping_country]"].selectedIndex = document.update_profile_form.elements["cloned_profile[billing_country]"].selectedIndex;
        document.update_profile_form.elements["cloned_profile[shipping_state]"].selectedIndex = document.update_profile_form.elements["cloned_profile[billing_state]"].selectedIndex;
		
	}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr valign="top">
	        <td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}: Customer information</td>
	        <td IF="target=#order#" align="right" valign="center"><widget template="modules/AOM/common/clone_button.tpl"></td>
	</tr>
</table>	
<br>
<table IF="target=#order#" width="100%" cellpadding="3" cellspacing="2">
<tr height="25" class="AomTableHead">
	<td width="200"><b style="font-size: 12px">Properties</b></td>
	<td width="250"><b style="font-size: 12px">Original</b></td>
    <td><b style="font-size: 12px">Current</b></td>
</tr>
</table>
<table IF="target=#create_order#" cellpadding="3" cellspacing="0" width="100%">
	<tr height="25" class="AomTableHead">
    	<td width="200"><b style="font-size: 12px">Properties</b></td>
		<td>&nbsp;</td>
	</tr>
</table>
<form action="admin.php" method="POST" name="update_profile_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update_profile">
<input type="hidden" name="mode" value="profile">
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="3" cellspacing="2">
<tr class="{getProfileRow(#TableRow#)}" height="25">
	<td width="200" class="ProductDetailsTitle">Login</td>
	<td IF="target=#order#" width="250">{if:profile.login}{profile.login:h}{else:}N/A{end:}</td>
	<td nowrap><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="left"><input class="Input" style="width: 150" type="text" name="cloned_profile[login]" value="{cloneProfile.login:h}"></td><td align="right"><input type="button" value="Lookup user" onClick="javascript: showUsersForm();"></td></tr></table></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Membership</td>
	    <td IF="target=#order#">{if:profile.membership}{profile.membership:h}{else:}No membership{end:}</td>
	    <td><widget class="XLite_View_MembershipSelect" field="cloned_profile[membership]" value="{cloneProfile.membership}"></td>
</tr>
</table>
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="0" cellspacing="0">
<tr>
    <td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" class="OrderTitle" style="font-size: 14px">Billing info</td>
</tr>
<tr valign="top">
    <td colspan="3"><hr style="background-color: #516176; height: 2px"></td>
</tr>
</table>
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="3" cellspacing="2">
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td width="200" class="ProductDetailsTitle">Title</td>
	<td IF="target=#order#" width="250">{if:profile.billing_title}{profile.billing_title:h}{else:}N/A{end:}</td>
    <td><select id="billing_title" name="cloned_profile[billing_title]">
		<option value="Mr." selected="{isSelected(cloneProfile.billing_title,#Mr.#)}">Mr.</option>
        <option value="Mrs." selected="{isSelected(cloneProfile.billing_title,#Mrs.#)}">Mrs.</option>
        <option value="Ms." selected="{isSelected(cloneProfile.billing_title,#Ms.#)}">Ms.</option>
	   	</select>
	</td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">First name</td>
    <td IF="target=#order#">{if:profile.billing_firstname}{profile.billing_firstname:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="billing_firstname" type="text" name="cloned_profile[billing_firstname]" value="{cloneProfile.billing_firstname:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Last name</td>
    <td IF="target=#order#">{if:profile.billing_lastname}{profile.billing_lastname:h}{else:}N/A{end:}</td>
	<td><input class="Input" id="billing_lastname" type="text" name="cloned_profile[billing_lastname]" value="{cloneProfile.billing_lastname}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Phone</td>
    <td IF="target=#order#">{if:profile.billing_phone}{profile.billing_phone:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="billing_phone" type="text" name="cloned_profile[billing_phone]" value="{cloneProfile.billing_phone:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Fax</td>
    <td IF="target=#order#">{if:profile.billing_fax}{profile.billing_fax:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="billing_fax" type="text" name="cloned_profile[billing_fax]" value="{cloneProfile.billing_fax:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Company</td>
    <td IF="target=#order#">{if:profile.billing_company}{profile.billing_company:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="billing_company" type="text" name="cloned_profile[billing_company]" value="{cloneProfile.billing_company:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Address</td>
    <td IF="target=#order#">{if:profile.billing_address}{profile.billing_address:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="billing_address" type="text" name="cloned_profile[billing_address]" value="{cloneProfile.billing_address:h}" size="32"></td>
	</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">City</td>
    <td IF="target=#order#">{if:profile.billing_city}{profile.billing_city:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="billing_city" type="text" name="cloned_profile[billing_city]" value="{cloneProfile.billing_city:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">State/Province</td>
    <td IF="target=#order#">{if:profile.billingState}{profile.billingState.state:h}{else:}N/A{end:}</td>
    <td><widget class="XLite_View_StateSelect" field="cloned_profile[billing_state]" value="{cloneProfile.billing_state}"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Country</td>
    <td IF="target=#order#">{if:profile.billingCountry}{profile.billingCountry.country:h}{else:}N/A{end:}</td>
    <td><widget class="XLite_View_CountrySelect" field="cloned_profile[billing_country]" value="{cloneProfile.billing_country}"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Zip/Postal code</td>
    <td IF="target=#order#">{if:profile.billing_zipcode}{profile.billing_zipcode:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="billing_zipcode" type="text" name="cloned_profile[billing_zipcode]" value="{cloneProfile.billing_zipcode:h}" size="32"></td>
</tr>
</table>
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="0" cellspacing="0">
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" class="OrderTitle" style="font-size: 14px">Shipping info</td>
	<td class="ProductDetailsTitle" align="right" valign="center"><input type="button" onClick="javascript: copyBillingInfo(); this.blur();" value="Copy Billing Info"></td>	
</tr>
<tr valign="top">
    <td colspan="3"><hr style="background-color: #516176; height: 2px"></td>
</tr>
</table>
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="3" cellspacing="2">
<tr style="display: none" class="{getProfileRow(#TableRow#)}">
	<td colspan="3">&nbsp;</td>	
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td width="200" class="ProductDetailsTitle">Title</td>
    <td IF="target=#order#" width="250">{if:profile.shipping_title}{profile.shipping_title:h}{else:}N/A{end:}</td>
    <td><select id="shipping_title" name="cloned_profile[shipping_title]">
		<option value="Mr." selected="{isSelected(cloneProfile.shipping_title,#Mr.#)}">Mr.</option>
        <option value="Mrs." selected="{isSelected(cloneProfile.shipping_title,#Mrs.#)}">Mrs.</option>
        <option value="Ms." selected="{isSelected(cloneProfile.shipping_title,#Ms.#)}">Ms.</option>
	   	</select>
	</td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">First name</td>
    <td IF="target=#order#">{if:profile.shipping_firstname}{profile.shipping_firstname:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="shipping_firstname" type="text" name="cloned_profile[shipping_firstname]" value="{cloneProfile.shipping_firstname:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Last name</td>
    <td IF="target=#order#">{if:profile.shipping_lastname}{profile.shipping_lastname:h}{else:}N/A{end:}</td>
	<td><input class="Input" id="shipping_lastname" type="text" name="cloned_profile[shipping_lastname]" value="{cloneProfile.shipping_lastname}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Phone</td>
    <td IF="target=#order#">{if:profile.shipping_phone}{profile.shipping_phone:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="shipping_phone" type="text" name="cloned_profile[shipping_phone]" value="{cloneProfile.shipping_phone:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Fax</td>
    <td IF="target=#order#">{if:profile.shipping_fax}{profile.shipping_fax:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="shipping_fax" type="text" name="cloned_profile[shipping_fax]" value="{cloneProfile.shipping_fax:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Company</td>
    <td IF="target=#order#">{if:profile.shipping_company}{profile.shipping_company:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="shipping_company" type="text" name="cloned_profile[shipping_company]" value="{cloneProfile.shipping_company:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Address</td>
    <td IF="target=#order#">{if:profile.shipping_address}{profile.shipping_address:h}{else:}N/A{end:}</td>
    <td><input class="Input" id="shipping_address" type="text" name="cloned_profile[shipping_address]" value="{cloneProfile.shipping_address:h}" size="32"></td>
	</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">City</td>
    <td IF="target=#order#">{if:profile.shipping_city}{profile.shipping_city:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="shipping_city" type="text" name="cloned_profile[shipping_city]" value="{cloneProfile.shipping_city:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">State/Province</td>
    <td IF="target=#order#">{if:profile.shippingState}{profile.shippingState.state:h}{else:}N/A{end:}</td>
    <td><widget class="XLite_View_StateSelect" field="cloned_profile[shipping_state]" value="{cloneProfile.shipping_state}"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Country</td>
    <td IF="target=#order#">{if:profile.shippingCountry}{profile.shippingCountry.country:h}{else:}N/A{end:}</td>
    <td><widget class="XLite_View_CountrySelect" field="cloned_profile[shipping_country]" value="{cloneProfile.shipping_country}"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25">
    <td class="ProductDetailsTitle">Zip/Postal code</td>
    <td IF="target=#order#">{if:profile.shipping_zipcode}{profile.shipping_zipcode:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="shipping_zipcode" type="text" name="cloned_profile[shipping_zipcode]" value="{cloneProfile.shipping_zipcode:h}" size="32"></td>
</tr>
</table>

<span IF="xlite.WholesaleTradingEnabled&isShowWholesalerFields()">
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="0" cellspacing="0">
<tr>
    <td colspan="3">&nbsp;</td>
</tr>
<tr>
    <td colspan="3" class="OrderTitle" style="font-size: 14px">Wholesaler details</td>
</tr>
<tr valign="top">
    <td colspan="3"><hr style="background-color: #516176; height: 2px"></td>
</tr>
</table>
<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="3" cellspacing="2">
<tr style="display: none" class="{getProfileRow(#TableRow#)}">
    <td colspan="3">&nbsp;</td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25" IF="xlite.config.WholesaleTrading.WholesalerFieldsTaxId">
    <td width="200" class="ProductDetailsTitle">Sales Permit/Tax ID#</td>
    <td IF="target=#order#" width="250">{if:profile.tax_id}{profile.tax_id:h}{else:}N/A{end:}</td>
	<td><input class="Input" style="width: 50%" id="tax_id" type="text" name="cloned_profile[tax_id]" value="{cloneProfile.tax_id:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25" IF="xlite.config.WholesaleTrading.WholesalerFieldsVat">
    <td width="200" class="ProductDetailsTitle">VAT Registration number</td>
    <td IF="target=#order#" width="250">{if:profile.vat_number}{profile.vat_number:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="vat_number" type="text" name="cloned_profile[vat_number]" value="{cloneProfile.vat_number:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25" IF="xlite.config.WholesaleTrading.WholesalerFieldsGst">
    <td width="200" class="ProductDetailsTitle">GST Registration number</td>
    <td IF="target=#order#" width="250">{if:profile.gst_number}{profile.gst_number:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="gst_number" type="text" name="cloned_profile[gst_number]" value="{cloneProfile.gst_number:h}" size="32"></td>
</tr>
<tr class="{getProfileRow(#TableRow#)}" height="25" IF="xlite.config.WholesaleTrading.WholesalerFieldsPst">
    <td width="200" class="ProductDetailsTitle">PST Registration number</td>
    <td IF="target=#order#" width="250">{if:profile.pst_number}{profile.pst_number:h}{else:}N/A{end:}</td>
    <td><input class="Input" style="width: 50%" id="pst_number" type="text" name="cloned_profile[pst_number]" value="{cloneProfile.pst_number:h}" size="32"></td>
</tr>
</table>
</span>

<table {if:target=#order#}width="100%"{else:}width="465"{end:} cellpadding="3" cellspacing="2">
<tr class="{getProfileRow(#TableRow#)}" IF="target=#order#">
    <td width="200">&nbsp;</td>
	<td width="250">&nbsp;</td>
	<td ><input type="submit" class="UpdateButton" value=" Update "></td>
</tr>
</table>
<table IF="target=#create_order#" width="100%" cellpadding="3" cellspacing="0">
<tr class="{getProfileRow(#TableRow#)}">
	<td width="200">&nbsp;</td>
    <td width="250" align="left"><input type="submit" class="UpdateButton" value=" Update "></td>
	<td>&nbsp;</td>	
</tr>
</table>
</form>
<br>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr height="2" class="TableRow">
    <td colspan="2"><img src="images/spacer.gif" width="1" height="1" border="0"></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td><a class="AomMenu" href="javascript: Previous();"><img src="images/back.gif" border="0" width="13" height="13" align="absmiddle">&nbsp;</a><a class="AomMenu" href="javascript: Previous();" id="profile_prev">Previous</a></td>
        <td align="right"><a class="AomMenu" href="javascript: Next();" id="profile_next">Next</a><a class="AomMenu" href="javascript: Next();">&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"></a></td>
    </tr>
</table>
<table width="100%" IF="isCloneUpdated()&!cloneOrder.isEmpty()">
    <tr>
        <td align="right" valign="center">
            <font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order_id}&page=order_preview">Review and Save Order&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a>
        </td>
    </tr>
</table>

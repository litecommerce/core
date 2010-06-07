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
<p align=justify>You can send a message to us using the form below. We will reply as soon as possible!</p>


<script language="JavaScript" type="text/javascript">
function checkEmailAddress(field) {
var goodEmail = field.value.match({emailValidatorRegExp});
    
if (goodEmail) {
    return true;
} else {
        alert("E-mail address is invalid! Please correct");
        field.focus();
        field.select();
        return false;
    }
}
</script>

<form action="cart.php" method=post name=contactus>
<input type="hidden" foreach="params,_param" name="{_param}" value="{get(_param):r}"/>
<input type="hidden" name="action" value="contactus">

<table width="100%" border=0 cellspacing=0 cellpadding=2>

<tr valign=middle>
<td class=FormButton>E-mail</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=email size=32 maxlength=128 value="{email:r}">
<widget class="XLite_Validator_EmailValidator" field="email">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>First Name</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=firstname size=32 maxlength=32 value="{firstname:r}">
<widget class="XLite_Validator_RequiredValidator" field="firstname">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Last Name</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=lastname size=32 maxlength=32 value="{lastname:r}">
<widget class="XLite_Validator_RequiredValidator" field="lastname">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Address</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=address size=32 maxlength=64 value="{address:r}">
<widget class="XLite_Validator_RequiredValidator" field="address">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Zip code</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=zipcode size=32 maxlength=32 value="{zipcode:r}">
<widget class="XLite_Validator_RequiredValidator" field="zipcode">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>City</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=city size=32 maxlength=64 value="{city:r}">
<widget class="XLite_Validator_RequiredValidator" field="city">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>State</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="XLite_View_StateSelect" field="contactus_state" state="{contactus_state}" isLinked=1 />
<widget class="XLite_Validator_StateValidator" field="contactus_state" countryField="contactus_country">
<widget class="XLite_Validator_RequiredValidator" field="contactus_state">
</td>
</tr>

<tr valign="middle">
<td class=FormButton>Other state (specify)</td>
<td>&nbsp;</td>
<td nowrap><input type="text" name="contactus_custom_state" value="{contactus_custom_state:r}" size="32" maxlength="64" /></td>
<td>&nbsp;</td>
</tr>

<tr valign=middle>
<td class=FormButton>Country</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="XLite_View_CountrySelect" field="contactus_country" country="{contactus_country}" />
<widget class="XLite_Validator_RequiredValidator" field="contactus_country">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Phone</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=phone size=32 maxlength=32 value="{phone:r}">
<widget class="XLite_Validator_RequiredValidator" field="phone">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Fax</td>
<td>&nbsp;</td>
<td nowrap>
<input type=text name=fax size=32 maxlength=128 value="{fax:r}"></td>
</tr>

<tr valign=middle>
<td class=FormButton>Department</td>
<td><font class=Star>*</font></td>
<td nowrap>
<select name=department>
<option value="All" selected>All</option>
<option value="Partners">Partners</option>
<option value="Marketing / publicity">Marketing / publicity</option>
<option value="Webdesign">Web design</option>
<option value="Sales">Sales</option>
</select>
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Subject</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name="subj" size=32 maxlength=128 value="{subj:r}">
<widget class="XLite_Validator_RequiredValidator" field="subj">
</td>
</tr>

<tr valign=middle>
<td class=FormButton valign=top>Message</td>
<td valign=top><font class=Star>*</font></td>
<td nowrap>
<textarea cols=48 rows=12 name=body>{body}</textarea>
<widget class="XLite_Validator_RequiredValidator" field="body">
</td>
</tr>

<tr valign="middle">
<td colspan="3">
<widget template="common/spambot_arrest.tpl" id="on_contactus">
</td>    
</tr>

<tr valign=middle>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td nowrap>
<br>
<widget class="XLite_View_Submit" href="javascript: if (checkEmailAddress(document.contactus.email)) document.contactus.submit()" font="FormButton">
</td>
</tr>
</table>

</form>

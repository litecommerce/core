<widget template="js/select_states_begin_js.tpl">

<p align=justify>You can send a message to us using the form below. We will reply as soon as possible!</p>


<script language="JavaScript">
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

<table width=100% border=0 cellspacing=0 cellpadding=2>
<input type="hidden" foreach="params,param" name="{param}" value="{get(param):r}"/>
<input type="hidden" name="action" value="contactus">

<tr valign=middle>
<td class=FormButton>E-mail</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=email size=32 maxlength=128 value="{email:r}">
<widget class="CEmailValidator" field="email">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>First Name</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=firstname size=32 maxlength=32 value="{firstname:r}">
<widget class="CRequiredValidator" field="firstname">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Last Name</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=lastname size=32 maxlength=32 value="{lastname:r}">
<widget class="CRequiredValidator" field="lastname">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Address</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=address size=32 maxlength=64 value="{address:r}">
<widget class="CRequiredValidator" field="address">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Zip code</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=zipcode size=32 maxlength=32 value="{zipcode:r}">
<widget class="CRequiredValidator" field="zipcode">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>City</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=city size=32 maxlength=64 value="{city:r}">
<widget class="CRequiredValidator" field="city">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>State</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="CStateSelect" field="contactus_state" onChange="javascript: changeState(this, 'contactus');" fieldId="contactus_state_select">
<widget class="CStateValidator" field="contactus_state" countryField="contactus_country">
<widget class="CRequiredValidator" field="contactus_state">
</td>
</tr>

<tr valign="middle" id="contactus_custom_state_body">
<td class=FormButton>Other state (specify)</td>
<td>&nbsp;</td>
<td nowrap><input type="text" name="contactus_custom_state" value="{contactus_custom_state:r}" size="32" maxlength="64">
</td>
<td>&nbsp;</td>
</tr>

<tr valign=middle>
<td class=FormButton>Country</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="CCountrySelect" field="contactus_country" onChange="javascript: populateStates(this,'contactus');" fieldId="contactus_country_select">
<widget class="CRequiredValidator" field="contactus_country">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Phone</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=phone size=32 maxlength=32 value="{phone:r}">
<widget class="CRequiredValidator" field="phone">
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
<widget class="CRequiredValidator" field="subj">
</td>
</tr>

<tr valign=middle>
<td class=FormButton valign=top>Message</td>
<td valign=top><font class=Star>*</font></td>
<td nowrap>
<textarea cols=48 rows=12 name=body>{body}</textarea>
<widget class="CRequiredValidator" field="body">
</td>
</tr>

<tr IF="GDLibLoaded" valign="middle">
<td colspan="3">
<widget template="common/spambot_arrest.tpl" id="on_contactus">
</td>    
</tr>

<tr valign=middle>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td nowrap>
<br>
<widget class="CSubmit" href="javascript: if (checkEmailAddress(document.contactus.email)) document.contactus.submit()" font="FormButton">
</td>
</tr>
</table>

</form>

<widget template="js/select_states_end_js.tpl">

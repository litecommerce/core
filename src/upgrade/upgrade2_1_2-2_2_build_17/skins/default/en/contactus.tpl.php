<?php
    $find_str = <<<EOT
<p align=justify>You can send a message to us using the form below. We will reply as soon as possible!</p>


<script language="JavaScript">
<!--
function checkEmailAddress(field) {
var goodEmail = field.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
    
if (goodEmail) {
    return true;
} else {
EOT;
    $replace_str = <<<EOT
<widget template="js/select_states_begin_js.tpl">

<p align=justify>You can send a message to us using the form below. We will reply as soon as possible!</p>

<script language="JavaScript">
function checkEmailAddress(field) {
var goodEmail = field.value.match({emailValidatorRegExp});

if (goodEmail) {
    return true;
} else {
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
        return false;
    }
}
// -->
</script>

<form action="cart.php" method=post name=contactus>
EOT;
    $replace_str = <<<EOT
        return false;
    }
}
</script>

<form action="cart.php" method=post name=contactus>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=email size=32 maxlength=128 value="{email:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=email size=32 maxlength=128 value="{email:r}">
<widget class="CEmailValidator" field="email">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=firstname size=32 maxlength=32 value="{firstname:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=firstname size=32 maxlength=32 value="{firstname:r}">
<widget class="CRequiredValidator" field="firstname">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=lastname size=32 maxlength=32 value="{lastname:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=lastname size=32 maxlength=32 value="{lastname:r}">
<widget class="CRequiredValidator" field="lastname">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=address size=32 maxlength=64 value="{address:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=address size=32 maxlength=64 value="{address:r}">
<widget class="CRequiredValidator" field="address">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=zipcode size=32 maxlength=32 value="{zipcode:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=zipcode size=32 maxlength=32 value="{zipcode:r}">
<widget class="CRequiredValidator" field="zipcode">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=city size=32 maxlength=64 value="{city:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=city size=32 maxlength=64 value="{city:r}">
<widget class="CRequiredValidator" field="city">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td class=FormButton>State</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="CStateSelect" field="state_id">
</td>
</tr>

<tr valign=middle>
<td class=FormButton>Country</td>
<td><font class=Star>*</font></td>
<td nowrap>
<widget class="CCountrySelect" field="country_id">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
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

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=phone size=32 maxlength=32 value="{phone:r}">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=phone size=32 maxlength=32 value="{phone:r}">
<widget class="CRequiredValidator" field="phone">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td class=FormButton>Subject</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name=subj size=32 maxlength=128 value="">
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td class=FormButton>Subject</td>
<td><font class=Star>*</font></td>
<td nowrap>
<input type=text name="subj" size=32 maxlength=128 value="{subj:r}">
<widget class="CRequiredValidator" field="subj">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<td class=FormButton valign=top>Message</td>
<td valign=top><font class=Star>*</font></td>
<td nowrap>
<textarea cols=48 rows=12 name=body></textarea>
</td>
</tr>

EOT;
    $replace_str = <<<EOT
<td class=FormButton valign=top>Message</td>
<td valign=top><font class=Star>*</font></td>
<td nowrap>
<textarea cols=48 rows=12 name=body>{body}</textarea>
<widget class="CRequiredValidator" field="body">
</td>
</tr>

EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</table>

</form>
EOT;
    $replace_str = <<<EOT
</table>

</form>

<widget template="js/select_states_end_js.tpl">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>

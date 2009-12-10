<?php
    $find_str = <<<EOT
<p align=justify>The form below allows you to create a profile which is necessary to place orders. Do not forget that this information is essential to use our services correctly.</p> 

<p align=justify>If you already have an account, please <a href="cart.php?target=profile&mode=login&returnUrl={dialog.backUrl:u}"><u>login from here</u></a>. If you are a new customer we need to know your personal details. Please fill in the form below and click 'Submit'.</p>
EOT;
    $replace_str = <<<EOT
<widget template="js/select_states_begin_js.tpl">

<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/billing_shipping.js"></script>

<p align=justify>The form below allows you to create a profile which is necessary to place orders. Do not forget that this information is essential to use our services correctly.</p> 

<p align=justify>If you already have an account, please <a href="cart.php?target=profile&mode=login&returnUrl={dialog.backUrl:u}"><u>login from here</u></a>. If you are a new customer we need to know your personal details. Please fill in the form below and click 'Submit'.</p>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right" width="150">E-mail</td>
    <td width="10"><font color="red">*</font></td>
    <td width="150">
        <input type="text" name="login" value="{login:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right" width="150">E-mail</td>
    <td width="10"><font class="Star">*</font></td>
    <td width="150">
        <input type="text" name="login" value="{login:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password" visible="{!allowAnonymous}">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Password hint</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="password_hint" value="{password_hint:r}" size="32" maxlength="128">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Password hint answer</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="password_hint_answer" value="{password_hint_answer:r}" size="32" maxlength="128">
    </td>
    <td>
    </td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
EOT;
    $replace_str = <<<EOT
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password" visible="{!allowAnonymous}">
    </td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="billing_firstname" value="{billing_firstname:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_firstname" value="{billing_firstname:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="billing_lastname" value="{billing_lastname:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_lastname" value="{billing_lastname:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="billing_phone" value="{billing_phone:r}" size="32" maxlength="32">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_phone" value="{billing_phone:r}" size="32" maxlength="32">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="billing_address" value="{billing_address:r}" size="32" maxlength="64">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_address" value="{billing_address:r}" size="32" maxlength="64">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="billing_city" value="{billing_city:r}" size="32" maxlength="64">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_city" value="{billing_city:r}" size="32" maxlength="64">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">State</td>
    <td><font color="red">*</font></td>
    <td>
		<widget class="CStateSelect" field="billing_state">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_state">
        <widget class="CStateValidator" field="billing_state" countryField="billing_country">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Country</td>
    <td><font color="red">*</font></td>
    <td>
		<widget class="CCountrySelect" field="billing_country">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_country">
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">State</td>
    <td><font class="Star">*</font></td>
    <td>
		<widget class="CStateSelect" field="billing_state" onChange="javascript: changeState(this, 'billing');" fieldId="billing_state_select">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_state">
        <widget class="CStateValidator" field="billing_state" countryField="billing_country">
    </td>
</tr>
<tr valign="middle" id="billing_custom_state_body">
	<td align="right">Other state (specify)</td>
	<td>&nbsp;</td>
	<td><input type="text" name="billing_custom_state" value="{billing_custom_state:r}" size="32" maxlength="64">
	</td>
	<td>
		&nbsp;
	</td>
</tr>
<tr valign="middle">
    <td align="right">Country</td>
    <td><font class="Star">*</font></td>
    <td>
		<widget class="CCountrySelect" field="billing_country" onChange="javascript: populateStates(this,'billing');" fieldId="billing_country_select">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_country">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font color="red">*</font></td>
    <td nowrap>
        <input type="text" name="billing_zipcode" value="{billing_zipcode:r}" size="32" maxlength="32">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font class="Star">*</font></td>
    <td nowrap>
        <input type="text" name="billing_zipcode" value="{billing_zipcode:r}" size="32" maxlength="32">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<!-- ********************************* SHIPPING ADDRESS ********************************* -->

<tr valign="middle">
    <td colspan="4"><b>Shipping Address (leave empty if same as billing address)</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Title</td>
EOT;
    $replace_str = <<<EOT
<!-- ********************************* SHIPPING ADDRESS ********************************* -->

<tr valign="middle">
    <td colspan="4"><b>Shipping Address (leave empty if same as billing address)</b><br><hr size="1" noshade><widget class="CButton" label="Copy Billing Info" href="javascript: copyBillingInfo(document.registration_form);"></td>
</tr>
<tr valign="middle">
    <td align="right">Title</td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="shipping_firstname" value="{shipping_firstname:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_firstname" value="{shipping_firstname:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="shipping_lastname" value="{shipping_lastname:r}" size="32" maxlength="128">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_lastname" value="{shipping_lastname:r}" size="32" maxlength="128">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="shipping_phone" value="{shipping_phone:r}" size="32" maxlength="32">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_phone" value="{shipping_phone:r}" size="32" maxlength="32">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="shipping_address" value="{shipping_address:r}" size="32" maxlength="64">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_address" value="{shipping_address:r}" size="32" maxlength="64">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="text" name="shipping_city" value="{shipping_city:r}" size="32" maxlength="64">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_city" value="{shipping_city:r}" size="32" maxlength="64">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">State</td>
    <td><font color="red">*</font></td>
    <td>
		<widget class="CStateSelect" field="shipping_state" value="{shipping_state}">
    </td>
    <td>
        <widget class="CStateValidator" field="shipping_state" countryField="shipping_country">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Country</td>
    <td><font color="red">*</font></td>
    <td>
		<widget class="CCountrySelect" field="shipping_country">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font color="red">*</font></td>
    <td nowrap>
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
EOT;
    $replace_str = <<<EOT
</tr>
<tr valign="middle">
    <td align="right">State</td>
    <td><font class="Star">*</font></td>
    <td>
		<widget class="CStateSelect" field="shipping_state" value="{shipping_state}" onChange="javascript: changeState(this, 'shipping');" fieldId="shipping_state_select">
    </td>
    <td>
        <widget class="CStateValidator" field="shipping_state" countryField="shipping_country">
    </td>
</tr>
<tr valign="middle" id="shipping_custom_state_body">
	<td align="right">Other state (specify)</td>
	<td>&nbsp;</td>
	<td><input type="text" name="shipping_custom_state" value="{shipping_custom_state:r}" size="32" maxlength="64">
	</td>
	<td>
		&nbsp;
	</td>
</tr>
<tr valign="middle">
    <td align="right">Country</td>
    <td><font class="Star">*</font></td>
    <td>
		<widget class="CCountrySelect" field="shipping_country" onChange="javascript: populateStates(this,'shipping');" fieldId="shipping_country_select">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font class="Star">*</font></td>
    <td nowrap>
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
    <td colspan="4">&nbsp;</td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>Signup for membership</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Membership</td>
    <td>&nbsp;</td>
    <td><widget class="CMembershipSelect" field="pending_membership">
	</td>
EOT;
    $replace_str = <<<EOT
    <td colspan="4">&nbsp;</td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>Membership</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Request membership</td>
    <td>&nbsp;</td>
    <td><widget class="CMembershipSelect" field="pending_membership">
	</td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tbody>

{*extraFields*}
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">

</table>
EOT;
    $replace_str = <<<EOT
</tbody>

{*extraFields*}
<widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_form.tpl">
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">

</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<tr valign="middle">
    <td width="160">&nbsp;</td>
    <td >
        By clicking "SUBMIT" you are agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>
        <br>
        <input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>
        <input type="hidden" name="action" value="{mode}">
EOT;
    $replace_str = <<<EOT
<tr valign="middle">
    <td width="160">&nbsp;</td>
    <td >
        By clicking "SUBMIT" you agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>
        <br>
        <input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>
        <input type="hidden" name="action" value="{mode}">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
</table>
</form>
EOT;
    $replace_str = <<<EOT
</tr>
</table>
</form>

<widget template="js/select_states_end_js.tpl">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>

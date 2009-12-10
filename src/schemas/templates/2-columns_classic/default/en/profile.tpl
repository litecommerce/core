<widget template="js/select_states_begin_js.tpl">

<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/billing_shipping.js"></script>

This form allows you to modify your profile so that your data is always valid.  Do not forget that this information is essential to use our services correctly. Fields which are marked with <font class="Star">*</font> are mandatory.

<widget module="Promotion" template="modules/Promotion/bonus_points.tpl">
<p>
<form action="cart.php" method="POST" name="profile_form">
<table>
<tr IF="success">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;Your profile has been updated successfully&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="!valid">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Your profile has not been updated!&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="userExists">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="userAdmin">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user's profile of {login} should be modified in admin back-end!&nbsp;&lt;&lt;</font></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td colspan="4"><b>E-mail & Password</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right" width="150">E-mail</td>
    <td width="10"><font class="Star">*</font></td>
    <td width="150">
        <input type="text" name="login" value="{login:r}" size="32" maxlength="128">
    </td>
    <td>
        <widget class="CEmailValidator" field="login">
    </td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>
Please leave the password fields empty<br> if you don't want to change the password.
</td></tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td>&nbsp;</td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
    <td>
		&nbsp;
    </td>
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td>&nbsp;</td>
    <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" size="32" maxlength="128">
    </td>
    <td>
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password">
    </td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>

<!-- ********************************* BILLING ADDRESS ********************************* -->

<tr valign="middle">
    <td colspan="4"><b>Billing Address</b><br><hr size="1" noshade></td>
</tr>
<tr valign="middle">
    <td align="right">Title</td>
    <td>&nbsp;</td>
    <td>
        <select name="billing_title">
            <option value="Mr." selected="{billing_title=#Mr.#}">Mr.</option>
            <option value="Mrs." selected="{billing_title=#Mrs.#}">Mrs.</option>
            <option value="Ms." selected="{billing_title=#Ms.#}">Ms.</option>
        </select>
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_firstname" value="{billing_firstname:r}" size="32" maxlength="128">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_firstname">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_lastname" value="{billing_lastname:r}" size="32" maxlength="128">
    </td>
    <td align="left">
        <widget class="CRequiredValidator" field="billing_lastname">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Company</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="billing_company" value="{billing_company:r}" size="32" maxlength="255">
    </td>        
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_phone" value="{billing_phone:r}" size="32" maxlength="32">
    </td>    
    <td>
        <widget class="CRequiredValidator" field="billing_phone">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Fax</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="billing_fax" value="{billing_fax:r}" size="32" maxlength="32">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_address" value="{billing_address:r}" size="32" maxlength="64">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_address">
    </td>
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="billing_city" value="{billing_city:r}" size="32" maxlength="64">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_city">
    </td>
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
    </td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font class="Star">*</font></td>
    <td nowrap>
        <input type="text" name="billing_zipcode" value="{billing_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>
        <widget class="CRequiredValidator" field="billing_zipcode">
    </td>
</tr>

<tr>
    <td colspan="4">&nbsp;</td>
</tr>

<!-- ********************************* SHIPPING ADDRESS ********************************* -->

<tr valign="middle">
    <td colspan="4"><b>Shipping Address (leave empty if same as billing address)</b><br><hr size="1" noshade>
		<div id="btn_copy_billing"><widget class="CButton" label="Copy Billing Info" href="javascript: copyBillingInfo(document.profile_form);"></div>
		<div id="btn_modify_shipping" style="display: none;"><widget class="CButton" label="Modify Shipping address" href="javascript: OnModifyShippingAddress(document.profile_form);"></div>
	</td>
</tr>
<tbody id="shipping_body">
<tr valign="middle">
    <td align="right">Title</td>
    <td>&nbsp;</td>
    <td>
        <select name="shipping_title">
            <option value="Mr." selected="{shipping_title=#Mr.#}">Mr.</option>
            <option value="Mrs." selected="{shipping_title=#Mrs.#}">Mrs.</option>
            <option value="Ms." selected="{shipping_title=#Ms.#}">Ms.</option>
        </select>
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">First Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_firstname" value="{shipping_firstname:r}" size="32" maxlength="128">
    </td>
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Last Name</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_lastname" value="{shipping_lastname:r}" size="32" maxlength="128">
    </td>
    <td align="left">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Company</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="shipping_company" value="{shipping_company:r}" size="32" maxlength="255">
    </td>        
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Phone</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_phone" value="{shipping_phone:r}" size="32" maxlength="32">
    </td>    
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Fax</td>
    <td>&nbsp;</td>
    <td>
        <input type="text" name="shipping_fax" value="{shipping_fax:r}" size="32" maxlength="32">
    </td>
    <td>
    </td>
</tr>
<tr valign="middle">
    <td align="right">Address</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_address" value="{shipping_address:r}" size="32" maxlength="64">
    </td>
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">City</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="shipping_city" value="{shipping_city:r}" size="32" maxlength="64">
    </td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font class="Star">*</font></td>
    <td nowrap>
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>&nbsp;</td>
</tr>

<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/notice_register.tpl">
</tbody>

<tbody IF="showMembership">
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>Membership</b><br><hr size="1" noshade></td>
</tr>
<tr IF="!membership=##" valign="middle">
    <td align="right">Current membership</td>
    <td>&nbsp;</td>
    <td>
	{membership}&nbsp;<widget module="WholesaleTrading" template="modules/WholesaleTrading/membership/exp_date.tpl">
	</td>
</tr>
<tr valign="middle">
    <td align="right">Request membership</td>
    <td>&nbsp;</td>
    <td><widget class="CMembershipSelect" field="pending_membership">
	</td>
</tr>
</tbody>

<widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_form.tpl">
{*extraFields*}
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">
<widget module="GiftCertificates" template="modules/GiftCertificates/active_certificates.tpl">

</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td width="160">&nbsp;</td>
    <td >
        By clicking "SUBMIT" you agree with our <a href="cart.php?target=help&mode=terms_conditions" style="TEXT-DECORATION: underline" target="_blank">Terms &amp; Conditions.</a><br>
        <br>
        <input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>
        <input type="hidden" name="action" value="{mode}">
        <widget class="CSubmit" href="javascript: document.profile_form.submit()" font="FormButton">
    </td>
</tr>
</table>
</form>

<widget template="js/select_states_end_js.tpl">

<script type="text/javascript" language="JavaScript 1.2">
	CheckBillingShipping(document.profile_form);
</script>

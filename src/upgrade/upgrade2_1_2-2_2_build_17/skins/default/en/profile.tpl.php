<?php
    $find_str = <<<EOT
This form allows you to modify your profile so that your data is always valid.  Do not forget that this information is essential to use our services correctly. Fields which are marked with <font class="Star">*</font> are mandatory.

<widget module="Promotion" template="modules/Promotion/bonus_points.tpl">
<p>
<form action="cart.php" method="POST" name="profile_form">
<table>
<tr IF="success">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;Your profile has been updated successfully&nbsp;&lt;&lt;</td>
</tr>
<tr IF="!valid">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Your profile has not been updated!&nbsp;&lt;&lt;</td>
</tr>
<tr IF="userExists">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</td>
</tr>
</table>
EOT;
    $replace_str = <<<EOT
<widget template="js/select_states_begin_js.tpl">

<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/billing_shipping.js"></script>

This form allows you to modify your profile so that your data is always valid.  Do not forget that this information is essential to use our services correctly. Fields which are marked with <font class="Star">*</font> are mandatory.

<widget module="Promotion" template="modules/Promotion/bonus_points.tpl">
<p>
<form action="cart.php" method="POST" name="profile_form">
<table IF="success|!valid">
<tr IF="success">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;Your profile has been updated successfully&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="!valid">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Your profile has not been updated!&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="userExists">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</font></td>
</tr>
</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password">
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
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password">
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
    <td>&nbsp;</td>
EOT;
    $replace_str = <<<EOT
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
    <td>&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Zip code</td>
    <td><font color="red">*</font></td>
    <td nowrap>
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>&nbsp;</td>
</tr>

<tbody IF="showMembership">
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr valign="middle">
    <td colspan="4"><b>Signup for membership</b><br><hr size="1" noshade></td>
</tr>
<tr IF="!membership=##" valign="middle">
    <td align="right">Current membership</td>
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
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<tr valign="middle">
    <td align="right">Membership</td>
    <td>&nbsp;</td>
    <td><widget class="CMembershipSelect" field="pending_membership">
EOT;
    $replace_str = <<<EOT
<tr valign="middle">
    <td align="right">Request membership</td>
    <td>&nbsp;</td>
    <td><widget class="CMembershipSelect" field="pending_membership">
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
</table>
</form>


EOT;
    $replace_str = <<<EOT
</table>
</form>

<widget template="js/select_states_end_js.tpl">

<script type="text/javascript" language="JavaScript 1.2">
	CheckBillingShipping(document.profile_form);
</script>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>

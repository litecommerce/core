<p class="TabHeader">Settings</p>
<p>This section allows to configure LiteCommerce ASPE Control Center.
<br>
<font class="Star">*</font> indicates a required field

<br><br><br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td colspan="4" class=AdminHead>General settings<br><br></td>
</tr>

<form action="cpanel.php" method="GET" name="settings_form_select">
<input FOREACH="dialog.allparams,_param,_val" type="hidden" name="{_param}" value="{_val:r}"/>

<tr valign="middle">
    <td width="150" align="right">Section:</td>
    <td width="10">&nbsp;</td>
    <td width="150">
        <select name=section onchange="document.settings_form_select.section.value=this.value; document.settings_form_select.submit()">
            <option FOREACH="sections,sec,nam" value="{sec}" selected="{section=sec}">{nam}</option>
        </select>
    </td>
    <td>&nbsp;</td>
</tr>

</form>

<form action="cpanel.php" method="POST" name="settings_form">
<input type=hidden name="target" value="options">
<input type=hidden name="action" value="update_options">
<input type="hidden" name="section" value="{section}">

<tr FOREACH="options,option" valign="top">
    <td nowrap align="right">{option.comment:h}: </td>
    <td width="10">&nbsp;</td>
    <td width="150">
    {if:option.isCheckbox()}
    <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}" class="inputCheckbox">
    {end:}
    {if:option.isText()}
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    {end:}
    </td>
    <td width="100%">&nbsp;</td>
</tr>

<tr valign=middle>
	<td colspan=2>&nbsp;</td>
    <td colspan=2><br><input type=submit value=" Update " class="DialogMainButton" onClick="this.blur();"></td>
</tr>

</form>

</table>

<br><hr>

<form action="cpanel.php" method="post" name="options_form">
<input type=hidden name="target" value="options">
<input type=hidden name="action" value="update_profile">

<table width="100%" border="0" cellspacing="0" cellpadding="2">

<tr IF="adminLoginUpdated">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;The LiteCommerce ASPE administrator credentials were updated successfully&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="rootLoginUpdated">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;The MySQL root credentials were updated successfully&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="mysqlLoginUpdated">
    <td colspan="4"><font class="SuccessMessage">&gt;&gt;&nbsp;The MySQL credentials of LiteCommerce ASPE Administrator were updated successfully&nbsp;&lt;&lt;</font></td>
</tr>

<tr IF="!valid">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Settings have not been updated!&nbsp;&lt;&lt;</font></td>
</tr>

</table>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td colspan="4" class=AdminHead>LiteCommerce ASPE Administrator Credentials</td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr valign="middle">
    <td nowrap width="150" align="right">E-mail</td>
    <td width="10"><font class="Star">*</font></td>
    <td nowrap width="150">
        <input type="text" name="login" value="{auth.profile.login:r}" class="ShortField" maxlength="128">
    </td>
    <td width="80%">
        <widget class="CEmailValidator" field="login">
    </td>
</tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="password" value="{password:r}" class="ShortField" maxlength="128">
    </td>
    <td>&nbsp;</td>
	<td>
		<widget class="CRequiredValidator" field="password">
	</td>
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="confirm_password" value="{confirm_password:r}" class="ShortField" maxlength="128">
    </td>
    <td>
        <widget class="CPasswordValidator" field="confirm_password" passwordField="password">
    </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="2">
		<br>
		<input type="submit" value=" Change " class="DialogMainButton" onClick="this.blur();">
	</td>
</tr>
</table>

</form>

<hr>

<form action="cpanel.php" method="POST" name="mysql_root_form">
<input type="hidden" name="target" value="options">
<input type="hidden" name="action" value="mysql_update_root">

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td colspan="4" class=AdminHead>MySQL Root Credentials</td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr valign="middle">
    <td nowrap width="150" align="right">Login</td>
    <td width="10" class="Star">*</td>
    <td nowrap width="150">
        <input type="text" name="mysql_root_user" value="{xlite.aspConfig.MySQL.root_user:r}" class="ShortField" maxlength="128">
    </td>
    <td width="80%">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td class="Star">*</td>
    <td>
        <input type="password" name="mysql_root_password" value="{mysql_root_password:r}" class="ShortField" maxlength="128">
    </td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="2">
		<br>
		{if:!xlite.aspConfig.MySQL.root_password}
		<input type=submit value=" Save " class="DialogMainButton" onClick="this.blur();">
		{else:}
		<input type="submit" value=" Update " class="DialogMainButton" onClick="this.blur();">
		&nbsp;&nbsp;
		<input type=button value=" Remove password from DB " onClick="this.blur(); javascript: document.mysql_root_form.action.value='mysql_remove_root'; document.mysql_root_form.submit();">
		{end:}
	</td>
</tr>
</table>

</form>

<hr>

<form action="cpanel.php" method="POST">
<input type="hidden" name="target" value="options">
<input type="hidden" name="action" value="mysql_update">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td colspan="4" class=AdminHead>MySQL Credentials of LiteCommerce ASPE Administrator</td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tbody IF="!xlite.aspConfig.MySQL.root_password">
<tr>
	<td colspan="4">
	<b>Note: </b> To change MySQL password you need root access to MySQL.
	</td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr valign="middle">
    <td nowrap width="150" align="right">MySQL root login</td>
    <td width="10"><font class="Star">*</font></td>
    <td nowrap width="150">
        <input type="text" name="root_user" value="{root_user:r}" class="ShortField" maxlength="128">
    </td>
    <td width="80%">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">MySQL root password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="text" name="root_password" value="{root_password:r}" class="ShortField" maxlength="128">
    </td>
    <td>&nbsp;</td>
</tr>
</tbody>

<tr valign="middle">
    <td nowrap width="150"  align="right">Login</td>
    <td width="10"><font class="Star">*</font></td>
    <td nowrap width="150">{xlite.options.database_details.username}</td>
    <td width="80%">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="mysql_password" value="{mysql_password:r}" class="ShortField" maxlength="128">
    </td>
	<td>&nbsp;<widget class="CRequiredValidator" field="mysql_password"></td>
</tr>
<tr valign="middle">
    <td align="right">Confirm password</td>
    <td><font class="Star">*</font></td>
    <td>
        <input type="password" name="mysql_confirm_password" value="{mysql_confirm_password:r}" class="ShortField" maxlength="128">
    </td>
    <td>&nbsp;<widget class="CPasswordValidator" field="mysql_confirm_password" passwordField="mysql_password"></td>
</tr>
<tr IF="error">
	<td colspan="4">
		<span class="ErrorMessage">{error}<br></span>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="2">
		<br>
		<input type="submit" value=" Change " class="DialogMainButton" onClick="this.blur();">
	</td>
</tr>
</table>

</form>

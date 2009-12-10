<script type="text/javascript" language="JavaScript">
function uninstallConfirmation(name)
{
	if (confirm("Are you sure you want to uninstall the '"+name+"' shop?")) {
		document.confirm_form.submit();
	}
}
</script>

<form action="cpanel.php" method="POST" name="confirm_form">
<input type="hidden" name="target" value="shops">
<input type="hidden" name="action" value="uninstall">
<input type="hidden" name="shop_id" value="{shop_id}">
<input IF="returnUrl" type="hidden" name="returnUrl" value="{returnUrl:r}"/>

<table border=0 cellpadding=4 width="70%">
<tr><td colspan=2 class=AdminHead>Confirm uninstall</td></tr></tr>
<tr><td colspan=2>You are about to uninstall a client shop from the following URL: <B>{currentShop.url}</B></td></tr></tr>
<tr><td width="20%">&nbsp;</td><td>&nbsp;</td></tr>

<tr>
    <td nowrap rowspan=2 valign=top>Unistallation options:</td>
	<td valign=top><table border=0 cellspacing=0 cellpadding=0 valign=top><tr><td><input id="remove_files_id" type="checkbox" name="remove_files" checked class="inputCheckbox"></td><td><label for="remove_files_id">Remove shop files</label></td></tr></table></td>
</tr>    
<tr>
	<td valign=top><table border=0 cellspacing=0 cellpadding=0 valign=top><tr><td><input id="remove_database_id" type="checkbox" name="remove_database" checked class="inputCheckbox"></td><td><label for="remove_database_id">Remove shop database</label></td></table></td>
</tr>

<tbody IF="!xlite.aspConfig.MySQL.root_password">
<tr>
    <td nowrap>MySQL root user name:</td>
    <td><input size=32 name="root_user" value="{root_user:r}">&nbsp;<widget class="CRequiredValidator" field="root_user" visible="{!xlite.aspConfig.MySQL.root_password}"></td>
</tr>
<tr>
    <td nowrap>MySQL root password:</td>
    <td><input size=32 name="root_password" value="{root_password:r}"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><table border=0><tr><td><input id="save_password_id" type="checkbox" name="save_password" checked="{save_password}" class="inputCheckbox"></td><td><label for="save_password_id">Save the password for future use</label></td></table></td>
</tr>
</tbody>

<tr>
	<td colspan="2">
	<table>
	<tr>
		<td valign="top" class="ErrorMessage">WARNING:</td>
		<td valign="top"> The uninstallation process will begin after confirmation. This operation cannot be reverted.</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top" class="ErrorMessage">ALL THE DATA WILL BE LOST!</td>
	</tr>
	</table>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>
        <input type=button name="b_submit" value="Uninstall shop" onClick="this.blur(); uninstallConfirmation('{addSlashes(currentShop.name):h}');">
        &nbsp;&nbsp;
        <input type=button name="b_cancel" value="Cancel" onClick="this.blur(); document.location='{returnUrl}'">
    </td>
</tr>

</table>
</form>

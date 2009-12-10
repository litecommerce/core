<p class="TabHeader">Access policy</p>
<p>Access policies define sets of permissions and restrictions to be applied to client shops administrators.
<br>
<font class="Star">*</font> indicates a required field

<br><br><br>

<table border=0 IF="xlite.factory.AspProfile.readAll()">
<form action="cpanel.php" method=POST name=update_profile_form>
<input type=hidden name=target value=profiles>
<input type=hidden name=action value=update_profile>
<tr>
    <td colspan=3 class=AdminHead>Modify access policy</td></tr>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
    <td>Name:</td>
    <td>&nbsp;</td>
    <td>&nbsp;
    <select name=profile_name onChange="document.location='cpanel.php?target=profiles&profile_name=' + this.value;">
    <option FOREACH="xlite.factory.AspProfile.readAll(),p" selected="{profile_name=p.name}" value="{p.name}">{p.name}</option>
    </select>
    </td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr valign=top>
    <td nowrap>Grant access to:</td>
    <td>&nbsp;</td>
    <td>
        <table border=0>
        <tr FOREACH="profile.policyRules,aname,arule" valign=middle>
            <td><input type=checkbox name="rules[]" value="{aname}" checked="isActiveRule(profile.rules,aname)" class="inputCheckbox"></td>
            <td>{arule}</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
    <td>
        <br>
        <input type=submit name=update value=" Update " class="DialogMainButton" onClick="this.blur();">
        &nbsp;&nbsp;
        <span IF="!profile.shopsNumber">
        <input type=button name=delete value=" Delete " onClick="this.blur(); confirmDelete('{profile_name}')">
        <script language="JavaScript">
        <!-- 
        function confirmDelete(policy) {
            if (confirm('Are you sure you want to delete policy ' + policy + '?')) {
                document.update_profile_form.action.value='delete_profile';
                document.update_profile_form.submit();
            }
        }
        // -->
        </script>
        </span>
        <span IF="profile.shopsNumber">
        <input type=button value=" Delete " disabled onClick="this.blur();">
        </span>
    </td>
</tr>    
</form>
</table>

<table border=0 cellpadding="0" cellspacing="0" IF="profile.shopsNumber">
<tr><td class=AdminHead><br>Shops that use this access policy</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td class="CenterBorder">
	<table border="0" cellspacing="1" cellpadding="5">
        <tr class="TableHead">
			<th>Shop name</th>
			<th>Shop URL</th>
			<th>Status</th>
			<th>&nbsp;</th>
		</tr>
		<tr FOREACH="profile.shops,idx,shop" class="{getRowClass(idx,#DialogBox#,#TableRow#)}">
			<td nowrap>{shop.name:h}</td>
			<td nowrap><a href="{shop.url}/index.php" target="_blank">{shop.url}</a></td>
			<td nowrap><font IF="shop.enabled" class="SuccessMessage">&nbsp;Enabled</font><font IF="!shop.enabled" class="ErrorMessage">Disabled</font>&nbsp;</td>
			<td><a href="cpanel.php?target=shops&mode=configure&shop_id={shop.id}&returnUrl={url:u}"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;details&nbsp;</a></td>
		</tr>
	</table>
	</td>
</tr>    
</table>

<br>

<!-- ADD NEW POLICY -->

<a name="add_profile">
<hr>
<p class=AdminTitle>Add new access policy</p>
<table border=0>
<form action="cpanel.php#add_profile" method=POST name=add_profile_form>
<input type=hidden name=target value=profiles>
<input type=hidden name=action value=add_profile>
<tr>
    <td nowrap>Name:</td>
    <td class=Star>*</td>
    <td>
        <input type=text name=name value="{name}">
        &nbsp;
        <widget class="CRequiredValidator" field="name">
        <font IF="profileExists" class="ValidateErrorMessage">&nbsp;&nbsp;&lt;&lt;&nbsp;Policy &quot;{name}&quot; already exists! Please select another name</font>
    </td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
    <td><br><input type=submit name=add value=" Add " class="DialogMainButton" onClick="this.blur();"></td>
</tr>    
</form>
</table>

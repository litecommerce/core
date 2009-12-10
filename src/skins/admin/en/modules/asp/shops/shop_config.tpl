<script type="text/javascript" language="JavaScript">
// <!--
function goSynchronize(_document)
{
	if (confirm("Are you sure?")) {
		_document.action.value = "synchronize";
		_document.submit();
	}
}

function setChecked(form, input, check, key)
{
	var elements = document.forms[form].elements[input];

	if ( elements.length > 0 ) {
		for (var i = 0; i < elements.length; i++) {
			elements[i].checked = check;
		}
	} else {
		elements.checked = check;
	}
	if (key) {
		checkUpdated(key);
	}
}

function checkUpdated(key)
{
	var Element = document.getElementById("update_button_"+key);
	if (Element) {
		Element.className = "DialogMainButton";
	}
}

function setHeaderChecked(key)
{
	var Element = document.getElementById("activate_modules_"+key);
	if (Element && !Element.checked) {
		Element.checked = true;
	}
}

function uninstallModule(moduleForm, moduleID, moduleName)
{
	if (confirm('Are you sure you want to uninstall ' + moduleName + ' add-on?')) {
		moduleForm.module_id.value = moduleID;
		moduleForm.module_name.value = moduleName;
		moduleForm.action.value = 'uninstall';
		moduleForm.submit();
	}
}
// -->
</script>

<p>This section allows to view and modify the settings for a deployed shop.
<br>
<br><br>

<div IF="!valid">
<font class="ErrorMessage" IF="action=#synchronize#">Synchronize error:</font>
<br>
<font class="ErrorMessage" IF="config_err=#config_not_found#">Cannot find config file ({currentShop.configFile:h}).</font>
<font class="ErrorMessage" IF="config_err=#config_not_write#">Not allowed to write to config file. Please, set necessary permissions for the ({currentShop.configFile:h}) file and try again to synchronize.</font>
</div>
<p>

<table border=0 cellpadding=4>
<form action="cpanel.php?target=shops" method="POST" name="update_form">
<input type="hidden" name="action" value="update">
<input type="hidden" name="mode" value="">
<input type="hidden" name="shop_id" value="{shop_id}">
<!--input type="hidden" name="backUrl" value="{backUrl}"-->
<input type="hidden" foreach="params,param" name="{param}" value="{get(param):r}"/>
<tr><td colspan=3 class=AdminHead>Shop ({CurrentShop.name:h}) details<br><br></td></tr>
<tr>
	<td nowrap width="1%" align="right">Shop name</td>
	<td nowrap width="200"><input type="text" name="name" value="{CurrentShop.name:r}" maxlength="32" size="64"></td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">URL</td>
	<td nowrap>
		<input name="url" value="{CurrentShop.url}" size=64>
		<span class="ErrorMessage" IF="config_diff.url">* Shop's config value: {config_diff.url:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">Secure URL</td>
	<td nowrap>
		<input name="secure_url" value="{CurrentShop.secure_url}" size=64>
		<span class="ErrorMessage" IF="config_diff.secure_url">* Shop's config value: {config_diff.secure_url:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">Primary path</td>
	<td nowrap>
		{primaryPath:h}
		<span class="ErrorMessage" IF="config_diff.primary_path">* Shop's config value: {config_diff.primary_path:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">Directory path</td>
	<td nowrap>
		<input name="path" value="{CurrentShop.path}" size=64>
		<span class="ErrorMessage" IF="config_err=#not_found#">* Cannot find config file (<b>{currentShop.configFile}</b>)</span>
		<span class="ErrorMessage" IF="config_err=#not_read#">* Cannot read config file (<b>{currentShop.configFile}</b>)</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">MySQL database name</td>
	<td nowrap>
		<input name="dn_name" value="{CurrentShop.db_name}" size=32>
		<span class="ErrorMessage" IF="config_diff.db_name">* Shop's config value: {config_diff.db_name:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">MySQL user name</td>
	<td nowrap>
		<input name="db_username" value="{CurrentShop.db_username}" size=32>
		<span class="ErrorMessage" IF="config_diff.db_username">* Shop's config value: {config_diff.db_username:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">MySQL password</td>
	<td nowrap>
		<input name="db_password" value="{CurrentShop.db_password}" size=32>
		<span class="ErrorMessage" IF="config_diff.db_password">* Shop's config value: {config_diff.db_password:r}</span>
	</td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
    <td nowrap align="right">Access policy</td>
    <td nowrap>
        <select name=profile>
	        <option FOREACH="xlite.factory.AspProfile.readAll(),p" selected="{CurrentShop.profile=p.name}">{p.name}</option>
        </select>
    </td>
	<td width="80%">&nbsp;</td>
</tr>
<tr>
	<td nowrap align="right">Status</td>
	<td nowrap>
		<select name="enabled">
			<option value="1" selected="{CurrentShop.enabled}">Enabled</option>
			<option value="0" selected="{!CurrentShop.enabled}">Disabled</option>
		</select>
	</td>
	<td width="80%">&nbsp;</td>
</tr>

<tr>
	<td nowrap align="right">PHP Memory Limit</td>
	<td nowrap>
		<select name="memory_limit">
			<option FOREACH="memoryLimitValues,v" value="{v:h}" selected="CurrentShop.memory_limit=v">{v:h}</option>
		</select>
	</td>
	<td width="80%">&nbsp;</td>
</tr>

<tr IF="config_diff">
	<td colspan="3"><span class="ErrorMessage">Note:</span> A (<span class="ErrorMessage">*</span>) next to the field means that the field value in the ASPE database differs from the value in the shop's config file. Click 'Synchronize' to copy values from the ASPE database to the shop's config file.</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>
	<table border=0 cellpadding=0 cellspacing=0>
    <tr>
    	<td><input type="submit" value="Update" class="DialogMainButton" onClick="this.blur();">
    	<!--td width="48">&nbsp;</td>
		<td><input type="button" value="Cancel" onClick='this.blur(); document.location="{backUrl}"'></td-->
		<td IF="config_diff" width="48">&nbsp;</td>
		<td IF="config_diff"><input type="submit" value="Synchronize" onClick="this.blur(); goSynchronize(document.update_form);"></td>
    </tr>
	</table>
	</td>
	<td>&nbsp;</td>
</tr>
</form>
</table>



<p>
<table cellpadding="0" cellspacing="0" border="0">

{* Display payment modules *}

<tbody IF="getSortModules(#8#)">
<widget template="modules/asp/shops/modules_body.tpl" caption="Commercial payment modules" key="8">
</tbody>

{* Display shipping modules *}

<tbody IF="getSortModules(#4#)">
<widget template="modules/asp/shops/modules_body.tpl" caption="Commercial shipping modules" key="4">
</tbody>


{* Display commercial modules *}

<tbody IF="getSortModules(#2#)">
{if:getSortModules(#8#)|getSortModules(#4#)}
<widget template="modules/asp/shops/modules_body.tpl" caption="Other commercial modules" key="2">
{else:}
<widget template="modules/asp/shops/modules_body.tpl" caption="Commercial modules" key="2">
{end:}
</tbody>

{* Display commercial skin modules *}

<tbody IF="getSortModules(#16#)">
<widget template="modules/asp/shops/modules_body.tpl" caption="Commercial skin modules" key="16">
</tbody>


{* Display free modules *}

<tbody IF="getSortModules(#1#)">
<widget template="modules/asp/shops/modules_body.tpl" caption="Free modules" key="1">
</tbody>


{* Display 3rd party modules *}

<tbody IF="getSortModules(#4096#)">
<widget template="modules/asp/shops/modules_body.tpl" caption="3rd party modules" key="4096">
</tbody>

</table>

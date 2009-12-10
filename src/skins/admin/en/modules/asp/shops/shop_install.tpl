<script type="text/javascript" language="JavaScript">
function InitialShopName()
{
	obj = document.getElementById('shop_name');
	if (obj) {
		obj.value = "{generateShopName}";
		obj.style.color = "#888888";
	}
}

function actionOnFocus(obj)
{
	if (obj.value == "{generateShopName}") {
		obj.style.value = "";
	}

	obj.style.color = "#000000";
}

function actionOnBlur(obj)
{
	if (obj.value == "") {
		InitialShopName();
	} else if (obj.value == "{generateShopName}") {
		obj.style.color = "#888888";
	}
}
<!--
function moveModule(action, all) {
	if (action == 'select') {
		from = document.asp_install_shop.all_modules;
		to = document.asp_install_shop.install_modules;
	} else if (action == 'unselect') {
		from = document.asp_install_shop.install_modules;
		to = document.asp_install_shop.all_modules;
	}
	var i = 0;
	while (i < from.length) {
		if (all) {
			from[i].selected = true;
		}
		if (from[i].selected) {
			var idx = to.length;
			to.length++;
			to[idx] = new Option(from[i].text, from[i].value);
			to[idx].className = from[i].className;
			from[i] = null;
		} else {
			i++;
		}
	}
	// mark all install modules as selected
	install_modules = document.asp_install_shop.install_modules;
	for (j = 0; j < install_modules.length; j++) {
		install_modules[j].selected = true;
	}
}
// -->
</script>

<p>The form below allows you to create a new client store.
<br>
<font class="Star">*</font> indicates a required field.

<br><br><br>

<table border="0" cellpadding=4 cellspacing=0>
<form name="asp_install_shop" action="cpanel.php#install" method="POST">
<input type="hidden" name="target" value="shops">
<input type="hidden" name="action" value="install">
<input type="hidden" name="mode" value="{mode}">

<tbody IF="!xlite.aspConfig.MySQL.root_password">
<tr>
	<td colspan="3"><widget template="modules/asp/separator.tpl" caption="MySQL root access info"></td>
</tr>
<tr>
	<td valign="top" align="right">MySQL root user name</td>
	<td valign="top"><font class=Star>*</font></td>
	<td><input class="ShortField" maxlength=32 name="root_user" value="{root_user:r}">
		<widget class="CRequiredValidator" field="root_user">
	</td>
</tr>
<tr>
	<td valign="top" align="right">MySQL root password</td>
	<td valign="top"><font class=Star>*</font></td>
	<td><input class="ShortField" maxlength=32 name="root_password" value="{root_password:r}">
		<widget class="CRequiredValidator" field="root_password">
	</td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
	<td>
		<input id="save_password_id" type="checkbox" name="save_password" checked="{save_password}" class="inputCheckbox">
		<label for="save_password_id">Save the password for future use</label>
	</td>
</tr>
</tbody>

<tr>
	<td colspan="3"><widget template="modules/asp/separator.tpl" caption="Shop details"></td>
</tr>
<tr>
	<td valign="top" align="right">Shop name</td>
	<td>&nbsp;</td>
	<td><input id="shop_name" class="NormalField" maxlength="32" type="text" name="name" value="" onFocus="actionOnFocus(this);" onBlur="actionOnBlur(this);"></td>
</tr>
<tr>
	<td valign="top" align="right">New shop URL, including webdir</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
		<input class="LongField" name="shop_url" value="{shop_url:r}">
		<widget class="CRequiredValidator" field="shop_url">
		<br><span class="Comment">Example: http://www.store.com/eshop</span>
	</td>
</tr>
<tr IF="urlExists|urlWrong">
	<td colspan="2">&nbsp;</td>
	<td>
    	<font IF="urlExists" class="ValidateErrorMessage">The shop URL is already registered! Please select another URL.</font>
	    <font IF="urlWrong" class="ValidateErrorMessage">The shop URL should start from "http://", please correct URL.</font>
	</td>
</tr>
<tr>
	<td valign="top" align="right">New shop secure URL, including webdir (optional)</td>
	<td>&nbsp;</td>
	<td>
		<input class="LongField" name="shop_secure_url" value="{shop_secure_url:r}">
		<br><span class="Comment">Example: https://secure.hosting.com/eshop</span>
	</td>
</tr>
<tr IF="secureUrlExists|secureUrlWrong|secureUrlMismatch">
	<td colspan="2">&nbsp;</td>
	<td>
    	<font IF="secureUrlExists" class="ValidateErrorMessage">The shop secure URL is already registered! Please select another the secure URL.</font>
	    <font IF="secureUrlWrong" class="ValidateErrorMessage">The shop secure URL should start from "https://". Please correct the secure URL.</font>
	    <font IF="secureUrlMismatch" class="ValidateErrorMessage">The web directory specified in the secure shop URL does not match the web directory specified in the shop URL. Please correct the secure URL.</font>
	</td>
</tr>
<tr>
	<td valign="top" align="right">Absolute file path to the shop directory</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
    	<input class="LongField" name="shop_path" value="{shop_path:r}">
		<widget class="CRequiredValidator" field="shop_path">
		<br><span class="Comment">Example: /var/shops/store.com/eshop</span>
	</td>
</tr>
<tr IF="pathExists|pathSymLink">
	<td colspan="2">&nbsp;</td>
	<td>
    	<font IF="pathExists" class="ValidateErrorMessage"><br>The shop path already exists! Please select another path.</font>
	    <font IF="pathSymLink" class="ValidateErrorMessage"><br>The shop path should not contain any symbolic link! Please select another path.</font>
	</td>
</tr>

<tr>
	<td colspan="3"><widget template="modules/asp/separator.tpl" caption="MySQL access info"></td>
</tr>
<tr>
	<td valign="top" align="right">MySQL database name</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
	    <input class="ShortField" name="shop_db_database" value="{shop_db_database:r}">
    	<widget class="CRequiredValidator" field="shop_db_database">
	    <font IF="dbExists" class="ValidateErrorMessage"><br>The database already exists! Please select another database</font>
	</td>
</tr>
<tr>
	<td valign="top" align="right">MySQL database usage</td>
	<td>&nbsp;</td>
	<td>
		<select name="shop_db_database_usage" class="ShortField">
			<option value="create" selected="{shop_db_database_usage=#create#}">Create database</option>
			<option value="exists" selected="{shop_db_database_usage=#exists#}">Database exists</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="top" align="right">MySQL user name</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
		<input class="ShortField" name="shop_db_user" value="{shop_db_user:r}">
		<widget class="CRequiredValidator" field="shop_db_user">
		<font IF="dbUserExists" class="ValidateErrorMessage"><br>The MySQL user name already exists! Please select another user name or specify that it exists.</font>
	</td>
</tr>
<tr>
	<td valign="top" align="right">MySQL user name usage</td>
	<td>&nbsp;</td>
	<td>
		<select name="shop_db_user_usage" class="ShortField">
			<option value="create" selected="{shop_db_user_usage=#create#}">Create user name</option>
			<option value="exists" selected="{shop_db_user_usage=#exists#}">User name exists</option>
		</select>
	</td>
</tr>
<tr>
	<td valign="top" align="right">MySQL password</td>
	<td valign="top"><font class=Star>*</font></td>
	<td><input class="ShortField" name="shop_db_password" value="{shop_db_password:r}">
	<widget class="CRequiredValidator" field="shop_db_password"></td>
</tr>



<tr>
	<td colspan="3"><widget template="modules/asp/separator.tpl" caption="Shop configuration"></td>
</tr>
<tr>
	<td valign="top" align="right">New shop access policy</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
    	<select name=shop_profile class="ShortField">
		    <option FOREACH="xlite.factory.AspProfile.readAll(),p" selected="{shop_profile=p.name}">{p.name}</option>
	    </select>
	</td>
</tr>
<tr IF="xlite.mm.modules">
<td valign=top align="right">New shop modules</td>
<td>&nbsp;</td>
<td valign=center>
    <table border=0 width="70%">
    <tr>
    <td width="10%">
        Install modules<br>
        <select id=install_modules name="shop_modules[]" class="ShortField" size=9 multiple>
            <option FOREACH="shop_modules,name" value="{name}" selected>{name}</option>
        </select>
    </td>
    <td valign=center align="center" width="220">
        <table border=0>
        <tr><td><input type=button name="sel" value="&lt;" onClick="moveModule('select', false)" style="width:40px"></td></tr>
        <tr><td><input type=button name="sel" value="&lt;&lt;" onClick="moveModule('select', true)" style="width:40px"></td></tr>
        <tr><td></td></tr>
        <tr><td><input type=button name="unsel" value="  &gt;  " onClick="moveModule('unselect', false)" style="width:40px"></td></tr>
        <tr><td><input type=button name="unsel" value=" &gt;&gt; " onClick="moveModule('unselect', true)" style="width:40px"></td></tr>
        </table>
    </td>
    <td width="10%">
        All modules<br>
        <select id=all_modules class="ShortField" size=9 multiple>
            {foreach:xlite.mm.modules,module}
            <option IF="{!inShopModules(module.name)}" value="{module.name}">{module.name}</option>
            {end:}
        </select>
    </td>
    </tr>
    <tr><td colspan=3><span class="Comment">Hint: Press an hold Ctrl button to select more than one module</span></td></tr>
    </table>
    
</td>
</tr>

<tr>
	<td valign="top" align="right">New shop admin login</td>
	<td valign="top"><font class=Star>*</font></td>
	<td><input class="ShortField" name="shop_user" value="{shop_user:r}"><widget class="CEmailValidator" field="shop_user"></td>
</tr>
<tr>
	<td valign="top" align="right">New shop admin password</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
		<input class="ShortField" name="shop_password" value="{shop_password:r}">
		<widget class="CRequiredValidator" field="shop_password">
	</td>
</tr>
<tr>
	<td valign="top" align="right">Confirm new shop admin password</td>
	<td valign="top"><font class=Star>*</font></td>
	<td>
		<input class="ShortField" name="shop_password_confirm" value="{shop_password_confirm:r}">
		<widget class="CPasswordValidator" field="shop_password_confirm" passwordField="shop_password">
		<widget class="CRequiredValidator" field="shop_password_confirm">
	</td>
</tr>
<tr>
	<td valign="top" align="right">Layout skin</td>
	<td>&nbsp;</td>
	<td valign="top">
		<select name="skin_layout" class="ShortField">
			<option value="">Leave default</option>
			<option FOREACH="layoutSkinList,k,v" value="{v.name:h}" selected="v.name=skin_layout">{v.name_str:h}</option>
		</select>
		<br>
		<span class="Comment">Select a layout skin for your store.</span>
	</td>
</tr>
<tr>
	<td valign="top" align="right">PHP Memory Limit</td>
	<td>&nbsp;</td>
	<td valign="top">
		<select name="memory_limit">
			<option FOREACH="memoryLimitValues,v" value="{v:h}" selected="v=memory_limit">{v:h}</option>
		</select>
		<br>
		<span class="Comment">Defines how much memory PHP engine is allowed to use.</span>
	</td>
</tr>

<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
	<td><input type="submit" value="Deploy shop" class="DialogMainButton" onClick="this.blur();"></td>
</tr>
</form>
</table>

<script type="text/javascript" language="JavaScript">
InitialShopName();
</script>

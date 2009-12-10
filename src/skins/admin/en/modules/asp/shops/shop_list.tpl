<p>This section allows to view all or selected deployed shops.

<br><br><br>

<table border=0 cellpadding=3 cellspacing=1 width="80%">
<form name=shops_form action="cpanel.php" method=GET>
<input type="hidden" name="target" value="shops">

<tr><td colspan=4 class=AdminHead>Installed shops</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td>
		<table border=0 cellpadding=1 cellspacing=2>
		<tr>
			<td>
				<table border="0">
				<tr>
					<td nowrap>Shop Name substring:&nbsp;</td>
					<td><input type="text" name="shopname" value="{shopname}" size="30"></td>
					<td><a href="javascript: document.shops_form.shopname.value=''; void(0);" onClick="javascript: this.blur();"><img src="images/modules/asp/clear_field.gif" width=16 height=16 border="0" alt="Clear field's contents"></a></td>
        			<td>&nbsp;</td>
        			<td nowrap>&nbsp;&nbsp;Status:&nbsp;</td>
        			<td>
                    <select name="enabled" style="width:100px;">
                        <option value=""  selected="enabled=##">All</option>
                        <option value="1" selected="enabled=#1#">Enabled</option>
                        <option value="0" selected="enabled=#0#">Disabled</option>
                    </select>
                	</td>
				</tr>
				<tr>
					<td nowrap>URL substring:&nbsp;</td>
					<td><input type=text name=filter value="{filter}" size=30></td>
					<td><a href="javascript: document.shops_form.filter.value=''; void(0);" onClick="javascript: this.blur();"><img src="images/modules/asp/clear_field.gif" width=16 height=16 border="0" alt="Clear field's contents"></a></td>
        			<td>&nbsp;</td>
        			<td nowrap>&nbsp;&nbsp;Access policy:&nbsp;</td>
        			<td>
                    <select name="profile_name" style="width:100px;">
                        <option value=""  selected="profile_name=##">All</option>
    					<option FOREACH="xlite.factory.AspProfile.readAll(),p" selected="{profile_name=p.name}" value="{p.name}">{p.name}</option>
                    </select>
                	</td>
				</tr>
		        <tr><td colspan="6">&nbsp;</td></tr>
		        <tr>
					<td width=100% colspan="6" align=right><input type=submit name=apply value="Refine list" class="DialogMainButton" onClick="this.blur();"></td>
		        </tr>
				</table>
			</td>
		</tr>
		</table>
    </td>
</tr>
<tr>
	<td><hr></td>
</tr>
<tr IF="installedShops">
	<td align="right"><b>{installedShopsCount:h}</b> record{if:isGreaterOne(installedShopsCount)}s{end:} found.</td>
</tr>
<tr IF="installedShops">
	<td align="left">
		<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td nowrap>Shops per page:&nbsp;</td>
				<td>
					<select name="itemsPerPage" onChange="document.shops_form.submit();">
						<option FOREACH="itemsPerPageValues,v" value="{v}" selected="itemsPerPage=v">{v:h}</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
    <td><widget class="CPager" data="{installedShops}" name="pager" itemsPerPage="{itemsPerPage}"></td>
</tr>

<tr IF="!installedShops">
    <td>0 shop(s) found</td>
</tr>

<tbody FOREACH="pager.pageData,key,shop">
<tr class=SidebarTitle height=20>
    <td>
		<table border=0 cellpadding=1 cellspacing=1 width=100%>
		<tr>
			<td nowrap><b>Shop ({shop.name:h})&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{shop.url}</b></td>
			<td nowrap width=100% align=right><a href="{shop.url}/index.php" target="_blank" onClick="this.blur()"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;<u>Visit this shop (opens a new window)</u></a></td>
		</tr>
		</table>
    </td>
</tr>
<tr>
    <td>
		<table border=0 cellpadding=1 cellspacing=1 width=100%>
		<tr>
			<td>
                <table border=0 cellpadding=4>
                <tr><td nowrap>Directory path:</td><td nowrap>{shop.path}</td></tr>
                <tr><td nowrap>Access policy:</td><td>{shop.profile}</td></tr>
                <tr><td nowrap>Status:</td><td><font IF="shop.enabled" class="SuccessMessage">Enabled</font><font IF="!shop.enabled" class="ErrorMessage">Disabled</font></td></tr>
                </table>
			</td>
			<td width="100">
                <table border=0 cellpadding=4>
                <tr>
                    <td nowrap>
                    <a href="cpanel.php?target=shops&mode=configure&shop_id={shop.id}&backUrl={url:u}" onClick="this.blur()"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;<u>Review shop details</u></a>
                    </td>
                </tr>
                <tr>
                    <td>
                    <a href="cpanel.php?target=shops&mode=uninstall&shop_id={shop.id}&returnUrl={url:u}" onClick="this.blur()"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;<u>Uninstall shop</u></a>
                    </td>
                </tr>
                </table>
			</td>
		</tr>
		</table>
    </td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
</tbody>

</form>
</table>

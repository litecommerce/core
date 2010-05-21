{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<font class="AdminHead">Free download privileges</font>
<table border=0 cellspacing="1" celpadding="3">
<form action="admin.php" name="free_for_memberships" method="POST"> 
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="update_free_charge">
<tbody IF="config.Memberships.memberships">
<tr>
	<td valign="top" width=200>
	Members of the following membership groups can download this product free of charge:<br>
	<b>Note:</b> To (un)select more than one group, use Ctrl-click.
	</td>
	<td rowspan=3>
	&nbsp;&nbsp;&nbsp;
	<select multiple size="10" name=free_charge[]>
		<option FOREACH="config.Memberships.memberships,membership" selected="product.isFreeForMembership(membership)">{membership}</option>
	</select>
	</td>
</tr>
<tr>
	<td valign="top">&nbsp;</td>
</tr>
<tr>
	<td valign="bottom">
		<input type="submit" value=" Update " IF="config.Memberships.memberships"/>
	</td>
</tr>
</tbody>
<tr IF="!config.Memberships.memberships">
	<td>
	You have no memberships. You can add them <a href="admin.php?target=memberships"><u>here</u></a>.<br><br>
	</td>
</tr>
</form>
</table>

<table border="0">
<tbody FOREACH="product.egoods,egood">
<tr> 
<td>

<form action="admin.php" name="form_egood_{egood.file_id}" method="POST" enctype="multipart/form-data"> 
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="update_egood">
<input type="hidden" name="file_id" value="{egood.file_id}">
<table border=0 cellspacing="1" celpadding="3">
<tr>
	<td colspan="2" class="AdminHead"><hr>Product file: {egood.data:h}</td>
</tr>
<tr>
	<td colspan=2>&nbsp;</td>
</tr>
<tr>
	<td valign=top class="FormButton">File:</td>
	<td valign=top>
		<table border="0">
		<tr>
			<td><input type="radio" name="remote" value="Y" checked> Upload remote file</td>
			<td><input type="file" name='remote_file'></td>
		</tr>
		<tr>
			<td><input type="radio" name="remote" value="N"> Open local file</td>
			<td><input name='local_file'></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="FormButton">Delivery:</td>
	<td>
		<select name='delivery'>
		<option value='L' selected="egood.delivery=#L#">Download through Link</option>
		<option value='M' selected="egood.delivery=#M#">Send via e-mail</option>
		</select>
	</td>
</tr>
<tr>
	<td colspan=2>
<br>
<input type="button" value=" Update file " onclick="document.form_egood_{egood.file_id}.action.value='update_egood';document.form_egood_{egood.file_id}.submit();">
<input type="button" value=" Delete file " onclick="document.form_egood_{egood.file_id}.action.value='delete_egood';document.form_egood_{egood.file_id}.submit()">
	</td>
</tr>
<tr>
	<td colspan="2">
	<table border="0" cellspacing="0" cellpadding="0">
	<tbody IF="!egood.hasManualLinks()">
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</tbody>
	<tbody IF="egood.hasManualLinks()">
	<tr>
		<td colspan="2" class="FormButton"><br>Manually generated download links:<br><br></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;</td>
		<td>
<!-- links table {{{ -->
		<table celpadding="4" cellspacing="2">
		<tr class="TableHead">
			<td>&nbsp;</td>
			<td>Link URL</td>
			<td align="center" nowrap>Expiration date</td>
			<td>Downloads</td>
			<td>Expires on</td>
			<td>Status</td>
		</tr>
		<tr FOREACH="egood.manualLinks,link">
			<td><input type="checkbox" name="selected_links[]" value="{link.access_key}"></td>
			<td><input type="text" size=50 readonly value="{xlite.getShopUrl(#cart.php?target=download&action=download&acc=#)}{link.access_key}"></td>
			<td IF="link.expire_on=#T#">{link.printDate(#M#,#d#,#Y#,#/#)}</td>
			<td IF="link.expire_on=#B#">{link.printDate(#M#,#d#,#Y#,#/#)}</td>
			<td IF="link.expire_on=#D#">n/a</td>
			<td IF="link.expire_on=#T#">n/a</td>
			<td IF="link.expire_on=#B#">{link.available_downloads}</td>
			<td IF="link.expire_on=#D#">{link.available_downloads}</td>
			<td>
				<span IF="link.expire_on=#T#">Date</span>
				<span IF="link.expire_on=#D#">Downloads</span>
				<span IF="link.expire_on=#B#">Date and downloads</span>
			</td>	
			<td>
				<span IF="link.active"><font class="FormButton"><font color="green">Active</font></font></span>
				<span IF="!link.active"><font class="FormButton"><font color="red">Expired</font></font></span>
			</td>	
		</tr>
		<tr><td colspan="6">&nbsp;</td>
		</table>
<!-- }}} -->		
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;</td>
		<td>
		<input IF="egood.hasManualLinks()" type="button" value=" Delete selected " onclick="document.form_egood_{egood.file_id}.action.value='delete_links';document.form_egood_{egood.file_id}.submit();"/>
		</td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	</tbody>
	<tr>
		<td colspan=2>
		<font class="FormButton"><font color="red">Generate new download link:</font></font>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;</td>
		<td>
		<br>
		<table celpadding="4" cellspacing="2">
		<tr class="TableHead">
			<td>Link ID</td>
			<td align="center">Expiration date</td>
			<td>Downloads</td>
			<td>Expires on</td>
		</tr>
		<tr>
			<td><input name="new_acc" size="32" value="{newLinkAccessKey}"></td>
			<td nowrap><widget class="XLite_View_Date" field="new_exp_date"></td>
			<td><input name="new_downloads" size="10" value="{config.Egoods.exp_downloads}"></td>
			<td>
				<select name="new_expires">
				<option value="T" selected="{config.Egoods.link_expires=#T#}">Date</option>
				<option value="D" selected="{config.Egoods.link_expires=#D#}">Downloads</option>
				<option value="B" selected="{config.Egoods.link_expires=#B#}">Date and downloads</option>
			</td>	
		</tr>
		</table>
		<input type="button" value=" Add new link " onclick="document.form_egood_{egood.file_id}.action.value='add_link';document.form_egood_{egood.file_id}.submit();">
		</td>
	</tr>
	</table>
	</td>	
</tr>
</table>
</form>
</td>

</tr>
</tbody>
</table>

<hr>
<font class="AdminTitle">Add new file</font>

<p IF="!isValidEgoodsStoreDir()" align="justify">
<font color="red">WARNING!</font> Remote files cannot be uploaded to the specified egoods directory  ("{xlite.config.Egoods.egoods_store_dir}"), because the directory does not exist or you do not have write permissions for it.<br>To enable file uploads, specify a different egoods directory in the <a href="admin.php?target=module&page=Egoods"><u>Egoods settings page</u></a>.
</p>

<form action="admin.php" name="add_egood_form" enctype="multipart/form-data" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="add_egood">
<table border="0" cellspacing="1" cellpadding="3">
<tr>
	<td valign=top class="FormButton">File:</td>
	<td valign=top>
		<table border="0">
		<tr IF="isValidEgoodsStoreDir()">
			<td><input type="radio" name="new_remote" value="Y" checked> Upload remote file</td>
			<td><input type="file" name='new_remote_file'></td>
		</tr>
		<tr>
			<td><input type="radio" name="new_remote" value="N" checked="!isValidEgoodsStoreDir()"> Use local file</td>
			<td><input name='new_local_file'></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="FormButton">Delivery:</td>
	<td>
		<select name='new_file_delivery'>
		<option value='L'>Download through Link</option>
		<option value='M'>Send by Mail</option>
		</select>
	</td>
</tr>
</table>
<input type="submit" value=" Add ">
</form>

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
<table border=0 cellpadding=0 cellspacing=5 width="100%">
	<tr>
		<td align="center"><img src="images/modules/Froogle/froogle.gif" border="0"></td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td>
The LiteCommerce FroogleExport add-on module generates specially formatted data feed to enable you to list your products in Froogle.<br>It can be generated and uploaded to Froogle in one step using the Export Catalog functionallity.
		</td>
	</tr>
</table>

<br>

<form action="admin.php" name="options_form" method="POST" >
<input type="hidden" name="target" value="module">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="Froogle">

<table border=0 cellpadding=2 cellspacing=2 width="100%">
	<tr>
		<td colspan=3>
			<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Account options&nbsp;&nbsp;&nbsp;</td>
					<td width=100%>&nbsp;</td>
				</tr>
				<tr>
					<td class="SidebarTitle" align=center colspan=2 height=3></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align="right">Froogle password:</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_password" value="{froogleOptions.froogle_password.value:h}" size=45></td>
	</tr>

	<tr>
		<td align="right">Froogle username:</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_username" value="{froogleOptions.froogle_username.value:h}" size=45></td>
	</tr>

	<tr>
		<td align="right">Froogle host:</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_host" value="{froogleOptions.froogle_host.value:h}" size=45></td>
	</tr>

	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>

	<tr>
		<td colspan=3>
			<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Product export attributes&nbsp;&nbsp;&nbsp;</td>
					<td width=100%>&nbsp;</td>
				</tr>
				<tr>
					<td class="SidebarTitle" align=center colspan=2 height=3></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align="right">Brand:</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_brand" value="{froogleOptions.froogle_brand.value:h}" size=45></td>
	</tr>

	<tr>
		<td align="right">Expiration (days):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_expiration" value="{froogleOptions.froogle_expiration.value:h}" size=45></td>
	</tr>

	<tr>
		<td align="right">Id format (<font class="star">*</font>):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_id_format" value="{froogleOptions.froogle_id_format.value:h}" size=45></td>
	</tr>

	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>


	<tr>
		<td colspan=3>
			<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Other options&nbsp;&nbsp;&nbsp;</td>
					<td width=100%>&nbsp;</td>
				</tr>
				<tr>
					<td class="SidebarTitle" align=center colspan=2 height=3></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align="right">File name (if this option is empty,<br>'username.txt' will be used):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="froogle_file_name" value="{froogleOptions.froogle_file_name.value:h}" size=45></td>
	</tr>

	<tr>
		<td align="right">The "product_type" field includes:</td>
		<td>&nbsp;</td>
		<td>
			<select name="export_label">
				<option value='category' selected="froogleOptions.export_label.value=#category#">category</option>
				<option value='meta_tags' selected="froogleOptions.export_label.value=#meta_tags#">meta_tags</option>
				{if:isVersionUpper2_1()}
				<option value='meta_title' selected="froogleOptions.export_label.value=#meta_title#">meta_title</option>
				<option value='meta_desc' selected="froogleOptions.export_label.value=#meta_desc#">meta_desc</option>
				{end:}
				<option value='custom' selected="froogleOptions.export_label.value=#custom#">[custom]</option>
			</select>
		</td>
	</tr>

	<tr>
		<td align="right">Custom "product_type" value (used if the previous parameter is set to "[custom]"):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="export_custom_label" value="{froogleOptions.export_custom_label.value:h}" size=45></td>
	</tr>

	<tr IF="displayOverrideOption">
		<td align="right">Override "Allow direct URL access to products in the<br>categories which are not available":</td>
		<td>&nbsp;</td>
		<td>
			<select name="direct_product_url">
				<option value='' selected="!froogleOptions.direct_product_url.value">No, do not override</option>
				<option value='always' selected="froogleOptions.direct_product_url.value=#always#">Always allow</option>
				<option value='never' selected="froogleOptions.direct_product_url.value=#never#">Never allow</option>
			</select>
		</td>
	</tr>

	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>

	<tr>
		<td colspan=3>
			<hr>
			<b>(<font class="star">*</font>) You can use following variables in the name format fields:</b>
			<table cellspacing=2 cellpadding=2 border=0>
				<tr>
					<td>%pid</td>
					<td>&nbsp;-&nbsp;</td>
					<td>product ID</td>
				</tr>
				<tr>
					<td>%pname</td>
					<td>&nbsp;-&nbsp;</td>
					<td>product name</td>
				</tr>
				<tr>
					<td>%psku</td>
					<td>&nbsp;-&nbsp;</td>
					<td>product SKU</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align="middle" colspan="3"><input type="submit" value="Submit"></td>
	</tr>

</table>
</form>

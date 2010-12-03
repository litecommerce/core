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
<script type="text/javascript" language="JavaScript">
function cbDeclaredValue(value)
{
	obj = document.settings_form.ups_declared_value;
	if (!obj)
		return;

	if (value) {
		obj.value = '{product.price}';
		obj.disabled = true;
	} else {
		obj.value = '{product.ups_declared_value}';
		obj.disabled = false;
	}
}

var packages = new Array();
{foreach:upspackaginglist,k,v}
packages[{k}] = "{v.name:h}, W: {v.width} X H: {v.height} X L: {v.length} ({v.length_unit:h}), Weight: {v.weight_limit} ({v.weight_unit:h})";
{end:}

function displayContainerDetails(id)
{
	if (id == 0)
		id = 2;

	var obj = document.getElementById('packaging_details');
	if (obj) {
		text = "";
		if (packages[id]) {
			text = packages[id];
		}

		obj.innerHTML = text;
	}
}
</script>

<form action="admin.php" method="POST" name="settings_form">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="settings_update">

Enter the product dimensions to be used for distributing the ordered products into containers for UPS delivery.

<hr>

<div align="center">
<table border="0" cellpadding="2" cellspacing="2" width="80%">

	<tr IF="productOversize">
		<td colspan="3" class="ErrorMessage">Product size is larger than the recommended packaging '{currentPackaging.name:h}' dimensions.</td>
	</tr>
	<tr IF="productOverweight">
		<td colspan="3" class="ErrorMessage">Product weight is larger than the recommended packaging '{currentPackaging.name:h}' weight limit.</td>
	</tr>

	<tr>
		<td colspan="3"><widget template="modules/CDev/UPSOnlineTools/settings/separator.tpl" caption="Product dimensions"></td>
	</tr>
	<tr>
		<td align="right">Width (inches):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="ups_width" value="{product.ups_width}" style="width:70"></td>
	</tr>
	<tr>
		<td align="right">Length (inches):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="ups_length" value="{product.ups_length}" style="width:70"></td>
	</tr>
	<tr>
		<td align="right">Height (inches):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="ups_height" value="{product.ups_height}" style="width:70"></td>
	</tr>
	<tr>
		<td align="right">Weight ({config.General.weight_unit:h}):</td>
		<td>&nbsp;</td>
		<td><input type="text" name="weight" value="{product.weight}" style="width:70">&nbsp;&nbsp;<i>{product.getWeightConv(#lbs#):h} (lbs)</i></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>


	<tr>
		<td colspan="3"><widget template="modules/CDev/UPSOnlineTools/settings/separator.tpl" caption="Advanced shipping options"></td>
	</tr>
	<tr>
		<td align="right">Handle with care:</td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="ups_handle_care" value="1" checked="{product.ups_handle_care=#1#}"></td>
	</tr>
	<tr>
		<td align="right">Additional handling:</td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="ups_add_handling" value="1" checked="{product.ups_add_handling=#1#}"></td>
	</tr>
	<tr>
		<td align="right">Declared value:</td>
		<td>&nbsp;</td>
		<td><input type="text" name="ups_declared_value" value="{product.ups_declared_value}" style="width:70">&nbsp;&nbsp;<input type="checkbox" name="ups_declared_value_price" value="1" checked="{product.ups_declared_value_set=#0#}" OnClick="this.blur(); cbDeclaredValue(this.checked);">Use price as declared value</td>
	</tr>
	<tr>
		<td align="right">Required packaging:</td>
		<td>&nbsp;</td>
		<td>
			<select name="ups_packaging" style="width:200" OnChange="displayContainerDetails(this.value);">
				<option value="0" selected="{product.ups_packaging=#0#}">none</option>
				<option FOREACH="upspackaginglist,k,v" value="{k}" selected="{product.ups_packaging=k}">{v.name:h}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
	<tr>
		<td colspan="3" id="packaging_details"></td>
	</tr>
</table>
</div>

<p>

<div align="center">
<input type="submit" value="Update" class="DialogMainButton">
</div>

</form>

<script type="text/javascript" language="JavaScript">
cbDeclaredValue({if:product.ups_declared_value_set=#1#}false{else:}true{end:});
displayContainerDetails({product.ups_packaging});
</script>

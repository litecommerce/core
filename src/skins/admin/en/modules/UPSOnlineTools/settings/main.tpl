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
<script type="text/javascript" language="JavaScript 1.2">
function AlgorithmChanged(index)
{
	pack_dims = document.getElementById('package_dimensions');
	pack_type = document.getElementById('packaging_type');

	if (pack_dims) {
		pack_dims.style.display = 'none';
	}

	if (pack_type) {
		pack_type.style.display = 'none';
	}

	if (index == 0 && pack_dims) {
		pack_dims.style.display = '';
	}

	if ((index == 2 || index == 3) && pack_type) {
		pack_type.style.display = '';

		sb_pack_type = document.getElementById('sb_packaging_type');
		if (sb_pack_type && (sb_pack_type.value == 2 || sb_pack_type.value == 30)) {
			pack_dims.style.display = '';
		}
	}
}

function PackagingChanged(index)
{
	pack_dims = document.getElementById('package_dimensions');

	if (pack_dims) {
		pack_dims.style.display = 'none';
	}

	if ((index == 2 || index == 30) && pack_dims) {
		pack_dims.style.display = '';
	}
}
</script>

<br>
<table border="0" cellpadding="2" cellspacing="2" width="100%">
<form action="admin.php" method="POST" name="upsconfigureform">
<input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update">

<tr>
<td>&nbsp;</td>
<td width="100%">

<table border="0" width="100%">
<tr>
    <td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="UPS OnLine&reg; Tools Rates and Service Selection"></td>
</tr>
<tr>
	<td>The fields marked with <font class="Star">*</font> are mandatory.</td>
</tr>
<tr>
    <td width="50%"><b>Account Type:<font class="Star">*</font></b><br>
    <a href="javascript:void(0);" onclick="javascript:window.open('admin.php?target=popup_ups_online_tool&mode=help','UPS_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" title="Open a new window" class="SmallNote">Click here for help</a></td>
    <td width="50%">
        <select name="settings[account_type]" size="3" style="width:220">
            <option value="01" selected="{options.account_type=#01#}">Daily Pickup</option>
            <option value="02" selected="{options.account_type=#02#}">Occasional</option>
            <option value="03" selected="{options.account_type=#03#}">Suggested Retail Rates (UPS Store)</option>
        </select>
    </td>
</tr>

<tr>
    <td><b>Destination type:<font class="Star">*</font></B></td>
    <td>
    <table cellpadding="1" cellspacing="1">
    <tr>
        <td><input type="radio" name="settings[residential]" value="Y" checked="options.residential=#Y#"></td>
        <td>Residential address</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input type="radio" name="settings[residential]" value="N" checked="!options.residential=#Y#"></td>
        <td>Commercial address</td>
    </tr>
    </table>
    </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
    <td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Type of packing"></td>
</tr>
<tr>
    <td colspan="2">
This option allows to select the method of calculating how the products added to shopping cart are distributed into the defined package types.
    </td>
</tr>
<tr>
	<td nowrap><b>Packing algorithm:</b></td>
	<td nowrap>
		<select name="settings[packing_algorithm]" style="width:200" OnChange="AlgorithmChanged(this.value);">
			<option value="0" selected="{options.packing_algorithm=#0#}">Fixed size</option>
			<option value="1" selected="{options.packing_algorithm=#1#}">Max size</option>
			<option value="2" selected="{options.packing_algorithm=#2#}">Bin Packing</option>
			<option value="3" selected="{options.packing_algorithm=#3#}">Bin Packing oversize</option>
		</select>
	</td>
</tr>

<tr id="packaging_type" style="display: none;">
    <td><b>Packaging:</B></td>
    <td>
		<select id="sb_packaging_type" name="settings[packaging_type]" style="width:200" OnChange="PackagingChanged(this.value);">
			<option FOREACH="packingTypeList,k,v" value="{k}" selected="{options.packaging_type=k}">{v:h}</option>
		</select>
    </td>
</tr>

<tr id="package_dimensions" style="display: none;">
    <td nowrap><b>Length x Width x Height ({options.dim_units}):</b></td>
    <td nowrap>
        <input type="text" name="settings[length]" value="{options.length}" size="7">x
        <input type="text" name="settings[width]" value="{options.width}" size="7">x
        <input type="text" name="settings[height]" value="{options.height}" size="7">
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
</tr>

<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Service options"></td>
</tr>
<tr>
	<td colspan="2">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td><INPUT type="checkbox" name="settings[upsoptions][]" value="SP" checked="options.upsoptions.SP=#Y#"></td>
			<td>Saturday pickup</td>
		</tr>
		<tr>
			<td><INPUT type="checkbox" name="settings[upsoptions][]" value="SD" checked="options.upsoptions.SD=#Y#"></td>
			<td>Saturday delivery</td>
		</tr>
	</table>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>


<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Delivery confirmation"></td>
</tr>
<tr>
    <td><b>Delivery confirmation:</b></td>
    <td>
        <select name="settings[delivery_conf]" style="width:200">
	        <option value="0">No confirmation</option>
    	    <option value="1" selected="options.delivery_conf=#1#">No signature</option>
        	<option value="2" selected="options.delivery_conf=#2#">Signature required</option>
	        <option value="3" selected="options.delivery_conf=#3#">Adult signature required</option>
        </select>
    </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Conversion rate"></td>
</tr>
<tr>
    <td><b>Shipping cost conversion rate:</B><br>
    <font class="SmallText">The shipping cost is always returned in the local currency of the country where your shop is located. If the prices in your shop are in non-local currency (for example the shop is located in Great Britain but prices are in USD) then you need to define currency conversion rate to convert shipping cost returned by UPS in necessary currency.</font>
    </td>
    <td valign="top">
        <input type="text" name="settings[conversion_rate]" value="{options.conversion_rate}" style="width:200">
    </td>
</tr>

<tr>
    <td><b>Shipping cost currency:</B><br>
    <font class="SmallText">The currency code that is returned by UPS is defined automatically when a request for getting the shipping rates is successfully performed. If this currency code is unknown you need to perform this request before (click on 'Test' button below).</font>
    </td>
    <td valign="top">
        <b>{if:options.currency_code}{options.currency_code}{else:}Unknown{end:}</B>
    </td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

{*
<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="UPS OnLine&reg; Tools Address Validation"></td>
</tr>
<tr>
    <td><b>Address Validation status:</B></td>
    <td>
        <select name="settings[av_status]" style="width:200">
        <option value="Y" selected="{options.av_status=#Y#}">Enabled</option>
        <option value="N" selected="{options.av_status=#N#}">Disabled</option>
        </select>
    </td>
</tr>

<tr>
    <td><b>Address Validation quality:</B></td>
    <td>
        <select name="settings[av_quality]" style="width:200">
        <option value="exact" selected="{options.av_quality=#exact#}">Exact match</option>
        <option value="very_close" selected="{options.av_quality=#very_close#}">Very close match</option>
        <option value="close" selected="{options.av_quality=#close#}">Close match</option>
        <option value="possible" selected="{options.av_quality=#possible#}">Possible match</option>
        <option value="poor" selected="{options.av_quality=#poor#}">Poor match</option>
        </select>
    </td>
</tr>

<tr>
<td colspan="2">The quality factor, which describes the accuracy of the result compared to the request.
<br><br>
<b>Note:</B> The UPS OnLine&reg; Tools Address Validation is only supported for addresses within the United States. If a customer is from outside the United States then address validation will be disabled for him.</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
*}

<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Cache settings"></td>
</tr>
<tr>
	<td><b>Cache expiration (days)</b></td>
	<td><input type="text" name="settings[cache_autoclean]" value="{options.cache_autoclean}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td colspan="2"><widget template="modules/UPSOnlineTools/settings/separator.tpl" caption="Container representation"></td>
</tr>
<tr>
	<td><b>Display method</b></td>
	<td>
	<table cellpadding="1" cellspacing="1" border=0>
		<tr>
			<td width="1%"><input type="radio" name="settings[display_gdlib]" value="1" checked="isUseDGlibDisplay()" {if:!isGDlibEnabled()}disabled{end:}></td>
			<td>GDlib</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="1%"><input type="radio" name="settings[display_gdlib]" value="0" checked="!isUseDGlibDisplay()"></td>
			<td>HTML</td>
		</tr>
		<tr>
			<td colspan="5" class="ErrorMessage" IF="!isGDlibEnabled()">&nbsp;GDlib is disabled or its version is lower than 2.0.</td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td><b>Container layout width (pixels):</B></td>
	<td>
		<select name="settings[visual_container_width]">
			<option value="50" selected="{options.visual_container_width=#50#}">50</option>
			<option value="100" selected="{options.visual_container_width=#100#}">100</option>
			<option value="150" selected="{options.visual_container_width=#150#}">150</option>
			<option value="200" selected="{options.visual_container_width=#200#}">200</option>
			<option value="250" selected="{options.visual_container_width=#250#}">250</option>
		</select>
	</td>
</tr>
<tr>
	<td><b>Level display method:</B></td>
	<td>
		<select name="settings[level_display_method]">
			<option value="0" selected="{options.level_display_method=#0#}">Proportional</option>
			<option value="1" selected="{options.level_display_method=#1#}">Actual</option>
		</select>
	</td>
</tr>
<tr>
	<td><b>Product packing limit:</b><br>Pack all the products using 'Max size' algorithm if the number of products in the order exceeds this number.</B></td>
	<td valign="top"><input type="text" name="settings[packing_limit]" value="{options.packing_limit}"></td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td colspan="2" align="left"><br><input type="submit" class="DialogMainButton" value="Update"></td>
</tr>
</table>

<br><br>

<p>

<div align="right">
<a href="admin.php?target=ups_online_tool"><u>UPS OnLine&reg; Tools main page<img src="images/go.gif" border=0></u></a>
</div>

</td>
</tr>

</FORM>

</table>

<script type="text/javascript" language="JavaScript 1.2">
	AlgorithmChanged({options.packing_algorithm});
</script>

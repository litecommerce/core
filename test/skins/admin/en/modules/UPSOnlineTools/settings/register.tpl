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
<div class="ErrorMessage" IF="!valid">
The form contains some errors!<br>
Please check that you have completed all the required fields correctly.
<p>
</div>

<table width="100%" border="0" cellspacing="3" cellpadding="2">

<tr valign="middle">
    <td align="left">Contact Name:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="contact_name" size="32" maxlength="30" value="{reg.contact_name}" style="width:250;"/></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="contact_name"></td>
</tr>

<tr valign="middle">
    <td align="left">Title:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="title_name" size="32" maxlength="35" value="{reg.title_name}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="title_name"></td>
</tr>

<tr valign="middle">
    <td align="left">Company name:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="company" size="32" maxlength="35" value="{reg.company}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="company"></td>
</tr>

<tr valign="middle">
    <td align="left">Street Address:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="address" size="32" maxlength="50" value="{reg.address}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="address"></td>
</tr>

<tr valign="middle">
    <td align="left">City:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="city" size="32" maxlength="50" value="{reg.city}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="city"></td>
</tr>

<tr valign="middle">
	<td align="left">State</td>
	<td><font class="Star">*</font></td>
	<td>
		<select name="state" style="width:250;">
			<option value="">Non US/Canadian Address</option>
			<option FOREACH="upsstates,k,v" value="{k}" selected="reg.state=k">{v:h}</option>
		</select>
	</td>
	<td>&nbsp;</td>
</tr>

<tr valign="middle">
	<td align="left">Country</td>
	<td><font class="Star">*</font></td>
	<td>
		<select name="country" style="width:250;">
			<option value="">Please select one</option>
			<option FOREACH="upscountries,k,v" value="{k}" selected="reg.country=k">{v:h}</option>
		</select>
	</td>
	<td><widget class="XLite_Validator_RequiredValidator" field="country"></td>
</tr>

<tr valign="middle">
    <td align="left">Zip/Postal code:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="postal_code" size="32" maxlength="11" value="{reg.postal_code}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="postal_code"></td>
</tr>

<tr valign="middle">
    <td align="left">Phone Number:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="phone" size="32" maxlength="25" value="{reg.phone}" style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="phone"></td>
</tr>

<tr>
    <td colspan="4"><b>Note:</b> Phone Number must contain only digits without any separators.</td>
</tr>

<tr valign="middle">
    <td align="left">Web Site URL:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="web_url" size="32" maxlength="254" {if:reg.web_url}value="{reg.web_url}"{else:}value="{getShopUrl(##)}"{end:} style="width:250;"></td>
    <td><widget class="XLite_Validator_RequiredValidator" field="web_url"></td>
</tr>

<tr valign="middle">
    <td align="left">E-mail Address:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="email" size="32" maxlength="50" value="{reg.email}" style="width:250;"/></td>
    <td><widget class="XLite_Validator_EmailValidator" field="email"></td>
</tr>

<tr valign="middle">
    <td align="left">UPS Account Number:</td>
	<td class="Star">*</td>
    <td nowrap><input type="text" name="shipper_number" size="32" maxlength="10" value="{reg.shipper_number}" style="width:250;"></td>
    <td>&nbsp;</td>
</tr>

<tr>
	<td colspan="4"><font class="Star">*</font> Indicates required field.</td>
</tr>
<tr>
<td colspan="4">
<br>
To open a UPS Account <a href="https://www.ups.com/myups/info/openacct" target="ups"><b>click here</b></a> or call 1-800-PICK-UPS. 
<br><br><br>
I would like a UPS Sales Representative to contact me about opening a UPS shipping account or to answer questions about UPS services:
<table border="0">
<tr>
    <td><input type="radio" name="software_installer" value="yes" checked="{reg.software_installer=#yes#}"></TD>
    <td>Yes</td>
    <td>&nbsp;&nbsp;</td>
    <td><input type="radio" name="software_installer" value="no" checked="{reg.software_installer=#no#}"></TD>
    <td>No</td>
    <td>&nbsp;</td>
</tr>
</table>

</td>
</tr>
</table>

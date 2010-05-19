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
<script language="Javascript">
<!--

function setMIDColor(elm)
{
	var element = document.getElementById("merchant_id");
	if (!element) {
		return;
	}

	if (elm.checked) {
    	element.style.backgroundColor = "#E0E0E0";
    	element.style.color = "#8A8A8A";
	} else {
    	var element2 = document.getElementById("insured");
    	if (!element2) {
    		return;
    	}
    	element.style.backgroundColor = element2.style.backgroundColor;
    	element.style.color = element2.style.color;
    }
}

-->
</script>

<span class="SuccessMessage" IF="{updated}">Canada Post settings were successfully saved</span>
<table width="100%" border="0" cellpadding="2" cellspacing="1">
	<form action="admin.php" method="POST">
	<input type="hidden" name="target" value="cps">
	<input type="hidden" name="action" value="update">
    <tr>
        <td colspan="2"> You should obtain Merchant ID from CanadaPost at <a href="http://www.canadapost.ca/"><b>www.canadapost.ca</b></a></td>
    </tr>
	<tr>
		<td width="25%"><b>Canada Post Merchant ID:</b></td>
		<td width="50%"><input type="text" name="merchant_id" id="merchant_id" value="{settings.merchant_id:r}" size="32"></td>
	</tr>
 <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td><b>Insured value:</b></td>
        <td width="50%"><input type="text" name="insured" id="insured" value="{settings.insured:r}" size="32"></td>
    </tr>
    <tr>
        <td><b>Already packed:</b></td>
        <td width="50%">
			<select name="packed">
				<option value="Y" selected="{isSelected(settings.packed,#Y#)}">Yes</option>
                <option value="N" selected="{isSelected(settings.packed,#N#)}">No</option>
			</select>
		</td>
    </tr>
    <tr>
        <td><b>Package width (cm):</b></td>
        <td width="50%"><input type="text" name="width" value="{settings.width:r}" size="32"></td>
    </tr>
    <tr>
        <td><b>Package height (cm):</b></td>
        <td width="50%"><input type="text" name="height" value="{settings.height:r}" size="32"></td>
    </tr>
    <tr>
        <td><b>Package length (cm):</b></td>
        <td width="50%"><input type="text" name="length" value="{settings.length:r}" size="32"></td>
    </tr>
    <tr>
        <td><b>Currency rate (shop's currency/CAD):</b></td>
        <td width="50%"><input type="text" name="currency_rate" value="{settings.currency_rate:r}" size="32"></td>
    </tr>
    <tr>
        <td><b>Use test environment:</b></td>
        <td width="50%"><input type="checkbox" name="test_server" id="test_server" checked="{settings.test_server}" onClick="this.blur();setMIDColor(this)"></td>
		<script language="Javascript">var element = document.getElementById("test_server");if (element) { setMIDColor(element); } </script>
    </tr>
	<tr>
		<td colspan=2><INPUT type=submit value="Apply"></TD>
	</tr>
	</form>	
</table>

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
<script language="JavaScript">
var names = Array();
var labels = Array();

{foreach:taxes._taxes,ind,tax}
names[{ind}] = 'name_{ind}';
labels[{ind}] = 'display_label_{ind}';
{end:}

	function showTaxMsg() 
	{
		var tax_incl = document.getElementById('tax_included');
		var msg_text = document.getElementById('tax_message');
		if (tax_incl.selectedIndex == 0) {
			msg_text.style.display = "none";
		} else {
			msg_text.style.display = "";
		}
	}

    function deleteTax()
    {
        if (confirm('You are about to delete selected taxes display.\n\nAre you sure you want to delete them?')) { 
            document.taxes_form.action.value = 'delete_tax'; 
            document.taxes_form.submit(); 
            return true;
        }
        return false;
    }   

	function updateTax()
	{
		len = names.length;
		for (i = 0; i < len; i++)
		{
			nameObj = document.getElementById(names[i]);
			if ( nameObj )
			{
				str = nameObj.value;
				if ( str.replace(/ +$/, "") == '' )
				{
					alert("Tax name is empty");
					return;
				}
			}

			labelObj = document.getElementById(labels[i]);
			if ( labelObj )
			{
				str = labelObj.value;
				if ( str = str.replace(/ +$/, "") == '' )
				{
					alert('Tax label is empty');
					return;
				}
			}
		}

		document.taxes_form.submit();
	}

    function addTax()
    {
		var tax_label = document.getElementById('tax_label');
		var tax_name = document.getElementById('tax_name');
		if (!tax_name.value) {
			alert('Tax name is empty');
			return false;
		}
        if (!tax_label.value) {
            alert('Tax label is empty');
            return false;
        }
		document.taxes_form.action.value = 'add_tax'; 
        document.taxes_form.submit(); 
    }
</script>
<table border=0 cellpadding="0">
<form action="admin.php" method="POST" name="taxes_form"> 
<input type="hidden" name="target" value="taxes">
<input type="hidden" name="action" value="update_options">
<tr>
	<td class=ProductDetails width="30%">Address to use for tax calculations:</td>
	<td class=ProductDetails><select name="use_billing_info">
		<option value="N" selected="{config.Taxes.use_billing_info=0}">Shipping info</option>
		<option value="Y" selected="{config.Taxes.use_billing_info=1}">Billing info</option>
		</select>
	</td>
</tr>
<tr>
    <td>Taxes included in product prices:</td>
    <td><select id="tax_included" name="prices_include_tax" onChange="showTaxMsg();">
        <option value="N" selected="{config.Taxes.prices_include_tax=0}">No</option>    
        <option value="Y" selected="{config.Taxes.prices_include_tax=1}">Yes</option>   
        </select>
    </td>
</tr>
<tbody id="tax_message">
<tr IF="discountUsedForTaxes">
    <td>Discounts charged after taxes application:</td>
    <td>
        <select name="discounts_after_taxes">
            <option value="N" selected="{config.Taxes.discounts_after_taxes=0}">No</option>    
            <option value="Y" selected="{config.Taxes.discounts_after_taxes=1}">Yes</option>   
        </select>
    </td>
</tr>
<tr>
    <td>Message next to the product price when tax is included:</td>
    <td><input type="text" size="40" name="include_tax_message" value="{config.Taxes.include_tax_message:r}"></td>
</tr>
</tbody>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
</table>
<script language="JavaScript">
    showTaxMsg();
</script>
<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td class="ProductDetailsTitle" nowrap valign="top">Taxes to display</td>
</tr>
<tr>
    <td class="ProductDetailsTitle" nowrap valign="top">&nbsp;</td>
</tr>
<tr>
	<td class=ProductDetails>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
   		<tr>
       		<td class="CenterBorder" colspan="2">
       			<table width="100%" cellspacing="1" cellpadding="2" border="0">
           		<tr class="TableHead" IF='!taxes._taxes'>
					<td colspan="5">no taxes</td>
				</tr>
				<tr IF='taxes._taxes' class="TableHead">
					<th>Pos.</th>
					<th>Tax name</th>
					<th>Display name</th>
					<th>Registration number</th>
					<th>&nbsp;</th>
				</tr>
				<tr FOREACH="taxes._taxes,ind,tax" align="center" class="{getRowClass(ind,#DialogBox#,#TableRow#)}">
					<td><input style="width : 100%" type="text" name="pos[{ind}]" value="{getIndex(tax,ind)}" size="4"></td>
					<td><input style="width : 100%" type="text" name="name[{ind}]" id="name_{ind}" value="{getTaxName(tax):r}" size="14"></td>
					<td><input style="width : 100%" type="text" name="display_label[{ind}]" id="display_label_{ind}" value="{getDisplayName(tax):r}" size="16"></td>
					<td><input style="width : 100%" type="text" name="registration[{ind}]" value="{getRegistration(tax):r}" size="16"></td>	
					<td><input type="checkbox" name="deleted[{ind}]"></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	    <tr>
	        <td align="left"><input type="button" value=" Update " class="DialogMainButton" OnClick="updateTax();"></td>
	        <td align="right"><input type="button" value=" Delete selected " onClick="deleteTax();"></td>
	    </tr>
		</table>
		<br>
		<p>
    		<font class="AdminTitle">Add new tax</font>
		</p>
	    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        <td class="CenterBorder" colspan="2">
            <table width="100%" cellspacing="1" cellpadding="0" border="0">
				<tr class="TableHead">
                    <th>Pos.</th>
                    <th>Tax name</th>
                    <th>Display name</th>
                    <th>Registration number</th>
				</tr>
				<tr class="DialogBox">
					<td width="11%"><input type="text" style="width : 100%" name="new_pos" value="" size="4"></td>
					<td><input type="text" id="tax_name" style="width : 100%" name="new_name" value="" size="14"></td>
					<td><input type="text" id="tax_label" style="width : 100%" name="new_display_label" value="" size="16"></td>
			        <td><input type="text" style="width : 100%" name="new_registration" value="" size="16"></td> 
				</tr>	 
			</table>
  		</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>	
		<tr>
			<td colspan="2"><input type="button" value=" Add " onClick="addTax();"></td>
		</tr>
	</table>
	</td>
</tr>
</form>
</table>

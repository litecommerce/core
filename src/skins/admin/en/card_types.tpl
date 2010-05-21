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
Use this section to define types of credit cards accepted at your store.<hr>

<font IF="status=#added#" class="SuccessMessage"><br>&gt;&gt;&nbsp;Credit card added successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#deleted#" class="SuccessMessage"><br>&gt;&gt;&nbsp;Credit card deleted successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#updated#" class="SuccessMessage"><br>&gt;&gt;&nbsp;Credit card updated successfully&nbsp;&lt;&lt;<br><br></font>

<br>

<form action="admin.php" method="post" name="card_types">
<input type="hidden" name="code" value="">
<input type="hidden" name="target" value="card_types">
<input type="hidden" name="action" value="update">

<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr>
				    <th class="TableHead">Card code</th>
				    <th class="TableHead">Card type</th>
				    <th class="TableHead">CVV2</th>
				    <th class="TableHead">Enabled</th>
			    	<th class="TableHead">Pos.</th>
				    <th class="TableHead">&nbsp;</th>
				</tr>
				<tr FOREACH="xlite.factory.XLite_Model_Card.readAll(),card_idx,card" class="{getRowClass(card_idx,#DialogBox#,#TableRow#)}">
				    <td width="15%">
				        {card.code:h}
				        <input type="hidden" name="card_types[{card.code}][code]" value="{card.code}">
				    </td>
				    <td width="30%">
				        <input type="text" name="card_types[{card.code}][card_type]" size="24" value="{card.card_type:r}" style="width: 200px">
				    </td>
				    <td width="7%" align="center"><input type="checkbox" name="card_types[{card.code}][cvv2]" value="1" checked="{card.isCVV2()}"></td>
				    <td width="10%" align="center"><input type="checkbox" name="card_types[{card.code}][enabled]" value="1" checked="{card.enabled}"></td>
				    <td width="10%" align="center"><input type="text" name="card_types[{card.code}][orderby]" value="{card.orderby}" size="10%" maxlength="5"></td>
				    <td width="1%"><input type="button" name="delete" value="Delete" onclick="document.card_types.code.value='{card.code}'; document.card_types.action.value='delete'; document.card_types.submit();"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	<tr>
    	<td colspan="6">
        	<input type="submit" name="update" value="Update" class="DialogMainButton">
	    </td>
	</tr>
</table>
</form>

<p>

<form action="admin.php" method="post" name="card_delete">
<table cellpadding="0" cellspacing="0" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminTitle">Add credit card type</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr>
    				<th class="TableHead">Card code</th>
				    <th class="TableHead">Card type</th>
				    <th class="TableHead">CVV2</th>
				    <th class="TableHead">Enabled</th>
				    <th class="TableHead">Pos.</th>
				</tr>
				<tr class="DialogBox">
				    <td width="15%">
			    	    <input type="text" name="code" size="15%" value="{code}">
				    </td>
				    <td width="30%">
				        <input type="text" name="card_type" size="24" value="{card_type}" style="width: 200px">
				    </td>
				    <td width="7%" align="center"><input type="checkbox" name="cvv2" value="1" /></td>
				    <td width="10%" align="center"><input type="checkbox" name="enabled" value="1" /></td>
				    <td width="10%" align="center"><input type="text" name="orderby" value="{orderby}" size="10%" maxlength="5" /></td>
				</tr>
			</table>
		</td>
	</tr>

{if:!valid}
	<tr>
		<td IF="status=#code#" colspan="5"><font class="ErrorMessage">&gt;&gt;&nbsp;Mandatory field "Code" is empty.</font><br><br></td>
		<td IF="status=#card_type#" colspan="5"><font class="ErrorMessage">&gt;&gt;&nbsp;Mandatory field "Card type" is empty.</font><br><br></td>
		<td IF="status=#exists#" colspan="5"><font class="ErrorMessage">&gt;&gt;&nbsp;Card already exists</font><br><br></td>
	</tr>
{end:}

	<tr>
	    <td colspan="5">
    	    <input type="hidden" name="target" value="card_types">
        	<input type="hidden" name="action" value="add">
	        <input type="submit" name="add" value="Add new">
    	</td>
	</tr>
</table>

<p><b>Note:</b> Checkmark the 'CVV2' field if a credit card type requires it.

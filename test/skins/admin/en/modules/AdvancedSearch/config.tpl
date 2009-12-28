<span IF="allPrices">
<table width="400" cellpadding="0" cellspacing="3" border="0">
<form name="pr_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="advanced_search">
<input type="hidden" name="action" value="update">
	<tr>
		<th colspan="5" align="left" class="AdminTitle">Product price ranges</th>
	</tr>
    <tr>
        <td colspan="6"><hr></td>
    </tr>
	<tr class="TableHead">
        <th>&nbsp;Active&nbsp;</th>
		<th>Label</th>
        <th>Start</th>
        <th>End</th>
	    <th>&nbsp;</th>
	</tr>
    <tr foreach="allPrices,key,price">
	     <td align="center"><input type="checkbox" name="prices[{key}][enabled]" checked="{price.enabled}"></td>
        <td><input type="text" name="prices[{key}][label]" size="12" value="{price.label}"></td>
        <td><input type="text" name="prices[{key}][start]" size="12" value="{price.start}"></td>
        <td><input type="text" name="prices[{key}][end]" size="12" value="{price.end}"></td>
        <td width="20px"><input type="checkbox" name="deleted_prices[]" value="{key}" onClick="this.blur()"></td>
    </tr>
	<tr>
		<td align="left" colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" colspan="2">
        <input type="submit" value="Update">
		</td>
		<td align="right" colspan="4">
        <input type="button" onClick="javascript: document.pr_form.action.value='delete'; document.pr_form.submit();" value="Delete selected">
		</td>
	</tr>
</form>
</table>
</span>
<span IF="!allPrices">
<table width="400" cellpadding="0" cellspacing="3" border="0">
	<tr>
		<th align="left" class="AdminTitle">Product price ranges</th>
	</tr>
	<tr>
		<td align="left"><hr>No product price ranges found.</td>
	</tr>
</table>
</span>

<table width="400" cellpadding="0" cellspacing="3" border="0">
<form name="new_pr_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="advanced_search">
<input type="hidden" name="action" value="add">
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="5" align="left" class="AdminTitle">Add new price range</th>
    </tr>
	<tr>
		<td colspan="5"><hr></td>
	</tr>
    <tr class="TableHead">
        <th align=left>&nbsp;&nbsp;&nbsp;Label</th>
        <th align=left>&nbsp;&nbsp;&nbsp;Start</th>
        <th align=left>&nbsp;&nbsp;&nbsp;End</th>
    </tr>
    <tr>
        <td><input type="text" name="new_price[label]" size="12"></td> 
        <td><input type="text" name="new_price[start]" size="12"></td> 
        <td><input type="text" name="new_price[end]" size="12"></td> 
    </tr>
	<tr>
		<td align="left" colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5"><input type="submit" value="Add"></td>
	</tr>
<input type="hidden" name="new_price[enabled]" value="on">
</form>
</table>

<br>
<hr>
<br>

<span IF="allWeights">
<table width="400" cellpadding="0" cellspacing="3" border="0">
<form name="wr_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="advanced_search">
<input type="hidden" name="action" value="update">
	<tr>
		<th colspan="5" align="left" class="AdminTitle">Product weight ranges</th>
	</tr>
    <tr>
        <td colspan="6"><hr></td>
    </tr>
	<tr class="TableHead">
        <th>&nbsp;Active&nbsp;</th>
		<th>Label</th>
        <th>Start</th>
        <th>End</th>
		<th>&nbsp;</th>
	</tr>
    <tr foreach="allWeights,key,weight">
		<td><input type="checkbox" name="weights[{key}][enabled]" checked="{weight.enabled}"></td>
        <td><input type="text" name="weights[{key}][label]" size="12" value="{weight.label}"></td>
        <td><input type="text" name="weights[{key}][start]" size="12" value="{weight.start}"></td>
        <td><input type="text" name="weights[{key}][end]" size="12" value="{weight.end}"></td>
        <td width="20px"><input type="checkbox" name="deleted_weights[]" value="{key}" onClick="this.blur()"></td>
    </tr>
	<tr>
		<td align="left" colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" colspan="2">
        <input type="submit" value="Update">
		</td>
		<td align="right" colspan="3">
        <input type="button" onClick="javascript: document.wr_form.action.value='delete'; document.wr_form.submit();" value="Delete selected">
		</td>
	</tr>
</form>
</table>
</span>
<span IF="!allWeights">
<table width="400" cellpadding="0" cellspacing="3" border="0">
	<tr>
		<th colspan="5" align="left" class="AdminTitle">Product weight ranges</th>
	</tr>
    <tr>
        <td colspan="5"><hr>No product weight ranges found.</td>
    </tr>
</table>
</span>
<br>
<table width="400" cellpadding="0" cellspacing="3" border="0">
<form name="new_wr_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="advanced_search">
<input type="hidden" name="action" value="add">
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="5" align="left" class="AdminTitle">Add new weight range</th>
    </tr>
	<tr>
		<td colspan="5"><hr></td>
	</tr>
    <tr class="TableHead">
        <th align=left>&nbsp;&nbsp;&nbsp;Label</th>
        <th align=left>&nbsp;&nbsp;&nbsp;Start</th>
        <th align=left>&nbsp;&nbsp;&nbsp;End</th>
    </tr>
    <tr>
        <td><input type="text" name="new_weight[label]"	size="12"></td> 
        <td><input type="text" name="new_weight[start]"	size="12"></td> 
        <td><input type="text" name="new_weight[end]"  	size="12"></td> 
    </tr>
	<tr>
		<td align="left" colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5"><input type="submit" value="Add"></td>
	</tr>
<input type="hidden" name="new_weight[enabled]" value="on">
</form>
</table>


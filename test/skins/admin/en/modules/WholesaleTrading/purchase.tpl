<form action="admin.php" method="POST">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="add_purchase_limit">
<table border="0">
<tr>
	<td>Minimal purchase limit</td>
	<td><input name="min_purchase" size="7" value="{PurchaseLimit.min}">
</tr>
<tr>
	<td>Maximal purchase limit</td>
	<td><input name="max_purchase" size="7" value="{purchaseLimit.max}">
</tr>
<tr>
	<td colspan="2"><input type="submit" value=" Update "></td>
</tr>	
</table>
</form>

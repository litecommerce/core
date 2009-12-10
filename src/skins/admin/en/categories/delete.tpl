<form name="deleteForm" action="admin.php" method="POST">
<input type="hidden" name="target" value="category">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="category_id" value="{category_id}">

<table border="0">
<tr>
	<td colspan="3">
All products under the following category will be removed:
	</td>
</tr>
<tr>
	<td colspan="3">
    <b>{category.name}<br></b>
	</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>
	<td colspan="3" class="AdminTitle">
Warning: this operation can not be reverted!
	</td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr>	
	<td colspan=3>Are you sure you want to continue?<br><br>
		<input type="submit" value="Yes" class="DialogMainButton">&nbsp;&nbsp;
		<input type="button" value="No" onclick="javascript: document.location='admin.php?target=categories&category_id={category.parent}'">
	</td>
</tr>	
</table>
</form>

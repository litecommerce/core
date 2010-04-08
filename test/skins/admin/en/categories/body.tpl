
<table border="0" width="100%" IF="!category.category_id=#0#">
	<tr>
 		<td valign="top" IF="category.hasImage()">
 			<img src="cart.php?target=image&action=category&category_id={category.category_id}" border="0">
 		</td>
 		<td width=100% valign=top>
			<table border="0" cellpadding="3" cellspacing="1" valign=top>
			<tr>
        		<td nowrap>Category name:</td>
                <td>&nbsp;</td>
        		<td class="FormButton">{category.name}</td>
            </tr>
			<tr>
        		<td nowrap>Description:</td>
                <td>&nbsp;</td>
        		<td class="FormButton">{category.description}</td>
            </tr>
			<tr>
        		<td nowrap>Availability:</td>
                <td>&nbsp;</td>
        		<td class="FormButton">{if:category.enabled}Enabled{else:}Disabled{end:}</td>
            </tr>
			<tr>
        		<td nowrap>Membership access:</td>
                <td>&nbsp;</td>
        		<td class="FormButton">
            	{if:isSelected(#%#,category.membership)}All memberships
            	{else:}
            		{if:isSelected(##,category.membership)}No membership
            		{else:}
            			{if:isSelected(#pending_membership#,category.membership)}Pending membership
            			{else:}
                    		{foreach:config.Memberships.memberships,membership}{if:category.membership=membership}{category.membership}{end:}{end:}
                    	{end:}
            		{end:}
            	{end:}
        		</td>
            </tr>
			<tr>
                <td nowrap>Parent category:</td>
                <td>&nbsp;</td>
        		<td class="FormButton"><a href="admin.php?target=categories&category_id={category.parent}">{if:!category.parent=#0#}{category.parentCategory.name}{else:}[Root Level]{end:}</a></td>
            </tr>
			<tr>
                <td nowrap>Products number:</td>
                <td>&nbsp;</td>
        		<td IF="category.productsNumber"><span class="FormButton">{category.productsNumber} product{if:!category.productsNumber=#1#}s{end:}</span>&nbsp;<a href="admin.php?target=product_list&mode=search&mode=search&search_category={category.category_id}" onClick="this.blur()"><u>details</u></a>&nbsp;&gt;&gt;</td>
        		<td class="FormButton" IF="!category.productsNumber">0</td>
            </tr>
			<tr>
                <td colspan=3>
				<input type="button" value="Modify" onClick="onModifyClick('{category.category_id}')">
                </td>
            </tr>
            </table>
 		</td>
	</tr>
</table>

<p>

<form name="CategoryForm" method="POST" action="admin.php">
<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<th colspan=3 align="left" IF="!category.category_id=#0#">
        <span class="FormButton" IF="!category.category_id=#0#">Subcategories</span>
        <hr>
		</th>
	</tr>
	<tr class="TableHead">
		<th align="left">Pos.</th>
		<th align="left" width="100%" colspan=2>&nbsp;&nbsp;&nbsp;Category Name</th>
	</tr>
	<tr FOREACH="category.subcategories,id,cat" class="{getRowClass(id,##,#TableRow#)}">
		<td align="right" width="45"><input name="category_order[{cat.category_id}]" value="{cat.order_by}" size="3"></td>
		<td width="100%">
			&nbsp;
			<input type="button" value="Modify" onClick="onModifyClick('{cat.category_id}')">
			&nbsp;
			<a href="admin.php?target=categories&category_id={cat.category_id}" title="Click here to access/add subcategories" onClick="this.blur()"><font class="ItemsList"><u>{cat.name:h}</u></font></a>{if:!cat.enabled}&nbsp;&nbsp;<font color=red>(disabled)</font>{end:}
		</td>
        <td nowrap>
            &nbsp;&nbsp;
			<input type="button" value="Delete" onClick="onDeleteClick('{cat.category_id}')">
        </td>
	</tr>
</table>

<br>
<input type="hidden" name="target" value="categories">
<input type="hidden" name="category_id" value="{category.category_id}">
<input type="hidden" name="action">
<input type="hidden" name="mode">

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>	
		<td>		
		<input id="update_button" type="button" value="Update" onclick="onUpdateClick()" class="DialogMainButton">
		&nbsp;&nbsp;&nbsp;
		<input type="button" value="Add new category" onclick="onAddClick()">
		</td>		
		<td align=right>
		<input id="delete_all_button" type="button" value="Delete all" onClick="onDeleteAllClick()">
		</td>		
	</tr>	
</table>

</form>

<script language="javascript">

{if:!category.subcategories}
    // disable "delete" controls
    disableControls();
{end:}

function disableControls()
{
    document.CategoryForm.update_button.disabled = true;
    document.CategoryForm.delete_all_button.disabled = true;
}

function onUpdateClick()
{
	document.CategoryForm.action.value = "update";
	document.CategoryForm.submit();
}

function onAddClick()
{
    document.location = "admin.php?target=category&category_id={category.category_id}&mode=add";
}

function onDeleteAllClick()
{
    document.location = "admin.php?target=categories&category_id={category.category_id}&mode=delete_all";
}	

function onDeleteClick(category_id)
{
	document.location = "admin.php?target=category&category_id=" + category_id + "&mode=delete";
}	

function onModifyClick(category_id)
{
    document.location = "admin.php?target=category&category_id=" + category_id + "&mode=modify";
}	

</script>

<p IF="category.subcategories"><b>Note:</b> click on category name to access/add subcategories.</p>

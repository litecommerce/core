<table cellpadding=0 cellspacing=0 width=100%>
<form action="admin.php" method="POST" name="inventory_tracking_select">
<input FOREACH="dialog.allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="tracking_selection"/>
<tr>
    <td colspan=2>
		<table cellpadding=0 cellspacing=0>
			<tr>
                <td align="right">
                    Track product:&nbsp;
                </td>
                <td>
                    <select name="tracking">
                        <option value="0" selected="{isSelected(#0#,product.tracking)}">without product options</option>
                        <option value="1" selected="{isSelected(#1#,product.tracking)}">with product options</option>
                    </select>
                </td>
                <td>
                    &nbsp;&nbsp;<input type=submit value="Change">
                </td>
			</tr>
		</table>
    </td>
</tr>
<tr>
    <td colspan=2><hr></td>
</tr>
<tr IF="error">
    <td colspan=2>
        <p class="ErrorMessage">
            <br>Invalid option (you are trying to add a tracking option which already exists or an empty tracking option)
        </p>
    </td>
</tr>
</form>
</table>

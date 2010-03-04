<tr>
	<td valign=top>
        {if:label}{label:h}{else:}Categories:{end:}<br><br>
        <i>To (un)select more than one category,<br>Ctrl-click it</i>
    </td>
	<td valign=top>
        <select id="category_selector" name="selected_categories[]" multiple size="10">
		    <option FOREACH="categories,v" value="{v.category_id:r}" selected="{isSelectedItem(#selected_categories#,v.category_id)}">{v.stringPath:h}</option>
		</select>

        <widget template="modules/EcommerceReports/checker.tpl" selector="category_selector">
        
	</td>
</tr>


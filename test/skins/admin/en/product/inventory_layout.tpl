<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.XLite_Model_ProductInventory.getImportFields(#inventory_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="inventory_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
</table>

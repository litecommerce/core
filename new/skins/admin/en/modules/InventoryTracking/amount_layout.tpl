<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="inventory.getImportFields(#amount_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="amount_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
</table>

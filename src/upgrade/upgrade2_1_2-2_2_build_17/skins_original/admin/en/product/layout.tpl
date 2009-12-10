<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.Product.importFields,id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="product_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan="2">
    <input type=button value="Save field order" onClick="javascript: document.data_form.action.value='layout'; document.data_form.submit();">
    </td>
</tr>
</table>

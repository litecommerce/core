This page allows to export product options into CSV file.<hr>

<p>
<form action="admin.php" method=post name=data_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="export_product_options">

<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.XLite_Module_ProductOptions_Model_ProductOption.getImportFields(#product_options_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="product_options_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="value=id">{field}</option>
        </select>
    </td>
</tr>
</table>
<p>
Field delimiter:<br><widget template="common/delimiter.tpl"><br>
<br>
<input type=submit value=" Export ">

</form>

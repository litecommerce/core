This page allows to export PIN codes into CSV file.<hr>

<p>
<form action="admin.php" method="POST" name=data_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="export_pin_codes">

<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.XLite_Module_Egoods_Model_PinCode.getImportFields(#pin_codes_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="pin_codes_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
</table>
<p>
Field delimiter:<br><widget template="common/delimiter.tpl"><br>
<br>
<input type=submit value=" Export ">

</form>

This page allows to export wholesale pricing into CSV file.<hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
</p>

<p>
<form action="admin.php" method=post name=data_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="export_wholesale_pricing">

<table border=0>
<tr>
    <td colspan=2><widget template="modules/WholesaleTrading/field_order.tpl"></td>
</tr>
<tr FOREACH="xlite.factory.XLite_Module_WholesaleTrading_Model_WholesalePricing.getImportFields(#wholesale_pricing_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="wholesale_pricing_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{isOrderFieldSelected(id,field,value)}">{field}</option>
        </select>
    </td>
</tr>
</table>
<p>
Field delimiter:<br><widget template="common/delimiter.tpl"><br>
<br>
<input type=submit value=" Export ">

</form>

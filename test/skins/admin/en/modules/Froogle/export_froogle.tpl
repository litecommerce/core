<p align=justify>This page allows you to export products into Froogle format.</p>

<br>

<form action="admin.php" method=POST name=data_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="export_froogle">

<table border=0 cellpadding=3 cellspacing=0>
<tr>
    <td><input type=radio name=mod value=upload checked></td>
    <td colspan=2>Export and upload to Froogle (PHP should be installed with &quot;--enable-ftp&quot; option)</td>
</tr>
<tr>
    <td><input type=radio name=mod value=download></td>
    <td colspan=2>Download only</td>
</tr>
</table>

<br>
<input type=submit value=" Export ">

</form>

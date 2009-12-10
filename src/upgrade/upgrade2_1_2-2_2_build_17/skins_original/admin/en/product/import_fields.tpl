<p>Use this section to import product extra fields from a CSV file. <hr>

<p IF="import_error">
<font class="ErrorMessage">
&gt;&gt; Import error occured &lt;&lt;<br>
You have specified incorrect file format or the file doesn't match. 
</font>
</p>

<p>
<form action="admin.php" method=POST enctype="multipart/form-data" name=data_form>
<input type="hidden" name="target" value="import_catalog">
<input type="hidden" name="action" value="import_fields">
<input type="hidden" name="page" value="{page}">

<widget template="product/fields_layout.tpl">

<table border="0">
<tr>
    <td colspan=2>
    <br>
    Field delimiter:<br><widget template="common/delimiter.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    Text qualifier:<br><widget template="common/qualifier.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) local:<br>
    <input type=text size=32 name=localfile>
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) for upload:<br><input type=file size=32 name=userfile><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" />
    <br>
    <br>
    <input type=submit value="Import fields" class="DialogMainButton">
    </td>
</tr>
</table>
</form>

Use this section to export product pricing information into a CSV file or import it from a CSV file.
<br><b>Note:</b> This section provides simple functionality. Use 'Export products' / 'Import products' components if you need more options. <hr><br>

<FORM method=POST action="admin.php" enctype="multipart/form-data" name="inventory_form">
<input type="hidden" name="target" value="update_inventory">
<input type="hidden" name="action" value="">
<input type="hidden" name="what" value="pricing">

<widget template="product/inventory_layout.tpl">

<p>Field delimiter:&nbsp;<widget template="common/delimiter.tpl">

<p>
<font class="AdminTitle">Export pricing information</font>
<br>

<TABLE border=0 cellpadding=0 cellspacing=4>
<TR>
<TD colspan=2><INPUT type=button value="Export" onclick="document.inventory_form.action.value='export'; document.inventory_form.submit()">
</TABLE>

</p>

<br>

</p>
<font class="AdminTitle">Update pricing information</font>
<br>

<TABLE border=0 cellpadding=0 cellspacing=4>

<tr>
<td>Text qualifier:&nbsp;</td>
<td><widget template="common/qualifier.tpl"></td>
</tr>

<tr>
<td>Field delimiter:&nbsp;</td>
<td><widget template="common/delimiter.tpl"></td>
</tr>

<TR>
<TD>CSV file</TD>
<TD><INPUT type=file name=userfile><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" /></TD>
</TR>
<TR>
<TD colspan=2><INPUT type=button value="Import" onclick="document.inventory_form.action.value='import'; document.inventory_form.submit()">
</TR>
</TABLE>

</p>
</FORM>

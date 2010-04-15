{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
Use this section to export product pricing information into a CSV file or import it from a CSV file.
<br><b>Note:</b> This section provides simple functionality. Use 'Export products' / 'Import products' components if you need more options. <hr><br>

<form method=POST action="admin.php" enctype="multipart/form-data" name="inventory_form">
<input type="hidden" name="target" value="update_inventory">
<input type="hidden" name="action" value="export">
<input type="hidden" name="what" value="pricing">

<widget template="product/inventory_layout.tpl">

<p>Field delimiter:&nbsp;<widget template="common/delimiter.tpl">

<p>
<font class="AdminTitle">Export pricing information</font>
<br>

<TABLE border=0 cellpadding=0 cellspacing=4>
<TR>
<TD colspan=2><INPUT type=submit value="Export">
</TABLE>

</p>
</form>


<br>

</p>
<font class="AdminTitle">Update pricing information</font>
<br>

<form method=POST action="admin.php" enctype="multipart/form-data" name="inventory_form">
<input type="hidden" name="target" value="update_inventory">
<input type="hidden" name="action" value="import">
<input type="hidden" name="what" value="pricing">
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
<TD colspan=2><INPUT type=submit value="Import">
</TR>
</TABLE>

</p>
</form>

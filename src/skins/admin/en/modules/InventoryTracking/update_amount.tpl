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
This page allows you to export / update product amount information<hr><br>

<FORM method=POST action="admin.php" enctype="multipart/form-data" name="inventory_form">
<input type="hidden" name="target" value="update_inventory">
<input type="hidden" name="page" value="amount">
<input type="hidden" name="action" value="">
<input type="hidden" name="what" value="amount">

<widget template="modules/InventoryTracking/amount_layout.tpl">

<p>CSV delimiter <widget template="common/delimiter.tpl">

<p>
<br>
<font class="AdminTitle">Export amount information</font>
<br>

<TABLE border=0 cellpadding=0 cellspacing=4>
<TR>
<TD colspan=2><INPUT type=button value="Export" onclick="document.inventory_form.action.value='export'; document.inventory_form.submit()">
</TABLE>

</p>

<br>

</p>
<font class="AdminTitle">Update amount information</font>
<br>

<TABLE border=0 cellpadding=0 cellspacing=4>

<TR>
<TD>Text qualifier</TD>
<TD><widget template="common/qualifier.tpl">
</TD>
</TR>

<TR>
<TD>CSV file</TD>
<TD><INPUT type=file name=userfile><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" /></TD>
</TR>
<TR>
<TD colspan=2><INPUT type=button value="Update" onclick="document.inventory_form.action.value='import'; document.inventory_form.submit()">
</TR>
</TABLE>

</p>
</FORM>

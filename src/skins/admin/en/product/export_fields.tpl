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
Use this section to export product extra fields into a CSV file.

<hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
    <font IF="import_error" class="ErrorMessage">You have specified incorrect file format or the file doesn't match.<br></font>
</p>

<p>
<form action="admin.php" method=POST name=data_form>
<input type="hidden" name="target" value="export_catalog">
<input type="hidden" name="action" value="export_fields">
<input type="hidden" name="page" value="{page}">

<widget template="product/fields_layout.tpl">

<table border="0">
<tr>
    <td colspan=2>
    <br>
    Delimiter:<br><widget template="common/delimiter.tpl">
    </td>
</tr>
<tr>
    <td colspan=2> <input type=submit value="Export extra fields" class="DialogMainButton"> </td>
</tr>
</table>
</form>

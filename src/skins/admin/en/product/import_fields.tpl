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
<p>Use this section to import product extra fields from a CSV file. <hr>


<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
    <font IF="import_error" class="ErrorMessage">You have specified incorrect file format or the file doesn't match.<br></font>
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
    <input type=text size=32 name=localfile value="{localfile}"><widget IF="invalid_localfile" template="common/uploaded_file_validator.tpl" state="{invalid_localfile_state}" />
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) for upload:<br><input type=file size=32 name=userfile><widget IF="invalid_userfile" template="common/uploaded_file_validator.tpl" state="{invalid_userfile_state}" />
    <br>
    <br>
    <input type=submit value="Import fields" class="DialogMainButton">
    </td>
</tr>
</table>
</form>

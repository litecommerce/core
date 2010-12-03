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
<p>This page allows you to import product detailed images.<hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
</p>

<p>
<form action="admin.php" method="post" enctype="multipart/form-data" name="data_form">
<input type="hidden" name="target" value="import_catalog">
<input type="hidden" name="action" value="import_detailed_images">
<input type="hidden" name="page" value="detailed_images">

<table border=0>
<tr>
    <td colspan=2 class=AdminHead>Field order</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr FOREACH="xlite.factory.\XLite\Module\CDev\DetailedImages\Model\DetailedImage.getImportFields(),id,fields">
    <td>{id}:</td>
    <td><select name="detailed_images_layout[{id}]">
            <option FOREACH="fields,name,value" value="{name}" selected="{value}">{name}</option>
        </select>
    </td>
</tr>
</table>

<table border=0>
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
    Directory where images are located:<BR>
    <input type=text size=32 name=images_directory value="{config.Images.images_directory}">
    &nbsp;
    <input type=checkbox name=save_images> Save images to database
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) local:<br>
    <input type=text size=32 name=localfile><widget IF="invalid_localfile" template="common/uploaded_file_validator.tpl" state="{invalid_localfile_state}" />
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) for upload:<br><input type=file size=32 name=userfile><widget IF="invalid_userfile" template="common/uploaded_file_validator.tpl" state="{invalid_userfile_state}" />
    <br>
    <br>
    <input type=submit value="Import images">
    </td>
</tr>
</table>

</form>

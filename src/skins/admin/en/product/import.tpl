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
<p>Use this section to import catalog data from a CSV file. <hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
    <font IF="import_error" class="ErrorMessage">You have specified incorrect file format or the file doesn't match.<br></font>
    <font IF="category_unspecified_error" class="ErrorMessage">Category unspecified</font>
</p>

<p>
<form action="admin.php" method=POST enctype="multipart/form-data" name=data_form>
<input type="hidden" name="target" value="import_catalog">
<input type="hidden" name="action" value="import_products">
<input type="hidden" name="page" value="{page}">

<widget template="product/layout.tpl">

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
		Unique identifier:<br>
		<select name="unique_identifier">
			<option value="sku" selected="{unique_identifier=#sku#}">Product SKU</option>
			<option value="name" selected="{unique_identifier=#name#}">Product name</option>
		</select>
	</td>
</tr>
<tr>
    <td colspan=2>
    <br>
    Default&nbsp;category:<br><widget class="XLite_View_CategorySelect" fieldName="category_id" noneOption>
    </td>
</tr>
<tr>
    <td colspan=2>
    Directory where images are located:<BR>
    {if:action=#import_products#}
        <input type=text size=32 name=images_directory value="{images_directory}">
    {else:}
        <input type=text size=32 name=images_directory value="{getImagesDir()}">
    {end:}
    &nbsp;
    <input type=checkbox name=save_images checked="{save_images}"> Save images to database
    </td>
</tr>
<tr>
    <td colspan=2>
    <b>Note:</b> If you don't specify images directory then <i><b>{getImagesDir()}</b></i> will be used as default.
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
    <input type="checkbox" value="yes" name="delete_products" checked="{delete_products}" onClick="javascript: if (document.data_form.delete_products.checked) return confirm('Are you sure?');">Drop all products before import
    <br><br>
    <input type=submit value="Import products" class="DialogMainButton">
    </td>
</tr>
</table>
</form>

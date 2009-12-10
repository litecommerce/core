<p>This page allows you to import products from CSV file. <hr>

<p IF="import_error">
<font class="ErrorMessage">
&gt;&gt; Import error occured &lt;&lt;<br>
You have specified incorrect file format or the file doesn't match. 
</font>
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
    Fields delimiter:<br><widget template="common/delimiter.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    Text qualifier:<br><widget template="common/qualifier.tpl">
    </td>
</tr>
<tr>
    <td colspan=2>
    <br>
    Default&nbsp;category:<br><widget class="CCategorySelect" fieldName="category_id" noneOption>
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
    <input type=text size=32 name=localfile>
    </td>
</tr>
<tr>
    <td colspan=2>
    File (CSV) for upload:<br><input type=file size=32 name=userfile>
    <br>
    <br>
    <input type="checkbox" value="yes" name="delete_products" onClick="javascript: if (document.data_form.delete_products.checked) return confirm('Are you sure?');">Drop all products before import
    <br><br>
    <input type=submit value="Import products">
    </td>
</tr>
</table>
</form>

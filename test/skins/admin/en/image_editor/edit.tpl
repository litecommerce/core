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
Use this tool to replace LiteCommerce standard graphic elements with your store's original and distinctive graphics.
<hr><br>
<span class="ErrorMessage" IF="editor.uploadError">{editor.uploadError:h}<br></span>
<table border="0" width="100%">
<form name="images_editor_form" action="admin.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="target" value="image_edit">
<input type="hidden" name="action" value="change">
<input type="hidden" name="current_image">
<tbody FOREACH="editor.images,key,image">

	<tr><td><font class="TopLabel">{image.description:h}</font>&nbsp;&nbsp;({image.filename})</td></tr>
	<tr>
        <td>
            <table border=1 cellspacing=0 cellpadding=5 background="images/pattern.gif" width=300>
            <tr>
                <td>
                    <img src="{image.filename}?{rand()}">
                </td>
            </tr>
            </table>
        </td>
    </tr>
	{if:image.example}
	<tr><td>Example: <br>{image.example:h}</td></tr>
	{end:}
	{if:image.recommended_size}
	<tr><td>Recommended size: {image.recommended_size}</td></tr>
	{end:}
	<tr>
        <td>
    		<input type="file" name="new_image_{key}">
    		<input type="button" value="Apply" onclick="submitForm('{key}')">
	    </td>
    </tr>
    <tr><td><br><br><br></td></tr>

</tbody>
</form>
</table>

<script language="javascript">
<!--
function submitForm(key)
{
	document.images_editor_form.current_image.value = key;
	document.images_editor_form.submit();
}
// -->
</script>

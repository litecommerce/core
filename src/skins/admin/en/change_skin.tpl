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
<script language="Javascript">
<!-- 
    function selectPreview(elm)
    {
    	var preview_container = document.getElementById("preview_container");
        if (preview_container) {
        	preview_container.style.display = "none";
        }
    	if (previewImages[elm.value]) {
    		var preview_img = document.getElementById("preview_img");
    		if (preview_img) {
    			preview_img.src = previewImages[elm.value];
    		}
            if (preview_container) {
            	preview_container.style.display = "";
            }
    	}

        var submit_button = document.getElementById("submit_button");
        if (submit_button) {
            if (elm.value != currentSkin) {
                submit_button.value="Install selected skin";
            } else {
                submit_button.value="Reinstall selected skin";
            }
        }
    }

// -->
</script>

<p>Use this section to configure the look & feel of your store.</p>

<hr>
<p class="adminParagraph"><b class="Star">Warning:</b> It is strongly recommended that you close the shop for maintenance on the <a href="admin.php?target=settings"><u>General settings</u></a> page before performing any operations on this page!</p>

<form action="admin.php" method="POST" name="change_skin_form">
<input type="hidden" name="target" value="change_skin">
<input type="hidden" name="action" value="update">

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="50%">
	<b>Current layout skin:</b>&nbsp;<span class="AdminHead">{if:currentSkin=##}???{else:}{currentSkin}{end:}</span>
	</td>
	<td width="50%">
    Select the layout skin for your store:&nbsp;
	<SELECT name="layout" id="schemas_list" onChange="selectPreview(this)">
	{foreach:skins,skin_name,skin_info}
	<OPTION value="{skin_name}" selected="skin_info.name=currentSkin">{skin_info.name}</OPTION>
	{end:}
	</SELECT>
	</td>
</tr>
<tr IF="isDisplayWarning()">
	<td colspan="2" class="ErrorMessage"><br>Warning: The module for current commercial skin "{if:currentSkin=##}???{else:}{currentSkin}{end:}" is deleted or deactivated. You can check the module status in the '<a href="admin.php?target=modules">Modules</a>' section of the Admin menu.</td>
</tr>
<tr id="preview_container" style="display: none;">
	<td colspan=2>
	    <br>
	    <br>
       	<table width="640" border=0 cellpadding=0 cellspacing=0 align=center>
    	<tr>
     		<td width=640><div style="overflow: auto; height: 350; width: 660"><img id="preview_img" border=1 src=""></div></td>
    	</tr>
    	</table>
 	</td>
</tr>
<tr id="button_container">
	<td colspan=2 align=center>
	    <br>
	    <br>
       	<input type="submit" id="submit_button" value="Install selected skin">
 	</td>
</tr>
</table>

</form>

<SCRIPT language="JavaScript">
var currentSkin = "{currentSkinName}";
var previewImages = new Array();

{foreach:skins,skin_name,skin_info}
	{if:skin_info.preview}
previewImages["{skin_name}"] = "http://{xlite.options.host_details.http_host}{xlite.options.host_details.web_dir}/{skin_info.preview}";
	{end:}
{end:}

var schemas_list = document.getElementById("schemas_list");
if (schemas_list) {
	selectPreview(schemas_list);
}

</SCRIPT>

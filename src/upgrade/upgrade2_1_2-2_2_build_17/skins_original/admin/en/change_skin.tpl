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

    	var button_container = document.getElementById("button_container");
        if (button_container) {
        	button_container.style.display = "none";
        }
    	if (elm.value != currentSkin) {
            if (button_container) {
            	button_container.style.display = "";
            }
    	}
    }

// -->
</script>

<p>Use this section to configure the look & feel of your store.</p>

<hr>

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
<tr id="button_container" style="display: none;">
	<td colspan=2 align=center>
	    <br>
	    <br>
       	<input type=submit value="Install selected skin">
 	</td>
</tr>
</table>

</form>

<SCRIPT language="JavaScript">
var currentSkin = "{dialog.currentSkinName}";
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


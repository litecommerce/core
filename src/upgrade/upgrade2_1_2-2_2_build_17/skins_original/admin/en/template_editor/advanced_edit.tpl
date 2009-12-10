<table border="0" cellpadding=2 cellspacing=2>
<form action="admin.php" method="POST" name="editor_form">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="advanced_update">
<input type="hidden" name="editor" value="advanced">
<input type="hidden" name="zone" value="{zone}">
<input type="hidden" name="file" value="{file.path}">
<input type="hidden" name="node" value="{file.node}">

<tr>
<td colspan=2>
	<table border="0" cellpadding=0 cellspacing=0 width=100%>
    <tr>
    	<td width=50%><img src="images/doc.gif" border="0" align="top">&nbsp;<b>{file.path}</b></td>
    	<td width=50% align=right>
        	<table border="0" cellpadding=0 cellspacing=0 align=right>
            <tr>
            	<td><a href="{dialog.url:h}&node={file.node}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Go back to&nbsp;</a></td>
            	<td><a href="{dialog.url:h}&node={file.node}"><img src="images/folder.gif" border="0" align="top">&nbsp;<u>{file.node}</u></a></td>
        	</tr>
        	</table>
    	</td>
	</tr>
	</table>
</td>
</tr>

<tr>
<td colspan=2>
<br>
<textarea name="content" cols="120" rows="40">{file.content}</textarea>
</td>
</tr>

<tr>
<td align=left>
<input type="submit" value=" Save " class="DialogMainButton">
</td>
<td align=right>
{if:file.node}<input type="button" value=" Cancel " onclick="document.location='{dialog.url}&node={file.node:u}'">{else:}<input type="button" value=" Cancel " onclick="document.location='{dialog.url}'">{end:}
</td>
</tr>

</form>
</table>

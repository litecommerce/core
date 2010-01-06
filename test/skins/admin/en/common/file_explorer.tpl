<table border="0" width="100%">
<tr>
<td FOREACH="columns,column,val" width="50%" valign="top" style="font-size:10pt">

{foreach:getColumnsData(column),node}

{if:formSelectionName}<input type="radio" name="{formSelectionName}" value="{node.id}">{end:}

{if:node.leaf}<a href="{dialog.url:h}&mode=edit&file={node.path}"><img src="images/doc.gif" border="0" align="top">
{else:}<a href="{dialog.url:h}&node={node.path}"><img src="images/folder.gif" border="0" align="top">
{end:}
{node.name}</a>
{if:node.comment}&nbsp;&nbsp;-&nbsp;<font style="font-size:8pt">{node.comment}</font><br>
{else:}
<br>
{end:}

{end:}

</td>
</tr>
</table>


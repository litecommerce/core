<table border="0" width="100%">
<tr>
<td FOREACH="columns,column,val" width="50%" valign="top">

{foreach:getColumnsData(column),node}

{if:formSelectionName}<input type="radio" name="{formSelectionName}" value="{node.id}">{end:}
<a href="{node.url:h}" style="font-size:10pt">{if:node.leaf}<img src="images/doc.gif" border="0">
{else:}<img src="images/folder.gif" border="0">
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


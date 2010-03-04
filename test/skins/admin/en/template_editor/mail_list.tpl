<p align=top>This section contains the full set of e-mail message templates for editing. To edit a template, click on its name. 
<widget template="template_editor/notes.tpl">
<br>
<table border="0" width="100%">
<tr>
<td FOREACH="columns,column,val" width="50%" valign="top" style="font-size:10pt">

{foreach:getColumnsData(column),node}

<a href="{url:h}&mode=edit&path={path}&node={node.path}"><img src="images/letter.gif" border="0" align="top" alt="click to edit template">&nbsp;{node.name}</a>
{if:node.comment}&nbsp;&nbsp;-&nbsp;<font style="font-size:8pt">{node.comment}</font><br>
{else:}
<br>
{end:}

{end:}

</td>
</tr>
</table>


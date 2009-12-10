<p IF="status=#updated#" class="SuccessMessage">&gt;&gt; Page has been updated successfully &lt;&lt;</p>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="update_page">
<input type="hidden" name="editor" value="extra_pages">
<input type="hidden" name="page" value="{extraPage.page}">
<input IF="{extraPage.page}" type="hidden" name="returnUrl" value="{url}&mode=page_edit&page={extraPage.page}&status=updated"/>

<table border="0" cellpadding=2 cellspacing=2 IF="{extraPage.page}" width="430">
<tr>
<td>
	<table border="0" cellpadding=0 cellspacing=0 width=100%>
    <tr>
    	<td width=50%><img src="images/doc.gif" border="0" align="top">&nbsp;<b>Page template:</b> <i>{extraPage.page}.tpl</i></td>
    	<td width=50% align=right><a href="{url}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Go back</a></td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td>
&nbsp;
</td>
</tr>
</table>

<b>Page title:</b><br>
<input type="text" name="title" value="{extraPage.title}" size="80" width="150px">
<widget class="CRequiredValidator" field="title">
<br><br>
<b>Page text:</b><br>
<textarea name="content" cols="81" rows="20">{extraPage.template.content}</textarea>
<p>

<table border="0" cellpadding=2 cellspacing=2 width="430">
<tr>
<td align=left>
<input IF="{extraPage.page}" type="submit" value=" Update page " class="DialogMainButton" />
<input IF="{!extraPage.page}" type="submit" value=" Add page " class="DialogMainButton" />
</td>
<td align=right>
<input type="button" IF="{extraPage.page}" name="cancel" value="Cancel" onclick="javascript: document.location='{url}'">
</td>
</tr>
</table>

</form>

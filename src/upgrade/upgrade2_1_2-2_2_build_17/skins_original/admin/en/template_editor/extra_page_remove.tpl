Are you sure you want to remove the page titled "{extraPage.title:h}" ({extraPage.page}.tpl) ?

<p>

<form action="admin.php" method="POST" name="page_remove">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="page_remove">
<input type="hidden" name="editor" value="extra_pages">
<input type="hidden" name="page" value="{extraPage.page}">
<input type=button value=" Yes " onClick="document.page_remove.submit()" class="DialogMainButton">
&nbsp;
&nbsp;
&nbsp;
<input type=button value=" No " onClick="document.page_remove.action.value=''; document.page_remove.submit()">
</form>

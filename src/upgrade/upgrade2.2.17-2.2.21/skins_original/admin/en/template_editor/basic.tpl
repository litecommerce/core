<p align="justify">
This section contains the store's basic templates to be edited.
</p>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="update_templates">

<span FOREACH="basicTemplates,temp">
<p>
<span IF="{temp.comment}"><b>{temp.comment}</b>, </span><i>template file: {temp.path}</i><br>
<span IF="temp.read_only_access" class="ErrorMessage">WARNING! File cannot be overwritten! Please check and correct file permissions.<br></span>
<textarea name="template[{temp.path}]" cols="81" rows="10">{temp.content}</textarea>
<br>
<input type="submit" value=" Update templates ">
<br><br>
</span>
</form>



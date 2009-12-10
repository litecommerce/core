<input type="file" name="{field}">&nbsp;&nbsp;&nbsp;
<input IF="hasImage()" type="button" value="Delete" onClick="document.{formName}.{field}_delete.value='1';document.{formName}.action.value='{actionName}';document.{formName}.submit()"/>
<input type="hidden" value="0" name="{field}_delete">
<br>
<input type="checkbox" name="{field}_filesystem" value="1" checked="{fs}"> Upload to file system
<br>&nbsp;

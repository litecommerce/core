<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tbody FOREACH="options,id,option">
<tr id="option_{option.name}">
    <TD width="50%" align=right>{option.comment:h}: </TD>
    <TD width="50%">
    {if:option.isName(#news_order#)}
    <select name="{option.name}">
        <option value="A" selected="{option.isSelected(#A#)}">Ascending</option>
        <option value="D" selected="{option.isSelected(#D#)}">Descending</option>
    </select>
    {else:}
    <input type="text" name="{option.name}" value="{option.value}" size="20">
    {end:}
    </TD>
</tr>
</tbody>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Update"></TD>
<TD>&nbsp;</TD></TR>
</table>
</form>

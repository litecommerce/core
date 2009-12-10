<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tbody FOREACH="options,id,option">
<tr id="option_{option.name}">
    <TD width="50%" align=right>{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}">
    </TD>
</tr>
</tbody>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Update"></TD>
<TD>&nbsp;</TD></TR>
</table>

</form>

<script language="Javascript">
<!--

function visibleBox(id, status)
{
    var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}

-->
</script>

{if:!config.ShowcaseOrganizer.template=#modules/ShowcaseOrganizer/icons.tpl#}
<script language="Javascript">
<!--
visibleBox("option_so_columns", false);
-->
</script>
{end:}

{if:config.ShowcaseOrganizer.template=#modules/ShowcaseOrganizer/icons.tpl#|config.ShowcaseOrganizer.template=#modules/ShowcaseOrganizer/table.tpl#}
<script language="Javascript">
<!--
visibleBox("option_so_show_description", false);
-->
</script>
{end:}



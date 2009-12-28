Use this page to configure your store to communicate with CardinalCommerce service.<br>
Complete the required fields below and press the <b>"Update"</b> button.

<P>

<table border=0 cellspacing=0 cellpadding=10>
<tr>
	<td valign=top>
    	<img src="images/modules/CardinalCommerce/logo.gif">
	</td>
	<td>
        <table cellSpacing=2 cellpadding=2 border=0 width="100%">
        <form action="admin.php" name="options_form" method="POST">
        <input type="hidden" name="target" value="{target}">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="page" value="{page}">
        <tbody FOREACH="options,id,option">
        <tr id="option_{option.name}">
            <TD width="50%" align=right>{option.comment:h}: </TD>
            <TD width="50%">
            <widget template="modules/{page}/settings.tpl" option="{option}" dialog="{dialog}">
            </TD>
        </tr>
        </tbody>
        <TR><TD colspan=2>&nbsp;</TD></TR>
        <TR><TD align="right"><input type="submit" value="Update"></TD>
        <TD>&nbsp;</TD></TR>
        </form>
        </table>
	</td>
</tr>
</table>

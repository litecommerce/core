<br>
<form action="cart.php" method=GET>
<input type="hidden" foreach="dialog.allparams,param,v" name="{param}" value="{v}"/>
<font class=TextTitle>Select category:</font>
<br><br>
<widget class="CCategorySelect" template="modules/Affiliate/select_category.tpl" fieldName="category_id">
<br><br>
<input type=submit value="Build links">
</form>

<p IF="category_id">
<br><br>
    <table border=0>
    <tr class=TableHead>
        <td>Text link:</td>
        <td align=center width="100%">Preview:</td>
    </tr>
    <tr>
        <td>
        <textarea cols=50 rows=4><a href="{shopURL(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}</a></textarea>
        </td>
        <td align=center>
        <a href="{shopURL(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}</a>
        </td>
    </tr>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr class=TableHead>
        <td>Small graphic link:</td>
        <td align=center width="100%">Preview:</td>
    </tr>
    <tr>
        <td valign=top>
        <textarea cols=50 rows=4><a href="{shopURL(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}">{category.name:h}<img src="{shopURL(#cart.php#,secure,#1#)}?target=image&action=category&id={category_id}" border=0></a></textarea>
        </td>
        <td align=center>
        <a href="{shopURL(#cart.php#)}?target=category&category_id={category_id}&partner={auth.profile.profile_id}"><img src="{shopURL(#cart.php#,secure,#1#)}?target=image&action=category&id={category_id}" border=0></a>
        </td>
    </tr>
    </table>
</p>

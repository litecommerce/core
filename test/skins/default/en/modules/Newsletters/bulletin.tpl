<table IF="lastNews" border=0 cellpadding=1 cellspacing=1>
<tbody FOREACH="lastNews,ln">
<tr>
    <td><font color=gray>{date_format(ln,#send_date#,#%e %b %Y#):h}</font></td>
</tr>
<tr>    
    <td>
        <a href="cart.php?target=news&amp;mode=view&amp;news_id={ln.news_id}" class="SidebarItems"><u>{truncate(ln.subject,50):h}</u></a>
        <br><br>
    </td>
</tr>
</tbody>
<tbody>
<tr>
    <td><a href="cart.php?target=news&amp;mode=view_all" class="SidebarItems"><u>All news messages...</u></a></td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>


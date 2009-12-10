<p>Use this section to create additional pages in the Customer Zone (page location depends on the store layout design).
<hr>

<span IF="extraPages">
<p class="AdminHead">Available pages</p>
<table cellpadding=0 cellspacing=0 border=0>
<tr><td>

<table IF="extraPages" border=0 cellpadding=0 cellspacing=0>
<tbody FOREACH="extraPages,page">
<tr>
<td class="TableHead">

<table border=0 cellpadding=5 cellspacing=1 class="TableHead" width=100%>
<tr>
    <th align="right"">Page: </th>
	<td class="AdminHead"><i>{page.title}</i></td>
</tr>
<tr class="Center">
    <th align="right"">Template: </th>
    <td>{page.page}.tpl</td>
</tr>
<tr class="Center">
    <th align="right">URL:</th>
    <td><a href="{xlite.shopUrl(#cart.php#)}?page={page.page}" target="_blank"><u>{xlite.shopUrl(#cart.php#)}?page={page.page}</u></a></td>
</tr>
</table>

</td></tr>

<tr><td>
    <table border="0" cellpadding=2 cellspacing=2 width="100%">
    <tr>
    <td align=left>
    <input type=button value=" Edit " onclick="document.location='admin.php?target=template_editor&editor=extra_pages&mode=page_edit&page={page.page}'" class="DialogMainButton">
    </td>
    <td align=right>
    <input type=button value="Delete" onclick="document.location='admin.php?target=template_editor&editor=extra_pages&mode=remove_page&page={page.page}'">
    </td>
    </tr>
    </table>
</td></tr>

<tr><td><br><hr><br></td></tr>

</tbody>
</table>

</td></tr>
</table>

</span>

<span class="AdminTitle">Add new page</span>
<br><br>
<widget template="template_editor/extra_page.tpl">


<widget class="XLite_View_Pager"data="{stat}" name="pager">
<table IF="stat" border="0" cellspacing="1" cellpadding="3">
<tr class="TableHead">
	<td>&nbsp;Product&nbsp;</td>
	<td>&nbsp;Date&nbsp;</td>
	<td>&nbsp;Headers&nbsp;</td>
</td>
<tr FOREACH="pager.pageData,stat_line">
	<td><a href="{getProductHref(stat_line.file_id)}"><u>{getProductName(stat_line.file_id)}</u></a>&nbsp;</td>
	<td nowrap>{time_format(stat_line.date)}&nbsp;</td>
	<td>{stat_line.headers}</td>
</tr>
</table>
<widget name="pager">

<span IF="!stat">No downloads found.</span>

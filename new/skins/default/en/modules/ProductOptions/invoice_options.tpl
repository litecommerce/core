<tr IF="item.productOptions">
    <td valign="top" nowrap>Selected options&nbsp;&nbsp;&nbsp;</td>
    <td>
	<table cellpadding="0" cellspacing="0" border="0">
	<tr FOREACH="item.productOptions,poption">
    		<td nowrap>{poption.class:h}: {poption.option:h}<br></td>
	</tr>
	</table>
    </td>
</tr>

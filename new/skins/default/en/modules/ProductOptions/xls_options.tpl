<tbody IF="product.hasOptions()">
<tr>
    <td valign="top" nowrap>Selected options</td>
    <td>
	<table cellpadding="0" cellspacing="0" border="0">
	<tr FOREACH="getOptions(),option">
    		<td nowrap>{option.class:h}: {option.option:h}<br></td>
	</tr>
	</table>
    </td>
</tr>
</tbody>

    <tr IF="!isSelected(order,#discount#,#0#)">
        <td>Discount:</td>
        <td align="left">
            {price_format(invertSign(order.discount)):h}
        </td>
	</tr>


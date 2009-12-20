    <tr IF="!isSelected(order,#payedByPoints#,#0#)">
        <td>Bonus points discount:</td>
        <td align="left">
            {price_format(invertSign(order.payedByPoints)):h}
        </td>
	</tr>

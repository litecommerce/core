<tbody IF="item.gcid">
    <tr><td>Recipient:</td><td>{item.gc.recipient}</td></tr>
    <tr IF="item.gc.send_via=#E#)"><td>Recipient email:</td><td>{item.gc.recipient_email}</td></tr>
    <tr IF="item.gc.send_via=#P#)"><td>Mail address:</td><td>
{item.gc.recipient_firstname} {item.gc.recipient_lastname}<br>
{item.gc.recipient_address}, {item.gc.recipient_city},<br>
{if:!item.gc.recipient_state=-1)}{item.gc.recipientState.state}{end:} {item.gc.recipientCountry.country}, {item.gc.recipient_zipcode}
	</td></tr>
	<tr><td>Message:</td><td>{item.gc.message}</td></tr>
</tbody>

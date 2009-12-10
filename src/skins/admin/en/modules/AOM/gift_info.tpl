<table width="100%">
	<tr>
	 	<td IF="widget.clone" nowrap colspan="2"><input type="checkbox" name="delete_gc[]" style="margin: 0px 5px 0px 0px;" value="GC{widget.gc.gcid}"><b>Certificate #</b>{widget.gc.gcid}</td> 
	 	<td IF="!widget.clone" nowrap colspan="2"><b>Certificate #</b>{widget.gc.gcid}</td>
	</tr>
     <tr>
        <td>Amount:</td>
         <td>{price_format(widget.gc.amount):h}</td>
     </tr>
     <tr>
        <td>Remainder:</td>
        <td>{price_format(widget.gc.debit):h}</td>
	 </tr>
	 <tr>
	 	<td>Recipient:</td>
		<td>{widget.gc.recipient}</td>
	</tr>
	<tr IF="isSelected(widget.gc,#send_via#,#E#)">
		<td>Recipient email:</td>
		<td>{widget.gc.recipient_email}</td>
	</tr>
	<tr IF="isSelected(widget.gc,#send_via#,#P#)">
		<td>Mail address:</td>
		<td>{widget.gc.recipient_firstname} {widget.gc.recipient_lastname}<br>
			{widget.gc.recipient_address}, {widget.gc.recipient_city},<br>

            {if:widget.gc.recipient_custom_state}
                {widget.gc.recipient_custom_state},
            {else:}
			    {widget.gc.recipientState.state},
            {end:}
            
			{widget.gc.recipientCountry.country}, 
			{widget.gc.recipient_zipcode}</td>
	</tr>
    <tr>
		<td>Message:</td>
		<td>{widget.gc.message}</td>
	</tr>	
	<tr>
		<td colspan="2"><hr style="background-color: #516176; border: 0; height: 2px"></td>
	</tr>
</table>

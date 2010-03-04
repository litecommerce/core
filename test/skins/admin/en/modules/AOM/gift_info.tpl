<table width="100%">
	<tr>
	 	<td IF="clone" nowrap colspan="2"><input type="checkbox" name="delete_gc[]" style="margin: 0px 5px 0px 0px;" value="GC{gc.gcid}"><b>Certificate #</b>{gc.gcid}</td> 
	 	<td IF="!clone" nowrap colspan="2"><b>Certificate #</b>{gc.gcid}</td>
	</tr>
     <tr>
        <td>Amount:</td>
         <td>{price_format(gc.amount):h}</td>
     </tr>
     <tr>
        <td>Remainder:</td>
        <td>{price_format(gc.debit):h}</td>
	 </tr>
	 <tr>
	 	<td>Recipient:</td>
		<td>{gc.recipient}</td>
	</tr>
	<tr IF="isSelected(gc,#send_via#,#E#)">
		<td>Recipient email:</td>
		<td>{gc.recipient_email}</td>
	</tr>
	<tr IF="isSelected(gc,#send_via#,#P#)">
		<td>Mail address:</td>
		<td>{gc.recipient_firstname} {gc.recipient_lastname}<br>
			{gc.recipient_address}, {gc.recipient_city},<br>

            {if:gc.recipient_custom_state}
                {gc.recipient_custom_state},
            {else:}
			    {gc.recipientState.state},
            {end:}
            
			{gc.recipientCountry.country}, 
			{gc.recipient_zipcode}</td>
	</tr>
    <tr>
		<td>Message:</td>
		<td>{gc.message}</td>
	</tr>	
	<tr>
		<td colspan="2"><hr style="background-color: #516176; border: 0; height: 2px"></td>
	</tr>
</table>

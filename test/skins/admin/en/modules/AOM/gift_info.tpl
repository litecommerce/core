{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
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

<form action="{getShopUrl(#cart.php#)}" method=POST name="send{product_id}_form">
<input type=hidden name=target value="product">
<input type=hidden name=action value="send_friend">
<input type=hidden name=product_id value="{product_id}">
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td class="NumberOfArticles">Your name:</td>
		<td width="10"><font class="Star">*</font></td>
		<td><input type="text" name="sender_name" size="32" value="{senderName}"></td>
		<td><widget class="XLite_Validator_RequiredValidator" field="sender_name" value="{sender_name}"></td>
	</tr>
	<tr>	
        <td class="NumberOfArticles">Your e-mail:</td> 
		<td width="10"><font class="Star">*</font></td>
        <td><input type="text" name="sender_email" size="32" value="{senderEmail}"></td>
        <td><widget class="XLite_Validator_EmailValidator" field="sender_email"></td>
	</tr>	
	<tr>
        <td class="NumberOfArticles">Friend's e-mail:</td>
		<td width="10"><font class="Star">*</font></td>
        <td><input type="text" name="recipient_email" size="32" value="{recipient_email}"></td>
        <td><widget class="XLite_Validator_EmailValidator" field="recipient_email"></td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
        <td colspan="2"><br><widget class="XLite_View_Button_Submit" label="Send to friend" /></td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>

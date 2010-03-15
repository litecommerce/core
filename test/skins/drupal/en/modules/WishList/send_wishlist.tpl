<form action="{shopURL(#cart.php#)}" method="POST" name="wishlist{wishlist.wishlist_id}_form">
<input type=hidden name=target value="wishlist">
<input type=hidden name=action value="send">
<input type=hidden name=wishlist_id value="{wishlist.wishlist_id}">
<table cellpadding=5 cellspacing=0 border=0 width="100%">
<tr>
	<td align="left">
<widget class="XLite_View_Button_Link" label="Clear Wish List" location="{buildURL(#wishlist#,#clear#)}" />
	</td>
    <td align="right"> 
	<b>Send entire wish list by e-mail:</b> <input type=text name="wishlist_recipient" value="{wishlist_recipient}"><br><widget class="XLite_Validator_EmailValidator" field="wishlist_recipient">
    </td>
    <td align="left"> 
<widget class="XLite_View_Button_Submit" label="Send" />
	</td>
</tr>
</table>
</form>

<table cellpadding="5" cellspacing="0" border="0" width="100%" IF="item.gcid">
<tr>
    <td valign="top" width="70">
        <a href="cart.php?target=add_gift_certificate&gcid={item.gcid}"><img src="images/modules/GiftCertificates/gift_certificate.gif" border="0"></a>
    </td>
    <td>
        <a href="cart.php?target=add_gift_certificate&gcid={item.gcid}"><FONT class="ProductTitle">Gift certificate</FONT></a><br><br>
		From: {item.gc.purchaser}<br>
		To: {item.gc.recipient}<br>
        <br>
        
        <FONT class="ProductPriceTitle">Amount:</FONT> <FONT class="ProductPriceConverting">{price_format(item,#price#):h}</FONT>
        <br>
        <br>
        <widget class="CButton" href="cart.php?target=add_gift_certificate&gcid={item.gcid}" label="Modify certificate" font="FormButton">
        &nbsp;&nbsp;&nbsp;
        <widget class="CButton" href="cart.php?target=cart&action=delete&cart_id={cart_id}" label="Delete item" font="FormButton">
    </td>
</tr>
</table>

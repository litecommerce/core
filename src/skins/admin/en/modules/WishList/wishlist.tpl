<table border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td nowrap><b>Wish List ID:</b></td>
        <td>#{wishlist.wishlist_id:h}</td>
    </tr>
    <tr>
        <td nowrap><b>Creation date:</b></td>
        <td>{date_format(wishlist.date)}</td>
    </tr>
    <tr>
        <td nowrap><b>E-mail:</b></td>
        <td><a href="admin.php?target=profile&profile_id={wishlist.profile.profile_id}"><u>{wishlist.profile.login:h}</u></a></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
        <td colspan="4" nowrap bgcolor="#DDDDDD"><b>Products</b></td>
    </tr>
    <tbody FOREACH="wishlist.products,item">
        <tr valign="top">
			<td IF="item.hasImage()" valign=top align="left" width=100 rowspan="6"><a href="admin.php?target=product&product_id={item.product_id}"><img src="{item.imageURL}" border=0></a></td>
			<td IF="!item.hasImage()" valign=top align="left" width=100 rowspan="6">&nbsp;</td> 
            <td nowrap valign=top>SKU:</td>
            <td valign=top>{item.sku}</td>
        </tr>
        <tr>
            <td nowrap valign=top>Product:</td>
            <td valign=top><a href="admin.php?target=product&product_id={item.product_id}">{item.name}</a></td>
        </tr>
        <tr>
            <td nowrap valign=top>Quantity:</td>
            <td valign=top>{item.amount}</td>
        </tr>
        <tr>
            <td nowrap valign=top>Item price:</td>
            <td valign=top>{price_format(item,#price#):h}</td>
        </tr>
		<tr IF="item.hasOptions()">
			<td colspan="3" valign=top>
				<table valign=top>
				 <widget module="ProductOptions" template="modules/ProductOptions/invoice_options.tpl">
				</table>
			</td>
		</tr>
		<tr IF="!item.hasOptions()">
			<td colspan="3"></td>
		</tr>
	    <tr>
            <td nowrap valign=top><b>Total:</b></td>
            <td valign=top>{price_format(item,#total#):h}</td>
	    </tr>
		<tr>
			<td colspan="3"><hr></td>
		</tr>
    </tbody>
	<tr If="!wishlist.products">
		<td colspan="2">Wish list is empty</td>
	</tr>
</table>
<p>
<b IF="!mode"><a href="admin.php?target=wishlist&mode=print&wishlist_id={wishlist.wishlist_id}" target="_blank" onClick="this.blur()"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Print Wish List</A></b>&nbsp;&nbsp;&nbsp;
<script>
function delete_warning() 
{
    if (confirm('You are about to delete a customer wish list.\n\nAre you sure you want to delete it?')) { 
		window.location = 'admin.php?target=wishlist&action=delete&wishlist_id={wishlist.wishlist_id}';
        return true;
    }
}
</script>
<b IF="!mode"><a href="javascript: delete_warning();" onClick="this.blur()"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Delete Wish List</A></b>



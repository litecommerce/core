<script language="Javascript" type="text/javascript">
<!--

function Add2Cart(product_id)
{
	document.add_to_cart.product_id.value = product_id;
	document.add_to_cart.submit();
}

-->
</script>

<widget class="CPAPager" data="{product.ProductsAlsoBuy}" name="pabpager" itemsPerPage="{config.General.products_per_page}" pageIDX="pabPageID" extraParameter="pageID">

<table cellpadding="3" cellspacing="1" border="0" bgcolor="#cccccc" width="100%">
<tr FOREACH="pabpager.pageData,pabIdx,PAB" class="DialogBox">
	<td width=30><FONT class="ProductTitle">&nbsp;#{inc(pabIdx)}&nbsp;</FONT></td>
	<td width="100%"><a href="cart.php?target=product&amp;product_id={PAB.product.product_id}&amp;category_id={PAB.product.category.category_id}"><FONT class="ProductTitle">{PAB.product.name:h}</FONT></a></td>
	<td nowrap IF="config.ProductAdviser.pab_show_price"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(PAB.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {PAB.product.priceMessage:h}</FONT></td>
	<td nowrap IF="config.ProductAdviser.pab_show_buynow"><widget class="CButton" label="Add to Cart" href="javascript: Add2Cart('{PAB.product.product_id}')" img="cart4button.gif" font="FormButton"></td>
</tr>
</table>

<widget name="pabpager">
